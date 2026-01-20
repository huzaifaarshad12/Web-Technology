<?php
/**
 * Student Management System - Create Operation
 * Enhanced form with additional fields
 */

require_once 'config.php';

$message = '';
$message_type = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and validate input
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $birthdate = $_POST['birthdate'] ?? '';
    $address = trim($_POST['address'] ?? '');
    $course = trim($_POST['course'] ?? '');
    $year_level = $_POST['year_level'] ?? 'Freshman';
    $gpa = !empty($_POST['gpa']) ? floatval($_POST['gpa']) : null;
    $status = $_POST['status'] ?? 'Active';
    
    // Validation
    $errors = [];
    
    if (empty($name)) {
        $errors[] = "Name is required.";
    } elseif (strlen($name) > 100) {
        $errors[] = "Name must be less than 100 characters.";
    }
    
    if (empty($email)) {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    } elseif (strlen($email) > 100) {
        $errors[] = "Email must be less than 100 characters.";
    }
    
    if (!empty($phone) && strlen($phone) > 20) {
        $errors[] = "Phone number must be less than 20 characters.";
    }
    
    if (!empty($birthdate) && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $birthdate)) {
        $errors[] = "Invalid birthdate format.";
    }
    
    if (!empty($gpa) && ($gpa < 0 || $gpa > 4.0)) {
        $errors[] = "GPA must be between 0.00 and 4.00.";
    }
    
    if (empty($errors)) {
        $conn = getDBConnection();
        
        // Using prepared statement to prevent SQL injection
        $stmt = $conn->prepare("INSERT INTO students (name, email, phone, birthdate, address, course, year_level, gpa, status) 
                                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        // Convert empty strings to NULL for optional fields
        $phone = empty($phone) ? null : $phone;
        $birthdate = empty($birthdate) ? null : $birthdate;
        $address = empty($address) ? null : $address;
        $course = empty($course) ? null : $course;
        $gpa = empty($gpa) ? null : $gpa;
        
        $stmt->bind_param("sssssssds", $name, $email, $phone, $birthdate, $address, $course, $year_level, $gpa, $status);
        
        if ($stmt->execute()) {
            $message = "Student added successfully!";
            $message_type = "success";
            // Clear form fields
            $name = $email = $phone = $birthdate = $address = $course = '';
            $year_level = 'Freshman';
            $gpa = '';
            $status = 'Active';
        } else {
            // Check if error is due to duplicate email
            if ($conn->errno == 1062) {
                $message = "Error: Email already exists in the database.";
            } else {
                $message = "Error: " . $conn->error;
            }
            $message_type = "error";
        }
        
        $stmt->close();
        $conn->close();
    } else {
        $message = implode("<br>", $errors);
        $message_type = "error";
    }
} else {
    // Initialize form fields
    $name = $email = $phone = $birthdate = $address = $course = '';
    $year_level = 'Freshman';
    $gpa = '';
    $status = 'Active';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Student - Student Management System</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            background-attachment: fixed;
            padding: 20px;
            min-height: 100vh;
            position: relative;
        }
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: 
                radial-gradient(circle at 20% 50%, rgba(120, 119, 198, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(255, 255, 255, 0.1) 0%, transparent 50%);
            pointer-events: none;
            z-index: 0;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3), 0 0 0 1px rgba(255,255,255,0.1);
            padding: 40px;
            position: relative;
            z-index: 1;
            animation: fadeInUp 0.6s ease-out;
        }
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        h1 {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 10px;
            text-align: center;
            font-size: 2.5em;
            font-weight: 700;
            letter-spacing: -0.5px;
        }
        .subtitle {
            text-align: center;
            color: #888;
            margin-bottom: 35px;
            font-size: 1em;
        }
        .message {
            padding: 18px 24px;
            margin-bottom: 25px;
            border-radius: 12px;
            text-align: center;
            animation: slideDown 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            font-weight: 500;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            position: relative;
            overflow: hidden;
        }
        .message::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            width: 4px;
            height: 100%;
            background: currentColor;
            opacity: 0.3;
        }
        @keyframes slideDown {
            from { 
                opacity: 0; 
                transform: translateY(-20px) scale(0.95); 
            }
            to { 
                opacity: 1; 
                transform: translateY(0) scale(1); 
            }
        }
        .message.success {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            color: #155724;
            border: 1px solid #b8dacc;
        }
        .message.error {
            background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
            color: #721c24;
            border: 1px solid #f1b0b7;
        }
        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group.full-width {
            grid-column: 1 / -1;
        }
        label {
            display: block;
            margin-bottom: 10px;
            color: #333;
            font-weight: 600;
            font-size: 14px;
            letter-spacing: 0.3px;
        }
        input[type="text"],
        input[type="email"],
        input[type="tel"],
        input[type="date"],
        input[type="number"],
        select,
        textarea {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            font-size: 16px;
            font-family: inherit;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: #fafafa;
        }
        input:focus,
        select:focus,
        textarea:focus {
            outline: none;
            border-color: #667eea;
            background: white;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
            transform: translateY(-1px);
        }
        textarea {
            resize: vertical;
            min-height: 100px;
        }
        input[type="number"] {
            -moz-appearance: textfield;
        }
        input[type="number"]::-webkit-outer-spin-button,
        input[type="number"]::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        .btn {
            display: inline-block;
            padding: 16px 30px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            width: 100%;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
            position: relative;
            overflow: hidden;
        }
        .btn::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }
        .btn:hover::before {
            width: 300px;
            height: 300px;
        }
        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.5);
        }
        .btn:active {
            transform: translateY(-1px);
        }
        .btn-secondary {
            background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
            margin-top: 12px;
            box-shadow: 0 4px 15px rgba(108, 117, 125, 0.3);
        }
        .btn-secondary:hover {
            box-shadow: 0 8px 25px rgba(108, 117, 125, 0.4);
        }
        .required {
            color: #dc3545;
        }
        .form-section {
            margin-bottom: 35px;
            padding: 25px;
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
            border-radius: 16px;
            border: 1px solid #e9ecef;
            box-shadow: 0 2px 10px rgba(0,0,0,0.03);
            transition: all 0.3s;
        }
        .form-section:hover {
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            transform: translateY(-2px);
        }
        .form-section:last-child {
            border-bottom: none;
        }
        .form-section h3 {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 25px;
            font-size: 1.3em;
            font-weight: 700;
        }
        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
            .container {
                padding: 25px;
                border-radius: 15px;
            }
            h1 {
                font-size: 1.8em;
            }
            .form-section {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>➕ Add New Student</h1>
        <p class="subtitle">Create Operation - Insert into Database</p>
        
        <?php if ($message): ?>
            <div class="message <?php echo $message_type; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="create.php">
            <div class="form-section">
                <h3>📋 Personal Information</h3>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="name">Full Name <span class="required">*</span></label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               value="<?php echo htmlspecialchars($name ?? ''); ?>" 
                               required 
                               maxlength="100"
                               placeholder="Enter full name">
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email Address <span class="required">*</span></label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               value="<?php echo htmlspecialchars($email ?? ''); ?>" 
                               required 
                               maxlength="100"
                               placeholder="student@example.com">
                    </div>
                    
                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="tel" 
                               id="phone" 
                               name="phone" 
                               value="<?php echo htmlspecialchars($phone ?? ''); ?>" 
                               maxlength="20"
                               placeholder="+1-555-0123">
                    </div>
                    
                    <div class="form-group">
                        <label for="birthdate">Date of Birth</label>
                        <input type="date" 
                               id="birthdate" 
                               name="birthdate" 
                               value="<?php echo htmlspecialchars($birthdate ?? ''); ?>">
                    </div>
                    
                    <div class="form-group full-width">
                        <label for="address">Address</label>
                        <textarea id="address" 
                                  name="address" 
                                  placeholder="Enter full address"
                                  maxlength="500"><?php echo htmlspecialchars($address ?? ''); ?></textarea>
                    </div>
                </div>
            </div>
            
            <div class="form-section">
                <h3>🎓 Academic Information</h3>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="course">Course/Major</label>
                        <input type="text" 
                               id="course" 
                               name="course" 
                               value="<?php echo htmlspecialchars($course ?? ''); ?>" 
                               maxlength="100"
                               placeholder="e.g., Computer Science">
                    </div>
                    
                    <div class="form-group">
                        <label for="year_level">Year Level</label>
                        <select id="year_level" name="year_level">
                            <option value="Freshman" <?php echo ($year_level ?? 'Freshman') === 'Freshman' ? 'selected' : ''; ?>>Freshman</option>
                            <option value="Sophomore" <?php echo ($year_level ?? '') === 'Sophomore' ? 'selected' : ''; ?>>Sophomore</option>
                            <option value="Junior" <?php echo ($year_level ?? '') === 'Junior' ? 'selected' : ''; ?>>Junior</option>
                            <option value="Senior" <?php echo ($year_level ?? '') === 'Senior' ? 'selected' : ''; ?>>Senior</option>
                            <option value="Graduate" <?php echo ($year_level ?? '') === 'Graduate' ? 'selected' : ''; ?>>Graduate</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="gpa">GPA (0.00 - 4.00)</label>
                        <input type="number" 
                               id="gpa" 
                               name="gpa" 
                               value="<?php echo htmlspecialchars($gpa ?? ''); ?>" 
                               min="0" 
                               max="4" 
                               step="0.01"
                               placeholder="3.75">
                    </div>
                    
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select id="status" name="status">
                            <option value="Active" <?php echo ($status ?? 'Active') === 'Active' ? 'selected' : ''; ?>>Active</option>
                            <option value="Inactive" <?php echo ($status ?? '') === 'Inactive' ? 'selected' : ''; ?>>Inactive</option>
                            <option value="Graduated" <?php echo ($status ?? '') === 'Graduated' ? 'selected' : ''; ?>>Graduated</option>
                            <option value="Suspended" <?php echo ($status ?? '') === 'Suspended' ? 'selected' : ''; ?>>Suspended</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <button type="submit" class="btn">💾 Save Student</button>
            <a href="index.php" class="btn btn-secondary">← Back to List</a>
        </form>
    </div>
</body>
</html>
