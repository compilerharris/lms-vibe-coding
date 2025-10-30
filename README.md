# Lead Assignment App (Laravel)

A role‑based lead management and assignment system built with Laravel and MySQL. It supports Admin, Leader, Developer, Channel Partner (CP), Customer Service (CS) and Biddable roles. Leads are assigned to mapped channel partners in a deterministic, fair order using CP numbers and the last assignment history.

## Installation & Usage

1) Clone the repository
```bash
git clone https://github.com/compilerharris/lms-vibe-coding.git
cd lms-vibe-coding/lead-assignment-app
```

2) Install PHP dependencies
```bash
composer install
```

3) Environment
```bash
cp .env.example .env
# Edit .env and set your DB_* values (MySQL), APP_URL, etc.
```

4) Generate key & run migrations
```bash
php artisan key:generate
php artisan migrate
```

5) (Optional) Seed or create users and roles via UI/SQL as needed.

6) Run the app
```bash
php artisan serve
```

7) A simple API test page is available under `public/` and a minimal demo frontend exists at `lead-assignment-app-frontend/`.

## Features

- Multi‑role access: Admin, Leader, Developer, Channel Partner, CS, Biddable.
- Developer ↔ CP Mapping:
  - Admin UI to map channel partner users to developer users.
  - “Edit” action on each developer card pre‑fills the mapping form with already mapped CPs.
  - Only mapped CPs appear where appropriate (e.g., Developer dashboard, Lead edit form).
- Deterministic CP assignment:
  - Each CP user has a unique integer `cp_number` (migration populates missing numbers; unique index enforced at DB).
  - For each developer+project, the system checks the last assigned CP. If last was number N, the next goes to the smallest number > N; if none, it cycles to the lowest number.
- Lead edit safety: In Edit Lead, the “Channel Partner” dropdown lists only CPs mapped to the selected lead’s project’s developer.
- Dashboards:
  - Developer dashboard shows only mapped CPs, plus analytics: total leads, converted leads, conversion rate, active projects, charts.
  - CS/Biddable dashboard with metrics and recent leads.
  - Channel Partner dashboard lists the CP’s assigned leads with analytics.
- API endpoints (for landing pages/integrations):
  - `POST /api/v1/leads` – create a lead for a given `developer_alt_name` and `project_alt_name`.
  - `GET  /api/v1/developers-projects` – list developers with active projects and alt names.
- QoL: Select2 multi‑select, DataTables for tables, and Chart.js charts.

## Technologies Used

- PHP 8.2+
- Laravel (Blade, Eloquent, Migrations)
- MySQL
- Bootstrap 5, Select2, DataTables, Chart.js
- Vanilla JS / jQuery for light interactions

## API – Quick Start

Create lead
```http
POST /api/v1/leads
Content-Type: application/json
Accept: application/json

{
  "name": "John Doe",
  "email": "john@example.com",
  "phone": "+1234567890",
  "source": "Website",
  "message": "Interested",
  "developer_alt_name": "DEVXXXXX",
  "project_alt_name":   "PRJYYYYY"
}
```

Get developers & projects
```http
GET /api/v1/developers-projects
Accept: application/json
```

## Folder Structure (excerpt)

```
lms-vibe-coding/
└── lead-assignment-app/
    ├── app/
    │   ├── Http/Controllers/
    │   │   ├── Api/LeadApiController.php
    │   │   ├── CSDashboardController.php
    │   │   ├── CPDashboardController.php
    │   │   ├── DeveloperDashboardController.php
    │   │   ├── DeveloperMappingController.php
    │   │   └── LeadController.php
    │   └── Models/
    ├── database/migrations/
    ├── public/
    ├── resources/views/
    ├── routes/web.php
    ├── routes/api.php
    └── README.md
```

## Development Notes

- `.env` and `vendor/` are intentionally ignored by Git. Copy `.env.example` to `.env` and set your environment.
- Migrations include helpers to populate missing `cp_number` and add a unique index.
- The mapping UI ensures a CP is active and can be mapped to a single developer at a time.

## Stay in touch

- LinkedIn - [@linkedin-compilerharris](https://www.linkedin.com/in/compilerharris)
- Medium  - [@medium-compilerharris](https://medium.com/@compilerharris)
- Twitter - [@compilerharris](https://twitter.com/compilerharris)

## Author

Haris Shaikh.
