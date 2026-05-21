# Laravel 13 CMS + AI Chatbot Backend Documentation

## Overview

This document defines the backend architecture for a Laravel 13 website that combines a full content management system, an AI chatbot built with Laravel AI SDK, lead capture, contact workflows, SMTP notifications, IP-based location tracking, referral and UTM attribution, and Hostinger-ready deployment practices.[cite:1][cite:3][cite:16]

The target outcome is a production-oriented Laravel application where non-technical admins can edit site content, visitors can submit forms or chat with an AI assistant, every important submission is stored in the backend, and operational emails are sent automatically to both the visitor and the business team.[cite:1][cite:4][cite:16]

## Goals

The system should support five core business functions in one codebase:[cite:1][cite:16]

- Full CMS control over the website, including hero sections, page content, service listings, testimonials, navigation, footer content, contact information, and media assets.[cite:16]
- AI-assisted chat that can answer visitor questions, ask follow-up questions, and extract structured lead information such as name, email, phone, company, budget, timeline, and project idea.[cite:1][cite:3][cite:4]
- Backend persistence for chat sessions, chat messages, leads, contact submissions, visitor attribution, and location intelligence.[cite:1][cite:4]
- SMTP-based email workflows for visitor acknowledgments and internal notifications.[cite:16]
- Deployment practices that fit Hostinger constraints, especially Laravel’s public directory requirement, production caching, and non-persistent worker limitations on shared hosting.[cite:16][cite:17]

## Core stack

| Layer | Recommended choice |
|---|---|
| Backend framework | Laravel 13 [cite:16][cite:19] |
| AI integration | Laravel AI SDK (`laravel/ai`) [cite:1][cite:3][cite:5] |
| Database | MySQL / MariaDB [cite:16] |
| Queue driver | `database` for Hostinger shared hosting compatibility [cite:16][cite:17] |
| Frontend integration | Blade with Livewire or Blade + Alpine for admin and chat UI [cite:16] |
| Mail | Laravel Mail over SMTP [cite:16] |
| Tracking | Request-based referrer capture + UTM parsing + IP geolocation [cite:16] |
| Hosting target | Hostinger shared hosting or VPS [cite:7][cite:18][cite:20] |

Laravel AI SDK is the right fit because it provides a Laravel-native API for agents, tools, structured output, text generation, and other provider-backed AI workflows through a unified interface.[cite:1][cite:3][cite:4][cite:5]

## System architecture

The recommended backend flow is request-driven and database-centered.[cite:1][cite:16]

1. A visitor lands on the site with possible referrer and UTM metadata.
2. Middleware records visit context, IP address, referrer, campaign fields, and geolocation data when available.
3. Public forms and chat endpoints validate input and persist records immediately.
4. The AI chatbot sends conversation context to a Laravel AI SDK agent that returns both a natural-language reply and structured lead data.[cite:1][cite:4]
5. Structured data is merged into lead records and email jobs are queued.
6. The admin panel exposes content editing, lead management, contact submissions, and reporting views.

### Suggested modules

- Content management module.
- Chatbot and conversation memory module.
- Lead qualification and CRM-lite module.
- Contact form module.
- Email and notification module.
- Visitor tracking and attribution module.
- Admin reporting module.
- Deployment and operations module.

## Content management design

A complete CMS for this project should not be hardcoded page by page; instead, editable content should be normalized into reusable sections and keys so that nearly every visible part of the website can be changed without editing Blade templates.[cite:16]

### Recommended CMS entities

| Table / entity | Purpose |
|---|---|
| `site_contents` | Stores editable key-value content for each page and section |
| `pages` | Optional higher-level page registry for slugs, titles, status |
| `media` | Uploaded images, documents, and file metadata |
| `menus` / `menu_items` | Editable header and footer navigation |
| `settings` | Global settings such as contact email, phone, social links, SMTP display values |

### `site_contents` structure

A flexible content table should include these fields:

- `id`
- `page`
- `section`
- `key`
- `value`
- `type` (`text`, `textarea`, `richtext`, `image`, `json`, `html`, `boolean`)
- timestamps

This design allows content like `home.hero.title`, `home.hero.subtitle`, `contact.details.email`, and `footer.links.linkedin` to be updated from the backend while templates read values through a service or helper.[cite:16]

### CMS capabilities checklist

- Editable text for all pages.
- Editable image references and alt text.
- Editable SEO metadata for title, description, and OG image.
- Editable CTA labels and URLs.
- Contact page details and office addresses.
- Service cards, FAQs, testimonials, pricing blocks, and homepage sections.
- Reorderable sections if the UI needs drag-and-drop management.
- Audit logging for admin changes.

## AI chatbot design

Laravel AI SDK supports building agents with structured output and multi-provider AI integration, which makes it suitable for a business chatbot that must both converse naturally and extract specific lead fields.[cite:1][cite:3][cite:4][cite:5]

### Chatbot responsibilities

The chatbot should do two jobs in the same interaction:[cite:1][cite:4]

- Answer questions about services, pricing approach, process, timelines, support, and company capabilities.
- Progressively collect business intelligence from the visitor, including project idea, pain points, contact details, and urgency.[cite:1][cite:4]

### Chatbot behavior

The assistant should be instructed to:

- Start friendly and concise.
- Answer the current question first.
- Ask one relevant follow-up question when useful.
- Detect and extract name, email, phone, company, project type, budget, timeline, and free-text plan/idea.
- Avoid requesting everything at once.
- Mark a lead as qualified once key contact and project fields are present.

### Structured output pattern

A strong pattern is to require the agent to return JSON with at least:

```json
{
  "reply": "Natural-language answer for the user",
  "extracted": {
    "name": null,
    "email": null,
    "phone": null,
    "company": null,
    "project_type": null,
    "plan_or_idea": null,
    "budget": null,
    "timeline": null
  },
  "lead_status": "new",
  "send_ack_email": false
}
```

This approach makes backend automation deterministic because the application can persist structured fields without unreliable regex-only parsing.[cite:1][cite:4]

## Conversation persistence

The system should store both session-level metadata and message-level history so the chatbot can maintain context and the admin team can review what the visitor actually said.[cite:1][cite:4]

### Recommended tables

| Table | Purpose |
|---|---|
| `chat_sessions` | One row per visitor chat session |
| `chat_messages` | One row per message from user or assistant |
| `leads` | Consolidated qualified or partially qualified lead record |

### `chat_sessions` suggested fields

- `id`
- `session_uuid`
- `ip_address`
- `country`
- `region`
- `city`
- `referrer_url`
- `referrer_source`
- `utm_source`
- `utm_medium`
- `utm_campaign`
- `utm_term`
- `landing_page`
- `is_qualified`
- timestamps

### `chat_messages` suggested fields

- `id`
- `chat_session_id`
- `role` (`user`, `assistant`, `system`)
- `content`
- `structured_payload` JSON nullable
- timestamps

### `leads` suggested fields

- `id`
- `chat_session_id` nullable
- `source_type` (`chat`, `contact_form`, `manual`)
- `name`
- `email`
- `phone`
- `company`
- `project_type`
- `plan_or_idea`
- `budget`
- `timeline`
- `lead_status`
- `ip_address`
- `country`
- `city`
- `referrer_url`
- `referrer_source`
- `utm_source`
- `utm_medium`
- `utm_campaign`
- `notes`
- timestamps

## Contact and form management

Every input form on the website should submit into backend persistence rather than email-only handling, because email alone is not a durable system of record.[cite:16]

### Forms that should be stored

- Contact us form.
- Quote request form.
- Newsletter form.
- Book a call / consultation form.
- Download lead magnet form.
- Any landing-page campaign form.

### Suggested `contact_submissions` fields

- `id`
- `form_name`
- `name`
- `email`
- `phone`
- `subject`
- `message`
- `payload` JSON for extra fields
- `ip_address`
- `country`
- `city`
- `referrer_url`
- `referrer_source`
- `utm_source`
- `utm_medium`
- `utm_campaign`
- `thank_you_sent`
- `admin_notified`
- timestamps

### Form workflow

1. Validate server-side.
2. Persist submission.
3. Dispatch thank-you email to the visitor.
4. Dispatch internal notification email to the business inbox.
5. Optionally create or update a lead record.
6. Expose the submission in admin UI.

## SMTP and email workflow

Laravel’s deployment guidance emphasizes production configuration and caching, and for this system email should be treated as a first-class operational feature rather than an afterthought.[cite:16]

### Required emails

- Visitor thank-you email after contact form submission.
- Internal new-contact notification to the business owner.
- Visitor acknowledgment when a chatbot captures their email and key intent.
- Internal notification when a chat lead becomes qualified.
- Optional admin alerts for high-value or urgent leads.

### Recommended mail settings

- Use SMTP over SSL/TLS.
- Keep a dedicated mailbox such as `noreply@domain.com` or `hello@domain.com`.
- Configure an admin destination such as `sales@domain.com` or a personal business email.
- Queue mail sending so slow SMTP responses do not block the user request.[cite:16][cite:17]

### Mailables to create

| Class | Purpose |
|---|---|
| `ContactThankYouMail` | Confirmation to the visitor |
| `NewContactReceivedMail` | Internal business alert |
| `LeadCapturedMail` | Internal alert for qualified chatbot lead |
| `ChatAcknowledgementMail` | Optional visitor follow-up after chat capture |

## IP location tracking

The PHP GeoIP manual documents GeoIP-related functionality, but modern Laravel projects commonly rely on API-based services or maintained libraries because extension-based GeoIP approaches are less convenient in shared hosting environments.[cite:16]

### Practical recommendation

Use IP-based lookup through either:

- An external IP geolocation API.
- A maintained Laravel/PHP package backed by MaxMind or a similar provider.

This is generally more deployable on Hostinger than relying on legacy PHP GeoIP extension availability.[cite:16]

### Important limitations

- IP geolocation is approximate, not exact.
- Users on VPNs, corporate proxies, or mobile networks may appear in another city or country.
- Treat location as sales intelligence, not legal proof of identity.
- Publish a privacy policy describing collection of IP, analytics, and contact data.

### Data to capture

- IP address.
- Country.
- Region/state.
- City.
- Latitude/longitude if needed for analytics.
- ISP if needed for diagnostics.

## Referral and UTM tracking

Laravel routing and request handling make it straightforward to capture referrer headers and query parameters at entry time, and that data should be stored in session and copied into every downstream form or chat lead record.[cite:16]

### Recommended attribution fields

- `referrer_url`
- `referrer_source`
- `utm_source`
- `utm_medium`
- `utm_campaign`
- `utm_term`
- `utm_content`
- `landing_page`

### Source normalization

Map common domains into business-friendly labels:

- `linkedin.com` → `linkedin`
- `google.com` → `google`
- `facebook.com` → `facebook`
- `instagram.com` → `instagram`
- `youtube.com` → `youtube`
- unknown referrer → `referral`
- no referrer and no UTM → `direct`

### LinkedIn lead detail extraction

If campaign URLs include human-readable parameters or custom names, parse them from query parameters rather than brittle path assumptions.[cite:16]

Examples:

- `?lead_name=John%20Doe`
- `?company=Acme`
- `?campaign=linkedin_outreach_may`

## Admin panel requirements

The backend should expose a secure admin panel with clear separation between website content operations and lead operations.[cite:16]

### Admin modules

| Module | Key functions |
|---|---|
| Dashboard | Overview cards for visitors, leads, contacts, email health |
| Content | Edit page sections, global settings, navigation, SEO fields |
| Media | Upload and select images/files |
| Leads | Search, filter, update lead status, export |
| Chats | View chat sessions and full message history |
| Contacts | View form submissions and reply status |
| Analytics | Breakdown by country, source, campaign, page |
| Users | Admin authentication and role control |

### Lead filters

- Status.
- Source type.
- Country/city.
- UTM source.
- Campaign.
- Date range.
- Has email / missing email.

## Route design

Laravel’s routing system should separate public website traffic from authenticated admin operations and, if necessary, dedicated API endpoints for the chat widget.[cite:16]

### Public routes

- `GET /`
- `GET /about`
- `GET /services`
- `GET /contact`
- `POST /contact`
- `POST /chat`
- `POST /newsletter`

### Admin routes

- `GET /admin`
- `GET /admin/content`
- `POST /admin/content`
- `GET /admin/leads`
- `PATCH /admin/leads/{id}`
- `GET /admin/chats`
- `GET /admin/contacts`
- `GET /admin/settings`

### Middleware

- `auth`
- `verified` if needed
- `throttle` for chat and forms
- custom `track.visitor`
- custom `capture.utm`

## Validation and security

A system that collects visitor data and sends email must enforce strict validation and abuse protection in production.[cite:16][cite:17]

### Required protections

- CSRF on all Blade forms.
- Rate limiting on chat and contact submissions.
- Email validation and normalization.
- Phone and message length validation.
- Escaped output in Blade templates.
- Auth-protected admin routes.
- `APP_DEBUG=false` in production.[cite:17]
- Hidden `.env` and correct document root pointing only to `/public`.[cite:16][cite:17]

### Additional protections

- Honeypot or CAPTCHA on public forms.
- Block disposable email domains if quality matters.
- Store audit logs for admin edits.
- Consider queue retries and failed-jobs monitoring.

## Queue and job design

Laravel deployment guidance recommends production caching and optimized execution, and on Hostinger shared hosting the safest queue strategy is usually a database-backed queue processed via cron rather than a persistent daemon.[cite:16][cite:17]

### Recommended jobs

- `SendContactThankYouJob`
- `SendNewContactNotificationJob`
- `SendQualifiedLeadNotificationJob`
- `ProcessChatLeadJob` if extraction is decoupled
- `GeolocateVisitorJob` if lookup is deferred

### Hostinger-friendly queue strategy

- Use `QUEUE_CONNECTION=database`.
- Run `php artisan queue:work --once` or scheduler-driven queue processing from cron if persistent workers are not available.[cite:16][cite:17]

## Database schema summary

| Table | Main role |
|---|---|
| `users` | Admin authentication |
| `site_contents` | Editable page/section content |
| `settings` | Global site settings |
| `media` | Uploaded assets |
| `chat_sessions` | Visitor chat sessions |
| `chat_messages` | Stored conversation history |
| `leads` | Structured business leads |
| `contact_submissions` | Form submissions |
| `page_visits` | Visit logs and attribution |
| `jobs` / `failed_jobs` | Queued work and monitoring |

## Deployment on Hostinger

Laravel’s deployment documentation states that the server should point requests to `public/index.php`, recommends optimized Composer autoloading, and recommends running production cache commands such as `optimize`, `config:cache`, `route:cache`, and `view:cache` during deployment.[cite:16][cite:17]

### Deployment requirements

- PHP 8.3 or newer for Laravel 13.[cite:16]
- Required PHP extensions enabled on the host.[cite:16]
- Document root set to the project’s `public` directory.[cite:16][cite:17]
- Proper write permissions for `storage/` and `bootstrap/cache/`.

### Shared hosting deployment steps

1. Upload the project files.
2. Install Composer dependencies with optimized autoloading and no dev packages.[cite:17]
3. Create and configure `.env`.
4. Run `php artisan key:generate`.
5. Run migrations.
6. Configure storage link if uploads are used.
7. Run `php artisan optimize` plus deployment caches.[cite:16]
8. Configure cron for queue processing and scheduled tasks.
9. Verify mail, chat, forms, and admin login in production.

### Caching commands

- `php artisan optimize`.[cite:16]
- `php artisan config:cache`.[cite:16]
- `php artisan route:cache` for larger apps.[cite:16]
- `php artisan view:cache`.[cite:16]
- `php artisan event:cache` if event discovery is used.[cite:16]

## Recommended project structure

```text
app/
  AI/
    Agents/
      LeadChatAgent.php
  Http/
    Controllers/
      Admin/
        ContentController.php
        LeadController.php
        ContactSubmissionController.php
        AnalyticsController.php
      ChatController.php
      ContactController.php
    Middleware/
      TrackVisitor.php
      CaptureAttribution.php
  Mail/
    ContactThankYouMail.php
    NewContactReceivedMail.php
    LeadCapturedMail.php
  Models/
    SiteContent.php
    ChatSession.php
    ChatMessage.php
    Lead.php
    ContactSubmission.php
    PageVisit.php
    Setting.php
  Services/
    ContentService.php
    GeolocationService.php
    AttributionService.php
resources/views/
  admin/
  components/
  emails/
database/migrations/
routes/
  web.php
  api.php
```

## End-to-end workflows

### Chat lead workflow

1. Visitor opens chat.
2. Session is created with IP, landing page, and attribution.
3. User asks a question.
4. Message is saved.
5. Agent receives recent conversation context and returns reply plus structured extraction.[cite:1][cite:4]
6. Assistant message is saved.
7. Lead record is created or updated.
8. If an email is captured and qualification threshold is met, a notification job is queued.

### Contact form workflow

1. Visitor submits form.
2. Server validates request.
3. Submission is stored.
4. Lead record is created or updated.
5. Thank-you email is queued to the visitor.
6. Internal alert is queued to the business inbox.
7. Admin sees the submission in dashboard.

### CMS update workflow

1. Admin logs in.
2. Admin edits content blocks or settings.
3. Data is saved in CMS tables.
4. Relevant cache is cleared.
5. Frontend renders updated content immediately.

## Implementation TODOs

### Phase 1: foundation

- Install Laravel 13 and verify PHP 8.3 compatibility.[cite:16]
- Add `laravel/ai` package and configure provider keys.[cite:1][cite:5]
- Configure database, mail, cache, queue, and session drivers.
- Set up admin authentication.

### Phase 2: CMS

- Create `site_contents`, `settings`, and optional `media` tables.
- Build content helper/service layer.
- Build admin CRUD UI for pages, sections, and settings.
- Add image upload and storage strategy.

### Phase 3: AI chatbot

- Create `LeadChatAgent`.
- Define structured response contract.
- Build `chat_sessions` and `chat_messages` persistence.
- Implement `POST /chat` endpoint.
- Add frontend widget and typing UX.

### Phase 4: lead management

- Create `leads` table and merge logic.
- Define qualification rules.
- Add lead status workflow.
- Build lead list, detail, notes, and export.

### Phase 5: contact workflows

- Build contact form controller and validation.
- Create `contact_submissions` table.
- Add mailables and queued jobs.
- Add admin contact listing.

### Phase 6: tracking

- Add visitor tracking middleware.
- Capture referrer and UTM fields.
- Add geolocation service.
- Persist page visits and copy attribution into leads/forms.

### Phase 7: deployment

- Point Hostinger domain to `/public`.[cite:16][cite:17]
- Upload code and install production dependencies.[cite:17]
- Run migrations and caches.[cite:16]
- Configure cron-driven queue processing.
- Smoke test SMTP, forms, admin, and AI endpoints.

## Final recommendations

This project should be built as one unified Laravel application rather than separate microsystems, because the same visitor attribution, lead model, admin panel, and email workflows should be shared across CMS, chat, and contact features.[cite:16]

Laravel AI SDK should be used as the orchestration layer for conversational intelligence and structured extraction, while standard Laravel controllers, models, queues, and mailables should own persistence, notification, and operational reliability.[cite:1][cite:3][cite:4][cite:5]

For Hostinger, design conservatively around shared-hosting realities: database queue, cron-driven jobs, strong caching, and strict public-directory deployment discipline.[cite:16][cite:17]
