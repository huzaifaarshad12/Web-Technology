# Product Management System

## Installation Instructions
1. Place the `product-management` folder in your XAMPP `htdocs` directory (e.g., C:\xampp\htdocs\product-management).
2. Start XAMPP Apache and MySQL services.
3. Open phpMyAdmin[](http://localhost/phpmyadmin), create the database by importing `database/schema.sql`.
4. Access the frontend at http://localhost/product-management/frontend/index.html.
5. The API base is http://localhost/product-management/backend/api/.

## API Documentation
- **Products:**
  - GET /api/products?page=1&per_page=10&search=term&category=Electronics&sort=price desc : Get paginated products with filters.
  - GET /api/products/{id} : Get single product.
  - POST /api/products : Create product (JSON body: {name, description, price, category, stock_quantity}).
  - PUT /api/products/{id} : Update product (JSON body same as POST).
  - DELETE /api/products/{id} : Delete product.

- **Categories:**
  - GET /api/categories : Get all categories.
  - GET /api/categories/{id}/products : Get products for category by ID.

All responses follow the specified JSON format.

## Usage Examples
- Use Postman to test API endpoints, e.g., GET http://localhost/product-management/backend/api/products.
- In the frontend, add/edit/delete products dynamically without reload.

## Testing Documentation
- API: Test in Postman with the above endpoints.
- Frontend: Open index.html, interact with the UI (screenshots can be taken of table, modal, etc.).