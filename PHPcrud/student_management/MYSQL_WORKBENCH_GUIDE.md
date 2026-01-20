# MySQL Workbench Connection Guide
## Step-by-Step Instructions for Connecting to Your Database

---

## Prerequisites
Before connecting, ensure you have:
- ✅ MySQL Server installed and running (XAMPP, WAMP, or standalone MySQL)
- ✅ MySQL Workbench installed
- ✅ Database credentials (username and password)

---

## Method 1: Connecting via MySQL Workbench (Recommended)

### Step 1: Open MySQL Workbench
1. Launch **MySQL Workbench** from your applications
2. You'll see the **Home** screen with connection options

### Step 2: Create a New Connection
1. Click on the **"+"** icon next to "MySQL Connections" (or click "MySQL Connections" and then the "+" icon)
2. A new connection setup dialog will appear

### Step 3: Configure Connection Settings

Fill in the following details:

**Connection Name:**
```
Student Management
```
(Or any name you prefer)

**Connection Method:**
- Select **"Standard (TCP/IP)"**

**Hostname:**
```
localhost
```
(Or `127.0.0.1`)

**Port:**
```
3306
```
(Default MySQL port)

**Username:**
```
root
```
(Default username, change if you have a different one)

**Password:**
- Click **"Store in Keychain"** or **"Store in Vault"** to save your password
- Enter your MySQL root password
- If you're using XAMPP/WAMP, the default password is often **empty (blank)**

**Default Schema:**
- Leave blank (we'll create the database later)
- Or type: `student_management` if you've already created it

### Step 4: Test Connection
1. Click **"Test Connection"** button
2. If successful, you'll see a green checkmark: ✅ **"Successfully made the MySQL connection"**
3. If it fails, check:
   - Is MySQL server running?
   - Are the credentials correct?
   - Is the port correct?

### Step 5: Save and Connect
1. Click **"OK"** to save the connection
2. Double-click on your new connection to open it
3. You'll be prompted for the password (if not stored)
4. Once connected, you'll see the SQL editor

---

## Method 2: Using XAMPP/WAMP (Quick Setup)

### For XAMPP Users:
1. **Start XAMPP Control Panel**
2. **Start Apache** and **MySQL** services
3. **Default MySQL Settings:**
   - Host: `localhost`
   - Username: `root`
   - Password: *(empty/blank)*
   - Port: `3306`

### For WAMP Users:
1. **Start WAMP Server**
2. Wait for the icon to turn **green**
3. **Default MySQL Settings:**
   - Host: `localhost`
   - Username: `root`
   - Password: *(empty/blank)*
   - Port: `3306`

---

## Step 6: Creating the Database

### Option A: Using MySQL Workbench SQL Editor

1. Once connected, open the **SQL Editor** (you should see it by default)
2. Copy and paste the following SQL script:

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

3. Click the **Execute** button (lightning bolt icon) or press `Ctrl+Enter`
4. You should see a success message in the output panel

### Option B: Using the Provided SQL File

1. In MySQL Workbench, go to **File → Open SQL Script**
2. Navigate to `database_setup.sql` in your project folder
3. Click **Open**
4. Click **Execute** (or press `Ctrl+Enter`)
5. Verify the database was created by checking the **Schemas** panel on the left

---

## Step 7: Verifying the Database

1. In the **Schemas** panel on the left side, click the **refresh** icon
2. You should see `student_management` database listed
3. Expand it to see the `students` table
4. Right-click on `students` → **Select Rows - Limit 1000** to view data

---

## Troubleshooting Common Issues

### Issue 1: "Cannot connect to MySQL server"
**Solutions:**
- ✅ Check if MySQL service is running (XAMPP/WAMP Control Panel)
- ✅ Verify hostname is `localhost` or `127.0.0.1`
- ✅ Check if port 3306 is correct (check in MySQL settings)
- ✅ Ensure MySQL is not blocked by firewall

### Issue 2: "Access denied for user 'root'@'localhost'"
**Solutions:**
- ✅ Verify username is correct (usually `root`)
- ✅ Check password (try empty password for XAMPP/WAMP)
- ✅ If you forgot the password, you may need to reset it

### Issue 3: "Unknown database 'student_management'"
**Solutions:**
- ✅ Run the `database_setup.sql` script first
- ✅ Or manually create the database using:
  ```sql
  CREATE DATABASE student_management;
  USE student_management;
  ```

### Issue 4: "Can't connect to MySQL server on 'localhost' (10061)"
**Solutions:**
- ✅ MySQL service is not running - start it from XAMPP/WAMP
- ✅ Check if MySQL is installed correctly
- ✅ Try restarting MySQL service

### Issue 5: Port Already in Use
**Solutions:**
- ✅ Check if another MySQL instance is running
- ✅ Change port in MySQL configuration (advanced users)
- ✅ Or stop the conflicting service

---

## Testing the Connection from PHP

After setting up the database, test your PHP connection:

1. Update `config.php` with your credentials:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_USER', 'root');
   define('DB_PASS', '');  // Your password here
   define('DB_NAME', 'student_management');
   ```

2. Open `index.php` in your browser
3. If you see the student list or "No students found", the connection works!

---

## Quick Reference: Connection Parameters

| Setting | Default Value | Notes |
|---------|--------------|-------|
| **Hostname** | `localhost` | Use `127.0.0.1` if localhost doesn't work |
| **Port** | `3306` | Standard MySQL port |
| **Username** | `root` | Default admin user |
| **Password** | *(empty)* | Often blank for local development |
| **Connection Method** | `Standard (TCP/IP)` | Most common method |

---

## Additional Tips

1. **Save Your Connection:**
   - MySQL Workbench saves connections automatically
   - You can manage them from the Home screen

2. **Multiple Connections:**
   - You can create multiple connections for different databases
   - Useful for development vs. production environments

3. **Connection Timeout:**
   - If connection drops, check server timeout settings
   - Default is usually sufficient

4. **Security Note:**
   - Never use root password in production
   - Create separate database users with limited privileges

---

## Next Steps

After successful connection:
1. ✅ Database is created and ready
2. ✅ Update `config.php` with correct credentials
3. ✅ Test the CRUD application
4. ✅ Start using the Student Management System!

---

## Need More Help?

- **MySQL Workbench Documentation:** https://dev.mysql.com/doc/workbench/en/
- **MySQL Official Docs:** https://dev.mysql.com/doc/
- **XAMPP Guide:** https://www.apachefriends.org/docs/
- **WAMP Guide:** https://www.wampserver.com/en/

---

**Happy Coding! 🚀**

