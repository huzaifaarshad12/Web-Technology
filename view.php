<?php
/**
 * Student Management System - View Student Details
 * Detailed view of a single student
 */

require_once 'config.php';

// Get student ID from URL
$student_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($student_id <= 0) {
    header("Location: index.php");
    exit();
}

// Fetch student data
$conn = getDBConnection();
$stmt = $conn->prepare("SELECT id, name, email, phone, birthdate, address, course, year_level, gpa, status, created_at, updated_at 
                        FROM students WHERE id = ?");
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();
$stmt->close();
$conn->close();

if (!$student) {
    header("Location: index.php");
    exit();
}

// Calculate age from birthdate
function calculateAge($birthdate) {
    if (empty($birthdate)) return 'N/A';
    $birth = new DateTime($birthdate);
    $today = new DateTime();
    return $today->diff($birth)->y;
}

// Get status badge class
function getStatusBadge($status) {
    $badges = [
        'Active' => 'badge-success',
        'Inactive' => 'badge-secondary',
        'Graduated' => 'badge-info',
        'Suspended' => 'badge-danger'
    ];
    return $badges[$status] ?? 'badge-secondary';
}

// Get GPA color class
function getGPAColor($gpa) {
    if ($gpa >= 3.7) return 'gpa-excellent';
    if ($gpa >= 3.0) return 'gpa-good';
    if ($gpa >= 2.0) return 'gpa-average';
    return 'gpa-poor';
}

// Format date
function formatDate($date) {
    if (empty($date)) return 'N/A';
    return date('F j, Y', strtotime($date));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Student - Student Management System</title>
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
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 35px;
            padding: 25px;
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
            border-radius: 16px;
            border: 1px solid #e9ecef;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }
        h1 {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-size: 2.2em;
            margin: 0;
            font-weight: 700;
            letter-spacing: -0.5px;
        }
        .badge {
            display: inline-block;
            padding: 10px 20px;
            border-radius: 25px;
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            transition: transform 0.2s;
        }
        .badge:hover {
            transform: scale(1.05);
        }
        .badge-success {
            background-color: #28a745;
            color: white;
        }
        .badge-secondary {
            background-color: #6c757d;
            color: white;
        }
        .badge-info {
            background-color: #17a2b8;
            color: white;
        }
        .badge-danger {
            background-color: #dc3545;
            color: white;
        }
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 25px;
            margin-bottom: 30px;
        }
        .info-section {
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
            padding: 30px;
            border-radius: 16px;
            border: 1px solid #e9ecef;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }
        .info-section::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            width: 5px;
            height: 100%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .info-section:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }
        .info-section h3 {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 20px;
            font-size: 1.2em;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .info-item {
            margin-bottom: 15px;
        }
        .info-item:last-child {
            margin-bottom: 0;
        }
        .info-label {
            font-weight: 600;
            color: #666;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }
        .info-value {
            color: #333;
            font-size: 16px;
            word-wrap: break-word;
        }
        .info-value.empty {
            color: #999;
            font-style: italic;
        }
        .gpa-excellent { color: #28a745; font-weight: 600; font-size: 1.2em; }
        .gpa-good { color: #17a2b8; font-weight: 600; font-size: 1.2em; }
        .gpa-average { color: #ffc107; font-weight: 600; font-size: 1.2em; }
        .gpa-poor { color: #dc3545; font-weight: 600; font-size: 1.2em; }
        .btn {
            display: inline-block;
            padding: 14px 28px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            margin-right: 12px;
            margin-bottom: 10px;
            border: none;
            cursor: pointer;
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
        .btn-edit {
            background: linear-gradient(135deg, #28a745 0%, #218838 100%);
            box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
        }
        .btn-edit:hover {
            box-shadow: 0 8px 25px rgba(40, 167, 69, 0.5);
        }
        .btn-secondary {
            background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
            box-shadow: 0 4px 15px rgba(108, 117, 125, 0.3);
        }
        .btn-secondary:hover {
            box-shadow: 0 8px 25px rgba(108, 117, 125, 0.4);
        }
        .actions {
            margin-top: 40px;
            padding: 25px;
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
            border-radius: 16px;
            border: 1px solid #e9ecef;
            box-shadow: 0 2px 10px rgba(0,0,0,0.03);
            text-align: center;
        }
        .full-width {
            grid-column: 1 / -1;
        }
        @media (max-width: 768px) {
            .info-grid {
                grid-template-columns: 1fr;
            }
            .container {
                padding: 25px;
                border-radius: 15px;
            }
            .header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
                padding: 20px;
            }
            h1 {
                font-size: 1.6em;
            }
            .info-section {
                padding: 20px;
            }
            .btn {
                width: 100%;
                margin-right: 0;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div>
                <h1>👤 <?php echo htmlspecialchars($student['name']); ?></h1>
                <p style="color: #666; margin-top: 5px;">Student ID: #<?php echo $student['id']; ?></p>
            </div>
            <span class="badge <?php echo getStatusBadge($student['status']); ?>">
                <?php echo htmlspecialchars($student['status']); ?>
            </span>
        </div>
        
        <div class="info-grid">
            <div class="info-section">
                <h3>📋 Personal Information</h3>
                <div class="info-item">
                    <div class="info-label">Full Name</div>
                    <div class="info-value"><?php echo htmlspecialchars($student['name']); ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Email Address</div>
                    <div class="info-value"><?php echo htmlspecialchars($student['email']); ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Phone Number</div>
                    <div class="info-value <?php echo empty($student['phone']) ? 'empty' : ''; ?>">
                        <?php echo !empty($student['phone']) ? htmlspecialchars($student['phone']) : 'Not provided'; ?>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-label">Date of Birth</div>
                    <div class="info-value <?php echo empty($student['birthdate']) ? 'empty' : ''; ?>">
                        <?php echo formatDate($student['birthdate']); ?>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-label">Age</div>
                    <div class="info-value"><?php echo calculateAge($student['birthdate']); ?> years old</div>
                </div>
            </div>
            
            <div class="info-section">
                <h3>🎓 Academic Information</h3>
                <div class="info-item">
                    <div class="info-label">Course/Major</div>
                    <div class="info-value <?php echo empty($student['course']) ? 'empty' : ''; ?>">
                        <?php echo !empty($student['course']) ? htmlspecialchars($student['course']) : 'Not specified'; ?>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-label">Year Level</div>
                    <div class="info-value"><?php echo htmlspecialchars($student['year_level'] ?? 'Not specified'); ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">GPA</div>
                    <div class="info-value <?php echo $student['gpa'] ? getGPAColor($student['gpa']) : 'empty'; ?>">
                        <?php echo $student['gpa'] ? number_format($student['gpa'], 2) : 'Not provided'; ?>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-label">Status</div>
                    <div class="info-value">
                        <span class="badge <?php echo getStatusBadge($student['status']); ?>">
                            <?php echo htmlspecialchars($student['status']); ?>
                        </span>
                    </div>
                </div>
            </div>
            
            <div class="info-section full-width">
                <h3>📍 Address</h3>
                <div class="info-item">
                    <div class="info-value <?php echo empty($student['address']) ? 'empty' : ''; ?>" style="line-height: 1.6;">
                        <?php echo !empty($student['address']) ? nl2br(htmlspecialchars($student['address'])) : 'Not provided'; ?>
                    </div>
                </div>
            </div>
            
            <div class="info-section">
                <h3>📅 Record Information</h3>
                <div class="info-item">
                    <div class="info-label">Created At</div>
                    <div class="info-value"><?php echo date('F j, Y g:i A', strtotime($student['created_at'])); ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Last Updated</div>
                    <div class="info-value"><?php echo date('F j, Y g:i A', strtotime($student['updated_at'])); ?></div>
                </div>
            </div>
        </div>
        
        <div class="actions">
            <a href="edit.php?id=<?php echo $student['id']; ?>" class="btn btn-edit">✏️ Edit Student</a>
            <a href="index.php" class="btn btn-secondary">← Back to List</a>
        </div>
    </div>
</body>
</html>

