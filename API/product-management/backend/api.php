<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');  // CORS for frontend (adjust for production)
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);  // Handle CORS preflight
}

require_once 'db.php';

$path = isset($_GET['path']) ? trim($_GET['path'], '/') : '';
$parts = explode('/', $path);

// Skip 'api' prefix if present to handle /api/ in URLs
if (isset($parts[0]) && strtolower($parts[0]) === 'api') {
    array_shift($parts);
}

$resource = $parts[0] ?? '';
$id = $parts[1] ?? null;
$subresource = $parts[2] ?? null;

$method = $_SERVER['REQUEST_METHOD'];

function sendResponse($status, $data = null, $message = '', $code = 200, $pagination = null) {
    http_response_code($code);
    $response = ['status' => $status, 'message' => $message];
    if ($data !== null) $response['data'] = $data;
    if ($pagination) $response['pagination'] = $pagination;
    echo json_encode($response);
    exit;
}

function validateInput($data, $requiredFields) {
    foreach ($requiredFields as $field) {
        if (!isset($data[$field]) || empty(trim($data[$field]))) {
            sendResponse('error', null, "Missing or empty field: $field", 400);
        }
    }
}

// Products Endpoints
if ($resource === 'products') {
    if ($method === 'GET' && $id === null) {
        // Get all products with pagination, search, filter, sort
        $page = max(1, intval($_GET['page'] ?? 1));
        $per_page = max(1, min(50, intval($_GET['per_page'] ?? 10)));
        $offset = ($page - 1) * $per_page;

        $search = trim($_GET['search'] ?? '');
        $category = trim($_GET['category'] ?? '');
        $sort = trim($_GET['sort'] ?? 'name asc');
        [$sortField, $sortDir] = explode(' ', $sort) + [1 => 'asc'];
        $sortField = in_array($sortField, ['name', 'price', 'category']) ? $sortField : 'name';
        $sortDir = strtoupper($sortDir) === 'DESC' ? 'DESC' : 'ASC';

        $where = '1=1';
        $params = [];
        if ($search) {
            $where .= ' AND (name LIKE :search OR description LIKE :search)';
            $params['search'] = "%$search%";
        }
        if ($category) {
            $where .= ' AND category = :category';
            $params['category'] = $category;
        }

        $totalStmt = $pdo->prepare("SELECT COUNT(*) FROM products WHERE $where");
        $totalStmt->execute($params);
        $total = $totalStmt->fetchColumn();

        $stmt = $pdo->prepare("SELECT * FROM products WHERE $where ORDER BY $sortField $sortDir LIMIT :offset, :per_page");
        foreach ($params as $key => $val) $stmt->bindValue(":$key", $val);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindValue(':per_page', $per_page, PDO::PARAM_INT);
        $stmt->execute();
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $pagination = ['total' => $total, 'page' => $page, 'per_page' => $per_page];
        sendResponse('success', $products, 'Products retrieved successfully', 200, $pagination);
    } elseif ($method === 'GET' && $id !== null) {
        // Get single product
        $stmt = $pdo->prepare('SELECT * FROM products WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($product) {
            sendResponse('success', $product, 'Product retrieved successfully', 200);
        } else {
            sendResponse('error', null, 'Product not found', 404);
        }
    } elseif ($method === 'POST' && $id === null) {
        // Create product
        $input = json_decode(file_get_contents('php://input'), true);
        validateInput($input, ['name', 'price']);
        $stmt = $pdo->prepare('INSERT INTO products (name, description, price, category, stock_quantity) VALUES (:name, :description, :price, :category, :stock_quantity)');
        $stmt->execute([
            'name' => $input['name'],
            'description' => $input['description'] ?? '',
            'price' => $input['price'],
            'category' => $input['category'] ?? '',
            'stock_quantity' => $input['stock_quantity'] ?? 0
        ]);
        $newId = $pdo->lastInsertId();
        $stmt = $pdo->prepare('SELECT * FROM products WHERE id = :id');
        $stmt->execute(['id' => $newId]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        sendResponse('success', $product, 'Product created successfully', 201);
    } elseif ($method === 'PUT' && $id !== null) {
        // Update product
        $input = json_decode(file_get_contents('php://input'), true);
        validateInput($input, ['name', 'price']);
        $stmt = $pdo->prepare('UPDATE products SET name = :name, description = :description, price = :price, category = :category, stock_quantity = :stock_quantity WHERE id = :id');
        $stmt->execute([
            'name' => $input['name'],
            'description' => $input['description'] ?? '',
            'price' => $input['price'],
            'category' => $input['category'] ?? '',
            'stock_quantity' => $input['stock_quantity'] ?? 0,
            'id' => $id
        ]);
        if ($stmt->rowCount() > 0) {
            $stmt = $pdo->prepare('SELECT * FROM products WHERE id = :id');
            $stmt->execute(['id' => $id]);
            $product = $stmt->fetch(PDO::FETCH_ASSOC);
            sendResponse('success', $product, 'Product updated successfully', 200);
        } else {
            sendResponse('error', null, 'Product not found', 404);
        }
    } elseif ($method === 'DELETE' && $id !== null) {
        // Delete product
        $stmt = $pdo->prepare('DELETE FROM products WHERE id = :id');
        $stmt->execute(['id' => $id]);
        if ($stmt->rowCount() > 0) {
            sendResponse('success', null, 'Product deleted successfully', 200);
        } else {
            sendResponse('error', null, 'Product not found', 404);
        }
    }
}

// Categories Endpoints
elseif ($resource === 'categories') {
    if ($method === 'GET' && $id === null) {
        // Get all categories
        $stmt = $pdo->query('SELECT * FROM categories');
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
        sendResponse('success', $categories, 'Categories retrieved successfully', 200);
    } elseif ($method === 'GET' && $id !== null && $subresource === 'products') {
        // Get products by category id
        $stmt = $pdo->prepare('SELECT name FROM categories WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $catName = $stmt->fetchColumn();
        if (!$catName) {
            sendResponse('error', null, 'Category not found', 404);
        }
        $stmt = $pdo->prepare('SELECT * FROM products WHERE category = :category');
        $stmt->execute(['category' => $catName]);
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        sendResponse('success', $products, 'Products retrieved for category', 200);
    }
}

sendResponse('error', null, 'Invalid endpoint or method', 400);
?>