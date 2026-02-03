# SIDAR Laravel 10 - Modern HRIS System

## 📋 Project Information

**Project Name**: SIDAR Laravel 10  
**Framework**: Laravel 10.x  
**PHP Version**: ^8.1  
**Created**: 3 Februari 2026  
**Purpose**: Modern rebuild of SIDAR HRIS system with best practices

---

## 🎯 Project Goals

Rebuild SIDAR (Sistem Informasi Daily Activity Report) menggunakan Laravel 10 dengan menerapkan:

1. ✅ **Modern Architecture** - Clean code, SOLID principles
2. ✅ **Best Practices** - Laravel standards, PSR-12
3. ✅ **Security First** - Proper validation, authorization
4. ✅ **Testable Code** - Unit & Feature tests
5. ✅ **API-First Design** - RESTful API with versioning
6. ✅ **Database Migrations** - Version controlled schema
7. ✅ **Proper Relationships** - Eloquent ORM
8. ✅ **Service Layer** - Separation of concerns

---

## 📦 Installed Packages

### Core Dependencies

```json
{
    "php": "^8.1",
    "laravel/framework": "^10.10",
    "laravel/sanctum": "^3.3",
    "laravel/tinker": "^2.8",
    "guzzlehttp/guzzle": "^7.2"
}
```

### Development Dependencies

```json
{
    "fakerphp/faker": "^1.9.1",
    "laravel/pint": "^1.0",
    "laravel/sail": "^1.18",
    "mockery/mockery": "^1.4.4",
    "nunomaduro/collision": "^7.0",
    "phpunit/phpunit": "^10.1",
    "spatie/laravel-ignition": "^2.0"
}
```

---

## 🚀 Quick Start

### 1. Environment Setup

Copy `.env.example` to `.env`:

```bash
cp .env.example .env
```

### 2. Database Configuration

Edit `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sidar_laravel_10
DB_USERNAME=root
DB_PASSWORD=
```

### 3. Install Dependencies

```bash
composer install
npm install
```

### 4. Generate Application Key

```bash
php artisan key:generate
```

### 5. Run Migrations

```bash
php artisan migrate
```

### 6. Seed Database (Optional)

```bash
php artisan db:seed
```

### 7. Start Development Server

```bash
php artisan serve
```

Visit: `http://localhost:8000`

---

## 📁 Project Structure

```
sidar-laravel-10/
├── app/
│   ├── Console/
│   ├── Exceptions/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Api/
│   │   │   │   └── V1/
│   │   │   └── Web/
│   │   ├── Middleware/
│   │   ├── Requests/
│   │   └── Resources/
│   ├── Models/
│   ├── Policies/
│   ├── Providers/
│   └── Services/
├── bootstrap/
├── config/
├── database/
│   ├── factories/
│   ├── migrations/
│   └── seeders/
├── public/
├── resources/
│   ├── css/
│   ├── js/
│   └── views/
├── routes/
│   ├── api.php
│   ├── web.php
│   ├── channels.php
│   └── console.php
├── storage/
├── tests/
│   ├── Feature/
│   └── Unit/
└── vendor/
```

---

## 🎨 Architecture Plan

### 1. Models (Eloquent ORM)

```
Models/
├── User.php
├── Employee.php
├── Dar.php
├── Attendance.php
├── Leave.php
├── Claim.php
├── Allowance.php
├── Letter.php
└── Department.php
```

**Features:**

- Eloquent relationships
- Model observers
- Accessors & Mutators
- Query scopes
- Soft deletes

### 2. Controllers (Thin Controllers)

```
Controllers/
├── Api/
│   └── V1/
│       ├── AuthController.php
│       ├── DarController.php
│       ├── AttendanceController.php
│       ├── LeaveController.php
│       └── ClaimController.php
└── Web/
    ├── DashboardController.php
    ├── DarController.php
    └── ReportController.php
```

**Principles:**

- Single responsibility
- Dependency injection
- Form request validation
- Resource transformers

### 3. Services (Business Logic)

```
Services/
├── DarService.php
├── AttendanceService.php
├── LeaveService.php
├── ClaimService.php
├── ApprovalService.php
└── NotificationService.php
```

### 4. Requests (Validation)

```
Requests/
├── Dar/
│   ├── StoreDarRequest.php
│   └── UpdateDarRequest.php
├── Attendance/
│   └── StoreAttendanceRequest.php
└── Leave/
    └── StoreLeaveRequest.php
```

### 5. Resources (API Transformers)

```
Resources/
├── DarResource.php
├── DarCollection.php
├── AttendanceResource.php
└── UserResource.php
```

### 6. Policies (Authorization)

```
Policies/
├── DarPolicy.php
├── LeavePolicy.php
└── ClaimPolicy.php
```

---

## 🗄️ Database Design

### Migration Strategy

1. **Create migrations for all tables**
2. **Use foreign keys properly**
3. **Add indexes for performance**
4. **Implement soft deletes**
5. **Version control schema**

### Key Tables

```sql
-- Users & Authentication
users
employees
roles
permissions

-- Core Features
dars
dar_attachments
attendances
leaves
claims
allowances
letters

-- Supporting
departments
divisions
locations
calendars
distributors
```

---

## 🔐 Authentication & Authorization

### Sanctum API Authentication

```php
// API routes protected with Sanctum
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('dars', DarController::class);
    Route::apiResource('attendances', AttendanceController::class);
});
```

### Role-Based Access Control

```php
// Using Gates
Gate::define('approve-dar', function (User $user, Dar $dar) {
    return $user->can_approve && $user->id === $dar->approver_id;
});

// Using Policies
$this->authorize('update', $dar);
```

---

## 🧪 Testing Strategy

### Unit Tests

```php
tests/Unit/
├── Models/
│   ├── UserTest.php
│   └── DarTest.php
└── Services/
    └── DarServiceTest.php
```

### Feature Tests

```php
tests/Feature/
├── Auth/
│   └── LoginTest.php
├── Dar/
│   ├── CreateDarTest.php
│   └── ApproveDarTest.php
└── Api/
    └── DarApiTest.php
```

### Run Tests

```bash
php artisan test
php artisan test --coverage
```

---

## 📊 API Design

### RESTful API v1

```
Base URL: /api/v1

Authentication:
POST   /api/v1/login
POST   /api/v1/logout
POST   /api/v1/refresh

DAR:
GET    /api/v1/dars
POST   /api/v1/dars
GET    /api/v1/dars/{id}
PUT    /api/v1/dars/{id}
DELETE /api/v1/dars/{id}

Attendance:
GET    /api/v1/attendances
POST   /api/v1/attendances/check-in
POST   /api/v1/attendances/check-out

Leaves:
GET    /api/v1/leaves
POST   /api/v1/leaves
PUT    /api/v1/leaves/{id}/approve
PUT    /api/v1/leaves/{id}/reject

Claims:
GET    /api/v1/claims
POST   /api/v1/claims
PUT    /api/v1/claims/{id}/approve
```

### Response Format

```json
{
    "success": true,
    "message": "DAR created successfully",
    "data": {
        "id": 1,
        "activity": "Meeting with client",
        "result": "Deal closed",
        "plan": "Follow up next week",
        "status": "pending",
        "created_at": "2026-02-03T11:00:00.000000Z"
    }
}
```

---

## 🔄 Migration from Laravel 7

### Differences to Address

1. **PHP Version**: 7.2 → 8.1
2. **Laravel Version**: 7.x → 10.x
3. **Authentication**: Custom → Sanctum
4. **Database**: Raw SQL → Eloquent + Migrations
5. **Validation**: Manual → Form Requests
6. **Testing**: None → PHPUnit
7. **Code Style**: Mixed → PSR-12

### Migration Steps

1. ✅ Create new Laravel 10 project
2. ⏳ Design database schema
3. ⏳ Create migrations
4. ⏳ Build models with relationships
5. ⏳ Implement services layer
6. ⏳ Create API endpoints
7. ⏳ Build web interface
8. ⏳ Write tests
9. ⏳ Data migration from old system
10. ⏳ Deployment

---

## 📝 Development Guidelines

### Code Style

```bash
# Run Laravel Pint (code formatter)
./vendor/bin/pint

# Check code style
./vendor/bin/pint --test
```

### Git Workflow

```bash
# Feature branch
git checkout -b feature/dar-module

# Commit with conventional commits
git commit -m "feat: add DAR creation endpoint"
git commit -m "fix: resolve attendance GPS issue"
git commit -m "docs: update API documentation"
```

### Naming Conventions

- **Models**: Singular, PascalCase (User, Dar, Attendance)
- **Controllers**: PascalCase + Controller (DarController)
- **Services**: PascalCase + Service (DarService)
- **Migrations**: snake_case (create_dars_table)
- **Routes**: kebab-case (/api/v1/daily-reports)
- **Variables**: camelCase ($userId, $darData)

---

## 🚀 Deployment

### Production Checklist

- [ ] Set `APP_ENV=production`
- [ ] Set `APP_DEBUG=false`
- [ ] Configure proper database credentials
- [ ] Run `composer install --optimize-autoloader --no-dev`
- [ ] Run `php artisan config:cache`
- [ ] Run `php artisan route:cache`
- [ ] Run `php artisan view:cache`
- [ ] Set up queue workers
- [ ] Configure cron for scheduled tasks
- [ ] Set up SSL certificate
- [ ] Configure backups
- [ ] Set up monitoring

---

## 📚 Resources

- [Laravel 10 Documentation](https://laravel.com/docs/10.x)
- [Laravel Best Practices](https://github.com/alexeymezenin/laravel-best-practices)
- [Laravel API Tutorial](https://laravel.com/docs/10.x/sanctum)
- [PHPUnit Documentation](https://phpunit.de/documentation.html)

---

## 👥 Team

**Developer**: [Your Name]  
**Project Manager**: [PM Name]  
**Started**: 3 Februari 2026

---

## 📄 License

This project is proprietary software for internal use only.

---

## 🔄 Changelog

### [Unreleased]

- Initial project setup
- Laravel 10 installation
- Project documentation

---

**Last Updated**: 3 Februari 2026, 11:07 WIB
