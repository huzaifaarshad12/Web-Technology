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
        /* Reset and base */
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f4f6f8;
            color: #223;
            padding: 20px;
            min-height: 100vh;
        }

        /* Card container */
        .container {
            max-width: 880px;
            margin: 24px auto;
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 8px 24px rgba(35,46,60,0.08);
            padding: 32px;
        }

        /* Headings */
        h1 {
            color: #0b3d91; /* deep navy */
            margin-bottom: 6px;
            text-align: left;
            font-size: 1.9rem;
            font-weight: 700;
        }
        .subtitle {
            color: #6c757d;
            margin-bottom: 24px;
            font-size: 0.95rem;
        }

        /* Messages */
        .message {
            padding: 14px 18px;
            margin-bottom: 18px;
            border-radius: 8px;
            font-weight: 600;
            box-shadow: 0 3px 10px rgba(35,46,60,0.04);
            border-left: 4px solid transparent;
        }
        .message.success { background: #e9f7ef; color: #145a32; border-left-color: #2ecc71; }
        .message.error   { background: #fdecea; color: #6b1e1e; border-left-color: #e74c3c; }
        .message.info    { background: #eef7ff; color: #084e8a; border-left-color: #3498db; }

        /* Form layout */
        .form-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; margin-bottom: 18px; }
        .form-group { margin-bottom: 12px; }
        .form-group.full-width { grid-column: 1 / -1; }

        label { display:block; margin-bottom:8px; color:#2b3b4a; font-weight:600; font-size:0.92rem; }

        input[type="text"], input[type="email"], input[type="tel"],
        input[type="date"], input[type="number"], select, textarea {
            width:100%; padding:12px 14px; font-size:0.95rem; border:1px solid #d6dce1; border-radius:8px;
            background:#fff; transition: box-shadow .18s ease, border-color .18s ease;
        }
        input:focus, select:focus, textarea:focus {
            outline: none; border-color: #0b6df0; box-shadow: 0 6px 18px rgba(11,109,240,0.08);
        }
        textarea { min-height:100px; resize:vertical; }

        .required { color:#c92a2a; }

    .form-section { margin-bottom: 20px; padding:18px; background:#fbfdff; border-radius:8px; border:1px solid #eef3f7; }
    .form-section h3 { color:#0b3d91; margin-bottom:14px; font-size:1.05rem; font-weight:700; }

        /* Buttons */
    .btn { display:inline-block; padding:12px 18px; border-radius:8px; font-weight:700; font-size:0.95rem; cursor:pointer; width:100%; border: none; }
    .btn { background: #0b6df0; color:#fff; box-shadow: 0 6px 18px rgba(11,109,240,0.14); }
    .btn:hover { transform: translateY(-2px); box-shadow: 0 10px 30px rgba(11,109,240,0.16); }
    .btn:active { transform: translateY(0); }

        .btn-secondary { background: transparent; color: #3b4754; border: 1px solid #d6dce1; margin-top:12px; box-shadow:none; }
        .btn-secondary:hover { background:#f6f8fb; }

        @media (max-width: 800px) {
            .form-grid { grid-template-columns: 1fr; }
            .container { padding: 20px; }
            h1 { font-size:1.4rem; }
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
