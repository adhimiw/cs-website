# ClimbSphere Workspace Guidelines & Invariants

## Architecture Overview
- **Dual-tier Architecture**:
  - Root directory (`./`): React 19 + Vite 8 SPA.
  - Backend directory (`./backend`): Laravel 13 + Filament 3 Admin + MySQL.
- **Frontend Development**: Port 5173 (`http://localhost:5173`).
- **Backend API & Admin**: Port 8000 (`http://localhost:8000`). Filament Admin accessible at `/admin`.
- **Database**: MySQL 8.0 on port 3307 (host) / 3306 (internal container network).

## Invariants & Coding Standards
1. **API Origin Resolution**: Always use `getApiUrl(path)` from `src/context/CMSContext.jsx` for frontend API and asset paths.
2. **CMS Fallback**: Never assume backend APIs are guaranteed to be populated; always maintain sensible static defaults in CMS consumers.
3. **Admin Routing**: Filament admin routes (`/admin/*`) and API routes (`/api/*`) are handled by Laravel. Never add overlapping client-side routes in React for those prefixes.
4. **Environment Variables**:
   - Frontend: Prefixed with `VITE_` (e.g., `VITE_API_BASE_URL`).
   - Backend: Standard Laravel `.env` (`APP_KEY`, `DB_*`, `GROQ_API_KEY`, etc.).
5. **Docker Workflow**:
   - Run the complete local stack from the repository root via `docker compose up -d`.
   - Never commit sensitive `.env` files or credentials to Git.
