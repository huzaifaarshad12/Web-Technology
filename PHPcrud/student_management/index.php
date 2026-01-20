<?php
/**
 * Student Management System - Main Page (Read Operation)
 * Enhanced with search, sorting, and better UI
 */

require_once 'config.php';

// Handle delete operation if requested
if (isset($_GET['delete']) && !empty($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);
    $conn = getDBConnection();
    
    // Using prepared statement to prevent SQL injection
    $stmt = $conn->prepare("DELETE FROM students WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    
    if ($stmt->execute()) {
        $message = "Student deleted successfully!";
        $message_type = "success";
    } else {
        $message = "Error deleting student: " . $conn->error;
        $message_type = "error";
    }
    
    $stmt->close();
    $conn->close();
    
    // Redirect to prevent resubmission
    header("Location: index.php?msg=" . urlencode($message) . "&type=" . $message_type);
    exit();
}

// Display message if redirected after delete
$message = '';
$message_type = '';
if (isset($_GET['msg'])) {
    $message = $_GET['msg'];
    $message_type = $_GET['type'] ?? 'info';
}

// Search and filter parameters
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$sort_by = isset($_GET['sort']) ? $_GET['sort'] : 'created_at';
$sort_order = isset($_GET['order']) ? $_GET['order'] : 'DESC';

// Validate sort parameters
$allowed_sort = ['id', 'name', 'email', 'course', 'year_level', 'gpa', 'status', 'created_at'];
$sort_by = in_array($sort_by, $allowed_sort) ? $sort_by : 'created_at';
$sort_order = strtoupper($sort_order) === 'ASC' ? 'ASC' : 'DESC';

// Fetch all students from database with search and sorting
$conn = getDBConnection();
$sql = "SELECT id, name, email, phone, birthdate, address, course, year_level, gpa, status, created_at 
        FROM students";

// Add search condition
if (!empty($search)) {
    $search_term = "%{$search}%";
    $sql .= " WHERE name LIKE ? OR email LIKE ? OR phone LIKE ? OR course LIKE ?";
}

$sql .= " ORDER BY {$sort_by} {$sort_order}";

$stmt = null;
if (!empty($search)) {
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $search_term, $search_term, $search_term, $search_term);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query($sql);
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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Management System - List All Students</title>
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
            max-width: 1400px;
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
            margin-bottom: 40px;
            flex-wrap: wrap;
            gap: 20px;
            padding-bottom: 25px;
            border-bottom: 3px solid #f0f0f0;
        }
        h1 {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-size: 2.5em;
            margin: 0;
            font-weight: 700;
            letter-spacing: -0.5px;
        }
        .subtitle {
            color: #888;
            margin-top: 8px;
            font-size: 1em;
            font-weight: 400;
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
        .toolbar {
            display: flex;
            gap: 15px;
            margin-bottom: 25px;
            flex-wrap: wrap;
            align-items: center;
        }
        .search-box {
            flex: 1;
            min-width: 250px;
            position: relative;
        }
        .search-box input {
            width: 100%;
            padding: 14px 45px 14px 18px;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            font-size: 16px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: #fafafa;
        }
        .search-box input:focus {
            outline: none;
            border-color: #667eea;
            background: white;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
            transform: translateY(-1px);
        }
        .search-box::after {
            content: '🔍';
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            pointer-events: none;
        }
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
        .btn-danger {
            background-color: #dc3545;
        }
        .btn-danger:hover {
            background-color: #c82333;
        }
        .btn-edit {
            background-color: #28a745;
        }
        .btn-edit:hover {
            background-color: #218838;
        }
        .btn-view {
            background-color: #17a2b8;
        }
        .btn-view:hover {
            background-color: #138496;
        }
        .btn-sm {
            padding: 8px 16px;
            font-size: 13px;
            border-radius: 8px;
            font-weight: 600;
        }
        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-top: 25px;
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }
        thead {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        th, td {
            padding: 18px 15px;
            text-align: left;
            border-bottom: 1px solid #f0f0f0;
        }
        th {
            color: white;
            font-weight: 600;
            cursor: pointer;
            user-select: none;
            position: relative;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.2s;
        }
        th:hover {
            background-color: rgba(255,255,255,0.15);
            transform: translateY(-1px);
        }
        th.sortable::after {
            content: ' ↕';
            font-size: 0.8em;
            opacity: 0.5;
        }
        tbody tr {
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            background: white;
        }
        tbody tr:hover {
            background-color: #f8f9fa;
            transform: scale(1.01);
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        tbody tr:last-child td {
            border-bottom: none;
        }
        .badge {
            display: inline-block;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
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
        .gpa-excellent { color: #28a745; font-weight: 600; }
        .gpa-good { color: #17a2b8; font-weight: 600; }
        .gpa-average { color: #ffc107; font-weight: 600; }
        .gpa-poor { color: #dc3545; font-weight: 600; }
        .actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }
        .no-data {
            text-align: center;
            padding: 80px 20px;
            color: #666;
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
            border-radius: 16px;
            margin-top: 20px;
        }
        .no-data h3 {
            font-size: 1.8em;
            margin-bottom: 15px;
            color: #999;
            font-weight: 600;
        }
        .no-data p {
            font-size: 1.1em;
            color: #aaa;
        }
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-bottom: 35px;
        }
        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px 25px;
            border-radius: 16px;
            text-align: center;
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }
        .stat-card::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            transition: transform 0.6s;
        }
        .stat-card:hover::before {
            transform: scale(1.1);
        }
        .stat-card:hover {
            transform: translateY(-5px) scale(1.02);
            box-shadow: 0 12px 35px rgba(102, 126, 234, 0.4);
        }
        .stat-card h3 {
            font-size: 2.5em;
            margin-bottom: 8px;
            font-weight: 700;
            position: relative;
            z-index: 1;
        }
        .stat-card p {
            opacity: 0.95;
            font-size: 1em;
            font-weight: 500;
            letter-spacing: 0.5px;
            position: relative;
            z-index: 1;
        }
        @media (max-width: 768px) {
            .container {
                padding: 20px;
                border-radius: 15px;
            }
            h1 {
                font-size: 1.8em;
            }
            table {
                font-size: 13px;
                display: block;
                overflow-x: auto;
            }
            th, td {
                padding: 12px 8px;
                white-space: nowrap;
            }
            .actions {
                flex-direction: column;
            }
            .btn-sm {
                width: 100%;
                margin-bottom: 5px;
            }
            .stats {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div>
                <h1>📚 Student Management System</h1>
                <p class="subtitle">Enhanced CRUD Application</p>
            </div>
            <a href="create.php" class="btn">➕ Add New Student</a>
        </div>
        
        <?php if ($message): ?>
            <div class="message <?php echo $message_type; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
        
        <?php
        // Get total count for stats
        $count_sql = "SELECT COUNT(*) as total, 
                     SUM(CASE WHEN status = 'Active' THEN 1 ELSE 0 END) as active,
                     AVG(gpa) as avg_gpa
                     FROM students";
        $count_result = $conn->query($count_sql);
        $stats = $count_result->fetch_assoc();
        ?>
        
        <div class="stats">
            <div class="stat-card">
                <h3><?php echo $stats['total'] ?? 0; ?></h3>
                <p>Total Students</p>
            </div>
            <div class="stat-card">
                <h3><?php echo $stats['active'] ?? 0; ?></h3>
                <p>Active Students</p>
            </div>
            <div class="stat-card">
                <h3><?php echo $stats['avg_gpa'] ? number_format($stats['avg_gpa'], 2) : 'N/A'; ?></h3>
                <p>Average GPA</p>
            </div>
        </div>
        
        <div class="toolbar">
            <form method="GET" action="index.php" class="search-box">
                <input type="text" 
                       name="search" 
                       placeholder="Search by name, email, phone, or course..." 
                       value="<?php echo htmlspecialchars($search); ?>">
                <?php if (isset($_GET['sort'])): ?>
                    <input type="hidden" name="sort" value="<?php echo htmlspecialchars($sort_by); ?>">
                <?php endif; ?>
                <?php if (isset($_GET['order'])): ?>
                    <input type="hidden" name="order" value="<?php echo htmlspecialchars($sort_order); ?>">
                <?php endif; ?>
            </form>
            <?php if (!empty($search)): ?>
                <a href="index.php" class="btn btn-secondary">Clear Search</a>
            <?php endif; ?>
        </div>
        
        <?php if ($result && $result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th class="sortable" onclick="sortTable('id')">ID</th>
                        <th class="sortable" onclick="sortTable('name')">Name</th>
                        <th class="sortable" onclick="sortTable('email')">Email</th>
                        <th>Phone</th>
                        <th>Age</th>
                        <th class="sortable" onclick="sortTable('course')">Course</th>
                        <th class="sortable" onclick="sortTable('year_level')">Year</th>
                        <th class="sortable" onclick="sortTable('gpa')">GPA</th>
                        <th class="sortable" onclick="sortTable('status')">Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['id']); ?></td>
                            <td><strong><?php echo htmlspecialchars($row['name']); ?></strong></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['phone'] ?? 'N/A'); ?></td>
                            <td><?php echo calculateAge($row['birthdate']); ?></td>
                            <td><?php echo htmlspecialchars($row['course'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($row['year_level'] ?? 'N/A'); ?></td>
                            <td class="<?php echo getGPAColor($row['gpa'] ?? 0); ?>">
                                <?php echo $row['gpa'] ? number_format($row['gpa'], 2) : 'N/A'; ?>
                            </td>
                            <td>
                                <span class="badge <?php echo getStatusBadge($row['status']); ?>">
                                    <?php echo htmlspecialchars($row['status']); ?>
                                </span>
                            </td>
                            <td>
                                <div class="actions">
                                    <a href="view.php?id=<?php echo $row['id']; ?>" class="btn btn-view btn-sm">👁️ View</a>
                                    <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn btn-edit btn-sm">✏️ Edit</a>
                                    <a href="index.php?delete=<?php echo $row['id']; ?>" 
                                       class="btn btn-danger btn-sm" 
                                       onclick="return confirm('Are you sure you want to delete <?php echo htmlspecialchars(addslashes($row['name'])); ?>?');">🗑️ Delete</a>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="no-data">
                <h3>🔍 No students found</h3>
                <p><?php echo !empty($search) ? 'Try adjusting your search criteria.' : 'Click "Add New Student" to create your first record.'; ?></p>
            </div>
        <?php endif; ?>
        
        <?php if ($stmt) $stmt->close(); ?>
        <?php $conn->close(); ?>
    </div>
    
    <script>
        function sortTable(column) {
            const url = new URL(window.location.href);
            const currentSort = url.searchParams.get('sort');
            const currentOrder = url.searchParams.get('order');
            
            let newOrder = 'ASC';
            if (currentSort === column && currentOrder === 'ASC') {
                newOrder = 'DESC';
            }
            
            url.searchParams.set('sort', column);
            url.searchParams.set('order', newOrder);
            window.location.href = url.toString();
        }
        
        // Auto-submit search on Enter
        document.querySelector('input[name="search"]').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                this.form.submit();
            }
        });
    </script>
</body>
</html>
