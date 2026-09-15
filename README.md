# 🏫 Bright Future Public School — Student Management System

Student   Identification
Student Name: Huzefa Parveen Rahmat Khan
Degree : B.Tech, Computer Science and Engineering (Artificial Intelligence and Machine Learning - CSE AIML), 3rd Year
Institution: P. R. Pote Patil College of Engineering & Management, Amravati, Maharashtra
Course : Database Management System (DBMS) —  Assignment (Secure Hosted PHP-MySQL Mini Project)
Project Title: School Management System & Student Directory Portal


A PHP + MySQL web application for managing school student records: online self-registration, a searchable public directory, admission & fee tracking, study materials, and role-based logins for students and admins.

🔗 **Live Demo:** [https://studentmsnagement.ct.ws/index.php](https://studentmsnagement.ct.ws/index.php)

> Hosted on InfinityFree — this is a demo/learning deployment. Please don't submit real personal data on the live link.

--

## ✨ Features

| Feature | Description |
|---|---|
| 📝 Student Signup | Students register with personal, parental, and contact details, plus their own login password. |
| 🔐 Student Login | Students log in with **Roll Number + Password** to view only their own directory record. |
| 🔐 Admin Login | A separate secure admin portal (bcrypt-hashed credentials) with full directory access. |
| 🔍 Public Directory Search | Anyone can search by name, roll number, or class. Edit/Delete actions are hidden unless an admin is logged in. |
| 📂 Admin Dashboard | Admins can filter students by class, edit records, and delete records. |
| 💳 Admissions & Fees | New applicants submit admission details and a simulated ₹500 application fee. Full guardian/payment details are visible to admins only. |
| 📚 Study Materials | Class-wise notes and resources, filterable by class. |

---

## 🛠️ Tech Stack

- **Backend:** PHP (`mysqli`, prepared statements throughout — no raw string-concatenated queries)
- **Database:** MySQL / MariaDB
- **Frontend:** Plain HTML, CSS (no framework, no JS build step)

---

## 📁 Project Structure

```
├── config.php            # DB connection + session bootstrap
├── database.sql          # Full schema + seed data
├── index.php             # Homepage
├── register.php          # Student signup form
├── save.php              # Processes student registration
├── student_login.php     # Student login (roll no + password)
├── my_directory.php      # Logged-in student's own record
├── admin_login.php       # Admin login
├── admin_logout.php      # Admin logout
├── admin_directory.php   # Admin dashboard (view/filter all students)
├── edit.php              # Admin: edit a student record
├── delete.php            # Admin: delete a student record
├── search.php            # Public student directory search
├── admission.php         # Admission application + fee tracking
├── materials.php         # Study material directory
├── about.php             # About the school
└── style.css             # Site-wide styling
```

---

## 🚀 Local Setup

1. **Clone the repo** into your web server's root (e.g. `htdocs/` for XAMPP).
2. **Import the database:**
   ```
   mysql -u root -p < database.sql
   ```
   or import `database.sql` via phpMyAdmin.
3. **Configure the connection** in `config.php`:
   ```php
   $host     = "localhost";
   $db_user  = "root";
   $db_pass  = "";
   $database = "school_management";
   ```
4. **Start your server** (e.g. XAMPP/Apache, or `php -S localhost:8000`) and open `index.php`.

---

## 🔑 Default Credentials (Demo Data)

| Role | Username / Roll No | Password |
|---|---|---|
| Admin | `admin` | `admin123` |
| Student (sample) | `BFS-101` | `Student@123` |

⚠️ **Change these before deploying anywhere public.** The seeded admin/student accounts are for local testing only.

---

## 🔒 Security Notes

- All SQL queries use **prepared statements** with bound parameters — no string-concatenated queries.
- Passwords (admin and student) are stored with `password_hash()` and checked with `password_verify()` — no plaintext passwords, no hardcoded login backdoors.
- Session cookies are set `httponly` with `SameSite=Lax`, and session IDs are regenerated on login.
- Admin-only data (full admissions ledger, edit/delete actions) is gated by server-side session checks, not just hidden in the UI.
- Server-side validation backs up all client-side form validation (email format, 10-digit phone, allowed class/section/gender values, password length/match).

---

