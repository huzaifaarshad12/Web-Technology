# Setup Checklist - Quick Reference

Use this checklist to ensure everything is installed and configured correctly.

---

## 📦 Installation Steps

### Step 1: Install XAMPP
- [ ] Download XAMPP from https://www.apachefriends.org/download.html
- [ ] Run installer as Administrator
- [ ] Select components: Apache, MySQL, PHP, phpMyAdmin
- [ ] Install to default location: `C:\xampp`
- [ ] Allow Windows Firewall access for Apache and MySQL
- [ ] Installation completed successfully

### Step 2: Start XAMPP Services
- [ ] Open XAMPP Control Panel
- [ ] Click "Start" for Apache → Status shows "Running" (green)
- [ ] Click "Start" for MySQL → Status shows "Running" (green)
- [ ] Test: Open browser → `http://localhost` → See XAMPP dashboard

### Step 3: (Optional) Install MySQL Workbench
- [ ] Download MySQL Workbench from https://dev.mysql.com/downloads/workbench/
- [ ] Install MySQL Workbench
- [ ] Create connection:
  - Connection Name: `XAMPP Local`
  - Hostname: `localhost`
  - Port: `3306`
  - Username: `root`
  - Password: `[blank/empty]` (XAMPP default)
- [ ] Test connection → Should be successful

### Step 4: Copy PHP Files
- [ ] Open File Explorer
- [ ] Navigate to: `C:\Users\uchih\OneDrive\Desktop\`
- [ ] Copy `PHP` folder
- [ ] Navigate to: `C:\xampp\htdocs\`
- [ ] Paste `PHP` folder
- [ ] Verify files are at: `C:\xampp\htdocs\PHP\`
- [ ] Check files exist: `index.php`, `create.php`, `edit.php`, `config.php`

### Step 5: Create Database
- [ ] Open MySQL Workbench (or phpMyAdmin)
- [ ] Connect to MySQL server
- [ ] Run `database_setup.sql` script
- [ ] Verify database `student_management` exists
- [ ] Verify table `students` exists
- [ ] (Optional) Check sample data was inserted

### Step 6: Configure Database Connection
- [ ] Open: `C:\xampp\htdocs\PHP\config.php`
- [ ] Check settings:
  - `DB_HOST`: `localhost` ✅
  - `DB_USER`: `root` ✅
  - `DB_PASS`: `Sharingan_82` (or empty if using XAMPP default)
  - `DB_NAME`: `student_management` ✅

### Step 7: Test Application
- [ ] Open browser
- [ ] Go to: `http://localhost/PHP/index.php`
- [ ] See Student Management System page
- [ ] Test Create: Add new student → Success
- [ ] Test Read: View student list → Success
- [ ] Test Update: Edit student → Success
- [ ] Test Delete: Delete student → Success

---

## ✅ Final Verification

### Services Running
- [ ] Apache: Running (green in XAMPP Control Panel)
- [ ] MySQL: Running (green in XAMPP Control Panel)

### Files in Place
- [ ] Files at: `C:\xampp\htdocs\PHP\`
- [ ] All PHP files present

### Database Ready
- [ ] Database `student_management` exists
- [ ] Table `students` exists
- [ ] Can connect to MySQL

### Application Working
- [ ] Can access: `http://localhost/PHP/index.php`
- [ ] No errors on page load
- [ ] CRUD operations work

---

## 🚨 Common Issues Quick Fix

### Apache Won't Start
- Solution: Close Skype or change port in `httpd.conf`

### MySQL Won't Start
- Solution: Check port 3306, restart as Administrator

### Connection Failed
- Solution: Check password in `config.php`, verify MySQL is running

### 404 Not Found
- Solution: Check files are in `htdocs` folder, verify Apache is running

### Access Denied
- Solution: Check MySQL username/password, verify database exists

---

## 📞 Quick Access URLs

- **XAMPP Dashboard**: `http://localhost`
- **phpMyAdmin**: `http://localhost/phpmyadmin`
- **Your Application**: `http://localhost/PHP/index.php`
- **PHP Info**: `http://localhost/PHP/info.php` (create this file if needed)

---

## 🎯 Success Criteria

You're ready when:
- ✅ Apache and MySQL are running
- ✅ Database is created
- ✅ Files are in `htdocs` folder
- ✅ Can access `http://localhost/PHP/index.php`
- ✅ Can add, edit, and delete students

---

**All checked? Your application is ready to use! 🎉**

