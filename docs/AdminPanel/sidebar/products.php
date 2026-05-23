<?php
require_once 'db_connection.php';

// Add Product
if(isset($_POST['add_product'])) {
    $name = $conn->real_escape_string($_POST['name']);
    $description = $conn->real_escape_string($_POST['description']);
    $category = $conn->real_escape_string($_POST['category'] ?? 'Main');
    // Remove currency symbol and comma from price
    $price_input = str_replace(['₱', ','], '', $_POST['price']);
    $price = floatval($price_input);

    // Insert product
    $sql = "INSERT INTO products (ProductName, Description, Category, Price, Availability) 
            VALUES ('$name', '$description', '$category', $price, 'Available')";
    $conn->query($sql);
}

// Update Product
if(isset($_POST['update_product'])) {
    $id = intval($_POST['product_id']);
    $name = $conn->real_escape_string($_POST['name']);
    $description = $conn->real_escape_string($_POST['description']);
    $category = $conn->real_escape_string($_POST['category'] ?? 'Main');
    $price = floatval($_POST['price']);
    $availability = $conn->real_escape_string($_POST['availability']);

    // Update query
    $sql = "UPDATE products SET 
            ProductName='$name', 
            Description='$description', 
            Category='$category',
            Price=$price,
            Availability='$availability'
            WHERE ProductID=$id";
    $conn->query($sql);
}

// Delete Product
if(isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $sql = "DELETE FROM products WHERE ProductID = $delete_id";
    $conn->query($sql);

    // Reset auto-increment
    $conn->query("ALTER TABLE products AUTO_INCREMENT = 1");
}

// Fetch Products
$products_result = $conn->query("SELECT * FROM products ORDER BY ProductID DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products</title>
    <link rel="stylesheet" href="../CSS/productDesign.css">
</head>
<body>
    
    <div class="header-container">
        <h1>Manage Products</h1>
        <button id="add-product-btn">Add New Product</button>
    </div>

    <!-- Add Product Modal -->
    <div id="add-product-modal">
        <div class="modal-content">
            <span class="close-modal">&times;</span>
            <form method="POST" action="">
                <h2>Add New Product</h2>
                <div>
                    <label for="name">Product Name</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div>
                    <label for="description">Description</label>
                    <textarea id="description" name="description" rows="3" required></textarea>
                </div>
                <div>
                    <label for="price">Price</label>
                    <input type="number" id="price" name="price" step="0.01" required>
                </div>
                <div>
                    <button type="submit" name="add_product">Add Product</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Product List -->
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Category</th>
                <th>Description</th>
                <th>Price</th>
                <th>Availability</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($product = $products_result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $product['ProductID']; ?></td>
                    <td><?php echo htmlspecialchars($product['ProductName']); ?></td>
                    <td><?php echo htmlspecialchars($product['Category']); ?></td>
                    <td><?php echo htmlspecialchars($product['Description']); ?></td>
                    <td>₱<?php echo number_format($product['Price'], 2); ?></td>
                    <td><?php echo $product['Availability']; ?></td>
                    <td>
                        <a href="?edit_id=<?php echo $product['ProductID']; ?>" class="edit-btn">Edit</a>
                        <a href="?delete_id=<?php echo $product['ProductID']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this product?')">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

   <!-- Edit Product Modal -->
    <?php 
    if(isset($_GET['edit_id'])) {
        $edit_id = intval($_GET['edit_id']);
        $edit_result = $conn->query("SELECT * FROM products WHERE ProductID = $edit_id");
        $edit_product = $edit_result->fetch_assoc();
    ?>
    <div id="edit-product-modal" style="display:block; position:fixed; z-index:100; left:0; top:0; width:100%; height:100%; background-color:rgba(0,0,0,0.5);">
        <div class="modal-content" style="background-color:white; margin:10% auto; padding:20px; border:1px solid #888; width:50%;">
            <form method="POST" action="">
                <h2>Edit Product 
                    <a href="?" style="float:right; color:red; text-decoration:none; font-size:16px;">✖ Cancel</a>
                </h2>
                <input type="hidden" name="product_id" value="<?php echo $edit_product['ProductID']; ?>">
                <div>
                    <label for="name">Product Name</label>
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($edit_product['ProductName']); ?>" required>
                </div>
                <div>
                    <label for="category">Category</label>
                    <select name="category" required>
                        <option value="Appetizer" <?php echo $edit_product['Category'] == 'Appetizer' ? 'selected' : ''; ?>>Appetizer</option>
                        <option value="Main" <?php echo $edit_product['Category'] == 'Main' ? 'selected' : ''; ?>>Main</option>
                        <option value="Dessert" <?php echo $edit_product['Category'] == 'Dessert' ? 'selected' : ''; ?>>Dessert</option>
                        <option value="Drink" <?php echo $edit_product['Category'] == 'Drink' ? 'selected' : ''; ?>>Drink</option>
                    </select>
                </div>
                <div>
                    <label for="description">Description</label>
                    <textarea id="description" name="description" rows="3" required><?php echo htmlspecialchars($edit_product['Description']); ?></textarea>
                </div>
                <div>
                    <label for="price">Price (₱)</label>
                    <input type="number" id="price" name="price" step="0.01" min="0" value="<?php echo $edit_product['Price']; ?>" required>
                </div>
                <div>
                    <label for="availability">Availability</label>
                    <select name="availability" required>
                        <option value="Available" <?php echo $edit_product['Availability'] == 'Available' ? 'selected' : ''; ?>>Available</option>
                        <option value="Not Available" <?php echo $edit_product['Availability'] == 'Not Available' ? 'selected' : ''; ?>>Not Available</option>
                    </select>
                </div>
                <div>
                    <button type="submit" name="update_product">Update Product</button>
                </div>
            </form>
        </div>
    </div>
    <?php } ?>

    <script>
        // Modal functionality
        const addProductBtn = document.getElementById('add-product-btn');
        const addProductModal = document.getElementById('add-product-modal');
        const closeModal = document.querySelector('.close-modal');
        
        addProductBtn.addEventListener('click', () => {
            addProductModal.style.display = 'block';
        });

        closeModal.addEventListener('click', () => {
            addProductModal.style.display = 'none';
        });

        window.addEventListener('click', (event) => {
            if (event.target === addProductModal) {
                addProductModal.style.display = 'none';
            }
        });
    </script>
</body>
</html>