# CLEARX - Smart Digital No-Due Management System

CLEARX is a full-stack PHP/MySQL college clearance platform with role-based dashboards for Students, Faculty, HODs, Principals and Super Admins.

## Requirements

- XAMPP or PHP 8+ with PDO MySQL
- MySQL 8+ or MariaDB 10.4+
- Apache
- A modern browser

## Installation

1. Copy this project into `C:\xampp\htdocs\CLEARX`.
2. Start Apache and MySQL in XAMPP.
3. Open phpMyAdmin and import `database/database.sql`.
4. If needed, update database credentials in `config/database.php` or set `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER` and `DB_PASSWORD` environment variables.
5. Open `http://localhost/CLEARX/`.

## Test accounts

All sample accounts use password `password`:

- Super Admin: `ADMIN001`
- Principal: `PRINCIPAL01`
- HOD: `HOD001`
- Faculty: `FAC001`
- Student: `1CR23CS001`

Accounts can also be accessed with the corresponding email address.

## Workflow

1. Student submits a request.
2. Faculty verifies the assigned department.
3. HOD reviews and approves or rejects the department clearance.
4. Principal performs final approval only after all departments and HOD approvals are complete.
5. CLEARX generates a unique certificate and public verification URL.

The student certificate page provides a print button that uses the browser print dialog, where "Save as PDF" can be selected.
