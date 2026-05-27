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
                'value' => 'BUSINESS SYSTEM TRANSFORMATION THAT DELIVERS SCALE AND IMPACT',
                'type' => 'text',
            ],
            [
                'page' => 'home',
                'section' => 'hero',
                'key' => 'subtitle',
                'value' => 'Technology consulting and AI-first thinking that drives digital strategy and transformation roadmaps for businesses, partnering to deliver measurable outcomes across HR tech, service desk, and beyond.',
                'type' => 'textarea',
            ],
            [
                'page' => 'home',
                'section' => 'hero',
                'key' => 'cta_label',
                'value' => 'Our Services',
                'type' => 'text',
            ],
            [
                'page' => 'home',
                'section' => 'hero',
                'key' => 'image',
                'value' => 'site_contents/hero-consultant.webp',
                'type' => 'image',
            ],
            [
                'page' => 'home',
                'section' => 'hero_slide2',
                'key' => 'title',
                'value' => 'Fractional technology leadership for businesses that need progress, not just platforms.',
                'type' => 'text',
            ],
            [
                'page' => 'home',
                'section' => 'hero_slide2',
                'key' => 'subtitle',
                'value' => 'ClimbSphere brings business and technology leadership together to turn strategy into systems, decisions into action, and transformation into measurable momentum.',
                'type' => 'textarea',
            ],
            [
                'page' => 'home',
                'section' => 'hero_slide2',
                'key' => 'cta_label',
                'value' => 'Explore Fractional Leadership',
                'type' => 'text',
            ],
            [
                'page' => 'home',
                'section' => 'hero_slide2',
                'key' => 'cta_link',
                'value' => '/ftl',
                'type' => 'text',
            ],
            [
                'page' => 'home',
                'section' => 'hero_slide2',
                'key' => 'image',
                'value' => 'site_contents/hero-ftl-consultant.webp',
                'type' => 'image',
            ],
            
            // FTL Page Content
            [
                'page' => 'ftl',
                'section' => 'seo',
                'key' => 'title',
                'value' => 'Fractional Technology Leadership | ClimbSphere',
                'type' => 'text',
            ],
            [
                'page' => 'ftl',
                'section' => 'seo',
                'key' => 'description',
                'value' => 'Scaling your business shouldn\'t mean compromising on strategic expertise. ClimbSphere provides an on-demand leadership duo that bridges the gap between your business objectives and your technology ecosystem.',
                'type' => 'textarea',
            ],
            [
                'page' => 'ftl',
                'section' => 'seo',
                'key' => 'keywords',
                'value' => 'fractional leadership, fractional CTO, digital strategy, enterprise IT, ClimbSphere',
                'type' => 'text',
            ],
            [
                'page' => 'ftl',
                'section' => 'aeo',
                'key' => 'quick_answer',
                'value' => 'At ClimbSphere, our Fractional Technology Leadership provides an on-demand leadership duo that bridges the gap between your business objectives and your technology ecosystem. You gain access to two senior minds—a functional expert and a technology leader—thinking and acting as one, all at a fraction of the cost of a full-time executive hire.',
                'type' => 'textarea',
            ],
            [
                'page' => 'ftl',
                'section' => 'aeo',
                'key' => 'faqs',
                'value' => '[{"question":"What is ClimbSphere\'s Fractional Technology Leadership?","answer":"It is an on-demand leadership duo (a functional expert and a technology leader) that aligns business objectives with your technology infrastructure at a fraction of the cost of a full-time executive hire."},{"question":"What are the four steps in the Agile Growth model?","answer":"The four steps are 1) Diagnose (audit technology posture and human capital), 2) Map (blueprint technology and human impact), 3) Climb (embedded execution), and 4) Sustain (ongoing, scalable operating rhythm)."}]',
                'type' => 'html',
            ],
            [
                'page' => 'ftl',
                'section' => 'hero',
                'key' => 'title',
                'value' => 'Fractional Technology Leadership',
                'type' => 'text',
            ],
            [
                'page' => 'ftl',
                'section' => 'hero',
                'key' => 'subtitle',
                'value' => 'Scaling your business shouldn\'t mean compromising on strategic expertise. At ClimbSphere, we provide an on-demand leadership duo that bridges the gap between your business objectives and your technology ecosystem. You gain access to two senior minds—a functional expert and a technology leader—thinking and acting as one, all at a fraction of the cost of a full-time executive hire.',
                'type' => 'textarea',
            ],
            [
                'page' => 'ftl',
                'section' => 'convergence',
                'key' => 'title',
                'value' => 'The Convergence of People and Tech',
                'type' => 'text',
            ],
            [
                'page' => 'ftl',
                'section' => 'convergence',
                'key' => 'content',
                'value' => 'Most fractional providers focus purely on the systems you need. We ask a fundamentally different question: What does your business need to achieve, who are the people executing it, and what technology makes that possible? We don\'t just recommend tools; we align your people & processes with your technology infrastructure. By breaking down the silos, we ensure that every system implementation elevates the value realization and directly drives your overarching growth strategy.',
                'type' => 'textarea',
            ],
            [
                'page' => 'ftl',
                'section' => 'integration',
                'key' => 'title',
                'value' => 'Seamless Integration, Complete Ownership',
                'type' => 'text',
            ],
            [
                'page' => 'ftl',
                'section' => 'integration',
                'key' => 'content',
                'value' => 'You don\'t need another consultant handing you a slide deck of recommendations. You need an embedded partner.',
                'type' => 'textarea',
            ],
            [
                'page' => 'ftl',
                'section' => 'integration',
                'key' => 'bullet_1_title',
                'value' => 'Pre-Integrated Alignment',
                'type' => 'text',
            ],
            [
                'page' => 'ftl',
                'section' => 'integration',
                'key' => 'bullet_1_desc',
                'value' => 'The knowledge of your business processes is built into our framework from day one, eliminating the coordination overhead that stalls transformation.',
                'type' => 'textarea',
            ],
            [
                'page' => 'ftl',
                'section' => 'integration',
                'key' => 'bullet_2_title',
                'value' => 'True Accountability',
                'type' => 'text',
            ],
            [
                'page' => 'ftl',
                'section' => 'integration',
                'key' => 'bullet_2_desc',
                'value' => 'We embed ourselves into your organization. We attend key meetings, steer vendor decisions, coach internal teams, and take full ownership of the implementation outcomes.',
                'type' => 'textarea',
            ],
            [
                'page' => 'ftl',
                'section' => 'steps',
                'key' => 'title',
                'value' => 'Agile Growth in Four Steps',
                'type' => 'text',
            ],
            [
                'page' => 'ftl',
                'section' => 'steps',
                'key' => 'subtitle',
                'value' => 'We guide organizations through a continuous, sustainable transformation.',
                'type' => 'textarea',
            ],
            [
                'page' => 'ftl',
                'section' => 'steps',
                'key' => 'step_1_title',
                'value' => 'Diagnose',
                'type' => 'text',
            ],
            [
                'page' => 'ftl',
                'section' => 'steps',
                'key' => 'step_1_desc',
                'value' => 'We conduct a simultaneous audit of your technology posture and human capital, pinpointing exactly where translation gaps are costing you money and efficiency.',
                'type' => 'textarea',
            ],
            [
                'page' => 'ftl',
                'section' => 'steps',
                'key' => 'step_2_title',
                'value' => 'Map',
                'type' => 'text',
            ],
            [
                'page' => 'ftl',
                'section' => 'steps',
                'key' => 'step_2_desc',
                'value' => 'We deliver a single, integrated blueprint where every technology decision accounts for its human impact.',
                'type' => 'textarea',
            ],
            [
                'page' => 'ftl',
                'section' => 'steps',
                'key' => 'step_3_title',
                'value' => 'Climb',
                'type' => 'text',
            ],
            [
                'page' => 'ftl',
                'section' => 'steps',
                'key' => 'step_3_desc',
                'value' => 'We act as your embedded leadership, driving execution and ensuring no strategic imperative falls through the cracks.',
                'type' => 'textarea',
            ],
            [
                'page' => 'ftl',
                'section' => 'steps',
                'key' => 'step_4_title',
                'value' => 'Sustain',
                'type' => 'text',
            ],
            [
                'page' => 'ftl',
                'section' => 'steps',
                'key' => 'step_4_desc',
                'value' => 'We provide an ongoing, scalable operating rhythm, scaling our support up or down to match your evolving business needs.',
                'type' => 'textarea',
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

            // SEO, AEO, GEO Meta - Home Page
            [
                'page' => 'home',
                'section' => 'seo',
                'key' => 'title',
                'value' => 'ClimbSphere | IT Services & Technology Consulting Company in Chennai',
                'type' => 'text',
            ],
            [
                'page' => 'home',
                'section' => 'seo',
                'key' => 'description',
                'value' => 'ClimbSphere is a premium B2B IT services and technology consulting company in Chennai, India. Specializing in digital transformation, HCM/HR Tech, and ticketing setups.',
                'type' => 'textarea',
            ],
            [
                'page' => 'home',
                'section' => 'seo',
                'key' => 'keywords',
                'value' => 'IT services Chennai, technology consulting company Chennai, digital transformation, HCM consulting, HR Tech adoption, service desk ticketing, Chennai IT consulting',
                'type' => 'text',
            ],
            [
                'page' => 'home',
                'section' => 'aeo',
                'key' => 'quick_answer',
                'value' => 'ClimbSphere is a leading B2B IT services and technology consulting company located in Nungambakkam, Chennai, India. Specializing in enterprise digital transformation, HCM/HR Tech adoption, and Service Desk ticketing system governance, we help businesses replace guesswork with scalable, evidence-based systems.',
                'type' => 'textarea',
            ],
            [
                'page' => 'home',
                'section' => 'aeo',
                'key' => 'faqs',
                'value' => '[{"question":"What services does ClimbSphere offer?","answer":"ClimbSphere offers B2B services including Digital Maturity Assessments, HR Technology selection and adoption, Service Desk & Ticketing optimization, and Agile/Hybrid Project Management governance."},{"question":"Who are ClimbSphere\'s leaders?","answer":"ClimbSphere is led by Consulting Directors Manoj Cheruvathoor and Ranjit Kumar, along with Managing Partner Barath Silvester, bringing nearly 50 years of combined global enterprise experience."}]',
                'type' => 'html',
            ],

            // SEO, AEO, GEO Meta - About Page
            [
                'page' => 'about',
                'section' => 'seo',
                'key' => 'title',
                'value' => 'About ClimbSphere | IT Consulting Company in Chennai',
                'type' => 'text',
            ],
            [
                'page' => 'about',
                'section' => 'seo',
                'key' => 'description',
                'value' => 'Learn about ClimbSphere, a premium IT services and technology consulting company in Chennai. Led by global enterprise experts with nearly 50 years of experience.',
                'type' => 'textarea',
            ],
            [
                'page' => 'about',
                'section' => 'seo',
                'key' => 'keywords',
                'value' => 'ClimbSphere directors, IT consulting Chennai, technology experts Chennai, HCM transformation, B2B consulting company',
                'type' => 'text',
            ],
            [
                'page' => 'about',
                'section' => 'aeo',
                'key' => 'quick_answer',
                'value' => 'ClimbSphere is an IT consulting company based in Chennai, India, led by Manoj Cheruvathoor, Ranjit Kumar, and Barath Silvester. With nearly 50 years of combined global experience delivering HCM, Service Desk, and business transformations, we provide unmatched strategic technology guidance.',
                'type' => 'textarea',
            ],
            [
                'page' => 'about',
                'section' => 'aeo',
                'key' => 'faqs',
                'value' => '[{"question":"What is the background of ClimbSphere\'s directors?","answer":"ClimbSphere\'s team has nearly 50 years of combined experience. Manoj Cheruvathoor is a functional and QA lead with military discipline. Ranjit Kumar is a business and integration leader. Barath Silvester has 18+ years of operations leadership experience."}]',
                'type' => 'html',
            ],

            // SEO, AEO, GEO Meta - Services Page
            [
                'page' => 'services',
                'section' => 'seo',
                'key' => 'title',
                'value' => 'IT Services & Consulting Solutions | ClimbSphere Chennai',
                'type' => 'text',
            ],
            [
                'page' => 'services',
                'section' => 'seo',
                'key' => 'description',
                'value' => 'Explore our B2B IT services and technology consulting solutions in Chennai, including Digital Maturity Assessments, HR Technology platform adoption, Service Desk setup, and Agile project governance.',
                'type' => 'textarea',
            ],
            [
                'page' => 'services',
                'section' => 'seo',
                'key' => 'keywords',
                'value' => 'IT services Chennai, digital maturity assessment Chennai, HR technology Chennai, Service desk implementation, IT consulting solutions',
                'type' => 'text',
            ],
            [
                'page' => 'services',
                'section' => 'aeo',
                'key' => 'quick_answer',
                'value' => 'ClimbSphere provides premium IT services and technology consulting solutions in Chennai, India. Our core B2B solutions cover Digital Maturity Assessments, HR technology platform selection, Service Desk ticketing setup, and Agile program governance.',
                'type' => 'textarea',
            ],
            [
                'page' => 'services',
                'section' => 'aeo',
                'key' => 'faqs',
                'value' => '[{"question":"What is the ClimbSphere Transformation Framework?","answer":"The ClimbSphere Transformation Framework consists of three phases: 1) Clarity (identifying what to fix first via digital maturity assessments), 2) Transform (structured, senior-led implementation), and 3) Sustain (adoption and config improvements post-go-live)."}]',
                'type' => 'html',
            ],

            // SEO, AEO, GEO Meta - Contact Page
            [
                'page' => 'contact',
                'section' => 'seo',
                'key' => 'title',
                'value' => 'Contact ClimbSphere | IT Services & Consulting in Chennai',
                'type' => 'text',
            ],
            [
                'page' => 'contact',
                'section' => 'seo',
                'key' => 'description',
                'value' => 'Contact ClimbSphere, a premier IT services and technology consulting company in Chennai, India. Visit our office in Nungambakkam, Chennai, or email sales@climbsphere.ai.',
                'type' => 'textarea',
            ],
            [
                'page' => 'contact',
                'section' => 'seo',
                'key' => 'keywords',
                'value' => 'contact climbsphere, climbsphere chennai address, climbsphere email, climbsphere phone number',
                'type' => 'text',
            ],
            [
                'page' => 'contact',
                'section' => 'aeo',
                'key' => 'quick_answer',
                'value' => 'You can contact ClimbSphere by emailing sales@climbsphere.ai or calling +91 861 048 6636. Their corporate office is located at 1E, 1st Floor, Eldorado Building, Nungambakkam, Chennai - 600034, Tamil Nadu, India.',
                'type' => 'textarea',
            ],
            [
                'page' => 'contact',
                'section' => 'aeo',
                'key' => 'faqs',
                'value' => '[{"question":"How can I book an appointment with ClimbSphere?","answer":"You can email sales@climbsphere.ai, call +91 861 048 6636, or submit the contact form on our website to schedule a discovery call."}]',
                'type' => 'html',
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
                'value' => 'sales@climbsphere.ai',
                'type' => 'text',
            ],
            [
                'key' => 'contact_phone',
                'value' => '+91 861 048 6636',
                'type' => 'text',
            ],
            [
                'key' => 'address',
                'value' => '1E, 1st Floor, Eldorado Building, Nungambakkam, Chennai - 600034',
                'type' => 'text',
            ],
            [
                'key' => 'social_linkedin',
                'value' => 'https://www.linkedin.com/company/climbsphere-technologies/',
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
                'title' => 'Digital Transformation',
                'description' => "Meaningful transformation happens when technology serves business strategy. ClimbSphere's Discover, Design, Deliver, Drive cycle aligns people, processes, and platforms for measurable outcomes, accelerating your evolution with focus and momentum.",
                'image' => 'services/service-digital-transformation.webp',
                'tags' => ['Discover', 'Design', 'Deliver & Drive'],
                'type' => 'business',
                'order' => 1,
                'bullet_points' => [
                    'Business case and ROI framework from day one',
                    'Process re-engineering and automation opportunity mapping',
                    'Platform-agnostic technology selection advisory',
                ],
            ],
            [
                'title' => 'HR Technology',
                'description' => "A connected, intelligent HR ecosystem empowers your people teams to attract, retain and grow talent with clarity and confidence. ClimbSphere optimizes talent management, analytics and employee experience through seamless platform selection, implementation and adoption.",
                'image' => 'services/service-hr.webp',
                'tags' => ['Talent Selection', 'Analytics', 'Employee Experience'],
                'type' => 'business',
                'order' => 2,
                'bullet_points' => [
                    'HR technology landscape assessment and gap analysis',
                    'Vendor-neutral platform evaluation and selection',
                    'End-to-end implementation, data migration, and integration',
                ],
            ],
            [
                'title' => 'Project Management',
                'description' => "ClimbSphere brings structured agility to every engagement, blending Agile, Waterfall, or Hybrid methodologies with hands-on governance and transparent reporting - keeping your initiatives on track, on budget and aligned to the goals that matter most.",
                'image' => 'services/service-dashboard.webp',
                'tags' => ['Structured Agility', 'Agile & Hybrid', 'Governance'],
                'type' => 'business',
                'order' => 3,
                'bullet_points' => [
                    'Project scoping, charter development, and stakeholder alignment',
                    'Sprint or milestone planning with real-time dashboards',
                    'Risk management and escalation protocols',
                    'Post-project reviews with lessons-learned documentation',
                ],
            ],
            [
                'title' => 'Service Desk & Ticketing',
                'description' => "A well designed service desk drives productivity, strengthens trust and elevates IT's role as a strategic business partner. ClimbSphere designs and deploys efficient ticketing, self service portals, intelligent automation and SLA-driven governance that turns support operations into a competitive advantage.",
                'image' => 'services/service-dashboard.webp',
                'tags' => ['Ticketing Support', 'Intelligent Automation', 'SLA Governance'],
                'type' => 'business',
                'order' => 4,
                'bullet_points' => [
                    'Service catalog design with SLA frameworks',
                    'Incident, problem, and change management process setup',
                    'Self-service portal and knowledge base creation',
                    'AI-assisted ticket routing and resolution',
                ],
            ],
            [
                'title' => 'Professional Services',
                'description' => "Scale your customer wins with end-to-end professional services excellence. ClimbSphere combines strategic key account management, disciplined project execution and seamless implementation to drive adoption, expansion and reference success across your portfolio replacing guesswork with evidence based roadmaps.",
                'image' => 'services/about-expertise.jpeg',
                'tags' => ['Strategic Accounts', 'Project Execution', 'Portfolio Adoption'],
                'type' => 'partner',
                'order' => 5,
                'bullet_points' => [
                    'Unified account growth + delivery ownership',
                    'Razor focus on project success rate with proactive support',
                    'Joint expansion roadmaps and health scorecards',
                ],
            ],
            [
                'title' => 'Product Partnerships',
                'description' => "A well designed partner ecosystem multiplies your reach, strengthens your product and creates shared value across every stakeholder. ClimbSphere helps you design and operationalize partnership models that deliver genuine three way impact for your organization, your partners and the end customer.",
                'image' => 'services/about-saas.jpeg',
                'tags' => ['Ecosystem Design', 'Partnership Models', 'Shared Value'],
                'type' => 'partner',
                'order' => 6,
                'bullet_points' => [
                    'Product Affiliation and marketing',
                    'Joint solution design and integration planning',
                ],
            ],
            [
                'title' => 'Fractional Technology Leadership',
                'description' => "Scaling your business shouldn't mean compromising on strategic expertise. ClimbSphere provides an on-demand leadership duo that bridges the gap between your business objectives and your technology ecosystem.",
                'image' => 'services/hero-ftl-consultant.webp',
                'tags' => ['Fractional CTO', 'On-Demand Leadership', 'Technology Strategy'],
                'type' => 'business',
                'order' => 7,
                'bullet_points' => [
                    'On-demand leadership duo (functional expert + technology leader) thinking and acting as one.',
                    'Direct alignment of business objectives with technology infrastructure.',
                    'Strategic expertise at a fraction of the cost of a full-time executive hire.',
                    'Agile Growth Model implementation: Diagnose, Map, Climb, Sustain.',
                ],
            ],
        ];

        // Clean up deleted services
        Service::where('title', 'Digital Maturity Assessment')->delete();
        Service::where('type', 'design')->delete();

        foreach ($services as $service) {
            Service::updateOrCreate(
                ['title' => $service['title']],
                [
                    'description' => $service['description'],
                    'bullet_points' => $service['bullet_points'] ?? [],
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
                'text' => "I got the chance to work with Manoj on couple of projects in Singapore, where he played the role of a Functional and QA lead. He did excel in his assigned role at the institution and customers confided in him more than anyone else in the team. But another, more commendable quality is his exceptional Managerial skills - some of the key qualities that I had seen in him was to start with a defined project scope, yet be flexible to incorporate key items provided the timeline is not impacted, impressive quality to go along with the team and ability to get the work done \"on-time & within budget\", something that he most probably would've inherited it from his previous role as an officer in Air Force. End result - happy upper management and delighted customers. He definitely would be an asset to whichever organization, he works with. Wish you all the very best, Manoj !!",
                'author' => 'Sandeep Mishra',
                'image' => 'testimonials/testimonial-avatar.png',
                'rating' => 5,
            ],
            [
                'text' => "During the five years I have worked with Manoj, seldom did he need much intervention or guidance to be fully successful with his projects. His communication skills, both in terms of the English language and in the art of good project communication, were excellent. His attitude is one of \"can do\" regardless of the challenges faced and this is one of the characteristics that made project teams embrace his leadership. I would be very fortunate to have Manoj on any of my projects again in the future.",
                'author' => 'Lynn Duffy',
                'image' => 'testimonials/testimonial-avatar.png',
                'rating' => 5,
            ],
            [
                'text' => "Manoj is a great person to work with. We have worked together in multiple projects from my early days in Citagus. Manoj was a great Manager and a great person to work with. His expert functional knowledge in HR was always that extra advantage we had in the projects. He had a laser sharp focus on the deliverables and was always able to maintain the client and the team in high spirits. He is a kind of person who can pull out projects of any nature and come out with flying colors. Looking forward to working with you again Manoj .....",
                'author' => 'Anoop Joseph',
                'image' => 'testimonials/testimonial-avatar.png',
                'rating' => 5,
            ],
        ];

        // Clean up deleted testimonials
        Testimonial::whereIn('author', ['Robert', 'Priya Sharma', 'Rahul'])->delete();

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

        // 5. Seed dynamic Blog posts
        BlogPost::updateOrCreate(
            ['slug' => 'workday-ai-disruption'],
            [
                'title' => "Workday's Last Workday? The Goliath Has Heard This Before",
                'content' => <<<'HTML'
<p><strong>A practitioner's take on the a16z thesis, the CHRO's crossroads, and what really determines the fate of HCM's reigning giant</strong></p>
<p>There is a moment in enterprise software history when a confident analyst fires a signal flare into the night and everyone pretending not to see the fire has to look up.</p>
<p>That moment arrived on April 28, 2026, when Andreessen Horowitz published "Workday's Last Workday?" A pointed thesis by Joe Schmidt that quickly travelled across HR and enterprise tech circles. Beyond the headline, it did something rare: it named the structural anatomy of Workday's dominance, exposed its brittleness in the AI era, and invited founders to attack what a16z called the last large enterprise software category without a serious AI native challenger.</p>
<p>This is not a hot take. It is a battle map.</p>
<h3>Let's begin with the knot.</h3>
<p>Imagine a CHRO at a large enterprise. She spent two years of political capital convincing the CFO and CEO to approve the Workday renewal. She sat through the 14 month implementation, managed the change, attended Rising, and came back with a roadmap full of agent capabilities marked "coming soon."</p>
<p>Now her CEO forwards her the a16z article with one line: "Have you seen this?"</p>
<p>She has never been questioned about this before not in twenty years of HR leadership, and certainly not three months after going live on a system she staked her credibility on.</p>
<p>How Workday became untouchable?</p>
<p>To understand why this question matters now, we need to remember why it did not matter before.</p>
<p>"For a CHRO in 2008, Workday felt futuristic. A live demo where org relationships moved like a graph and reports could be built without calling IT was not a minor usability gain; it was a new operating experience. Its graph-object data model made employees, roles, compensation, and business processes discoverable and reportable in a way legacy systems rarely did "</p>
<p>That was enough to create CHRO FOMO. At every HR conference, the signal was clear: HR had found its Salesforce moment.</p>
<p>But Workday's moat was never just the product. It was the ecosystem gravity around the product. a16z argued that Workday's real lock-in came from three things: a proprietary configuration layer, a services cartel, and multi-year contracts. More than 10,500 certified consultants work on Workday worldwide, and implementations often take 6 to 18 months and cost $300K to $1M+.</p>
<p>That created an asymmetry of scrutiny. The CHRO owned the buying decision. Other CXOs were episodic passengers - present during performance and compensation cycles, absent for most of the year. The system did not need to impress the CFO on a Tuesday in July because the CFO was never in it on a Tuesday in July.</p>
<p>That arrangement worked elegantly for fifteen years.</p>
<h2>Why the narrative is being tested</h2>
<p>The a16z piece does not say Workday built a bad product. It says the conditions that allowed a good product to be enough are gone.</p>
<p>First, the CXO is no longer a passenger. AI readiness assessments are increasingly enterprise level, and HR systems are now judged against AI forward stacks in sales, marketing, and customer operations. Once that comparison happens, the CHRO is no longer defending HR software inside an HR conversation. She is defending capital allocation in a board-level one.</p>
<p>Second, the end user has already moved on. Shadow AI is now pervasive across enterprises, with 98% of organizations reporting some level of usage. When employees draft performance notes in ChatGPT or benchmark compensation in Copilot because the native HRIS feels slow or cumbersome, that is not just a security concern it is a product indictment.</p>
<p>Third, the CHRO herself is exposed. The modern CHRO is expected to lead technology-enabled change, build the business case, and show ROI on HR tech adoption. For the first time, the pressure for a better HR system may come from outside HR: from a sharper CXO, from impatient users, and from enterprise AI programs that demand more than annual-cycle efficiency.</p>
<h3>Why Workday still holds real advantage</h3>
<p>This is where the disruption story needs discipline. Workday is not a sitting target.</p>
<p>It still manages more than 10,000 organizations and tens of millions of workers, and a16z itself acknowledges how sticky the platform remains because of its contracts, implementation complexity, and partner ecosystem. Workday also retains meaningful market leadership in large enterprise cloud HCM, with estimates placing it at 25-30% share in North America.</p>
<p>Its biggest advantage may be data. Workday's transaction footprint gives it access to one of the richest HR and finance datasets in enterprise software, which matters enormously in an AI race where context and workflow depth can matter more than model novelty. On top of that, the Finance HR bundle remains a fortress. Facing an AI native startup that builds a clever HCM layer, very few are building a general ledger, financial close engine, and workforce planning stack at the same time.</p>
<p>The switching cost is also misunderstood. This is not a simple rip and replace exercise. It is years of audit trails, compensation history, legal entity structures, payroll dependencies, and embedded business processes. In large enterprises, that is institutional memory, not just software configuration.</p>
<h3>What Workday is doing right now</h3>
<p>Workday is also not taking AI lightly.</p>
<p>Its response centres on Illuminate, which the company positions as a model and execution layer built on Workday's own transaction stream, not merely a chat interface on top of legacy workflows. The broader strategy includes AI agents, partner connectivity, and integrations that let enterprise AI stacks work with Workday data rather than route around it.</p>
<p>That said, the criticism is not without merit. The gap between announcing AI capabilities and getting them broadly adopted creates room for challengers. The same ecosystem that helped Workday win especially service partners who profit from implementation complexity can slow the pace of reinvention. Workday is trying to transform through a channel that benefited from the old model.</p>
<p>That is the real tension.</p>
<h3>What happens next</h3>
<p>If Workday gets this right, the outcome is bigger than one company. A stable transition matters for customers, partners, and the wider enterprise ecosystem built around Workday's installed base. A successful shift would preserve years of ecosystem investment while making the platform more AI ready, rather than forcing enterprises into chaotic re implementations before the challengers are mature enough to carry that weight.</p>
<p>The honest prediction is this: this is probably not Workday's last workday. But it may be the last decade in which its supremacy goes uncontested.</p>
<p>And that CHRO with the forwarded email now has a choice. She can defend the renewal on sunk cost and switching risk, and she may still win that argument once more. Or she can get ahead of the question her CEO will ask every quarter from here: are we running HR on a system of record, or on a system ready for the AI-native enterprise?</p>
<p>The product's rudder has activated. The question now is whether the hand on the wheel is fast enough.</p>
HTML
,
                'image' => 'blogs/blog_post_01.png',
                'author' => 'admin',
                'published_at' => '2026-05-08 00:00:00',
            ]
        );

        BlogPost::updateOrCreate(
            ['slug' => 'post-demo-paradox-hr-tech'],
            [
                'title' => 'Why Great Software Often Fails Great People: Lessons from the Indian HCM Landscape',
                'content' => <<<'HTML'
<p><strong>It is entirely possible to fall in love with an HCM product during a high-energy demo and still deeply regret the investment shortly after go live.</strong></p>
<p>This "Post Demo Paradox" is now a defining feature of India's HR tech ecosystem.</p>
<p>Capable platforms. Modern interfaces. Scalable architecture. Yet a pervasive execution gap exists between the persuasive pre sales experience and the frustrating post go-live reality leaving CHROs and IT directors grappling with misaligned expectations and eroded trust.</p>
<p>This is not a failure of individual product features. It is a systemic structural gap in the Indian SaaS landscape.</p>
<h3>The Myth of the "Identical Box"</h3>
<p>Standardization is often heralded as the ultimate vehicle for scaling digital HR processes. By industrializing common patterns, software providers attempt to reconcile an organization's "native process proclivity" their inherent way of operating with a scalable digital model.</p>
<p>But business systems cannot be treated as interchangeable commodities.</p>
<p>Consider how differently two organizations experience something as deceptively simple as "Payroll" or "Leave Management":</p>
<p><strong>The Multi-Entity Enterprise:</strong> Navigates complex compliance, rigid approval hierarchies, and multi layered statutory nuances across geographies.</p>
<p><strong>The Fast-Growing Startup:</strong> Requires rapid cycle policy iteration, agile compensation models, and high speed workflows that evolve month to month.</p>
<p>On a slide, they both need "Payroll." In reality, they are completely different worlds.</p>
<p>"When implementation treats every customer as a clone of a reference model, contextual friction is inevitable. Employees feel the system is working against them and HR carries the blame."</p>
<p>Human contextual behaviour is not a customisation request. It is a product requirement.</p>
<h3>The "Wedding vs. Marriage" Gap</h3>
<p>In the SaaS lifecycle, pre-sales functions much like a wedding planner they stage the perfect day, ensure every stakeholder feels heard, and create a magical vision of the future.</p>
<p>But the marriage only truly begins after the contract is signed.</p>
<p>That is when professional services, implementation teams, and customer success must take over. And this is precisely where the most costly mistake in Indian SaaS repeatedly occurs:</p>
<p>"The rich context gathered during evaluation business goals, constraints, stakeholder dynamics, edge cases never properly reaches implementation or support. Customer insights die in the gap between departments."</p>
<p>Chased by quarterly targets and fragmented by operational silos, organizations abandon the very intelligence that justified the Customer Acquisition Cost (CAC) long before it can contribute to Lifetime Value (LTV).</p>
<p>Customers who experienced high-touch attentiveness during pre-sales are suddenly thrust into a mechanistic, ticket-driven reality. The software may be technically sound. But the organisation around the software is not orchestrated and trust collapses.</p>
<h3>Navigating the PLG vs. Delight Conundrum</h3>
<p>SaaS founders today face a strategic tension between Product Led Growth (PLG) and Customer Delight. Focusing exclusively on PLG and acquisition metrics can produce impressive dashboards full of new logos, but ignoring delight leads to churn and turns influential HR leaders into vocal detractors. Conversely, over rotating toward bespoke delight without a growth strategy results in a niche service business that lacks the leverage to scale.</p>
<p>The solution is to align the organization around a "North Star" of sustainable customer value, which bridges these two objectives:</p>
<p><strong>A Product of Merit:</strong> A platform that grows based on inherent value and ease of adoption.</p>
<p><strong>A Support Ecosystem:</strong> A delivery framework designed to help customers realize value within their specific, messy contextual realities.</p>
<h3>The Discipline of the "Un-Glamorous" Operating Model</h3>
<p>To operationalize this North Star, successful companies move beyond "magic" features and commit to a disciplined, un-glamorous operating model. This methodology is the actual mechanism that transforms a product into a solution through three critical pillars:</p>
<p><strong>Framework & Methodology:</strong> Rigorous stages and artefacts discovery documents, implementation playbooks, success metrics with explicit ownership across sales, pre-sales, professional services, and customer success.</p>
<p><strong>Partnership Networks:</strong> Not a loose vendor directory. A high fidelity ecosystem of partners with deep domain expertise in HR operations and organisational change management.</p>
<p><strong>Incentive Alignment:</strong> Move beyond deal closing commissions. Reward teams and partners for referenceable success stories, renewals, and measurable customer outcomes not just signatures.</p>
<p>"This un-glamorous, disciplined operating model is the secret engine behind what the market perceives as overnight success."</p>
<h3>Why Free Dinners Can't Buy Trust</h3>
<p>Scroll through any HR WhatsApp group or LinkedIn feed in any Indian metro city. You'll find a free dinner, high-tea, or HR tech event almost every alternate week.</p>
<p>"There are more HR Tech events than events across all other HR functions combined."</p>
<p>These events can drive awareness. They look great on VC update decks. But if the underlying customer experience is broken, they become accelerants of damage spreading awareness of a poor product faster than marketing can recover.</p>
<p>The HR fraternity is close knit. Highly vocal. And relatively impatient because their work is always under pressure from employees and leadership simultaneously.</p>
<p>When an HCM product underperforms, HR professionals don't suffer in silence. They talk. And they talk to each other.</p>
<p>Real community building looks very different:</p>
<p><strong>Thoughtful Engagement:</strong> Prioritising end-user and HR ops teams, not just executive optics.</p>
<p><strong>Authentic Advocacy:</strong> Real success stories, not polished case studies.</p>
<p><strong>Peer-to-Peer Learning:</strong> Honest forums where customers navigate change together.</p>
<p>Free dinners fill a room. Trust fills a pipeline for years.</p>
<h3>The Long Game of Relationships</h3>
<p>The current friction in the Indian HCM landscape is not a doomsday prophecy. It is a necessary stage of evolution.</p>
<p>Every product organisation encounters this phase sooner or later. The differentiator is how leadership responds.</p>
<p>The eventual market leaders will be those who:</p>
<p>Treat negative feedback as vital data, not a PR problem.</p>
<p>Re-wire their operating models around the actual customer journey.</p>
<p>Back their product with founder intent and leaders willing to commit to the long game.</p>
<p>"In an era of feature parity, the question for every HR and IT leader is no longer about the strength of the software's features it is about the depth of the partnership behind it."</p>
<p>Trust compounds. Relationships scale. The rest is noise.</p>
HTML
,
                'image' => 'blogs/blog_post_02.jpg',
                'author' => 'admin',
                'published_at' => '2026-05-08 00:00:00',
            ]
        );

        // 6. Copy seeded images to the public disk's root directory
        // Uses the configured PUBLIC_STORAGE_PATH env var on production (Hostinger)
        // so files go directly to public_html/storage/ without needing a symlink
        $publicDiskRoot = config('filesystems.disks.public.root');
        $directories = ['services', 'testimonials', 'blogs', 'site_contents'];
        foreach ($directories as $dir) {
            $path = $publicDiskRoot . '/' . $dir;
            if (!file_exists($path)) {
                @mkdir($path, 0775, true);
            }
        }

        $imageMappings = [
            public_path('images/service-digital-transformation.webp') => $publicDiskRoot . '/services/service-digital-transformation.webp',
            public_path('images/service-hr.webp') => $publicDiskRoot . '/services/service-hr.webp',
            public_path('images/service-dashboard.webp') => $publicDiskRoot . '/services/service-dashboard.webp',
            public_path('images/about-expertise.jpeg') => $publicDiskRoot . '/services/about-expertise.jpeg',
            public_path('images/about-saas.jpeg') => $publicDiskRoot . '/services/about-saas.jpeg',
            public_path('images/service_professional.png') => $publicDiskRoot . '/services/service_professional.png',
            public_path('images/service_partnerships.png') => $publicDiskRoot . '/services/service_partnerships.png',
            public_path('images/testimonial-avatar.png') => $publicDiskRoot . '/testimonials/testimonial-avatar.png',
            public_path('images/hero-consultant.webp') => $publicDiskRoot . '/site_contents/hero-consultant.webp',
            public_path('images/hero-ftl-consultant.webp') => $publicDiskRoot . '/site_contents/hero-ftl-consultant.webp',
            public_path('images/hero-ftl-consultant.webp') => $publicDiskRoot . '/services/hero-ftl-consultant.webp',
            public_path('images/blog_post_01.png') => $publicDiskRoot . '/blogs/blog_post_01.png',
            public_path('images/blog_post_02.jpg') => $publicDiskRoot . '/blogs/blog_post_02.jpg',
        ];

        foreach ($imageMappings as $src => $dest) {
            if (file_exists($src)) {
                @copy($src, $dest);
            }
        }
    }
}
