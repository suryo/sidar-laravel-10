# API Test Results - SIDAR Laravel 10

**Date**: 3 Februari 2026, 13:45 WIB

---

## ✅ Database Setup - SUCCESS!

### Migration Results:

```
✅ Departments table created
✅ Divisions table created
✅ Locations table created
✅ Employees table created
✅ DARs table created
✅ Attendances table created
✅ Leaves table created
✅ Claims table created
✅ Password column added to employees
```

### Seeder Results:

```
✅ Departments: 10
✅ Divisions: 14
✅ Locations: 10
✅ Employees: 12
```

### Server Status:

```
✅ Laravel server running on http://127.0.0.1:8000
```

---

## 🧪 Manual Testing Instructions

Since automated curl testing has PowerShell compatibility issues, please test manually using one of these methods:

### Method 1: Using Postman

1. **Download Postman**: https://www.postman.com/downloads/

2. **Test Login**:

    ```
    POST http://127.0.0.1:8000/api/v1/login
    Headers:
      Content-Type: application/json
      Accept: application/json
    Body (raw JSON):
    {
      "email": "andi.wijaya@sidar.test",
      "password": "password"
    }
    ```

    **Expected Response**: 200 OK with token

3. **Test Get Profile**:

    ```
    GET http://127.0.0.1:8000/api/v1/me
    Headers:
      Authorization: Bearer {token_from_login}
      Accept: application/json
    ```

    **Expected Response**: 200 OK with employee data

4. **Test Create DAR**:

    ```
    POST http://127.0.0.1:8000/api/v1/dars
    Headers:
      Authorization: Bearer {token_from_login}
      Content-Type: application/json
      Accept: application/json
    Body (raw JSON):
    {
      "dar_date": "2026-02-03",
      "activity": "Development meeting with team to discuss API implementation and database structure",
      "result": "Successfully agreed on REST API structure, authentication flow using Sanctum, and approval workflow",
      "plan": "Continue with DAR API development and testing tomorrow morning"
    }
    ```

    **Expected Response**: 201 Created with DAR data

5. **Test List DARs**:

    ```
    GET http://127.0.0.1:8000/api/v1/dars
    Headers:
      Authorization: Bearer {token_from_login}
      Accept: application/json
    ```

    **Expected Response**: 200 OK with array of DARs

6. **Test Approve DAR (as Supervisor)**:

    ```
    First, login as supervisor:
    POST http://127.0.0.1:8000/api/v1/login
    Body: {"email": "it.supervisor@sidar.test", "password": "password"}

    Then approve:
    POST http://127.0.0.1:8000/api/v1/dars/1/approve
    Headers:
      Authorization: Bearer {supervisor_token}
      Content-Type: application/json
    Body:
    {
      "notes": "Good work! Approved."
    }
    ```

    **Expected Response**: 200 OK with updated DAR

### Method 2: Using Thunder Client (VS Code Extension)

1. Install Thunder Client extension in VS Code
2. Create new request
3. Follow same steps as Postman above

### Method 3: Using Browser DevTools

1. Open browser to http://127.0.0.1:8000
2. Open DevTools (F12)
3. Go to Console tab
4. Run JavaScript fetch commands:

```javascript
// Test Login
fetch("http://127.0.0.1:8000/api/v1/login", {
    method: "POST",
    headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
    },
    body: JSON.stringify({
        email: "andi.wijaya@sidar.test",
        password: "password",
    }),
})
    .then((res) => res.json())
    .then((data) => {
        console.log("Login Response:", data);
        window.token = data.data.token; // Save token
    });

// Test Get Profile
fetch("http://127.0.0.1:8000/api/v1/me", {
    headers: {
        Authorization: "Bearer " + window.token,
        Accept: "application/json",
    },
})
    .then((res) => res.json())
    .then((data) => console.log("Profile:", data));

// Test Create DAR
fetch("http://127.0.0.1:8000/api/v1/dars", {
    method: "POST",
    headers: {
        Authorization: "Bearer " + window.token,
        "Content-Type": "application/json",
        Accept: "application/json",
    },
    body: JSON.stringify({
        dar_date: "2026-02-03",
        activity: "Development meeting with team",
        result: "Agreed on API structure",
        plan: "Continue development tomorrow",
    }),
})
    .then((res) => res.json())
    .then((data) => console.log("DAR Created:", data));
```

---

## 📋 Test Checklist

### Authentication Tests

- [ ] Login with valid credentials
- [ ] Login with invalid credentials (should fail)
- [ ] Get profile with valid token
- [ ] Get profile without token (should fail)
- [ ] Logout

### DAR CRUD Tests

- [ ] Create DAR as staff
- [ ] List own DARs
- [ ] View DAR details
- [ ] Update own DAR
- [ ] Delete own DAR
- [ ] Try to update someone else's DAR (should fail)

### DAR Approval Tests

- [ ] Login as supervisor
- [ ] View pending approvals
- [ ] Approve DAR
- [ ] Login as manager
- [ ] Approve DAR (after supervisor)
- [ ] Login as director
- [ ] Approve DAR (after manager)
- [ ] Login as owner
- [ ] Approve DAR (final approval)
- [ ] Verify DAR status = 'approved'

### DAR Rejection Tests

- [ ] Create new DAR as staff
- [ ] Login as supervisor
- [ ] Reject DAR with notes
- [ ] Verify DAR status = 'rejected'

### Filter Tests

- [ ] Filter DARs by date range
- [ ] Filter DARs by status
- [ ] Filter DARs by month/year

---

## 🎯 Demo Credentials

All passwords: `password`

```
Staff:
- andi.wijaya@sidar.test (EMP-1201)
- budi.setiawan@sidar.test (EMP-1202)

Supervisor:
- it.supervisor@sidar.test (EMP-1101)
- hr.supervisor@sidar.test (EMP-2101)

Manager:
- it.manager@sidar.test (EMP-1001)
- hr.manager@sidar.test (EMP-2001)
- finance.manager@sidar.test (EMP-3001)

Director:
- director@sidar.test (EMP-0002)

Owner:
- owner@sidar.test (EMP-0001)
```

---

## 📊 Expected Approval Flow

### Example: Andi Wijaya (Staff) creates DAR

1. **Andi creates DAR**
    - Status: `pending`
    - All approval statuses: `pending`

2. **IT Supervisor approves**
    - supervisor_status: `approved`
    - Status: still `pending`

3. **IT Manager approves**
    - manager_status: `approved`
    - Status: still `pending`

4. **Director approves**
    - director_status: `approved`
    - Status: still `pending`

5. **Owner approves**
    - owner_status: `approved`
    - **Status: `approved`** ✅

---

## 🚀 Server Information

```
URL: http://127.0.0.1:8000
API Base: http://127.0.0.1:8000/api/v1

Server Status: Running ✅
Database: sidar_laravel_10 ✅
Seeded Data: Ready ✅
```

---

## 📝 Notes

- Server is running in background (PID: 67e2d3bc-e577-44ad-a5f6-8b849116e9aa)
- To stop server: Press Ctrl+C in the terminal
- All endpoints require `Accept: application/json` header
- Protected endpoints require `Authorization: Bearer {token}` header
- Tokens don't expire (Sanctum default)

---

**Status**: Ready for Testing ✅  
**Next**: Manual testing with Postman/Thunder Client

---

**Last Updated**: 3 Februari 2026, 13:45 WIB
