# Quick Fix - Showing Old Features

If you're still seeing old features, follow these steps:

## Step 1: Update Database (IMPORTANT!)

Your database table needs to have the new fields. Choose one option:

### Option A: Update Existing Table (Keeps Your Data)
1. Open MySQL Workbench
2. Connect to your database
3. Open `update_database.sql` file
4. Copy and paste the entire script
5. Execute it (⚡ icon or Ctrl+Enter)
6. This will ADD new columns without losing existing data

### Option B: Recreate Table (Fresh Start)
1. Open MySQL Workbench
2. Connect to your database
3. Open `database_setup.sql` file
4. Execute it (⚡ icon)
5. **WARNING:** This will DELETE all existing data and create a fresh table

## Step 2: Clear Browser Cache

After updating the database, clear your browser cache:

### Chrome/Edge:
1. Press `Ctrl + Shift + Delete`
2. Select "Cached images and files"
3. Click "Clear data"
4. Or press `Ctrl + F5` for hard refresh

### Firefox:
1. Press `Ctrl + Shift + Delete`
2. Select "Cache"
3. Click "Clear Now"
4. Or press `Ctrl + F5` for hard refresh

### Alternative: Hard Refresh
- Press `Ctrl + F5` (Windows/Linux)
- Press `Cmd + Shift + R` (Mac)

## Step 3: Verify Database Structure

Check if your table has all the new fields:

1. Open MySQL Workbench
2. Connect to database
3. Expand `student_management` → `students` → `Columns`
4. You should see these columns:
   - id
   - name
   - email
   - phone ✅
   - birthdate ✅
   - address ✅
   - course ✅
   - year_level ✅
   - gpa ✅
   - status ✅
   - created_at
   - updated_at ✅

If any are missing, run the update script again.

## Step 4: Check File Location

Make sure you're accessing the correct files:

1. Files should be in: `C:\xampp\htdocs\PHP\`
2. Access via: `http://localhost/PHP/index.php`

## Step 5: Test

1. Refresh the page (`Ctrl + F5`)
2. You should see:
   - Search box at the top
   - Statistics cards (Total, Active, Average GPA)
   - Table with columns: ID, Name, Email, Phone, Age, Course, Year, GPA, Status
   - Status badges (colored)
   - GPA color coding

## Still Not Working?

1. Check browser console for errors (F12)
2. Check PHP error logs: `C:\xampp\apache\logs\error.log`
3. Verify database connection is working
4. Try accessing directly: `http://localhost/PHP/index.php?refresh=1`

## Common Issues

### Issue: "Unknown column 'phone' in 'field list'"
**Solution:** Run `update_database.sql` script

### Issue: Still seeing old table structure
**Solution:** 
- Clear browser cache (Ctrl + F5)
- Restart Apache in XAMPP
- Check database structure in MySQL Workbench

### Issue: Forms don't have new fields
**Solution:**
- Check `create.php` and `edit.php` files are updated
- Clear browser cache
- Try incognito/private mode

---

**After updating database, the new features should appear!**

