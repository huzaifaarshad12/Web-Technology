# PHP Fundamentals & CRUD Application Implementation

A comprehensive PHP project demonstrating core PHP concepts and a complete CRUD (Create, Read, Update, Delete) application for student management.

---

## 📋 Table of Contents

1. [Project Overview](#project-overview)
2. [Features](#features)
3. [Prerequisites](#prerequisites)
4. [Installation & Setup](#installation--setup)
5. [Project Structure](#project-structure)
6. [Database Setup](#database-setup)
7. [Configuration](#configuration)
8. [Usage](#usage)
9. [Presentation Topics](#presentation-topics)
10. [Security Features](#security-features)
11. [Troubleshooting](#troubleshooting)

---

## 🎯 Project Overview

This project serves as both a learning resource and a functional application:

- **Part 1**: Educational presentation covering PHP fundamentals
- **Part 2**: Live CRUD application demonstrating database operations

The application allows you to manage student records with full CRUD functionality.

---

## ✨ Features

- ✅ **Create**: Add new students via form
- ✅ **Read**: Display all students in a table
- ✅ **Update**: Edit existing student information
- ✅ **Delete**: Remove student records with confirmation
- ✅ **Form Validation**: Input sanitization and validation
- ✅ **Security**: Prepared statements to prevent SQL injection
- ✅ **Modern UI**: Clean, responsive design
- ✅ **Error Handling**: User-friendly error messages

---

## 📦 Prerequisites

Before you begin, ensure you have:

1. **Web Server** with PHP support:
   - XAMPP (recommended for Windows)
   - WAMP (Windows)
   - MAMP (Mac)
   - LAMP (Linux)
   - Or standalone PHP + Apache/Nginx

2. **MySQL/MariaDB**:
   - Usually included with XAMPP/WAMP/MAMP
   - Or standalone installation

3. **MySQL Workbench** (optional but recommended):
   - For database management
   - See `MYSQL_WORKBENCH_GUIDE.md` for setup

4. **PHP Version**: 7.4 or higher (8.0+ recommended)

---

## 🚀 Installation & Setup

### Step 1: Download/Clone the Project

1. Download or clone this repository
2. Extract files to your web server directory:
   - **XAMPP**: `C:\xampp\htdocs\PHP\`
   - **WAMP**: `C:\wamp64\www\PHP\`
   - **MAMP**: `/Applications/MAMP/htdocs/PHP/`

### Step 2: Start Your Web Server

**For XAMPP:**
1. Open XAMPP Control Panel
2. Start **Apache** and **MySQL** services

**For WAMP:**
1. Start WAMP Server
2. Wait for icon to turn green

**For MAMP:**
1. Start MAMP
2. Start Apache and MySQL servers

### Step 3: Create the Database

**Option A: Using MySQL Workbench** (Recommended)
1. Open MySQL Workbench
2. Connect to your MySQL server
3. Open `database_setup.sql` file
4. Execute the script
5. See `MYSQL_WORKBENCH_GUIDE.md` for detailed instructions

**Option B: Using phpMyAdmin**
1. Open phpMyAdmin (usually at `http://localhost/phpmyadmin`)
2. Click on "SQL" tab
3. Copy and paste contents of `database_setup.sql`
4. Click "Go"

**Option C: Using Command Line**
```bash
mysql -u root -p < database_setup.sql
```

### Step 4: Configure Database Connection

1. Open `config.php` in a text editor
2. Update the database credentials:

```php
define('DB_HOST', 'localhost');      // Usually 'localhost'
define('DB_USER', 'root');           // Your MySQL username
define('DB_PASS', '');               // Your MySQL password (empty for XAMPP/WAMP)
define('DB_NAME', 'student_management'); // Database name
```

### Step 5: Access the Application

Open your web browser and navigate to:
```
http://localhost/PHP/index.php
```

Or if you placed it in a different directory:
```
http://localhost/your-folder-name/index.php
```

---

## 📁 Project Structure

```
PHP/
│
├── index.php                 # Main page (Read - List all students)
├── create.php                # Create new student
├── edit.php                  # Update student information
├── delete.php                # Delete student (handled in index.php)
├── config.php                # Database configuration
├── database_setup.sql         # Database creation script
├── presentation.md           # Presentation slides (Markdown)
├── MYSQL_WORKBENCH_GUIDE.md  # MySQL Workbench setup guide
└── README.md                 # This file
```

---

## 🗄️ Database Setup

### Database Schema

**Database Name:** `student_management`

**Table:** `students`

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| `id` | INT | PRIMARY KEY, AUTO_INCREMENT | Unique identifier |
| `name` | VARCHAR(100) | NOT NULL | Student name |
| `email` | VARCHAR(100) | NOT NULL, UNIQUE | Student email |
| `created_at` | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Record creation time |

### Running the Setup Script

The `database_setup.sql` file contains:
- Database creation
- Table creation
- Sample data insertion (optional)

---

## ⚙️ Configuration

### Database Configuration (`config.php`)

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'student_management');
```

**Important:** Update these values to match your MySQL setup.

---

## 💻 Usage

### Creating a Student

1. Click **"Add New Student"** button
2. Fill in the form:
   - Name (required)
   - Email (required, must be valid format)
3. Click **"Save Student"**
4. You'll be redirected to the list page

### Viewing Students

- The main page (`index.php`) displays all students in a table
- Shows: ID, Name, Email, Created At, and Actions

### Editing a Student

1. Click **"Edit"** button next to a student
2. Modify the information in the form
3. Click **"Update Student"**
4. Changes are saved to the database

### Deleting a Student

1. Click **"Delete"** button next to a student
2. Confirm the deletion in the popup dialog
3. Student record is permanently removed

---

## 📚 Presentation Topics

This project covers the following PHP concepts:

### Part 1: Core PHP Concepts

1. **Introduction to PHP**
   - What is PHP
   - Basic syntax (`<?php ... ?>`)

2. **Variables & Data Types**
   - Variable declaration (`$variable`)
   - Types: string, integer, float, boolean, array, NULL

3. **Operators**
   - Arithmetic: `+`, `-`, `*`, `/`, `%`
   - Assignment: `=`, `+=`, `.=`
   - Comparison: `==`, `===`, `!=`, `>`, `<`
   - Logical: `&&`, `||`, `!`

4. **Control & Loop Statements**
   - Control: `if`, `elseif`, `else`, `switch`
   - Loops: `for`, `while`, `foreach`

5. **Arrays**
   - Indexed arrays
   - Associative arrays
   - Array traversal

6. **Functions**
   - Custom function definition
   - Function calls
   - Built-in functions

7. **Classes (OOP)**
   - Basic OOP concepts
   - Class with properties and methods
   - Object instantiation

### Part 2: Practical Application

8. **Database Connectivity**
   - MySQLi connection
   - Database queries

9. **CRUD Operations**
   - Create, Read, Update, Delete

10. **Form Handling**
    - `$_POST` and `$_GET` superglobals
    - Input validation and sanitization

See `presentation.md` for detailed slides and code examples.

---

## 🔒 Security Features

### Implemented Security Measures:

1. **Prepared Statements**
   - Prevents SQL injection attacks
   - Used in all database operations

2. **Input Validation**
   - Required field checks
   - Email format validation
   - Length restrictions

3. **Input Sanitization**
   - `trim()` to remove whitespace
   - `filter_var()` for email validation
   - `htmlspecialchars()` for output escaping

4. **Error Handling**
   - Graceful error messages
   - No sensitive information exposed

### Security Best Practices:

- ✅ Always use prepared statements
- ✅ Validate and sanitize all user input
- ✅ Escape output with `htmlspecialchars()`
- ✅ Use HTTPS in production
- ✅ Implement proper authentication (for production)

---

## 🐛 Troubleshooting

### Common Issues:

**1. "Connection failed" error**
- ✅ Check if MySQL service is running
- ✅ Verify credentials in `config.php`
- ✅ Ensure database exists

**2. "Access denied" error**
- ✅ Check MySQL username and password
- ✅ Verify user has proper permissions

**3. Page not loading**
- ✅ Check if Apache is running
- ✅ Verify file path in browser
- ✅ Check PHP error logs

**4. "Table doesn't exist" error**
- ✅ Run `database_setup.sql` script
- ✅ Verify database name in `config.php`

**5. Form not submitting**
- ✅ Check PHP version (7.4+)
- ✅ Verify form method is POST
- ✅ Check browser console for errors

---

## 📖 Additional Resources

- **PHP Documentation**: https://www.php.net/docs.php
- **MySQL Documentation**: https://dev.mysql.com/doc/
- **MySQL Workbench Guide**: See `MYSQL_WORKBENCH_GUIDE.md`
- **Presentation**: See `presentation.md`

---

## 🎓 Learning Objectives

After completing this project, you should understand:

- ✅ PHP syntax and basic concepts
- ✅ Variable types and operators
- ✅ Control structures and loops
- ✅ Functions and arrays
- ✅ Basic OOP in PHP
- ✅ Database connectivity
- ✅ CRUD operations
- ✅ Form handling and validation
- ✅ Security best practices

---

## 📝 Notes

- This is a **learning/demonstration** project
- For production use, add:
  - User authentication
  - Session management
  - More robust error handling
  - API endpoints (optional)
  - Additional security measures

---

## 🤝 Contributing

Feel free to:
- Report issues
- Suggest improvements
- Add features
- Share feedback

---

## 📄 License

This project is for educational purposes.

---

## 👨‍💻 Author

Created as part of PHP Fundamentals & CRUD Application Implementation project.

---

**Happy Coding! 🚀**

---

## Quick Start Checklist

- [ ] Install XAMPP/WAMP/MAMP
- [ ] Start Apache and MySQL services
- [ ] Copy project files to `htdocs` or `www` folder
- [ ] Create database using `database_setup.sql`
- [ ] Update `config.php` with database credentials
- [ ] Open `http://localhost/PHP/index.php` in browser
- [ ] Test CRUD operations
- [ ] Review `presentation.md` for PHP concepts
- [ ] Review `MYSQL_WORKBENCH_GUIDE.md` for database setup

---

**Ready to start? Follow the Installation & Setup section above!**

