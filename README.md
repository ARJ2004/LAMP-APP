# College ERP (LAMP, PHP + MySQL)

A minimal, extensible College ERP starter app with authentication, RBAC, dashboards, Student Management (CRUD), class-based attendance, and subject-wise result entry.

## Features
- PHP 8+ + MySQL 8+ + Apache compatible structure.
- Session-based authentication with role-based authorization.
- Student module: list/search/create/edit/delete.
- **Super Admin Student Registration**: create student login + student profile in one action.
- **Course Management**: super admin creates courses, admin/super admin add subjects.
- **Student Course Registration**: student users can self-register to courses.
- **Attendance by Subject**: mark attendance for enrolled students in a selected course subject.
- **Fee Structures + Billing**: super admin defines course fee structures, bills auto-generated for enrolled students.
- **Student Fee Payment + Invoice**: student pays pending bill and gets invoice.
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
3. Seed roles/users/sample data:
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
- Student self-service routes:
  - `/courses/register`
  - `/billing/my-bills`
