# SIDAR Laravel 10 - Roles & Permissions System

**Date**: 3 Februari 2026, 14:05 WIB

---

## ✅ Roles & Permissions System - COMPLETE!

### Overview

Sistem roles dan permissions telah dibuat dengan 8 role yang berbeda, masing-masing dengan permission yang granular untuk mengontrol akses ke fitur-fitur sistem.

---

## 📋 Roles List

### 1. **Administrator**

**Slug**: `administrator`

**Permissions**:

- ✅ Write (Create/Edit data)
- ✅ Read Own
- ✅ Read Division
- ✅ Read Department
- ✅ Read All
- ✅ Approve
- ✅ Manage Users
- ✅ Admin Access

**Description**: Full system access dengan semua permissions

---

### 2. **Staff**

**Slug**: `staff`

**Permissions**:

- ✅ Write (Create/Edit data)
- ✅ Read Own
- ❌ Read Division
- ❌ Read Department
- ❌ Read All
- ❌ Approve
- ❌ Manage Users
- ❌ Admin Access

**Description**: Hanya bisa write dan read data sendiri

---

### 3. **Supervisor**

**Slug**: `supervisor`

**Permissions**:

- ✅ Write
- ✅ Read Own
- ✅ Read Division
- ❌ Read Department
- ❌ Read All
- ❌ Approve
- ❌ Manage Users
- ❌ Admin Access

**Description**: Bisa write dan read data division

---

### 4. **Supervisor (Approver)**

**Slug**: `supervisor-approver`

**Permissions**:

- ✅ Write
- ✅ Read Own
- ✅ Read Division
- ❌ Read Department
- ❌ Read All
- ✅ Approve
- ❌ Manage Users
- ❌ Admin Access

**Description**: Supervisor dengan kemampuan approve

---

### 5. **Manager**

**Slug**: `manager`

**Permissions**:

- ✅ Write
- ✅ Read Own
- ✅ Read Division
- ✅ Read Department
- ❌ Read All
- ❌ Approve
- ❌ Manage Users
- ❌ Admin Access

**Description**: Bisa write dan read data department

---

### 6. **Manager (Approver)**

**Slug**: `manager-approver`

**Permissions**:

- ✅ Write
- ✅ Read Own
- ✅ Read Division
- ✅ Read Department
- ❌ Read All
- ✅ Approve
- ✅ Manage Users
- ❌ Admin Access

**Description**: Manager dengan kemampuan approve dan manage users

---

### 7. **Director**

**Slug**: `director`

**Permissions**:

- ✅ Write
- ✅ Read Own
- ✅ Read Division
- ✅ Read Department
- ✅ Read All
- ✅ Approve
- ✅ Manage Users
- ❌ Admin Access

**Description**: Bisa write, read all data, approve, dan manage users

---

### 8. **HCS Print**

**Slug**: `hcs-print`

**Permissions**:

- ❌ Write
- ❌ Read Own
- ❌ Read Department
- ❌ Read Division
- ✅ Read All
- ❌ Approve
- ❌ Manage Users
- ❌ Admin Access
- ✅ HCS Print Access

**Description**: Special role untuk HCS printing access (read-only all data)

---

## 🗂️ Database Structure

### Roles Table

```sql
CREATE TABLE roles (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) UNIQUE,
    slug VARCHAR(255) UNIQUE,
    description TEXT,

    -- Permissions
    can_write BOOLEAN DEFAULT FALSE,
    can_read_own BOOLEAN DEFAULT FALSE,
    can_read_division BOOLEAN DEFAULT FALSE,
    can_read_department BOOLEAN DEFAULT FALSE,
    can_read_all BOOLEAN DEFAULT FALSE,
    can_approve BOOLEAN DEFAULT FALSE,
    can_manage_users BOOLEAN DEFAULT FALSE,
    is_admin BOOLEAN DEFAULT FALSE,
    is_hcs_print BOOLEAN DEFAULT FALSE,

    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

### Employees Table (Updated)

```sql
ALTER TABLE employees
ADD COLUMN role_id BIGINT NULL,
ADD FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE SET NULL;
```

---

## 📁 Files Created/Modified

### New Files (4)

```
database/migrations/2026_02_03_065816_create_roles_table.php ✅
database/migrations/2026_02_03_065819_add_role_id_to_employees_table.php ✅
app/Models/Role.php ✅
database/seeders/RoleSeeder.php ✅
```

### Modified Files (3)

```
app/Models/Employee.php ✅
  - Added role() relationship
  - Added role_id to fillable

database/seeders/DatabaseSeeder.php ✅
  - Added RoleSeeder to seeder list
  - Added Roles to summary table
```

---

## 🔐 Permission Matrix

| Role                  | Write | Read Own | Read Div | Read Dept | Read All | Approve | Manage Users | Admin |
| --------------------- | ----- | -------- | -------- | --------- | -------- | ------- | ------------ | ----- |
| Administrator         | ✓     | ✓        | ✓        | ✓         | ✓        | ✓       | ✓            | ✓     |
| Staff                 | ✓     | ✓        | ✗        | ✗         | ✗        | ✗       | ✗            | ✗     |
| Supervisor            | ✓     | ✓        | ✓        | ✗         | ✗        | ✗       | ✗            | ✗     |
| Supervisor (Approver) | ✓     | ✓        | ✓        | ✗         | ✗        | ✓       | ✗            | ✗     |
| Manager               | ✓     | ✓        | ✓        | ✓         | ✗        | ✗       | ✗            | ✗     |
| Manager (Approver)    | ✓     | ✓        | ✓        | ✓         | ✗        | ✓       | ✓            | ✗     |
| Director              | ✓     | ✓        | ✓        | ✓         | ✓        | ✓       | ✓            | ✗     |
| HCS Print             | ✗     | ✗        | ✗        | ✗         | ✓        | ✗       | ✗            | ✗     |

---

## 💻 Usage Examples

### Check User Role

```php
$employee = auth()->user();

// Get role
$role = $employee->role;

// Check permissions
if ($role->can_write) {
    // User can create/edit data
}

if ($role->can_approve) {
    // User can approve DARs
}

if ($role->is_admin) {
    // User is administrator
}
```

### Check Read Scope

```php
$role = auth()->user()->role;

// Check read scope
if ($role->canRead('all')) {
    // Can read all data
} elseif ($role->canRead('department')) {
    // Can read department data
} elseif ($role->canRead('division')) {
    // Can read division data
} else {
    // Can only read own data
}
```

### Assign Role to Employee

```php
use App\Models\Employee;
use App\Models\Role;

$employee = Employee::find(1);
$staffRole = Role::where('slug', 'staff')->first();

$employee->role_id = $staffRole->id;
$employee->save();
```

### Middleware Example (Future)

```php
// In route or controller
Route::middleware(['role:administrator'])->group(function () {
    // Admin only routes
});

Route::middleware(['permission:can_approve'])->group(function () {
    // Approver routes
});
```

---

## 🧪 Testing

### Test Role Creation

```bash
php artisan tinker
```

```php
// Check all roles
App\Models\Role::all();

// Get specific role
$admin = App\Models\Role::where('slug', 'administrator')->first();
echo $admin->name;
echo $admin->can_write ? 'Can Write' : 'Cannot Write';

// Check permissions
$staff = App\Models\Role::where('slug', 'staff')->first();
echo $staff->canRead('all') ? 'Can Read All' : 'Cannot Read All';
```

### Assign Role to Employee

```php
$employee = App\Models\Employee::where('email', 'andi.wijaya@sidar.test')->first();
$staffRole = App\Models\Role::where('slug', 'staff')->first();

$employee->role_id = $staffRole->id;
$employee->save();

// Verify
echo $employee->role->name; // Should output: Staff
```

---

## 🚀 Next Steps

### 1. Update EmployeeSeeder

Assign default roles to seeded employees based on their level:

- Owner → Administrator
- Director → Director
- Manager → Manager (Approver)
- Supervisor → Supervisor (Approver)
- Staff → Staff

### 2. Create Middleware

```bash
php artisan make:middleware CheckRole
php artisan make:middleware CheckPermission
```

### 3. Update Controllers

Add role-based authorization checks in controllers:

```php
if (!auth()->user()->role->can_approve) {
    abort(403, 'Unauthorized');
}
```

### 4. Update Views

Show/hide UI elements based on permissions:

```blade
@if(auth()->user()->role->can_manage_users)
    <a href="{{ route('users.create') }}">Add User</a>
@endif
```

---

## 📊 Summary

```
✅ Roles Table Created
✅ 8 Roles Seeded
✅ Employee-Role Relationship
✅ Permission System
✅ Helper Methods

Total Roles: 8
Total Permissions: 9
```

---

## 🎯 Role Hierarchy

```
Administrator (Full Access)
    ↓
Director (Read All, Approve, Manage Users)
    ↓
Manager (Approver) (Read Dept, Approve, Manage Users)
    ↓
Manager (Read Dept)
    ↓
Supervisor (Approver) (Read Div, Approve)
    ↓
Supervisor (Read Div)
    ↓
Staff (Read Own)
    ↓
HCS Print (Read All, Print Only)
```

---

**Status**: Roles System Complete ✅  
**Next**: Assign roles to employees, create middleware  
**Database**: Migrated and seeded

---

**Last Updated**: 3 Februari 2026, 14:05 WIB
