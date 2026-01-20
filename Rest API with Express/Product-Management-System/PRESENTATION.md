# Product Management System
## REST API Development and Consumption

**Presented by:** Huzaifa Arshad  
**Assignment #6:** REST API with Node.js + Express + MySQL + jQuery  
**Date:** December 2025

---

## Slide 1: Project Overview 🎯

### What is This Project?
A **full-stack Product Management System** demonstrating complete REST API development and consumption.

### Key Features
- ✅ **Backend REST API** built with Express.js and MySQL
- ✅ **Frontend SPA** using JavaScript, jQuery, and AJAX
- ✅ **Full CRUD Operations** (Create, Read, Update, Delete)
- ✅ **Real-time Search & Filtering**
- ✅ **Professional UI** with notifications and loading states

### Technologies Used
| Backend | Frontend | Database |
|---------|----------|----------|
| Node.js | HTML5 | MySQL |
| Express.js | CSS3 | - |
| MySQL2 | JavaScript | - |
| CORS | jQuery 3.7.1 | - |

---

## Slide 2: System Architecture 🏗️

### Three-Tier Architecture

```
┌─────────────────────────────────────────────────────────┐
│                    FRONTEND LAYER                        │
│  ┌──────────────────────────────────────────────────┐   │
│  │  HTML + CSS + JavaScript + jQuery                 │   │
│  │  • User Interface (Single Page Application)       │   │
│  │  • AJAX calls for async communication            │   │
│  │  • Real-time search and filtering                │   │
│  └──────────────────────────────────────────────────┘   │
└─────────────────────────────────────────────────────────┘
                          ↓ HTTP Requests (JSON)
┌─────────────────────────────────────────────────────────┐
│                    BACKEND LAYER                         │
│  ┌──────────────────────────────────────────────────┐   │
│  │  Node.js + Express.js REST API                    │   │
│  │  • API Endpoints (GET, POST, PUT, DELETE)        │   │
│  │  • Input validation & sanitization               │   │
│  │  • CORS middleware                               │   │
│  └──────────────────────────────────────────────────┘   │
└─────────────────────────────────────────────────────────┘
                          ↓ SQL Queries
┌─────────────────────────────────────────────────────────┐
│                    DATABASE LAYER                        │
│  ┌──────────────────────────────────────────────────┐   │
│  │  MySQL Database                                   │   │
│  │  • products table (id, name, price, category...)  │   │
│  │  • categories table (id, name, description)      │   │
│  │  • Prepared statements for security              │   │
│  └──────────────────────────────────────────────────┘   │
└─────────────────────────────────────────────────────────┘
```

### Data Flow
1. User interacts with **Frontend** (browser)
2. Frontend sends **AJAX request** to Backend API
3. Backend processes request, validates data
4. Backend queries **MySQL database**
5. Database returns data
6. Backend sends **JSON response** to Frontend
7. Frontend updates UI dynamically (no page reload)

---

## Slide 3: Backend REST API Implementation 🔧

### API Endpoints

| Method | Endpoint | Purpose | Status Codes |
|--------|----------|---------|--------------|
| GET | `/api/products` | Get all products | 200 |
| GET | `/api/products/:id` | Get single product | 200, 404 |
| POST | `/api/products` | Create new product | 201, 400 |
| PUT | `/api/products/:id` | Update product | 200, 400, 404 |
| DELETE | `/api/products/:id` | Delete product | 200, 404 |
| GET | `/api/categories` | Get all categories | 200 |

### Key Backend Features

#### 1. **Express.js Routing**
```javascript
app.get('/api/products', async (req, res) => {
  // Handle search and filter query parameters
  const { search, category } = req.query;
  // Return paginated JSON response
});
```

#### 2. **Input Validation & Sanitization**
```javascript
// Validate required fields
if (!name || name.trim().length === 0) {
  return res.status(400).json({ 
    status: "error", 
    message: "Product name is required" 
  });
}

// Sanitize and convert data types
const sanitized = {
  name: name.trim(),
  price: parseFloat(price),
  stock_quantity: parseInt(stock_quantity) || 0
};
```

#### 3. **Security Features**
- ✅ **Prepared SQL Statements** (prevents SQL injection)
- ✅ **CORS Configuration** (secure cross-origin requests)
- ✅ **Input Validation** (both client and server side)
- ✅ **Error Handling** (safe error messages)

#### 4. **Standardized JSON Responses**
```json
{
  "status": "success",
  "data": { /* product data */ },
  "message": "Product created successfully"
}
```

---

## Slide 4: Frontend Implementation 💻

### Single Page Application Features

#### 1. **Dynamic Product Table**
- Displays all products without page reload
- Real-time updates after CRUD operations
- Responsive design with hover effects

#### 2. **AJAX-Based Operations**
```javascript
// Example: Create Product with jQuery AJAX
$.ajax({
  url: 'http://localhost:3000/api/products',
  method: 'POST',
  contentType: 'application/json',
  data: JSON.stringify(productData),
  success: function(response) {
    showNotification('Product added!', 'success');
    loadProducts(); // Refresh table
  },
  error: function(xhr) {
    showNotification('Error: ' + xhr.responseJSON.message, 'error');
  }
});
```

#### 3. **User Experience Enhancements**

| Feature | Description |
|---------|-------------|
| **Loading Indicators** | Shows "Loading..." during API calls |
| **Notifications** | Success/error messages (no alerts!) |
| **Form Validation** | Real-time validation feedback |
| **Modal Forms** | Add/Edit products in popup modal |
| **Search & Filter** | Real-time filtering without refresh |
| **Confirmation Dialogs** | Confirm before deleting products |

#### 4. **Real-Time Search**
```javascript
// Live search as user types
$('#search, #categoryFilter').on('input change', function() {
  loadProducts(); // Automatically filters via API
});
```

### Why jQuery + AJAX?
- ✅ Simplified DOM manipulation
- ✅ Easy AJAX calls with error handling
- ✅ Cross-browser compatibility
- ✅ Less boilerplate code
- ✅ Perfect for assignment requirements

---

## Slide 5: Database Design & Implementation 🗄️

### Database Schema

#### **Categories Table**
```sql
CREATE TABLE categories (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) UNIQUE NOT NULL,
  description TEXT
);
```

#### **Products Table**
```sql
CREATE TABLE products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  description TEXT,
  price DECIMAL(10,2) NOT NULL,
  category VARCHAR(100),
  stock_quantity INT DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (category) REFERENCES categories(name)
);
```

### Entity Relationship
```
┌─────────────────┐         ┌─────────────────┐
│   categories    │         │    products     │
├─────────────────┤         ├─────────────────┤
│ id (PK)         │◄────────┤ id (PK)         │
│ name (UNIQUE)   │  1 : N  │ name            │
│ description     │         │ description     │
└─────────────────┘         │ price           │
                            │ category (FK)   │
                            │ stock_quantity  │
                            │ created_at      │
                            │ updated_at      │
                            └─────────────────┘
```

### Sample Data
```sql
-- Categories
Electronics, Books, Clothing

-- Products
1. Laptop ($999.99) - Electronics - Stock: 15
2. Smartphone ($699.99) - Electronics - Stock: 25
3. Programming Book ($29.99) - Books - Stock: 100
4. T-Shirt ($19.99) - Clothing - Stock: 50
```

### Query Examples
```sql
-- Get all products with category names
SELECT p.*, c.name as category_name 
FROM products p 
LEFT JOIN categories c ON p.category = c.name;

-- Search products
SELECT * FROM products 
WHERE name LIKE '%laptop%' 
   OR description LIKE '%laptop%';

-- Filter by category
SELECT * FROM products 
WHERE category = 'Electronics';
```

---

## Slide 6: Demo & Results ✨

### Live Application Screenshots

#### **Main Dashboard**
```
┌─────────────────────────────────────────────────────────┐
│        Product Management System                        │
├─────────────────────────────────────────────────────────┤
│ [Search...] [All Categories ▼] [Add New Product]       │
├────┬──────────────┬────────┬────────────┬───────┬───────┤
│ ID │ Name         │ Price  │ Category   │ Stock │ Actions│
├────┼──────────────┼────────┼────────────┼───────┼───────┤
│ 1  │ Laptop       │ $999.99│ Electronics│  15   │ E | D │
│ 2  │ Smartphone   │ $699.99│ Electronics│  25   │ E | D │
│ 3  │ Program Book │ $29.99 │ Books      │ 100   │ E | D │
└────┴──────────────┴────────┴────────────┴───────┴───────┘
```

#### **Add/Edit Product Modal**
```
┌──────────────────────────────────┐
│  ×                               │
│  Add Product                     │
│                                  │
│  [Product Name________]          │
│  [Description_________]          │
│  [Price_______________]          │
│  [Select Category▼____]          │
│  [Stock Quantity______]          │
│                                  │
│       [Save Product]             │
└──────────────────────────────────┘
```

### Testing Results

#### **API Testing (Thunder Client)**
✅ All endpoints tested and working
✅ Proper status codes (200, 201, 400, 404, 500)
✅ JSON responses formatted correctly
✅ Error handling validated

#### **Frontend Testing**
✅ Add product → Success notification appears
✅ Edit product → Modal populates, updates work
✅ Delete product → Confirmation dialog shown
✅ Search → Real-time filtering works
✅ Category filter → Products filtered correctly
✅ Loading indicators → Show during API calls

### Assignment Requirements Met ✅

| Requirement | Status |
|------------|--------|
| Express.js REST API with all CRUD endpoints | ✅ Complete |
| MySQL database with proper schema | ✅ Complete |
| Prepared SQL statements for security | ✅ Complete |
| Frontend consumes API via AJAX/jQuery | ✅ Complete |
| JSON data interchange format | ✅ Complete |
| Search and filter functionality | ✅ Complete |
| Loading states and error handling | ✅ Complete |
| Input validation (client & server) | ✅ Complete |
| Dynamic UI updates without page reload | ✅ Complete |
| Professional notifications (no alerts) | ✅ Complete |
| Complete documentation | ✅ Complete |
| Postman/Thunder Client collection | ✅ Complete |

### Key Achievements
- 🎯 **100% Functional** - All features working
- 🔒 **Secure** - SQL injection prevention, validation
- 🎨 **Professional UI** - Modern, responsive design
- 📚 **Well Documented** - Complete README and comments
- 🧪 **Tested** - All endpoints and features verified
- ⚡ **Fast** - Optimized queries and async operations

---

## Thank You! 🙏

### Project Summary
**Product Management System** - A complete full-stack REST API application demonstrating modern web development practices.

### Student Information
- **Name:** Huzaifa Arshad
- **Assignment:** REST API Development and Consumption
- **Technologies:** Node.js, Express.js, MySQL, JavaScript, jQuery

### Project Structure
```
Product-Management-System/
├── backend/          # Express.js API
├── frontend/         # HTML/CSS/JS interface
├── database/         # SQL schema
├── postman/          # API testing collection
└── README.md         # Documentation
```

### How to Run
```bash
# 1. Start MySQL (XAMPP)
# 2. Run database.sql in MySQL Workbench
# 3. Start backend
cd backend
npm install
node server.js

# 4. Open frontend
# Open frontend/index.html in browser
```

### Contact
📧 Email: [Your Email]  
🔗 GitHub: [Your GitHub Profile]

**Questions?** 🤔

---

**END OF PRESENTATION**
