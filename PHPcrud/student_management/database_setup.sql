-- Student Management Database Setup
-- Run this script in MySQL Workbench to create the database and table

-- Create database
CREATE DATABASE IF NOT EXISTS student_management;
USE student_management;

-- Drop existing table if you want to recreate with new structure
DROP TABLE IF EXISTS students;

-- Create students table with enhanced fields
CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    phone VARCHAR(20),
    birthdate DATE,
    address TEXT,
    course VARCHAR(100),
    year_level ENUM('Freshman', 'Sophomore', 'Junior', 'Senior', 'Graduate') DEFAULT 'Freshman',
    gpa DECIMAL(3,2),
    status ENUM('Active', 'Inactive', 'Graduated', 'Suspended') DEFAULT 'Active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert sample data (optional)
INSERT INTO students (name, email, phone, birthdate, address, course, year_level, gpa, status) VALUES
('John Doe', 'john.doe@example.com', '+1-555-0101', '2000-05-15', '123 Main St, City, State 12345', 'Computer Science', 'Junior', 3.75, 'Active'),
('Jane Smith', 'jane.smith@example.com', '+1-555-0102', '2001-08-22', '456 Oak Ave, City, State 12345', 'Business Administration', 'Sophomore', 3.90, 'Active'),
('Bob Johnson', 'bob.johnson@example.com', '+1-555-0103', '1999-12-10', '789 Pine Rd, City, State 12345', 'Engineering', 'Senior', 3.65, 'Active'),
('Alice Williams', 'alice.williams@example.com', '+1-555-0104', '2002-03-30', '321 Elm St, City, State 12345', 'Psychology', 'Freshman', 3.55, 'Active'),
('Charlie Brown', 'charlie.brown@example.com', '+1-555-0105', '1998-07-05', '654 Maple Dr, City, State 12345', 'Mathematics', 'Graduate', 3.95, 'Graduated');

