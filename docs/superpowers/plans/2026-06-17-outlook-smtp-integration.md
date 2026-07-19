# Outlook SMTP Integration & Local Testing Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Switch the backend email configurations to Outlook SMTP (`climbtest2026@outlook.com` / `adhipoDa@2026`), spin up the local Docker compose stack, and test the email dispatch successfully both directly via CLI and end-to-end via the React frontend.

**Architecture:** Update the local Laravel `.env` variables for SMTP, start the Docker containers (Nginx, PHP-FPM, MySQL, and Queue worker), run migrations, write a script to test email delivery, start the frontend Vite server, and verify form submissions end-to-end.

**Tech Stack:** Laravel, React, Vite, Nginx, Docker, MySQL, Outlook SMTP.

---

### Task 1: Update Backend Environment Configuration

**Files:**
- Modify: `backend/.env`

- [ ] **Step 1: Replace hostinger mail credentials with Outlook SMTP**
  Update the following settings:
  ```env
  MAIL_MAILER=smtp
  MAIL_SCHEME=null
  MAIL_HOST=smtp.office365.com
  MAIL_PORT=587
  MAIL_USERNAME=climbtest2026@outlook.com
  MAIL_PASSWORD="adhipoDa@2026"
  MAIL_ENCRYPTION=tls
  MAIL_FROM_ADDRESS="climbtest2026@outlook.com"
  MAIL_FROM_NAME="ClimbSphere"
  MAIL_ADMIN_RECIPIENT=climbtest2026@outlook.com
  ```
- [ ] **Step 2: Save the file and confirm changes**

---

### Task 2: Boot Backend Docker Stack

**Files:**
- Modify: None (pure command task)

- [ ] **Step 1: Stop existing container instances to clear state**
  Run: `docker compose down` inside `backend/`
- [ ] **Step 2: Build and start the containers**
  Run: `docker compose up -d`
- [ ] **Step 3: Verify all containers are healthy and running**
  Run: `docker ps`
  Expected output should list `climbsphere-backend-app`, `climbsphere-backend-web`, `climbsphere-backend-db`, and `climbsphere-backend-queue`.
- [ ] **Step 4: Run Laravel migrations & seed**
  Run: `docker exec -it climbsphere-backend-app php artisan migrate --force`

---

### Task 3: Create and Execute Direct SMTP Mail Script

**Files:**
- Create: `backend/test_smtp.php`

- [ ] **Step 1: Write a testing PHP script that triggers Laravel mail sending**
  ```php
  <?php
  require __DIR__.'/bootstrap/app.php';
  
  $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
  $kernel->bootstrap();
  
  echo "Sending test SMTP email to climbtest2026@outlook.com...\n";
  try {
      Illuminate\Support\Facades\Mail::raw('This is a local SMTP integration test message from ClimbSphere.', function ($message) {
          $message->to('climbtest2026@outlook.com')
                  ->subject('Local SMTP Test - ClimbSphere');
      });
      echo "SUCCESS: Email sent successfully.\n";
  } catch (\Throwable $e) {
      echo "ERROR: Failed to send email.\n";
      echo "Message: " . $e->getMessage() . "\n";
      echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
  }
  ```
- [ ] **Step 2: Execute test script inside the app container**
  Run: `docker exec -it climbsphere-backend-app php test_smtp.php`
  Verify the output is `SUCCESS: Email sent successfully.`.
- [ ] **Step 3: Remove the testing script to clean up**
  Remove file: `backend/test_smtp.php`

---

### Task 4: Run Frontend Development Server

**Files:**
- Modify: None (pure command task)

- [ ] **Step 1: Start React/Vite development server**
  Run: `npm run dev` in the root workspace directory (`c:\Users\adhit\Desktop\website\climbsphere-react`)
- [ ] **Step 2: Verify Vite server is active**
  Expected: Vite logs indicating server is listening on `http://localhost:5173`.

---

### Task 5: End-to-End Verification

**Files:**
- Modify: None (testing task)

- [ ] **Step 1: Open the local website in the browser**
  Go to: `http://localhost:5173`
- [ ] **Step 2: Submit the contact form**
  Fill out the contact form with test details (e.g., test name, email, and message) and submit.
- [ ] **Step 3: Verify database records**
  Check that the submission record is created in the database.
  Run: `docker exec -it climbsphere-backend-app php artisan tinker --execute="print_r(App\Models\ContactSubmission::latest()->first()->toArray())"`
- [ ] **Step 4: Verify email queue delivery**
  Watch queue worker logs to verify the email job is processed and sent.
  Run: `docker logs climbsphere-backend-queue`
