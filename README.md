# Attendance Management System (Laravel 12)

A role-based (Admin / Teacher / Student) attendance management system.

## ⚠️ Honest scope note

This delivers a **real, working core** of the system, not the entire wishlist from the original spec. Included:

- Auth (login, logout, forgot/reset password, remember me)
- Role-based middleware (Admin / Teacher / Student) protecting routes
- Full DB schema: users, roles, departments, teachers, students, classes, subjects, academic_years, semesters, attendance, attendance_details, holidays, notifications
- Eloquent models with relationships (HasMany, BelongsTo, BelongsToMany)
- Admin CRUD: Students, Teachers, Departments, Classes, Subjects (search, filter, pagination, photo upload)
- Attendance: session selection → roster → mark Present/Absent/Late/Leave/Holiday per student
- Dashboards: Admin (stats + charts-style progress bars), Teacher, Student (attendance % + history)
- Profile edit, photo upload, password change
- Responsive Bootstrap 5 UI, flash messages, validation errors

**Not included** (flagged so nothing is silently missing): PDF/Excel export, daily/weekly/yearly report breakdowns beyond the filterable list, in-app notification bell/broadcast, Policies, factories for automated testing, and the academic documentation/diagrams (ER/DFD/use-case) requested in the brief. These are straightforward to add on top of this foundation — ask if you'd like any of them built out next.

Also: this sandbox can't reach Packagist, so `vendor/` was never installed here and the app has **not been booted/tested**. Run it locally to verify (steps below).

## Setup

```bash
composer install
cp .env.example .env
php artisan key:generate

# create a MySQL database named attendance_system (or edit .env)
php artisan migrate --seed

php artisan storage:link
php artisan serve
```

Visit `http://localhost:8000`.

## Seeded demo accounts (password for all: `password`)

| Role    | Email                     |
|---------|----------------------------|
| Admin   | admin@attendance.test      |
| Teacher | teacher@attendance.test    |
| Student | alice@attendance.test      |
| Student | bob@attendance.test        |

## Notes

- `composer.json` includes `barryvdh/laravel-dompdf` and `maatwebsite/excel` as dependencies for when you add PDF/Excel export — they aren't wired into any controller yet.
- Add a default avatar image at `public/images/default-avatar.png` (referenced by `User::photoUrl()`).
# Student-Attendance-management-system
