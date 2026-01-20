import os

# Folder structure
structure = {
    "product-management": {
        "backend": {
            ".htaccess": """RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ api.php [QSA,L]""",

            "api.php": """<?php
// Main API Handler
header("Content-Type: application/json");
require_once "db.php";

$method = $_SERVER['REQUEST_METHOD'];

echo json_encode(["message" => "API Working", "method" => $method]);
?>""",

            "db.php": """<?php
// Database Connection
$host = "localhost";
$user = "root";
$pass = "";
$db = "product_db";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die(json_encode(["error" => "Database connection failed"]));
}
?>"""
        },

        "database": {
            "schema.sql": """CREATE DATABASE IF NOT EXISTS product_db;
USE product_db;

CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    price DECIMAL(10,2),
    quantity INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO products (name, price, quantity) VALUES
('Laptop', 1200.00, 10),
('Mouse', 15.00, 100);
"""
        },

        "frontend": {
            "index.html": """<!DOCTYPE html>
<html>
<head>
    <title>Product Management</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Product Management System</h1>
    <div id="product-list"></div>
    <script src="scripts.js"></script>
</body>
</html>
""",

            "scripts.js": """// Fetch products from API
fetch('../backend/api.php')
    .then(res => res.json())
    .then(data => console.log(data));""",

            "styles.css": """body {
    font-family: Arial, sans-serif;
}"""
        },

        "README.md": """# Product Management System

This project contains:
- Backend API in PHP
- Database schema
- Simple HTML/JS frontend

Run using XAMPP or any PHP server.
"""
    }
}

# Function to create folders and files
def create_structure(base_path, struct):
    for name, content in struct.items():
        path = os.path.join(base_path, name)

        if isinstance(content, dict):  # folder
            os.makedirs(path, exist_ok=True)
            create_structure(path, content)
        else:  # file
            with open(path, "w", encoding="utf-8") as f:
                f.write(content)

# Run generator
create_structure(".", structure)

print("🎉 Folder structure created successfully!")
