<?php

namespace App\AI\Agents;

use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\HasStructuredOutput;
use Laravel\Ai\Promptable;
use Illuminate\Contracts\JsonSchema\JsonSchema;

class BlogOptimizerAgent implements Agent, HasStructuredOutput
{
    use Promptable;

    protected string $blogContent = '';
    protected string $blogTitle = '';

    public function __construct(string $blogTitle, string $blogContent)
    {
        $this->blogTitle = $blogTitle;
        $this->blogContent = $blogContent;
    }

    public function instructions(): string
    {
        return "You are an expert AI system specializing in Search Engine Optimization (SEO), Answer Engine Optimization (AEO), and Generative Engine Optimization (GEO).\n" .
            "Your task is to analyze the provided blog post (Title: \"{$this->blogTitle}\") and content, and generate metadata to optimize it for AI-assisted search and search crawlers:\n" .
            "1. SEO Title & Description: Under 160 characters, descriptive, containing keywords.\n" .
            "2. Target Keywords: A list of 5-8 relevant search terms.\n" .
            "3. AEO Summary: A 40-60 word concise direct answer to the core question the article addresses. Begin with a clear question (e.g. 'How does HR technology improve productivity?').\n" .
            "4. AEO/GEO FAQs: A list of 2-4 frequently asked questions with highly factual, direct, and structured answers (perfect for Featured Snippets and LLM retrieval citations).\n" .
            "Here is the blog post content:\n\n" . $this->blogContent;
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'seo_title' => $schema->string()->required()->description('Optimized SEO title for browser tab (max 60 chars).'),
            'seo_description' => $schema->string()->required()->description('Compelling meta description (max 160 chars) summarizing the post.'),
            'target_keywords' => $schema->array($schema->string())->required()->description('List of 5 to 8 keywords.'),
            'aeo_summary' => $schema->string()->required()->description('A concise 40-60 word direct answer to the main question.'),
            'faqs' => $schema->array(
                $schema->object(function ($faqSchema) {
                    return [
                        'question' => $faqSchema->string()->required()->description('The FAQ question.'),
                        'answer' => $faqSchema->string()->required()->description('Factual, direct answer (30-50 words).'),
                    ];
                })
            )->required()->description('List of 2-4 FAQ items for JSON-LD schema injection.'),
        ];
    }
}
