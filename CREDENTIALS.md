# SIDAR Laravel 10 - Demo Credentials

**Created**: 3 Februari 2026

---

## 🔐 Demo User Accounts

### Owner Level

```
Email: owner@sidar.test
NIK: EMP-0001
Name: Budi Santoso
Level: Owner
Department: IT
Location: Jakarta Head Office
```

### Director Level

```
Email: director@sidar.test
NIK: EMP-0002
Name: Siti Nurhaliza
Level: Director
Department: IT
Location: Jakarta Head Office
Reports to: Owner
```

### Manager Level

#### IT Manager

```
Email: it.manager@sidar.test
NIK: EMP-1001
Name: Ahmad Hidayat
Level: Manager
Department: IT / Development
Location: Jakarta Head Office
Reports to: Director → Owner
```

#### HR Manager

```
Email: hr.manager@sidar.test
NIK: EMP-2001
Name: Dewi Lestari
Level: Manager
Department: HR / Recruitment
Location: Jakarta Head Office
Reports to: Director → Owner
```

#### Finance Manager

```
Email: finance.manager@sidar.test
NIK: EMP-3001
Name: Eko Prasetyo
Level: Manager
Department: Finance / Accounting
Location: Jakarta Head Office
Reports to: Director → Owner
```

### Supervisor Level

#### IT Supervisor

```
Email: it.supervisor@sidar.test
NIK: EMP-1101
Name: Rudi Hartono
Level: Supervisor
Department: IT / Development
Location: Jakarta Head Office
Reports to: IT Manager → Director → Owner
```

#### HR Supervisor

```
Email: hr.supervisor@sidar.test
NIK: EMP-2101
Name: Linda Wijaya
Level: Supervisor
Department: HR / Recruitment
Location: Jakarta Head Office
Reports to: HR Manager → Director → Owner
```

### Staff Level

#### IT Staff

```
1. Email: andi.wijaya@sidar.test
   NIK: EMP-1201
   Name: Andi Wijaya
   Position: Software Developer
   Reports to: IT Supervisor → IT Manager → Director → Owner

2. Email: budi.setiawan@sidar.test
   NIK: EMP-1202
   Name: Budi Setiawan
   Position: Frontend Developer
   Reports to: IT Supervisor → IT Manager → Director → Owner
```

#### HR Staff

```
Email: citra.dewi@sidar.test
NIK: EMP-2201
Name: Citra Dewi
Position: HR Recruiter
Reports to: HR Supervisor → HR Manager → Director → Owner
```

#### Finance Staff

```
Email: dian.pratama@sidar.test
NIK: EMP-3201
Name: Dian Pratama
Position: Accountant
Reports to: Finance Manager → Director → Owner
```

#### Sales Staff

```
Email: eko.saputra@sidar.test
NIK: EMP-4201
Name: Eko Saputra
Position: Sales Representative
Department: Sales / B2B
Location: Bandung Branch
Can attend outside: Yes
Reports to: Director → Owner
```

---

## 🏢 Organizational Structure

```
Owner (Budi Santoso)
└── Director (Siti Nurhaliza)
    ├── IT Manager (Ahmad Hidayat)
    │   └── IT Supervisor (Rudi Hartono)
    │       ├── Andi Wijaya (Software Developer)
    │       └── Budi Setiawan (Frontend Developer)
    │
    ├── HR Manager (Dewi Lestari)
    │   └── HR Supervisor (Linda Wijaya)
    │       └── Citra Dewi (HR Recruiter)
    │
    ├── Finance Manager (Eko Prasetyo)
    │   └── Dian Pratama (Accountant)
    │
    └── Sales (Bandung Branch)
        └── Eko Saputra (Sales Representative)
```

---

## 📊 Seeded Data Summary

### Departments (10)

- IT (Information Technology)
- HR (Human Resources)
- FIN (Finance)
- SALES (Sales & Marketing)
- OPS (Operations)
- LOG (Logistics)
- CS (Customer Service)
- PROD (Production)
- QC (Quality Control)
- RND (Research & Development)

### Divisions (14)

- IT: Development, Infrastructure, Support
- HR: Recruitment, Training, Administration
- Finance: Accounting, Tax, Audit
- Sales: B2B Sales, B2C Sales, Marketing
- Operations: General Operations, Facilities

### Locations (10)

- Jakarta Head Office (JKT-HO)
- Jakarta Warehouse (JKT-WH)
- Bandung Branch (BDG-BR)
- Surabaya Branch (SBY-BR)
- Semarang Branch (SMG-BR)
- Yogyakarta Branch (YGY-BR)
- Medan Branch (MDN-BR)
- Makassar Branch (MKS-BR)
- Bali Branch (BLI-BR)
- Bekasi Warehouse (BKS-WH)

### Employees (12)

- 1 Owner
- 1 Director
- 3 Managers (IT, HR, Finance)
- 2 Supervisors (IT, HR)
- 5 Staff (IT x2, HR x1, Finance x1, Sales x1)

---

## 🔑 Leave Quotas by Group

```
EXECUTIVE: 12 days/year (Owner, Director)
MANAGER: 12 days/year (Managers)
SUPERVISOR: 12 days/year (Supervisors)
STAFF: 12 days/year (Staff)
```

---

## 📍 GPS Coordinates

All locations have real GPS coordinates for testing attendance features:

```
Jakarta HO: -6.2088, 106.8456
Bandung: -6.9175, 107.6191
Surabaya: -7.2575, 112.7521
Semarang: -6.9667, 110.4167
Yogyakarta: -7.7956, 110.3695
Medan: 3.5952, 98.6722
Makassar: -5.1477, 119.4327
Bali: -8.6705, 115.2126
```

---

## 🧪 Testing Scenarios

### Scenario 1: DAR Approval Flow (IT Staff)

```
1. Andi Wijaya creates DAR
2. Approval chain:
   - IT Supervisor (Rudi Hartono)
   - IT Manager (Ahmad Hidayat)
   - Director (Siti Nurhaliza)
   - Owner (Budi Santoso)
```

### Scenario 2: Leave Request (HR Staff)

```
1. Citra Dewi requests annual leave
2. Delegates tasks to another employee
3. Approval chain:
   - Delegation approval
   - HR Supervisor (Linda Wijaya)
   - HCS/HR Manager (Dewi Lestari)
```

### Scenario 3: Claim Submission (Finance Staff)

```
1. Dian Pratama submits medical claim
2. Approval chain:
   - Finance Manager (Eko Prasetyo)
   - HCS/HR Manager (Dewi Lestari)
   - Finance/FAT (Eko Prasetyo)
```

### Scenario 4: Attendance (Sales - Outside Office)

```
1. Eko Saputra (Sales) can check-in from distributor
2. GPS tracking enabled
3. Photo capture for verification
4. Can attend outside office: Yes
```

---

## 📝 Notes

- All employees have `status = 'active'`
- All departments, divisions, and locations are active
- Join dates range from 2020 to 2022
- Default password will be set when User model is created
- Phone numbers are dummy data (081234567xxx)

---

**Last Updated**: 3 Februari 2026, 12:15 WIB
