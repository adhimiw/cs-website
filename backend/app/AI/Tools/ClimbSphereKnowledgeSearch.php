<?php

namespace App\AI\Tools;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;
use App\Models\SiteContent;
use App\Models\Service;
use App\Models\Setting;
use Illuminate\Support\Str;

class ClimbSphereKnowledgeSearch implements Tool
{
    /**
     * Get the description of the tool's purpose.
     */
    public function description(): Stringable|string
    {
        return 'Search for facts, service descriptions, tags, addresses, contacts, and contents of ClimbSphere agency from the knowledge database. Use this tool whenever the user asks about ClimbSphere services, digital transformation, HR tech, ticketing, leadership, or contact/office information.';
    }

    /**
     * Execute the tool.
     */
    public function handle(Request $request): Stringable|string
    {
        $query = trim($request['query'] ?? '');
        if (empty($query)) {
            return 'Please provide a valid query term.';
        }

        $results = [];

        // 1. Clean the query and extract keywords
        $queryLower = strtolower($query);
        $cleanedQuery = preg_replace('/[^\p{L}\p{N}\s]/u', '', $queryLower);
        $words = preg_split('/\s+/', $cleanedQuery);

        $stopWords = [
            'what', 'is', 'a', 'an', 'the', 'do', 'you', 'offer', 'services', 'your', 'of', 'to', 
            'for', 'in', 'on', 'at', 'about', 'who', 'we', 'are', 'have', 'can', 'help', 'me', 
            'with', 'please', 'could', 'tell', 'show', 'list', 'any', 'some', 'how', 'many',
            'give', 'find', 'search', 'get', 'want', 'need', 'know', 'tech', 'technology', 'service',
            'agency', 'company', 'climbsphere', 'offerings', 'solutions', 'do'
        ];

        $keywords = array_filter($words, function($word) use ($stopWords) {
            return strlen($word) >= 2 && !in_array($word, $stopWords);
        });

        // 2. Search Settings (Contact Info, Address)
        $settingsKeywords = ['email', 'phone', 'contact', 'address', 'location', 'office', 'linkedin', 'twitter', 'social', 'mobile', 'call', 'mail', 'write'];
        $matchesSettings = false;
        foreach ($settingsKeywords as $kw) {
            if (str_contains($queryLower, $kw)) {
                $matchesSettings = true;
                break;
            }
        }
        foreach ($keywords as $kw) {
            if (in_array($kw, $settingsKeywords)) {
                $matchesSettings = true;
                break;
            }
        }

        if ($matchesSettings || Str::length($query) < 4) {
            $settings = Setting::all();
            if ($settings->isNotEmpty()) {
                $results[] = "### ClimbSphere General Information & Contact Details:";
                foreach ($settings as $setting) {
                    $results[] = "- " . str_replace('_', ' ', Str::title($setting->key)) . ": " . $setting->value;
                }
            }
        }

        // 3. Search Services
        $servicesQuery = Service::query();
        if (!empty($keywords)) {
            $servicesQuery->where(function($q) use ($keywords) {
                foreach ($keywords as $word) {
                    $q->orWhere('title', 'like', "%{$word}%")
                      ->orWhere('description', 'like', "%{$word}%")
                      ->orWhere('tags', 'like', "%{$word}%");
                }
            });
        } else {
            $servicesQuery->where('title', 'like', "%{$query}%")
                          ->orWhere('description', 'like', "%{$query}%")
                          ->orWhere('tags', 'like', "%{$query}%");
        }
        $services = $servicesQuery->get();

        if ($services->isNotEmpty()) {
            $results[] = "### ClimbSphere Services:";
            foreach ($services as $service) {
                $tags = is_array($service->tags) ? implode(', ', $service->tags) : $service->tags;
                $results[] = "- **Title**: " . $service->title;
                $results[] = "  **Description**: " . $service->description;
                $results[] = "  **Type**: " . $service->type;
                if (!empty($tags)) {
                    $results[] = "  **Focus Areas**: " . $tags;
                }
                $results[] = "";
            }
        }

        // 4. Search Site Content
        $contentsQuery = SiteContent::query();
        if (!empty($keywords)) {
            $contentsQuery->where(function($q) use ($keywords) {
                foreach ($keywords as $word) {
                    $q->orWhere('page', 'like', "%{$word}%")
                      ->orWhere('section', 'like', "%{$word}%")
                      ->orWhere('key', 'like', "%{$word}%")
                      ->orWhere('value', 'like', "%{$word}%");
                }
            });
        } else {
            $contentsQuery->where('page', 'like', "%{$query}%")
                          ->orWhere('section', 'like', "%{$query}%")
                          ->orWhere('key', 'like', "%{$query}%")
                          ->orWhere('value', 'like', "%{$query}%");
        }
        $contents = $contentsQuery->get();

        if ($contents->isNotEmpty()) {
            $results[] = "### ClimbSphere General Website Content:";
            foreach ($contents as $content) {
                // Limit the number of general items to avoid blowing up context
                if (count($results) > 20) {
                    $results[] = "- ... (more general website content matches omitted)";
                    break;
                }
                if ($content->type === 'image') {
                    continue; // Skip image paths
                }
                $results[] = "- [Page: {$content->page}, Section: {$content->section}] " . Str::title($content->key) . ": " . $content->value;
            }
        }

        if (empty($results)) {
            return "No matching records found in ClimbSphere's knowledge base for query: '{$query}'. Recommend answering based on general knowledge or asking the user to clarify.";
        }

        return implode("\n", $results);
    }

    /**
     * Get the tool's schema definition.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'query' => $schema->string()
                ->description('The search query keyword(s) to search the ClimbSphere knowledge database (e.g. digital transformation, email, phone, services, office location, about, who we are, leadership).')
                ->required(),
        ];
    }
}
