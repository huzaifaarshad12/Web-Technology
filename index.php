<?php


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
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f4f6f8;
            color: #223;
            padding: 20px;
            min-height: 100vh;
        }

        .container {
            max-width: 1200px;
            margin: 24px auto;
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 8px 28px rgba(35,46,60,0.08);
            padding: 28px;
        }

        .header { display:flex; justify-content:space-between; align-items:center; gap:20px; margin-bottom:28px; padding-bottom:18px; border-bottom:1px solid #eef3f7; }
    h1 { color:#0b5e56; font-size:1.9rem; font-weight:700; margin:0; }
        .subtitle { color:#6c757d; margin-top:6px; font-size:0.95rem; }

        .message { padding:14px 18px; margin-bottom:16px; border-radius:8px; font-weight:600; box-shadow: 0 3px 10px rgba(35,46,60,0.04); border-left:4px solid transparent; }
        .message.success { background:#e9f7ef; color:#145a32; border-left-color:#2ecc71; }
        .message.error   { background:#fdecea; color:#6b1e1e; border-left-color:#e74c3c; }

        .toolbar { display:flex; gap:15px; margin-bottom:18px; flex-wrap:wrap; align-items:center; }
        .search-box { flex:1; min-width:250px; position:relative; }
        .search-box input { width:100%; padding:12px 40px 12px 14px; border:1px solid #d6dce1; border-radius:8px; background:#fff; font-size:0.95rem; transition: box-shadow .18s ease, border-color .18s ease; }
    .search-box input:focus { outline:none; border-color:#0d9488; box-shadow:0 6px 18px rgba(13,148,136,0.08); }
        .search-box::after { content: '🔍'; position:absolute; right:12px; top:50%; transform:translateY(-50%); opacity:0.6; }

    .btn { display:inline-block; padding:10px 20px; border-radius:8px; font-weight:700; font-size:0.95rem; cursor:pointer; border:none; color:#fff; background:#0d9488; box-shadow:0 6px 18px rgba(13,148,136,0.14); }
    .btn:hover { transform:translateY(-2px); box-shadow:0 10px 30px rgba(13,148,136,0.16); }
        .btn-secondary { background:transparent; color:#3b4754; border:1px solid #d6dce1; }
        .btn-danger { background:#dc3545; }
        .btn-edit { background:#0b6df0; }
        .btn-view { background:#17a2b8; }
        .btn-sm { padding:6px 12px; font-size:13px; border-radius:8px; }

        table { width:100%; border-collapse:separate; border-spacing:0; margin-top:18px; background:#fff; border-radius:12px; overflow:hidden; box-shadow:0 6px 20px rgba(0,0,0,0.06); }
    thead { background:#0d9488; }
        th, td { padding:14px 12px; text-align:left; border-bottom:1px solid #f0f0f0; }
        th { color:#fff; font-weight:700; font-size:13px; text-transform:uppercase; letter-spacing:0.5px; }
        th.sortable::after { content:' ↕'; font-size:0.8em; opacity:0.6; }
        tbody tr { background:#fff; transition: all .15s ease; }
        tbody tr:hover { background:#f8f9fa; }

        .badge { display:inline-block; padding:6px 12px; border-radius:16px; font-size:11px; font-weight:700; text-transform:uppercase; }
        .badge-success { background:#28a745; color:#fff; }
        .badge-secondary { background:#6c757d; color:#fff; }
        .badge-info { background:#17a2b8; color:#fff; }
        .badge-danger { background:#dc3545; color:#fff; }

        .gpa-excellent { color:#28a745; font-weight:600; }
        .gpa-good { color:#17a2b8; font-weight:600; }
        .gpa-average { color:#ffc107; font-weight:600; }
        .gpa-poor { color:#dc3545; font-weight:600; }

        .actions { display:flex; gap:8px; flex-wrap:wrap; }

        .no-data { text-align:center; padding:48px 18px; color:#666; background:#fbfdff; border-radius:12px; margin-top:18px; }
        .no-data h3 { font-size:1.4em; margin-bottom:8px; color:#7a7a7a; }

        .stats { display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr)); gap:16px; margin-bottom:20px; }
    .stat-card { background:#0d9488; color:#fff; padding:20px; border-radius:12px; text-align:center; box-shadow:0 8px 25px rgba(13,148,136,0.14); }
        .stat-card h3 { font-size:2rem; margin-bottom:6px; }
        .stat-card p { opacity:0.95; font-weight:600; }

        @media (max-width: 768px) {
            .container { padding:18px; }
            h1 { font-size:1.4rem; }
            table { display:block; overflow-x:auto; font-size:13px; }
            th, td { padding:10px 8px; white-space:nowrap; }
            .actions { flex-direction:column; }
            .btn-sm { width:100%; }
            .stats { grid-template-columns:1fr; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div>
                <h1>Student Management System</h1>
                <p class="subtitle">Enhanced CRUD application</p>
            </div>
            <a href="create.php" class="btn">Add New Student</a>
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
                                                <a href="view.php?id=<?php echo $row['id']; ?>" class="btn btn-view btn-sm">View</a>
                                                <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn btn-edit btn-sm">Edit</a>
                                                <a href="index.php?delete=<?php echo $row['id']; ?>" 
                                                    class="btn btn-danger btn-sm" 
                                                    onclick="return confirm('Are you sure you want to delete <?php echo htmlspecialchars(addslashes($row['name'])); ?>?');">Delete</a>
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
