
# 🎓 Student Enrollment From

A clean and responssive student enrollment from build in **HTML** , **CSS** , **JS** Where Student Can Enrole By Filling Details (Name, Email, Mobile, Gender) , After Successful student enrollment , show Success Massage.

# 🎓 Student Enrollment Dashboard

A clean and functional student enrollment dashboard built using **PHP**, **SQLite**, **Tailwind CSS**, and **TCPDF** for PDF export. It allows admin users to manage student records with dashboard insights, enrollment forms, export options, and secure login/logout handling.

---

## 🔧 Features

- ✅ Admin Login & Logout System
- ✅ Enroll Students (Name, Email, Mobile, Gender)
- ✅ Admin Dashboard with:
  - 📊 Total student count
  - 🚹 Gender-wise report
  - 📅 Today's enrollment
- ✅ Student List with Delete option
- ✅ Export student records to PDF
- ✅ Responsive UI with **Tailwind CSS**
- ✅ Lightweight SQLite database
- 📁 Clean code structure for easy understanding

---

## 📁 Folder Structure

```
Basic-student-Enrolemtnt-system/
├── admin/
│   ├── dashboard.php        # Main dashboard
│   ├── index.php            # Admin login
│   ├── enroll.php           # Add student form
│   ├── logout.php           # Logout script
├── libs/
│   └── tcpdf/               # PDF generation library
├── d1a526666a87b5e50ee2f26368dcdb2d.sqlite  # SQLite DB
├── db.php                  # DB connector
├── process.php             # Handles student form submissions
├── export_pdf.php          # Exports all data to PDF
├── index.html              # Student Registration form page 
```

---

## 🚀 Quick Start Guide

### 🖥️ Requirements

- PHP 7.x or newer
- SQLite3 enabled in PHP
- Web server (Apache/Nginx) or PHP built-in server

### ⚙️ Setup Instructions

1. **Clone the repository:**

   ```bash
   git clone https://github.com/your-username/student-enrollment-dashboard.git
   cd student-enrollment-dashboard/svfghfv
   ```

2. **Start local PHP server:**

   ```bash
   php -S localhost:8000
   ```
3. **Visit in browser For Student Registration form page :**

   ```
   http://localhost:8000/index.html

4. **Visit in browser For Admin Dashboard :**

   ```
   http://localhost:8000/admin/index.php
   ```

5. **Login as admin (modify Crediential in `admin/index.php` if needed):**

   ```php
   // admin/index.php
   // Modify the following credentials to match your admin accounts
   // Replace with your actual credentials
   // Hardcoded admin credentials
   $ADMIN_USERNAME = 'admin';
   $ADMIN_PASSWORD = 'admin123';
   ```

---

## 📤 Export to PDF

- The `export_pdf.php` file uses TCPDF to generate a downloadable PDF of all student records.
- Make sure the `libs/tcpdf/` directory is not removed.

---

## 📄 License

MIT License — Feel free to use, share, and modify.

---

## 👤 Author

**Sayan Mondal**  
🔗 GitHub: [@esayan-dev](https://github.com/esayan-dev)
