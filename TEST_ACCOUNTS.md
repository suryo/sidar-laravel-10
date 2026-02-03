# SIDAR HRIS - Test Credentials

Silakan gunakan akun-akun berikut untuk mencoba berbagai Role yang tersedia. Seluruh akun menggunakan password yang sama.

**Password Dasar**: `password`

| Nama Akun                    | Email                            | Role                  | Deskripsi                          |
| :--------------------------- | :------------------------------- | :-------------------- | :--------------------------------- |
| **Demo Administrator**       | `admin@sidar.test`               | Administrator         | Akses penuh ke seluruh sistem      |
| **Demo Director**            | `director@sidar.test`            | Director              | Read All, Approve, Manage Users    |
| **Demo Manager Approver**    | `manager.approver@sidar.test`    | Manager (Approver)    | Read Dept, Approve, Manage Users   |
| **Demo Manager**             | `manager@sidar.test`             | Manager               | Read Dept, Create/Edit data        |
| **Demo Supervisor Approver** | `supervisor.approver@sidar.test` | Supervisor (Approver) | Read Div, Approve                  |
| **Demo Supervisor**          | `supervisor@sidar.test`          | Supervisor            | Read Div, Create/Edit data         |
| **Demo Staff**               | `staff@sidar.test`               | Staff                 | Read Own, Create/Edit data         |
| **Demo HCS Print**           | `hcs.print@sidar.test`           | HCS Print             | Read All (Read-Only), Print Access |

---

### Hal-hal yang Bisa Dicoba:

1. **Navigasi Dinamis**: Perhatikan menu "Pending Approvals" hanya muncul untuk role dengan permission `can_approve`.
2. **Akses Dashboard**: Dashboard statistik akan menyesuaikan dengan data yang boleh dilihat (Read Own vs Read All).
3. **Attendance**: Coba lakukan Check-In sebagai Staff, lalu lihat riwayatnya.
4. **DAR Workflow**: Buat DAR sebagai Staff, lalu coba login sebagai Supervisor/Manager untuk melakukan Approve.

---

**Status**: Data Percobaan Siap ✅  
**Langkah Berikutnya**: Phase 5B - Modul Cuti & Izin
