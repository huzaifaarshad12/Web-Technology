-- Simple Database Update Script
-- Run this in MySQL Workbench to add new columns
-- This keeps your existing data!

USE student_management;

-- Add new columns (only if they don't exist)
ALTER TABLE students 
ADD COLUMN IF NOT EXISTS phone VARCHAR(20),
ADD COLUMN IF NOT EXISTS birthdate DATE,
ADD COLUMN IF NOT EXISTS address TEXT,
ADD COLUMN IF NOT EXISTS course VARCHAR(100),
ADD COLUMN IF NOT EXISTS year_level ENUM('Freshman', 'Sophomore', 'Junior', 'Senior', 'Graduate') DEFAULT 'Freshman',
ADD COLUMN IF NOT EXISTS gpa DECIMAL(3,2),
ADD COLUMN IF NOT EXISTS status ENUM('Active', 'Inactive', 'Graduated', 'Suspended') DEFAULT 'Active',
ADD COLUMN IF NOT EXISTS updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;

-- If MySQL doesn't support IF NOT EXISTS, use this instead:
-- (Uncomment and use if the above doesn't work)

/*
ALTER TABLE students ADD COLUMN phone VARCHAR(20);
ALTER TABLE students ADD COLUMN birthdate DATE;
ALTER TABLE students ADD COLUMN address TEXT;
ALTER TABLE students ADD COLUMN course VARCHAR(100);
ALTER TABLE students ADD COLUMN year_level ENUM('Freshman', 'Sophomore', 'Junior', 'Senior', 'Graduate') DEFAULT 'Freshman';
ALTER TABLE students ADD COLUMN gpa DECIMAL(3,2);
ALTER TABLE students ADD COLUMN status ENUM('Active', 'Inactive', 'Graduated', 'Suspended') DEFAULT 'Active';
ALTER TABLE students ADD COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;
*/

SELECT 'Database updated successfully!' AS Result;

