# Outlook SMTP Integration Design

This document details the configuration changes and local verification steps for switching ClimbSphere's backend email provider to Outlook SMTP.

## Goals
- Switch email provider from Hostinger to Outlook (`climbtest2026@outlook.com`).
- Verify SMTP connectivity and correct sending functionality locally.
- Run both backend and frontend locally to test the end-to-end contact form email flow.

## 1. Backend Configuration
Update `backend/.env` with the following parameters:
- `MAIL_MAILER=smtp`
- `MAIL_HOST=smtp.office365.com`
- `MAIL_PORT=587`
- `MAIL_USERNAME=climbtest2026@outlook.com`
- `MAIL_PASSWORD="adhipoDa@2026"`
- `MAIL_ENCRYPTION=tls`
- `MAIL_FROM_ADDRESS="climbtest2026@outlook.com"`
- `MAIL_ADMIN_RECIPIENT=climbtest2026@outlook.com`

## 2. Frontend Configuration
Check the React frontend configurations and ensure the API calls point to the local Nginx web container (`http://localhost:8000`).

## 3. Verification Plan
- **Step 1**: Start Docker Compose containers (`db`, `app`, `web`, `queue`).
- **Step 2**: Execute a test PHP script inside the `app` container to send a direct SMTP mail using Laravel's Mail facade.
- **Step 3**: Launch the React/Vite development server.
- **Step 4**: Perform a live form submission and trace the request through the backend queue to verify SMTP sending works under realistic conditions.
