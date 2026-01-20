# Quick Run Guide - If You Have MySQL Workbench

## ✅ What You Already Have
- MySQL Workbench ✅
- MySQL Server ✅ (running)

## 📦 What You Need to Install

**XAMPP** (only Apache + PHP components)

1. Download: https://www.apachefriends.org/download.html
2. Install XAMPP (select Apache + PHP, skip MySQL if you have it)
3. Start Apache in XAMPP Control Panel

---

## 🚀 Quick Steps to Run

### Step 1: Create Database (In MySQL Workbench)
1. Open MySQL Workbench
2. Connect to your MySQL server
3. Run this SQL script:

```sql
CREATE DATABASE IF NOT EXISTS student_management;
USE student_management;

CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

4. Click Execute (⚡ icon)

### Step 2: Copy Files
- Copy your `PHP` folder to: `C:\xampp\htdocs\PHP\`

### Step 3: Verify Config
- Open `config.php`
- Check password: `Sharingan_82` ✅ (already correct)

### Step 4: Start Apache
- Open XAMPP Control Panel
- Click "Start" for Apache
- Wait until it turns green

### Step 5: Run Application
- Open browser: `http://localhost/PHP/index.php`
- Done! ✅

---

## 📋 Your MySQL Credentials (From config.php)
- Host: `localhost`
- Username: `root`
- Password: `Sharingan_82`
- Database: `student_management`

---

## 🎯 That's It!

If you see the Student Management System page, everything is working!

For detailed instructions, see: `RUN_WITH_MYSQL_WORKBENCH.md`

