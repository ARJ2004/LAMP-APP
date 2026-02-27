# College ERP (LAMP, PHP + MySQL)

A minimal, extensible College ERP starter app with authentication, RBAC, dashboards, Student Management (CRUD), class-based attendance, and subject-wise result entry.

## Features
- PHP 8+ + MySQL 8+ + Apache compatible structure.
- Session-based authentication.
- Role-based authorization middleware.
- CSRF protection helper.
- Student module: list/search/create/edit/delete.
- Attendance module: mark by class (department + semester + batch) and optional subject, then view history.
- Result entry module: enter marks by class + subject for only enrolled students.
- Teacher dashboard for faculty workflows.
- SQL schema and seed script.

## Quick start
1. Copy environment file:
   ```bash
   cp .env.example .env
   ```
2. Create database and apply schema:
   ```bash
   mysql -u root -p < sql/schema.sql
   ```
3. Seed roles/admin/demo users + sample students:
   ```bash
   php scripts/seed.php
   ```
4. Run locally:
   ```bash
   php -S 0.0.0.0:8080 -t public
   ```
5. Login with seeded users (password: `Password@123`).

## Seeded roles
- super_admin
- admin
- faculty
- student
- accountant
- librarian

## Notes
- Change DB credentials in `.env` before running.
- For Apache, point document root to `public/`.
- Attendance routes are available at `/attendance` and `/attendance/history` for `super_admin`, `admin`, and `faculty`.
- Result entry route is available at `/results` for `super_admin`, `admin`, and `faculty`.
- Faculty-only teacher dashboard is available at `/teacher/dashboard`.
