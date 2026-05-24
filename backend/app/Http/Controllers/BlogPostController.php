<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\BlogPost;
use App\AI\Agents\BlogOptimizerAgent;

class BlogPostController extends Controller
{
    public function index()
    {
        // Seed default posts if the table is empty to ensure clean initial load
        if (BlogPost::count() === 0) {
            $this->seedDefaults();
        }

        $posts = BlogPost::orderBy('published_at', 'desc')
            ->orWhereNull('published_at')
            ->get();
        return response()->json($posts);
    }

    public function show($slug)
    {
        $post = BlogPost::where('slug', $slug)->firstOrFail();
        return response()->json($post);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|string',
            'author' => 'nullable|string',
            'published_at' => 'nullable|date',
            'seo_meta' => 'nullable|array',
        ]);

        $slug = Str::slug($validated['title']);
        $originalSlug = $slug;
        $count = 1;
        while (BlogPost::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        $post = BlogPost::create([
            'title' => $validated['title'],
            'slug' => $slug,
            'content' => $validated['content'],
            'image' => $validated['image'] ?: '/images/blog_post_01.png',
            'author' => $validated['author'] ?: 'Admin',
            'published_at' => $validated['published_at'] ?: now(),
            'seo_meta' => $validated['seo_meta'] ?: [],
        ]);

        return response()->json($post, 201);
    }

    public function update(Request $request, $slug)
    {
        $post = BlogPost::where('slug', $slug)->firstOrFail();

        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'content' => 'nullable|string',
            'image' => 'nullable|string',
            'author' => 'nullable|string',
            'published_at' => 'nullable|date',
            'seo_meta' => 'nullable|array',
        ]);

        if (isset($validated['title']) && $validated['title'] !== $post->title) {
            $newSlug = Str::slug($validated['title']);
            $originalSlug = $newSlug;
            $count = 1;
            while (BlogPost::where('slug', $newSlug)->where('id', '!=', $post->id)->exists()) {
                $newSlug = $originalSlug . '-' . $count++;
            }
            $post->slug = $newSlug;
        }

        // Fill non-null validated inputs
        $updateData = array_filter($validated, function ($val) {
            return $val !== null;
        });

        $post->update($updateData);

        return response()->json($post);
    }

    public function destroy($slug)
    {
        $post = BlogPost::where('slug', $slug)->firstOrFail();
        $post->delete();

        return response()->json(['success' => true]);
    }

    public function optimize(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'content' => 'required|string',
        ]);

        $agent = new BlogOptimizerAgent($validated['title'], $validated['content']);
        $result = $agent->prompt("Analyze and optimize the article.");

        return response()->json($result);
    }

    private function seedDefaults()
    {
        BlogPost::create([
            'title' => "Workday's Last Workday? The Goliath Has Heard This Before",
            'slug' => 'workday-ai-disruption',
            'content' => "Is this the end of traditional HCM Goliaths? A practitioner's take on the A16z thesis, the CHRO's crossroads, and what is next.\n\n### What is the Workday AI Disruption?\nWorkday is facing new competition from AI-first employee workflows. However, Workday's deep enterprise integrations make it a formidable player.\n\n### How does this impact HR teams?\nHR technology is shifting from system-of-record to system-of-intelligence. The future CHRO must adopt tools that integrate workflows and provide direct assistance rather than just data storage.\n\n### What is ClimbSphere's View?\nWe suggest that organizations focus on implementing automation layers on top of their existing Workday core. A well-designed middle layer delivers direct employee value without expensive migrations.",
            'image' => '/images/blog_post_01.png',
            'author' => 'Admin',
            'published_at' => now(),
            'seo_meta' => [
                'seo_title' => "Workday AI Disruption: A Practitioner's Perspective",
                'seo_description' => "Explore the implications of AI on legacy HCM tools like Workday and how HR tech is transitioning from records to intelligence.",
                'target_keywords' => ['Workday', 'HCM', 'HR Tech', 'AI Disruption', 'CHRO'],
                'aeo_summary' => "What is the Workday AI Disruption? The Workday AI disruption represents a transition from legacy databases to AI-native workflows that automate employee tasks. While new startups threaten the Core HCM model, enterprise dominance requires implementing overlay systems of intelligence.",
                'faqs' => [
                    [
                        'question' => 'Will AI replace legacy HCM tools like Workday?',
                        'answer' => 'AI is unlikely to completely replace legacy tools immediately due to enterprise contracts and records systems, but it will shift day-to-day employee interaction to overlay intelligence layers.'
                    ],
                    [
                        'question' => 'How can enterprise HR prepare for AI disruption?',
                        'answer' => 'HR teams should focus on implementing integrated workflows and AI middleware that sit on top of legacy cores to provide immediate utility.'
                    ]
                ]
            ]
        ]);

        BlogPost::create([
            'title' => 'Why Great Software Often Fails Great People: Lessons From The Post-Demo Paradox',
            'slug' => 'post-demo-paradox-hr-tech',
            'content' => "It is entirely possible to fall in love with an HCM product demo, only to realize the real-world implementation fails to deliver employee satisfaction.\n\n### What is the Post-Demo Paradox?\nThe post-demo paradox is the gap between expectations set by perfect software demonstrations and the complex reality of custom implementations. Many products look fantastic in mock environments but break down when integrated with real enterprise directory rules and business processes.\n\n### How to Mitigate Implementation Failures?\nTo prevent software implementation failure:\n1. Prioritize design-led discovery before picking the system.\n2. Involve real end-users in testing phases, not just executives.\n3. Focus heavily on clean data migration and adoption planning.",
            'image' => '/images/blog_post_02.jpg',
            'author' => 'Admin',
            'published_at' => now()->subDay(),
            'seo_meta' => [
                'seo_title' => 'The Post-Demo Paradox in HR Tech Implementations',
                'seo_description' => 'Why perfect software demos lead to failed enterprise implementations, and how design-led discovery resolves this paradox.',
                'target_keywords' => ['Software Demo', 'HR Tech', 'Software Implementation', 'User Experience'],
                'aeo_summary' => "What is the Post-Demo Paradox? The Post-Demo Paradox is the failure of software implementations to live up to perfect sales demos. It occurs because real environments contain integration complexities and user adoptability constraints omitted in sales sandbox settings.",
                'faqs' => [
                    [
                        'question' => 'Why do software implementations fail after good demos?',
                        'answer' => 'Implementations fail because demos skip real-world database constraints, custom business logic, and end-user adoption bottlenecks.'
                    ]
                ]
            ]
        ]);
    }
}
