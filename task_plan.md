# Task Plan: Local Blog Reading Page & WordPress-like Editor with SEO/AEO/GEO Optimization

## Goal
Implement a fully local Blog Reading template, a real-time WordPress-like Blog Editor with live preview, and integrated SEO/AEO/GEO optimization tools in the frontend and backend.

## Current Phase
Phase 1: Requirements & Discovery

## Phases

### Phase 1: Requirements & Discovery
- [x] Analyze current `BlogPost` model and migration files.
- [x] Check backend routing and controllers.
- [x] Define API endpoint structure and new database fields needed.
- [x] Document details in `findings.md`.
- **Status:** complete

### Phase 2: Backend API & Database Schema Updates
- [x] Create database migration to add `seo_meta` (JSON column) to `blog_posts` table.
- [x] Update `BlogPost` model to support `seo_meta` cast and fillable array.
- [x] Create `BlogPostController` with CRUD actions (`index`, `show`, `store`, `update`, `destroy`).
- [x] Define routes in `backend/routes/api.php` for blog management and AI optimization.
- [x] Implement AI Optimization endpoint (using existing Groq credentials or provider configs to generate AEO summaries, SEO meta, and GEO FAQs).
- **Status:** complete

### Phase 3: Frontend Blog Reading Template
- [x] Create `src/pages/BlogReadingPage.jsx` and `src/pages/BlogReadingPage.css` with clean, readable typography.
- [x] Update `src/components/Blog.jsx` to fetch posts dynamically from the backend and link to `/blog/:slug`.
- [x] Register the new `/blog/:slug` route in `src/App.jsx`.
- **Status:** complete

### Phase 4: WordPress-like Blog Editor & Live Preview
- [x] Create `src/pages/BlogEditorPage.jsx` and `src/pages/BlogEditorPage.css` at a dedicated admin route (e.g. `/admin/blog/write` or `/blog-editor`).
- [x] Build a split-screen layout: left side is the editor with insert/format toolbar (headings, bold, lists, links, image insert), right side is the live rendering preview.
- [x] Connect the editor save action to the backend API (`POST` / `PUT`).
- **Status:** complete

### Phase 5: Real-time SEO/AEO/GEO Sidebar Panel
- [x] Add an optimization sidebar in the editor showing real-time feedback (word count, direct answer placement, E-E-A-T indicators).
- [x] Implement an "AI Optimize" button that calls the backend AI endpoint to auto-generate SEO description, AEO summaries, and Schema JSON-LD.
- [x] Display the generated FAQ list and meta tags, automatically inserting them into the post's `seo_meta` block.
- **Status:** complete

### Phase 6: Verification & Build
- [x] Run database migrations on backend container.
- [x] Create a database seeder for default blog posts.
- [x] Verify writing, editing, optimization, and reading of blog posts.
- [x] Run `npm run build` to ensure the React client compiles successfully.
- **Status:** complete

### Phase 7: B2B Brand Alignment & SEO/AEO/GEO Integration
- [x] Integrate SEO and AEO quick answers in `Home.jsx`
- [x] Integrate SEO and AEO quick answers in `AboutPage.jsx`
- [x] Integrate SEO and AEO quick answers in `ServicesPage.jsx`
- [x] Integrate SEO and AEO quick answers in `ContactPage.jsx`
- [x] Update chatbot widget welcome message in `ChatbotWidget.jsx`
- [x] Update chatbot lead qualification agent schema in `LeadChatAgent.php`
- [x] Verify compiling and styling via `npm run build`
- **Status:** complete

## Key Questions
1. **How should we handle images for blog posts?** (We will support image URL text fields and base64/upload tools, saving to backend public uploads).
2. **What AI model will we use for the backend optimizer?** (We will use the existing Groq config setup in the backend, utilizing llama3-8b/70b or similar for super fast completions).
3. **How does the frontend render the rich text content?** (We will support standard markdown in the editor, and render it using a clean HTML layout or markdown renderer).

## Decisions Made
| Decision | Rationale |
|----------|-----------|
| Add JSON `seo_meta` column | Keeps SEO titles, description, AEO FAQ schemas, and target keywords clean and flexible without modifying the database schema for every new metadata field. |
| Use Markdown for the editor | Simple, lightweight, robust, and highly compatible with both React rendering and AI parsing (GEO/AEO). |

## Errors Encountered
| Error | Attempt | Resolution |
|-------|---------|------------|
|       | 1       |            |

## Notes
- Update phase status as you progress: pending → in_progress → complete
- Re-read this plan before major decisions (attention manipulation)
- Log ALL errors - they help avoid repetition

