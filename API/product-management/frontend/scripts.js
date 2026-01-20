//const API_BASE = '../backend/api/';  // Relative to frontend/index.html
const API_BASE = '../backend/';  // Changed from '../backend/api/'
$(document).ready(function() {
    loadCategories();
    loadProducts();

    $('#search').on('keyup', loadProducts);
    $('#categoryFilter').on('change', loadProducts);
    $('#sort').on('change', loadProducts);

    $('#addProductBtn').click(openModal);
    $('.close').click(closeModal);
    $('#productForm').submit(saveProduct);

    $(window).click(function(event) {
        if (event.target == $('#productModal')[0]) closeModal();
    });
});

function loadCategories() {
    $.ajax({
        url: API_BASE + 'categories',
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                const cats = response.data;
                $('#categoryFilter, #category').empty().append('<option value="">All/None</option>');
                cats.forEach(cat => {
                    $('#categoryFilter').append(`<option value="${cat.name}">${cat.name}</option>`);
                    $('#category').append(`<option value="${cat.name}">${cat.name}</option>`);
                });
            }
        },
        error: function() {
            showError('Failed to load categories');
        }
    });
}

function loadProducts(page = 1) {
    showLoading(true);
    const params = {
        page: page,
        per_page: 10,
        search: $('#search').val(),
        category: $('#categoryFilter').val(),
        sort: $('#sort').val()
    };
    $.ajax({
        url: API_BASE + 'products',
        method: 'GET',
        data: params,
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                displayProducts(response.data);
                displayPagination(response.pagination, page);
            } else {
                showError(response.message);
            }
        },
        error: function() {
            showError('Failed to load products');
        },
        complete: function() {
            showLoading(false);
        }
    });
}

function displayProducts(products) {
    const tbody = $('#productTable tbody').empty();
    products.forEach(product => {
        tbody.append(`
            <tr>
                <td>${product.id}</td>
                <td>${product.name}</td>
                <td>${product.description}</td>
                <td>${product.price}</td>
                <td>${product.category}</td>
                <td>${product.stock_quantity}</td>
                <td>
                    <button onclick="editProduct(${product.id})">Edit</button>
                    <button onclick="deleteProduct(${product.id})">Delete</button>
                </td>
            </tr>
        `);
    });
}

function displayPagination(pagination, currentPage) {
    const pages = Math.ceil(pagination.total / pagination.per_page);
    const pagDiv = $('#pagination').empty();
    for (let i = 1; i <= pages; i++) {
        const btn = $(`<button>${i}</button>`);
        if (i === currentPage) btn.css('font-weight', 'bold');
        btn.click(() => loadProducts(i));
        pagDiv.append(btn);
    }
}

function openModal() {
    $('#modalTitle').text('Add Product');
    $('#productId').val('');
    $('#name, #description, #price, #category, #stock_quantity').val('');
    $('#productModal').show();
}

function closeModal() {
    $('#productModal').hide();
    $('#formError').empty();
}

function saveProduct(e) {
    e.preventDefault();
    const id = $('#productId').val();
    const data = {
        name: $('#name').val(),
        description: $('#description').val(),
        price: parseFloat($('#price').val()),
        category: $('#category').val(),
        stock_quantity: parseInt($('#stock_quantity').val())
    };

    if (!data.name || isNaN(data.price)) {
        $('#formError').text('Name and Price are required.');
        return;
    }

    const method = id ? 'PUT' : 'POST';
    const url = API_BASE + 'products' + (id ? '/' + id : '');

    $.ajax({
        url: url,
        method: method,
        contentType: 'application/json',
        data: JSON.stringify(data),
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                closeModal();
                loadProducts();
            } else {
                $('#formError').text(response.message);
            }
        },
        error: function() {
            $('#formError').text('Failed to save product');
        }
    });
}

function editProduct(id) {
    $.ajax({
        url: API_BASE + 'products/' + id,
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                const p = response.data;
                $('#modalTitle').text('Edit Product');
                $('#productId').val(p.id);
                $('#name').val(p.name);
                $('#description').val(p.description);
                $('#price').val(p.price);
                $('#category').val(p.category);
                $('#stock_quantity').val(p.stock_quantity);
                $('#productModal').show();
            }
        },
        error: function() {
            showError('Failed to load product');
        }
    });
}

function deleteProduct(id) {
    if (confirm('Are you sure?')) {
        $.ajax({
            url: API_BASE + 'products/' + id,
            method: 'DELETE',
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    loadProducts();
                } else {
                    showError(response.message);
                }
            },
            error: function() {
                showError('Failed to delete product');
            }
        });
    }
}

function showError(msg) {
    $('#error').text(msg);
}

function showLoading(show) {
    $('#loading').toggle(show);
}