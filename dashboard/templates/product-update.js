$(document).ready(function() {
    // Load categories when page loads
    loadCategories();

    // Event listeners
    $('#category').on('change', loadProducts);
    $('#update-stock').on('click', updateStock);
});

function loadCategories() {
    fetch('/inventoryMS/api/get-categories.php')
        .then(response => response.json())
        .then(data => {
            const categorySelect = document.getElementById('category');
            categorySelect.innerHTML = '<option value="">Select Category</option>';
            
            if (data.error) {
                showMessage(data.error, 'error');
                return;
            }
            
            if (Array.isArray(data)) {
                data.forEach(category => {
                    const option = document.createElement('option');
                    option.value = category.Category_ID;
                    option.textContent = category.Category_Name;
                    categorySelect.appendChild(option);
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showMessage('Error loading categories. Please try again.', 'error');
        });
}

function loadProducts() {
    const categoryId = document.getElementById('category').value;
    const productSelect = document.getElementById('product');
    
    productSelect.innerHTML = '<option value="">Select Product</option>';
    productSelect.disabled = !categoryId;

    if (categoryId) {
        fetch(`/inventoryMS/api/get-products.php?category=${categoryId}`)
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    showMessage(data.error, 'error');
                    return;
                }
                
                if (Array.isArray(data)) {
                    data.forEach(product => {
                        const option = document.createElement('option');
                        option.value = product.Product_ID;
                        option.textContent = `${product.Product_Name} (In Stock: ${product.Quantity_In_Stock})`;
                        productSelect.appendChild(option);
                    });
                    productSelect.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showMessage('Error loading products. Please try again.', 'error');
            });
    }
}

function updateStock() {
    const productId = document.getElementById('product').value;
    const transactionType = document.getElementById('transaction-type').value;
    const quantity = document.getElementById('quantity').value;
    const remarks = document.getElementById('remarks').value;

    // Validation
    if (!productId || !transactionType || !quantity) {
        showMessage('Please fill in all required fields', 'error');
        return;
    }

    if (quantity <= 0) {
        showMessage('Quantity must be greater than 0', 'error');
        return;
    }

    const data = {
        productId: productId,
        transactionType: transactionType,
        quantity: parseInt(quantity),
        remarks: remarks
    };

    fetch('/inventoryMS/api/update-stock.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            showMessage(result.message, 'success');
            // Reset form
            document.getElementById('quantity').value = '';
            document.getElementById('remarks').value = '';
            // Reload products to update stock display
            loadProducts();
        } else {
            showMessage(result.message || 'Error updating stock', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showMessage('Error updating stock. Please try again.', 'error');
    });
}

function showMessage(message, type) {
    const messageDiv = document.getElementById('message');
    messageDiv.textContent = message;
    messageDiv.className = `alert alert-${type === 'success' ? 'success' : 'danger'}`;
    messageDiv.style.display = 'block';

    // Hide message after 5 seconds
    setTimeout(() => {
        messageDiv.style.display = 'none';
    }, 5000);
}
