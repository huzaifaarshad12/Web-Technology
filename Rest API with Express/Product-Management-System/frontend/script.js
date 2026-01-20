$(document).ready(function() {
  loadProducts();
  loadCategories();

  // Show notification helper
  function showNotification(message, type = 'success') {
    const notification = $('#notification');
    notification.text(message)
      .removeClass('success error')
      .addClass(type + ' show');
    setTimeout(() => notification.removeClass('show'), 3000);
  }

  // Show/hide loading indicator
  function showLoading(show) {
    $('#loading').toggleClass('show', show);
  }

  // Load all products
  function loadProducts() {
    const search = $('#search').val();
    const category = $('#categoryFilter').val();
    
    showLoading(true);
    $.ajax({
      url: 'http://localhost:3000/api/products',
      method: 'GET',
      data: { search: search, category: category },
      dataType: 'json',
      success: function(res) {
        showLoading(false);
        if (res.status === 'success') {
          displayProducts(res.data);
        }
      },
      error: function(xhr, status, error) {
        showLoading(false);
        showNotification('Failed to load products: ' + error, 'error');
      }
    });
  }

  // Display products in table
  function displayProducts(products) {
    const tbody = $('#productTable tbody');
    tbody.empty();
    if (products.length === 0) {
      tbody.append('<tr><td colspan="6">No products found</td></tr>');
      return;
    }
    products.forEach(p => {
      tbody.append(`
        <tr>
          <td>${p.id}</td>
          <td>${p.name}</td>
          <td>$${parseFloat(p.price).toFixed(2)}</td>
          <td>${p.category || 'N/A'}</td>
          <td>${p.stock_quantity}</td>
          <td>
            <button class="btn-small" onclick="editProduct(${p.id})">Edit</button>
            <button class="btn-small red" onclick="deleteProduct(${p.id})">Delete</button>
          </td>
        </tr>
      `);
    });
  }

  // Load categories into select
  function loadCategories() {
    showLoading(true);
    $.ajax({
      url: 'http://localhost:3000/api/categories',
      method: 'GET',
      dataType: 'json',
      success: function(res) {
        showLoading(false);
        if (res.status === 'success') {
          const cats = res.data;
          // Clear existing options except first
          $('#category').empty().append('<option value=\"\">Select Category</option>');
          $('#categoryFilter').empty().append('<option value=\"\">All Categories</option>');
          
          cats.forEach(c => {
            $('#category, #categoryFilter').append(`<option value=\"${c.name}\">${c.name}</option>`);
          });
        }
      },
      error: function(xhr, status, error) {
        showLoading(false);
        showNotification('Failed to load categories: ' + error, 'error');
      }
    });
  }

  // Open modal
  $('#addBtn, .close').click(function() {
    $('#modal').toggle();
    $('#productForm')[0].reset();
    $('#productId').val('');
    $('#modalTitle').text('Add New Product');
  });

  // Submit form
  $('#productForm').submit(function(e) {
    e.preventDefault();
    
    // Disable submit button during request
    const submitBtn = $('#submitBtn');
    submitBtn.prop('disabled', true).text('Saving...');
    
    const id = $('#productId').val();
    const product = {
      name: $('#name').val().trim(),
      description: $('#description').val().trim(),
      price: parseFloat($('#price').val()),
      category: $('#category').val() || null,
      stock_quantity: parseInt($('#stock').val()) || 0
    };

    // Client-side validation
    if (!product.name || product.price <= 0) {
      showNotification('Please enter valid product name and price', 'error');
      submitBtn.prop('disabled', false).text('Save Product');
      return;
    }

    const method = id ? 'PUT' : 'POST';
    const url = id ? `http://localhost:3000/api/products/${id}` : 'http://localhost:3000/api/products';

    $.ajax({
      url: url,
      method: method,
      contentType: 'application/json',
      data: JSON.stringify(product),
      success: function(response) {
        submitBtn.prop('disabled', false).text('Save Product');
        $('#modal').hide();
        loadProducts();
        showNotification(response.message || (id ? 'Product updated successfully!' : 'Product added successfully!'), 'success');
      },
      error: function(xhr) {
        submitBtn.prop('disabled', false).text('Save Product');
        const errorMsg = xhr.responseJSON ? xhr.responseJSON.message : 'Error saving product';
        showNotification(errorMsg, 'error');
      }
    });
  });

  // Edit product
  window.editProduct = function(id) {
    showLoading(true);
    $.ajax({
      url: `http://localhost:3000/api/products/${id}`,
      method: 'GET',
      dataType: 'json',
      success: function(res) {
        showLoading(false);
        if (res.status === 'success') {
          const p = res.data;
          $('#productId').val(p.id);
          $('#name').val(p.name);
          $('#description').val(p.description);
          $('#price').val(p.price);
          $('#category').val(p.category);
          $('#stock').val(p.stock_quantity);
          $('#modalTitle').text('Edit Product');
          $('#modal').show();
        }
      },
      error: function(xhr) {
        showLoading(false);
        const errorMsg = xhr.responseJSON ? xhr.responseJSON.message : 'Failed to load product';
        showNotification(errorMsg, 'error');
      }
    });
  };

  // Delete product
  window.deleteProduct = function(id) {
    if (confirm('Are you sure you want to delete this product? This action cannot be undone.')) {
      showLoading(true);
      $.ajax({
        url: `http://localhost:3000/api/products/${id}`,
        method: 'DELETE',
        success: function(response) {
          showLoading(false);
          loadProducts();
          showNotification(response.message || 'Product deleted successfully', 'success');
        },
        error: function(xhr) {
          showLoading(false);
          const errorMsg = xhr.responseJSON ? xhr.responseJSON.message : 'Failed to delete product';
          showNotification(errorMsg, 'error');
        }
      });
    }
  };

  // Live search
  $('#search, #categoryFilter').on('input change', function() {
    loadProducts();
  });
});