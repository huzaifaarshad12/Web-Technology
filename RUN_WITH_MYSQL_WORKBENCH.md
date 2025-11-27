# Running PHP Application with Existing MySQL Workbench Setup

This guide assumes you already have **MySQL Workbench** installed and configured. You'll need to install PHP and a web server to run the application.

---

## 📋 What You Already Have

✅ **MySQL Workbench** - Installed and configured  
✅ **MySQL Server** - Running (since you can use Workbench)

---

## 🎯 What You Need to Install

You need to install one of these options:

### Option 1: XAMPP (Recommended - Easiest)
- Includes: Apache (Web Server) + PHP + MySQL (you can skip MySQL if already installed)
- Download: https://www.apachefriends.org/download.html

### Option 2: WAMP (Windows Only)
- Includes: Apache + PHP + MySQL
- Download: https://www.wampserver.com/

### Option 3: PHP + Apache Separately (Advanced)
- More complex setup

**We'll use XAMPP as it's the easiest option.**

---

## 🚀 Step-by-Step Guide

### Step 1: Install XAMPP (Skip MySQL if You Have It)

1. **Download XAMPP** from: https://www.apachefriends.org/download.html
2. **Run installer as Administrator**
3. **Select Components:**
   - ✅ **Apache** (Required - Web Server)
   - ✅ **PHP** (Required - Programming Language)
   - ⚠️ **MySQL** (Optional - Skip if you already have MySQL)
   - ✅ **phpMyAdmin** (Optional but helpful)
4. **Install to:** `C:\xampp` (default)
5. **Complete installation**

**Note:** If you skip MySQL during XAMPP installation, you'll use your existing MySQL server that Workbench connects to.

---

### Step 2: Verify MySQL Connection Details

Since you have MySQL Workbench set up, you need to know:

1. **Open MySQL Workbench**
2. **Check your connection details:**
   - Hostname: Usually `localhost` or `127.0.0.1`
   - Port: Usually `3306`
   - Username: Usually `root`
   - Password: Your MySQL password (you mentioned `Sharingan_82`)

3. **Test Connection:**
   - Double-click your connection in Workbench
   - If it connects successfully, note these credentials

---

### Step 3: Update Database Configuration

1. **Open** `config.php` in your project folder
2. **Update** with your MySQL credentials:

```php
define('DB_HOST', 'localhost');      // Your MySQL hostname
define('DB_USER', 'root');           // Your MySQL username
define('DB_PASS', 'Sharingan_82');   // Your MySQL password
define('DB_NAME', 'student_management'); // Database name
```

3. **Save** the file

---

### Step 4: Create Database in MySQL Workbench

1. **Open MySQL Workbench**
2. **Connect** to your MySQL server
3. **Open SQL Editor** (click on your connection)
4. **Copy and paste** this SQL script:

```sql
-- Create database
CREATE DATABASE IF NOT EXISTS student_management;
USE student_management;

-- Create students table
CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert sample data (optional)
INSERT INTO students (name, email) VALUES
('John Doe', 'john.doe@example.com'),
('Jane Smith', 'jane.smith@example.com'),
('Bob Johnson', 'bob.johnson@example.com');
```

5. **Execute** the script:
   - Click the ⚡ **Execute** button (lightning bolt icon)
   - Or press **Ctrl+Enter**
6. **Verify** database was created:
   - In the **Schemas** panel on the left, click **refresh** icon
   - You should see `student_management` database
   - Expand it to see `students` table

---

### Step 5: Copy PHP Files to Web Server Directory

1. **Open File Explorer**
2. **Navigate to:** `C:\Users\uchih\OneDrive\Desktop\`
3. **Copy** the entire `PHP` folder
4. **Navigate to:**
   - **XAMPP:** `C:\xampp\htdocs\`
   - **WAMP:** `C:\wamp64\www\`
5. **Paste** the `PHP` folder there
6. **Verify** files are at: `C:\xampp\htdocs\PHP\`

---

### Step 6: Start Apache Service

1. **Open XAMPP Control Panel**
2. **Start Apache** (click "Start" button)
   - Wait until it turns **green** (shows "Running")
3. **If you installed MySQL in XAMPP:**
   - You can skip starting MySQL (use your existing MySQL)
   - Or start it if you want to use XAMPP's MySQL

**Note:** If you're using your existing MySQL (not XAMPP's), you only need Apache running.

---

### Step 7: Test Apache is Working

1. **Open your web browser**
2. **Go to:** `http://localhost`
3. **You should see:** XAMPP dashboard or welcome page
4. **If you see the page:** Apache is working! ✅

---

### Step 8: Run Your PHP Application

1. **Open your web browser**
2. **Go to:** `http://localhost/PHP/index.php`
3. **You should see:** Student Management System page!

---

## ✅ Testing Your Application

### Test 1: Check Database Connection
- Open: `http://localhost/PHP/index.php`
- If you see the page (even with "No students found"), connection works!

### Test 2: Create a Student
1. Click **"Add New Student"**
2. Fill in:
   - **Name:** John Doe
   - **Email:** john@example.com
3. Click **"Save Student"**
4. Should redirect to list page with success message

### Test 3: View Students
- Student should appear in the table
- Shows: ID, Name, Email, Created At

### Test 4: Edit Student
1. Click **"Edit"** button
2. Modify information
3. Click **"Update Student"**
4. Changes should be saved

### Test 5: Delete Student
1. Click **"Delete"** button
2. Confirm deletion
3. Student should be removed

---

## 🔧 Troubleshooting

### Problem: "Connection failed" Error

**Check:**
1. MySQL server is running (check in MySQL Workbench)
2. Password in `config.php` matches your MySQL password
3. Database name is correct: `student_management`
4. Hostname is correct: `localhost` or `127.0.0.1`

**Solution:**
- Verify connection in MySQL Workbench works
- Test connection with same credentials
- Check MySQL service is running (if using Windows Services)

### Problem: "Access denied" for Database

**Solution:**
- Verify username and password in `config.php`
- Check user has permission to access `student_management` database
- Try connecting in MySQL Workbench with same credentials

### Problem: Apache Won't Start

**Solution:**
- Port 80 might be in use (common: Skype)
- Close Skype or other programs using port 80
- Or change Apache port in `httpd.conf`:
  - Change `Listen 80` to `Listen 8080`
  - Access: `http://localhost:8080/PHP/index.php`

### Problem: "404 Not Found"

**Solution:**
- Check files are in: `C:\xampp\htdocs\PHP\`
- Verify Apache is running
- Check URL: `http://localhost/PHP/index.php`
- Try: `http://localhost/` (should show XAMPP dashboard)

### Problem: "Table doesn't exist"

**Solution:**
- Run `database_setup.sql` script in MySQL Workbench
- Verify database `student_management` exists
- Verify table `students` exists
- Check you're using the correct database

---

## 📋 Quick Checklist

Before running, verify:

- [ ] XAMPP installed (Apache + PHP)
- [ ] Apache service is running (green in XAMPP Control Panel)
- [ ] MySQL server is running (can connect in Workbench)
- [ ] Database `student_management` created in MySQL Workbench
- [ ] Table `students` created
- [ ] `config.php` has correct MySQL credentials
- [ ] PHP files copied to `C:\xampp\htdocs\PHP\`
- [ ] Can access `http://localhost` (XAMPP dashboard)
- [ ] Can access `http://localhost/PHP/index.php` (your application)

---

## 🎯 Quick Reference URLs

- **XAMPP Dashboard:** `http://localhost`
- **Your Application:** `http://localhost/PHP/index.php`
- **phpMyAdmin:** `http://localhost/phpmyadmin` (if installed)

---

## 💡 Important Notes

### Using Your Existing MySQL vs XAMPP MySQL

**If you have MySQL already installed:**
- ✅ Use your existing MySQL (via Workbench)
- ✅ Only need Apache from XAMPP
- ✅ Skip MySQL installation in XAMPP
- ✅ Use your existing MySQL credentials

**If you want to use XAMPP MySQL:**
- ✅ Install MySQL component in XAMPP
- ✅ Start MySQL in XAMPP Control Panel
- ✅ Use default XAMPP credentials (root, empty password)
- ✅ Update `config.php` accordingly

**Current Setup (Based on Your Config):**
- You're using password: `Sharingan_82`
- This suggests you're using your existing MySQL (not XAMPP default)
- Keep using your existing MySQL setup ✅

---

## 🚀 Summary: Quick Start

1. **Install XAMPP** (Apache + PHP only, skip MySQL)
2. **Start Apache** in XAMPP Control Panel
3. **Create database** in MySQL Workbench (run SQL script)
4. **Update `config.php`** with your MySQL credentials
5. **Copy PHP files** to `C:\xampp\htdocs\PHP\`
6. **Access:** `http://localhost/PHP/index.php`

---

## 📞 Need Help?

- Check MySQL connection in MySQL Workbench
- Verify database exists and table is created
- Check `config.php` credentials match Workbench connection
- Ensure Apache is running in XAMPP Control Panel

---

**You're all set! Follow the steps above to run your application. 🎉**

