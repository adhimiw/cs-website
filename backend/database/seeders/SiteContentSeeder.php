<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SiteContent;
use App\Models\Setting;
use App\Models\Service;
use App\Models\Testimonial;
use App\Models\BlogPost;

class SiteContentSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Site Contents (Home, About Pages)
        $contents = [
            // Home Page Hero
            [
                'page' => 'home',
                'section' => 'hero',
                'key' => 'title',
                'value' => 'Accelerating Software Innovation for Modern Enterprises',
                'type' => 'text',
            ],
            [
                'page' => 'home',
                'section' => 'hero',
                'key' => 'subtitle',
                'value' => 'We engineer custom web platforms, native mobile applications, and secure cloud architectures tailored to your business scale.',
                'type' => 'textarea',
            ],
            [
                'page' => 'home',
                'section' => 'hero',
                'key' => 'cta_label',
                'value' => 'Explore Services',
                'type' => 'text',
            ],
            [
                'page' => 'home',
                'section' => 'hero',
                'key' => 'image',
                'value' => 'site_contents/hero-consultant.webp',
                'type' => 'image',
            ],
            
            // About Page - Who We Are
            [
                'page' => 'about',
                'section' => 'who_we_are',
                'key' => 'subtitle',
                'value' => 'Who We Are',
                'type' => 'text',
            ],
            [
                'page' => 'about',
                'section' => 'who_we_are',
                'key' => 'title',
                'value' => 'Driving Transformation at Scale',
                'type' => 'text',
            ],
            [
                'page' => 'about',
                'section' => 'who_we_are',
                'key' => 'lead_text',
                'value' => 'Nearly 50 years of global experience delivering large-scale HCM and Service Desk transformation solutions across key regions.',
                'type' => 'textarea',
            ],

            // About Page - SaaS Expertise
            [
                'page' => 'about',
                'section' => 'saas_expertise',
                'key' => 'subtitle',
                'value' => 'Expertise in Scalable SaaS Solution',
                'type' => 'text',
            ],
            [
                'page' => 'about',
                'section' => 'saas_expertise',
                'key' => 'title',
                'value' => 'Expertise in Scalable SaaS Solution',
                'type' => 'text',
            ],
            [
                'page' => 'about',
                'section' => 'saas_expertise',
                'key' => 'lead_text',
                'value' => 'We deliver expert SaaS solutions that help businesses streamline processes, improve workflows, and build scalable, future-ready systems.',
                'type' => 'textarea',
            ],

            // About Page - Leadership Intro
            [
                'page' => 'about',
                'section' => 'leadership',
                'key' => 'title',
                'value' => 'Our Leadership Team',
                'type' => 'text',
            ],
            [
                'page' => 'about',
                'section' => 'leadership',
                'key' => 'subtitle',
                'value' => 'Meet the experienced professionals driving transformation and innovation',
                'type' => 'textarea',
            ],
        ];

        foreach ($contents as $content) {
            SiteContent::updateOrCreate(
                ['page' => $content['page'], 'section' => $content['section'], 'key' => $content['key']],
                ['value' => $content['value'], 'type' => $content['type']]
            );
        }

        // 2. Seed Global Settings
        $settings = [
            [
                'key' => 'contact_email',
                'value' => 'hello@climbsphere.com',
                'type' => 'text',
            ],
            [
                'key' => 'contact_phone',
                'value' => '+1 (555) 123-4567',
                'type' => 'text',
            ],
            [
                'key' => 'social_linkedin',
                'value' => 'https://linkedin.com/company/climbsphere',
                'type' => 'text',
            ],
            [
                'key' => 'social_twitter',
                'value' => 'https://twitter.com/climbsphere',
                'type' => 'text',
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                ['value' => $setting['value'], 'type' => $setting['type']]
            );
        }

        // 3. Seed Dynamic Services
        $services = [
            [
                'title' => 'Digital Maturity Assessment',
                'description' => "The right digital investment starts with clear assessment and allocation. ClimbSphere's structured assessment evaluates your capabilities across six dimensions — Strategy, Technology, Processes, People, Customer Experience and Governance — replacing guesswork with evidence-based roadmaps.",
                'image' => 'services/digital-maturity-assessment.webp',
                'tags' => ['Capabilities', 'Strategy', 'Transformation Roadmap'],
                'type' => 'business',
                'order' => 1,
            ],
            [
                'title' => 'Digital Transformation',
                'description' => "Meaningful transformation happens when technology serves business strategy. ClimbSphere's Discover, Design, Deliver, Drive cycle aligns people, processes, and platforms for measurable outcomes, accelerating your evolution with focus and momentum.",
                'image' => 'services/service-digital-transformation.webp',
                'tags' => ['Discover', 'Design', 'Deliver & Drive'],
                'type' => 'business',
                'order' => 2,
            ],
            [
                'title' => 'HR Technology',
                'description' => "A connected, intelligent HR ecosystem empowers your people teams to attract, retain and grow talent with clarity and confidence. ClimbSphere optimizes talent management, analytics and employee experience through seamless platform selection, implementation and adoption.",
                'image' => 'services/service-hr.webp',
                'tags' => ['Talent Selection', 'Analytics', 'Employee Experience'],
                'type' => 'business',
                'order' => 3,
            ],
            [
                'title' => 'Project Management',
                'description' => "ClimbSphere brings structured agility to every engagement, blending Agile, Waterfall, or Hybrid methodologies with hands-on governance and transparent reporting — keeping your initiatives on track, on budget and aligned to the goals that matter most.",
                'image' => 'services/service-dashboard.webp',
                'tags' => ['Structured Agility', 'Agile & Hybrid', 'Governance'],
                'type' => 'business',
                'order' => 4,
            ],
            [
                'title' => 'Service Desk & Ticketing',
                'description' => "A well designed service desk drives productivity, strengthens trust and elevates IT's role as a strategic business partner. ClimbSphere designs and deploys efficient ticketing, self service portals, intelligent automation and SLA-driven governance that turns support operations into a competitive advantage.",
                'image' => 'services/service-dashboard.webp',
                'tags' => ['Ticketing Support', 'Intelligent Automation', 'SLA Governance'],
                'type' => 'business',
                'order' => 5,
            ],
            [
                'title' => 'Professional Services',
                'description' => "Scale your customer wins with end-to-end professional services excellence. ClimbSphere combines strategic key account management, disciplined project execution and seamless implementation to drive adoption, expansion and reference success across your portfolio replacing guesswork with evidence based roadmaps.",
                'image' => 'services/about-expertise.jpeg',
                'tags' => ['Strategic Accounts', 'Project Execution', 'Portfolio Adoption'],
                'type' => 'partner',
                'order' => 6,
            ],
            [
                'title' => 'Product Partnerships',
                'description' => "A well designed partner ecosystem multiplies your reach, strengthens your product and creates shared value across every stakeholder. ClimbSphere helps you design and operationalize partnership models that deliver genuine three way impact for your organization, your partners and the end customer.",
                'image' => 'services/about-saas.jpeg',
                'tags' => ['Ecosystem Design', 'Partnership Models', 'Shared Value'],
                'type' => 'partner',
                'order' => 7,
            ],
        ];

        foreach ($services as $service) {
            Service::updateOrCreate(
                ['title' => $service['title']],
                [
                    'description' => $service['description'],
                    'image' => $service['image'],
                    'tags' => $service['tags'],
                    'type' => $service['type'],
                    'order' => $service['order'],
                ]
            );
        }

        // 4. Seed Dynamic Testimonials
        $testimonials = [
            [
                'text' => 'ClimbSphere offers an excellent user experience with smooth navigation and powerful features for businesses looking to streamline operations efficiently.',
                'author' => 'Priya Sharma',
                'image' => 'testimonials/testimonial-avatar.png',
                'rating' => 5,
            ],
            [
                'text' => 'Great platform with useful tools for management and collaboration. It has significantly improved our workflow and productivity.',
                'author' => 'Rahul',
                'image' => 'testimonials/testimonial-avatar.png',
                'rating' => 5,
            ],
        ];

        foreach ($testimonials as $t) {
            Testimonial::updateOrCreate(
                ['author' => $t['author']],
                [
                    'text' => $t['text'],
                    'image' => $t['image'],
                    'rating' => $t['rating'],
                ]
            );
        }

        // 5. Seed dynamic Blog post
        BlogPost::updateOrCreate(
            ['slug' => 'accelerating-software-innovation'],
            [
                'title' => 'Accelerating Software Innovation',
                'content' => '<p>In today\'s fast-paced enterprise landscape, delivering robust and scalable software transformation is the differentiator. ClimbSphere works with global partners to adopt Agile methodologies, optimize platform selections, and automate ticketing systems to deliver key outcomes. Discover how to streamline your operations with cloud integration.</p>',
                'image' => 'blogs/service-digital-transformation.webp',
                'author' => 'Manoj Cheruvathoor',
                'published_at' => now(),
            ]
        );
    }
}
