# Quick Start Guide - How to Run Your PHP Application

## ✅ Prerequisites Check
- [x] Database credentials configured in `config.php`
- [x] Database schema created in MySQL Workbench
- [ ] Files copied to web server directory
- [ ] Apache and MySQL services started

---

## 🚀 Step-by-Step Instructions

### Step 1: Copy Files to Web Server Directory

Your files are currently in: `C:\Users\uchih\OneDrive\Desktop\PHP\`

You need to copy them to your web server directory:

#### For XAMPP Users:
**Copy to:** `C:\xampp\htdocs\PHP\`

#### For WAMP Users:
**Copy to:** `C:\wamp64\www\PHP\`

#### For MAMP Users:
**Copy to:** `/Applications/MAMP/htdocs/PHP/`

**Quick Method:**
1. Copy the entire `PHP` folder from your Desktop
2. Paste it into `htdocs` (XAMPP) or `www` (WAMP) folder
3. Your path should be: `C:\xampp\htdocs\PHP\` (or similar)

---

### Step 2: Start Web Server Services

#### For XAMPP:
1. Open **XAMPP Control Panel**
2. Click **Start** button next to **Apache**
3. Click **Start** button next to **MySQL**
4. Both should show green "Running" status

#### For WAMP:
1. Open **WAMP Server**
2. Wait for icon to turn **GREEN**
3. If not green, right-click icon → **Start All Services**

#### For MAMP:
1. Open **MAMP**
2. Click **Start Servers** button
3. Wait for Apache and MySQL to start

---

### Step 3: Verify MySQL is Running

1. Open **MySQL Workbench**
2. Connect to your MySQL server
3. Verify `student_management` database exists
4. Check that `students` table exists with correct structure

---

### Step 4: Access the Application

Open your web browser and go to:

```
http://localhost/PHP/index.php
```

Or if you placed it in a different folder:
```
http://localhost/your-folder-name/index.php
```

---

### Step 5: Test the Application

1. **View Students List:**
   - You should see the main page with student list (may be empty if no data)

2. **Create a Student:**
   - Click **"Add New Student"** button
   - Fill in Name and Email
   - Click **"Save Student"**
   - You should be redirected to the list page

3. **View Students:**
   - The new student should appear in the table

4. **Edit a Student:**
   - Click **"Edit"** button next to a student
   - Modify the information
   - Click **"Update Student"**

5. **Delete a Student:**
   - Click **"Delete"** button
   - Confirm the deletion
   - Student should be removed

---

## 🔍 Troubleshooting

### Problem: "Connection failed" Error
**Solution:**
- Check if MySQL service is running
- Verify password in `config.php` matches your MySQL password
- Ensure database name is correct: `student_management`

### Problem: "404 Not Found" or Page Not Loading
**Solution:**
- Check if Apache service is running
- Verify files are in correct location: `C:\xampp\htdocs\PHP\`
- Check URL: `http://localhost/PHP/index.php`
- Try: `http://localhost/PHP/` (should list files)

### Problem: "Access denied" for database
**Solution:**
- Double-check username and password in `config.php`
- Verify MySQL user has access to `student_management` database
- Try connecting with MySQL Workbench using same credentials

### Problem: "Table doesn't exist"
**Solution:**
- Run the `database_setup.sql` script in MySQL Workbench
- Or manually create the database and table

### Problem: Browser shows PHP code instead of executing
**Solution:**
- PHP is not installed or Apache is not configured for PHP
- Reinstall XAMPP/WAMP or check PHP installation

---

## 📝 Quick Checklist

Before accessing the application:

- [ ] Files copied to `htdocs` or `www` folder
- [ ] Apache service is **RUNNING** (green in XAMPP/WAMP)
- [ ] MySQL service is **RUNNING** (green in XAMPP/WAMP)
- [ ] Database `student_management` exists in MySQL
- [ ] Table `students` exists with correct structure
- [ ] `config.php` has correct password: `Sharingan_82`
- [ ] Browser URL is correct: `http://localhost/PHP/index.php`

---

## 🎯 Expected Result

When you open `http://localhost/PHP/index.php`, you should see:

1. **Header:** "Student Management System"
2. **Button:** "Add New Student"
3. **Table:** List of students (or message "No students found")
4. **Actions:** Edit and Delete buttons for each student

---

## 💡 Additional Tips

1. **Check PHP Version:**
   - Create a file `info.php` with: `<?php phpinfo(); ?>`
   - Access: `http://localhost/PHP/info.php`
   - This shows PHP configuration

2. **View PHP Errors:**
   - Check `C:\xampp\apache\logs\error.log` (XAMPP)
   - Or enable error display in PHP (for development only)

3. **Test Database Connection:**
   - Try accessing `http://localhost/PHP/index.php`
   - If connection fails, you'll see an error message

---

## 🎉 You're Ready!

Once you see the student list page, your application is working correctly!

Start by adding a few students, then test the Edit and Delete features.

---

**Need Help?** Check the main `README.md` file for more detailed information.

