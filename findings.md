# Findings: Blog and SEO/AEO/GEO Integration

## Database Schema & Models
- **Current Model:** `backend/app/Models/BlogPost.php`
- **Fields:** `title`, `slug`, `content`, `image`, `author`, `published_at`
- **Missing Columns:** We need a metadata column like `seo_meta` (JSON) to store SEO/AEO/GEO optimization results (FAQ schemas, target keywords, custom SEO titles and meta descriptions, readability scores, and AI recommendations).
- **Current Migration:** `2026_05_20_000009_create_blog_posts_table.php`. We will write a new migration file `2026_05_24_000001_add_seo_meta_to_blog_posts_table.php` to add this column.

## Backend APIs
- No existing routes or controllers for blog posts exist in `backend/routes/api.php` or `backend/app/Http/Controllers`.
- We need to write:
  - `backend/app/Http/Controllers/BlogPostController.php` with standard RESTful routes.
  - CRUD operations and a special `POST /api/blogs/optimize` endpoint using the Groq API (or laravel/ai SDK if installed) to analyze and generate optimized SEO/AEO/GEO content structure.

## Frontend Routing
- Existing routes in `src/App.jsx` only include `/blog` which points to the list page.
- We need to register:
  - `/blog/:slug` pointing to `src/pages/BlogReadingPage.jsx` for viewing specific articles.
  - `/admin/blog` or `/blog-editor` pointing to `src/pages/BlogEditorPage.jsx` for the editing interface.

## SEO / AEO / GEO Implementation Details
- **SEO Optimization:** The blog reading page will inject meta tags dynamically (title, description, open graph tags) based on the database fields.
- **AEO Optimization:** The blog reading page will render structured FAQ schemas (using standard JSON-LD script tags) generated automatically during writing.
- **GEO Optimization:** The editor sidebar will provide real-time checklist feedback checking:
  - Presence of clear statistical evidence.
  - Placement of a concise direct answer immediately under a primary header.
  - Objective tone checks.
