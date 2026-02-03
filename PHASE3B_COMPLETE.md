# SIDAR Laravel 10 - Phase 3B Complete

**Date**: 3 Februari 2026, 13:40 WIB

---

## ✅ Phase 3B: DAR API - COMPLETE!

### What Was Completed

#### 1. **DarController** ✅

**File**: `app/Http/Controllers/Api/V1/DarController.php`

**CRUD Methods**:

- `index()` - List DARs with filters (date, status, employee)
- `store()` - Create new DAR
- `show($id)` - Get DAR details
- `update($id)` - Update DAR
- `destroy($id)` - Delete DAR

**Approval Methods**:

- `approve($id)` - Approve DAR at current level
- `reject($id)` - Reject DAR with notes
- `pendingApprovals()` - Get DARs pending approval for current user

**Features**:

- Auto-generate DAR number
- Authorization checks
- Approval chain validation
- Filter by date range, status, month/year
- Pagination support
- Transaction safety

#### 2. **DarResource** ✅

**File**: `app/Http/Resources/Api/V1/DarResource.php`

**Transforms**:

- DAR basic info (number, date, content)
- Employee data
- Approval status for all levels
- Approval notes and dates
- Approver details
- Attachments (when loaded)
- Metadata (timestamps)

#### 3. **Form Requests** ✅

**StoreDarRequest**:

- `dar_date` - Required, must be today or earlier
- `activity` - Required, 10-1000 characters
- `result` - Required, 10-1000 characters
- `plan` - Required, 10-1000 characters
- `tag` - Optional, max 100 characters

**UpdateDarRequest**:

- All fields optional (using `sometimes`)
- Same validation rules as store

#### 4. **API Routes** ✅

**File**: `routes/api.php`

**DAR Endpoints**:

```
GET    /api/v1/dars                    - List DARs
POST   /api/v1/dars                    - Create DAR
GET    /api/v1/dars/{id}               - Get DAR details
PUT    /api/v1/dars/{id}               - Update DAR
DELETE /api/v1/dars/{id}               - Delete DAR
POST   /api/v1/dars/{id}/approve       - Approve DAR
POST   /api/v1/dars/{id}/reject        - Reject DAR
GET    /api/v1/dars-pending-approvals  - Get pending approvals
```

---

## 📁 Files Created

### New Files (4)

```
app/Http/Controllers/Api/V1/DarController.php ✅
app/Http/Resources/Api/V1/DarResource.php ✅
app/Http/Requests/Api/V1/StoreDarRequest.php ✅
app/Http/Requests/Api/V1/UpdateDarRequest.php ✅
```

### Modified Files (1)

```
routes/api.php ✅
  - Added DAR CRUD routes
  - Added approval routes
  - Added pending approvals route
```

---

## 🎯 API Endpoints Documentation

### 1. List DARs

```http
GET /api/v1/dars
Authorization: Bearer {token}

Query Parameters:
- employee_id (optional) - Filter by employee
- start_date (optional) - Filter from date
- end_date (optional) - Filter to date
- status (optional) - Filter by status
- month (optional) - Filter by month (1-12)
- year (optional) - Filter by year
- per_page (optional) - Items per page (default: 15)

Response: 200 OK
{
  "data": [
    {
      "id": 1,
      "dar_number": "DAR/EMP-1201/20260203/1",
      "employee": {...},
      "dar_date": "2026-02-03",
      "activity": "...",
      "result": "...",
      "plan": "...",
      "status": "pending",
      ...
    }
  ],
  "links": {...},
  "meta": {...}
}
```

### 2. Create DAR

```http
POST /api/v1/dars
Authorization: Bearer {token}
Content-Type: application/json

{
  "dar_date": "2026-02-03",
  "activity": "Development meeting with team to discuss API implementation",
  "result": "Agreed on REST API structure and authentication flow",
  "plan": "Continue with DAR API development tomorrow",
  "tag": "development"
}

Response: 201 Created
{
  "success": true,
  "message": "DAR created successfully",
  "data": {...}
}
```

### 3. Get DAR Details

```http
GET /api/v1/dars/{id}
Authorization: Bearer {token}

Response: 200 OK
{
  "success": true,
  "message": "DAR retrieved successfully",
  "data": {
    "id": 1,
    "dar_number": "DAR/EMP-1201/20260203/1",
    "employee": {...},
    "approvers": {
      "supervisor": {...},
      "manager": {...},
      ...
    },
    ...
  }
}
```

### 4. Update DAR

```http
PUT /api/v1/dars/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
  "activity": "Updated activity description",
  "result": "Updated result",
  "plan": "Updated plan"
}

Response: 200 OK
{
  "success": true,
  "message": "DAR updated successfully",
  "data": {...}
}

Note: Can only update own DARs with status 'draft' or 'pending'
```

### 5. Delete DAR

```http
DELETE /api/v1/dars/{id}
Authorization: Bearer {token}

Response: 200 OK
{
  "success": true,
  "message": "DAR deleted successfully"
}

Note: Can only delete own DARs with status 'draft' or 'pending'
```

### 6. Approve DAR

```http
POST /api/v1/dars/{id}/approve
Authorization: Bearer {token}
Content-Type: application/json

{
  "notes": "Approved, good work!"
}

Response: 200 OK
{
  "success": true,
  "message": "DAR approved by supervisor",
  "data": {...}
}

Note: Only approvers in the chain can approve
```

### 7. Reject DAR

```http
POST /api/v1/dars/{id}/reject
Authorization: Bearer {token}
Content-Type: application/json

{
  "notes": "Please provide more details on the result"
}

Response: 200 OK
{
  "success": true,
  "message": "DAR rejected by supervisor",
  "data": {...}
}

Note: Notes are required when rejecting
```

### 8. Get Pending Approvals

```http
GET /api/v1/dars-pending-approvals
Authorization: Bearer {token}

Query Parameters:
- per_page (optional) - Items per page (default: 15)

Response: 200 OK
{
  "data": [
    {
      "id": 1,
      "dar_number": "DAR/EMP-1201/20260203/1",
      "employee": {...},
      "status": "pending",
      "supervisor_status": "pending",
      ...
    }
  ],
  "links": {...},
  "meta": {...}
}

Note: Returns DARs pending approval based on user's level
```

---

## 🔐 Authorization Rules

### View DAR

- Owner can view their own DARs
- Approvers can view DARs they need to approve

### Create DAR

- Any authenticated employee can create DAR

### Update DAR

- Only DAR owner can update
- Only if status is 'draft' or 'pending'

### Delete DAR

- Only DAR owner can delete
- Only if status is 'draft' or 'pending'

### Approve/Reject DAR

- Only designated approvers in the chain
- Must be at the correct approval level
- Previous levels must be approved first

---

## 🔄 Approval Workflow

### Approval Chain

```
Staff → Supervisor → Manager → Senior Manager → Director → Owner
```

### Approval Logic

1. DAR created with status 'pending'
2. Supervisor approves → `supervisor_status = 'approved'`
3. Manager approves → `manager_status = 'approved'`
4. Continue until all levels approve
5. When final approver approves → `status = 'approved'`

### Rejection Logic

- Any approver can reject
- When rejected → `status = 'rejected'`
- Workflow stops

---

## 🧪 Testing Scenarios

### Scenario 1: Staff Creates DAR

```
1. Login as: andi.wijaya@sidar.test
2. POST /api/v1/dars
3. Check: DAR created with pending status
4. Check: Approval chain set correctly
```

### Scenario 2: Supervisor Approves

```
1. Login as: it.supervisor@sidar.test
2. GET /api/v1/dars-pending-approvals
3. POST /api/v1/dars/{id}/approve
4. Check: supervisor_status = 'approved'
5. Check: DAR still pending (waiting for manager)
```

### Scenario 3: Complete Approval Chain

```
1. Supervisor approves
2. Manager approves
3. Director approves
4. Owner approves
5. Check: status = 'approved'
```

### Scenario 4: Rejection

```
1. Login as supervisor
2. POST /api/v1/dars/{id}/reject with notes
3. Check: status = 'rejected'
4. Check: rejection notes saved
```

### Scenario 5: Filter DARs

```
1. GET /api/v1/dars?start_date=2026-02-01&end_date=2026-02-28
2. GET /api/v1/dars?status=approved
3. GET /api/v1/dars?month=2&year=2026
```

---

## 📊 Progress Update

```
✅ Phase 1: Setup & Migrations (100%)
✅ Phase 2: Seeders (100%)
✅ Phase 3A: Authentication (100%)
✅ Phase 3B: DAR API (100%)

Phase 3: API Development
├─ Authentication ✅
├─ DAR CRUD ✅
├─ DAR Approval ✅
└─ Validation ✅

Overall: ~50% complete
```

---

## 🎉 Achievements

### ✅ Complete DAR API

- Full CRUD operations
- Approval workflow
- Authorization checks
- Validation rules
- Resource transformation

### ✅ Advanced Features

- Auto DAR number generation
- Multi-level approval chain
- Pending approvals listing
- Date range filtering
- Status filtering
- Pagination

### ✅ Security

- Authorization at every endpoint
- Ownership validation
- Approval chain validation
- Status-based restrictions

---

## 🚀 Next Steps

### Phase 4: Additional APIs (Optional)

1. **Attendance API**
    - Check-in/check-out endpoints
    - GPS tracking
    - Photo upload

2. **Leave API**
    - Leave request CRUD
    - Delegation workflow
    - Leave quota tracking

3. **Claim API**
    - Claim submission
    - Plafon tracking
    - Payment processing

### Phase 5: Testing

1. **Feature Tests**
    - Authentication tests
    - DAR CRUD tests
    - Approval workflow tests

2. **Manual Testing**
    - Postman collection
    - Test all scenarios
    - Verify approval chain

---

## 📝 Complete API Endpoints

```
Authentication:
POST   /api/v1/login
POST   /api/v1/logout
GET    /api/v1/me

DAR:
GET    /api/v1/dars
POST   /api/v1/dars
GET    /api/v1/dars/{id}
PUT    /api/v1/dars/{id}
DELETE /api/v1/dars/{id}
POST   /api/v1/dars/{id}/approve
POST   /api/v1/dars/{id}/reject
GET    /api/v1/dars-pending-approvals
```

---

**Status**: Phase 3B Complete ✅  
**Next**: Testing or Additional APIs  
**Total Endpoints**: 11

---

**Last Updated**: 3 Februari 2026, 13:40 WIB
