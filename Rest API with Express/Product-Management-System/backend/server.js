// Main Express Server + All API Routes
const express = require('express');//import express module
const cors = require('cors');
const db = require('./db');

//responses objects.
//res.json({ data: products })        // Send JSON response
//res.status(404).json({ error })     // Set HTTP status code
//res.status(201).json({ created })   // 201 = Created

//request objects
//req.params.id                      // URL parameter
//req.query.search                   // Query string parameter
//req.body                           // Parsed JSON body


const app = express();//initialize express app
app.use(cors()); //enable CORS for all routes , middleware
app.use(express.json());//parse JSON request bodies, middleware

// GET all products + search, category filter, pagination
//get request to fetch all products with optional search and filtering
app.get('/api/products', async (req, res) => {
  try {
    let query = `SELECT p.*, c.name as category_name FROM products p 
                 LEFT JOIN categories c ON p.category = c.name`;
    const params = [];
    
    if (req.query.search) {//query strings
      query += ` WHERE p.name LIKE ? OR p.description LIKE ?`;
      params.push(`%${req.query.search}%`, `%${req.query.search}%`);
    }
    if (req.query.category) {
      query += params.length ? ' AND' : ' WHERE';
      query += ' p.category = ?';
      params.push(req.query.category);
    }

    query += ' ORDER BY p.name';
    
    const [products] = await db.query(query, params);//database operation
    
    res.json({
      status: "success",
      data: products,
      pagination: {
        total: products.length,
        page: 1,
        per_page: products.length
      }
    });
  } catch (err) {
    res.status(500).json({ status: "error", message: err.message });
  }
});

// GET single product
app.get('/api/products/:id', async (req, res) => {
  try {
    const [product] = await db.query('SELECT * FROM products WHERE id = ?', [req.params.id]); //url parameters
    if (product.length === 0) {
      return res.status(404).json({ status: "error", message: "Product not found", code: 404 });
    }
    res.json({ status: "success", data: product[0], message: "Product retrieved successfully" });
  } catch (err) {
    res.status(500).json({ status: "error", message: err.message });
  }
});

// post request to create a new product
app.post('/api/products', async (req, res) => {
  const { name, description, price, category, stock_quantity } = req.body; //data sent in request body
  
  // Input validation
  if (!name || name.trim().length === 0) {
    return res.status(400).json({ status: "error", message: "Product name is required", code: 400 });
  }
  if (!price || isNaN(price) || price <= 0) {
    return res.status(400).json({ status: "error", message: "Valid price is required", code: 400 });
  }
  if (stock_quantity && (isNaN(stock_quantity) || stock_quantity < 0)) {
    return res.status(400).json({ status: "error", message: "Stock quantity must be a positive number", code: 400 });
  }
  
  try {
    const [result] = await db.query(
      'INSERT INTO products (name, description, price, category, stock_quantity) VALUES (?, ?, ?, ?, ?)',
      [name.trim(), description || null, parseFloat(price), category || null, parseInt(stock_quantity) || 0]
    );
    const [newProduct] = await db.query('SELECT * FROM products WHERE id = ?', [result.insertId]);
    res.status(201).json({ status: "success", data: newProduct[0], message: "Product created successfully" });
  } catch (err) {
    res.status(500).json({ status: "error", message: err.message, code: 500 });
  }
});

// put request to update an existing product
app.put('/api/products/:id', async (req, res) => {
  const { name, description, price, category, stock_quantity } = req.body; //data sent in request body
  
  // Input validation
  if (!name || name.trim().length === 0) {
    return res.status(400).json({ status: "error", message: "Product name is required", code: 400 });
  }
  if (!price || isNaN(price) || price <= 0) {
    return res.status(400).json({ status: "error", message: "Valid price is required", code: 400 });
  }
  if (stock_quantity && (isNaN(stock_quantity) || stock_quantity < 0)) {
    return res.status(400).json({ status: "error", message: "Stock quantity must be a positive number", code: 400 });
  }
  
  try {
    const [result] = await db.query(
      'UPDATE products SET name=?, description=?, price=?, category=?, stock_quantity=? WHERE id=?',
      [name.trim(), description || null, parseFloat(price), category || null, parseInt(stock_quantity) || 0, req.params.id]
    );
    
    if (result.affectedRows === 0) {
      return res.status(404).json({ status: "error", message: "Product not found", code: 404 });
    }
    
    const [updated] = await db.query('SELECT * FROM products WHERE id = ?', [req.params.id]);
    res.json({ status: "success", data: updated[0], message: "Product updated successfully" });
  } catch (err) {
    res.status(500).json({ status: "error", message: err.message, code: 500 });
  }
});

// DELETE request to delete product
app.delete('/api/products/:id', async (req, res) => {
  try {
    // Check if product exists first
    const [existing] = await db.query('SELECT id FROM products WHERE id = ?', [req.params.id]);
    if (existing.length === 0) {
      return res.status(404).json({ status: "error", message: "Product not found", code: 404 });
    }
    
    await db.query('DELETE FROM products WHERE id = ?', [req.params.id]);
    res.json({ status: "success", message: "Product deleted successfully" });
  } catch (err) {
    res.status(500).json({ status: "error", message: err.message });
  }
});

// GET all categories
app.get('/api/categories', async (req, res) => {
  try {
    const [categories] = await db.query('SELECT * FROM categories ORDER BY name');
    res.json({ status: "success", data: categories });
  } catch (err) {
    res.status(500).json({ status: "error", message: err.message, code: 500 });
  }
});

// GET products by category
app.get('/api/categories/:id/products', async (req, res) => {
  try {
    const [products] = await db.query(
      'SELECT p.* FROM products p JOIN categories c ON p.category = c.name WHERE c.id = ?',
      [req.params.id]
    );
    res.json({ status: "success", data: products });
  } catch (err) {
    res.status(500).json({ status: "error", message: err.message });
  }
});
//starting the server
const PORT = 3000;
app.listen(PORT, () => {
  console.log(`API Running at http://localhost:${PORT}`);
});