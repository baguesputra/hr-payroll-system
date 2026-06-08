# HR Payroll System

A comprehensive HR & Payroll Management System built with Laravel 13 and React.

## Tech Stack

**Backend:** Laravel 13, PHP 8.3, PostgreSQL, Redis  
**Frontend:** React 19, TypeScript, Tailwind CSS, shadcn/ui  
**Infrastructure:** Docker, GitHub Actions  

## Features

- Multi-role authentication (Super Admin, HR Manager, Finance, Employee)
- Employee management with position & salary history
- Multi-shift management with roster scheduling
- Attendance tracking (manual, self check-in, CSV import)
- Leave management with carry-over & encashment
- Overtime calculation (UU Ketenagakerjaan compliant)
- Automated payroll with PPh 21 & BPJS calculation
- Payslip PDF generation & email delivery
- Audit trail for all sensitive operations

## Requirements

- Docker & Docker Compose
- Node.js 20+
- Composer

## Quick Start

```bash
# Clone repository
git clone https://github.com/USERNAME/hr-payroll-system.git
cd hr-payroll-system

# Backend setup
cp backend/.env.example backend/.env
docker compose up -d --build
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate --seed

# Frontend setup
cd frontend
cp .env.example .env
npm install
npm run dev
```

## Services

| Service | URL |
|---|---|
| Laravel API | http://localhost:8000 |
| React Frontend | http://localhost:5173 |
| API Documentation | http://localhost:8000/docs |
| Mailpit | http://localhost:8025 |

## Architecture

Monolith REST API + SPA Frontend with Service + Action + FormRequest + DTO pattern.

## License

MIT