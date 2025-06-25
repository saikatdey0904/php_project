<!DOCTYPE html>
<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Editor - Foodie Hunt Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="admin-style.css">
    <style>
        /* Add these new styles for the profile image */
        .profile-image {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 10px;
        }

        .admin-user {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Add these styles for the back home button */
        .back-home-btn {
            background-color: #e40754;
            color: white;
            padding: 8px 15px;
            border-radius: 5px;
            text-decoration: none;
            margin-right: 10px;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .back-home-btn:hover {
            background-color: #269900;
            transform: translateY(-2px);
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }

        .back-home-btn i {
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <nav class="admin-sidebar">
            <div class="admin-logo">
                <img src="../uploads/IMG_20250425_112659.png-removebg-preview.png" alt="Foodie Hunt Logo" style="width: 150px; height: auto; margin-bottom: 10px;">
                <h2>Foodie Hunt Admin</h2>
            </div>
            <ul class="admin-menu">
                <li><a href="dashboard.php" <?php echo (basename($_SERVER['PHP_SELF']) == 'dashboard.php') ? 'class="active"' : ''; ?>><i class="fas fa-home"></i> Dashboard</a></li>
                <li><a href="hero-editor.php" <?php echo (basename($_SERVER['PHP_SELF']) == 'hero-editor.php') ? 'class="active"' : ''; ?>><i class="fas fa-image"></i> Hero Editor</a></li>
                <li><a href="menu-editor.php" <?php echo (basename($_SERVER['PHP_SELF']) == 'menu-editor.php') ? 'class="active"' : ''; ?>><i class="fas fa-utensils"></i> Menu Editor</a></li>
                <li><a href="gallery-editor.php" <?php echo (basename($_SERVER['PHP_SELF']) == 'gallery-editor.php') ? 'class="active"' : ''; ?>><i class="fas fa-images"></i> Gallery Editor</a></li>
            </ul>
        </nav>

        <main class="admin-content">
                <header class="admin-header">
                    <h1>Menu Editor</h1>
                    <div class="admin-user">
                        <?php
                        if (isset($_SESSION['admin_profile']) && $_SESSION['admin_profile']) {
                            echo '<img src="' . htmlspecialchars($_SESSION['admin_profile']) . '" alt="Profile" class="profile-image">';
                        }
                        echo '<span>Welcome, ' . htmlspecialchars($_SESSION['admin_username']) . '</span>';
                        echo '<a href="../index.php" class="back-home-btn"><i class="fas fa-home"></i> Back to Home</a>';
                        echo '<a href="logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i></a>';
                        ?>
                    </div>
                </header>
            <div id="alertContainer" class="alert" style="display: none;"></div>
            <div class="editor-container">
                <div class="add-item-form">
                    <h3>Add New Menu Item</h3>
                    <div id="alert" class="alert" style="display: none;"></div>
                    
                    <!-- Update the form tag to include id and onsubmit -->
                    <form id="menuForm" action="process_menu.php" method="POST" enctype="multipart/form-data" onsubmit="return submitForm(event)">
                        <input type="hidden" id="itemId" name="id">
                        <div class="form-group">
                            <label>Item Name</label>
                            <input type="text" name="name" required>
                        </div>
                        <div class="form-group">
                            <label>Category</label>
                            <div style="display: flex; gap: 10px; align-items: center;">
                                <select name="category" required>
                                    <option value="pizza">Pizza</option>
                                    <option value="burger">Burger</option>
                                    <option value="pasta">Pasta</option>
                                    <option value="dessert">Dessert</option>
                                    <option value="bengali_thali">Bengali Thali</option>
                                    <option value="noodles">Noodles</option>
                                    <option value="chicken_curry">Chicken Curry</option>
                                    <option value="biriyani">Biriyani</option>
                                </select>
                                <button type="button" onclick="showAddCategoryModal()" class="add-category-btn" style="padding: 8px; border-radius: 5px; background: #4CAF50; color: white; border: none; cursor: pointer;">
                                    <i class="fas fa-plus"></i> Add Category
                                </button>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Price</label>
                            <input type="number" name="price" step="0.01" required>
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="description" required></textarea>
                        </div>
                        <div class="form-group">
                            <label>Image</label>
                            <input type="file" name="image" accept="image/*" required onchange="previewImage(this)">
                            <div id="imagePreview" style="margin-top: 10px; display: none;">
                                <img id="preview" style="max-width: 200px; max-height: 200px;">
                            </div>
                        </div>
                        <button type="submit">Add Item</button>
                    </form>
                </div>

                <div class="menu-items-list">
                    <h3>Current Menu Items</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Price</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Include database connection
                            include 'db_connect.php';
                            
                            // Fetch menu items
                            $sql = "SELECT * FROM menu_items";
                            $result = $conn->query($sql);
                            
                            if ($result->num_rows > 0) {
                                while($row = $result->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td><img src='../uploads/" . $row['image'] . "' width='50' alt='" . $row['name'] . "'></td>";
                                    echo "<td>" . $row['name'] . "</td>";
                                    echo "<td>" . $row['category'] . "</td>";
                                    echo "<td>₹" . $row['price'] . "</td>";
                                    echo "<td>
                                            <button class='edit-btn' onclick='editItem(" . $row['id'] . ")'><i class='fas fa-edit'></i></button>
                                            <button class='delete-btn' onclick='deleteItem(" . $row['id'] . ")'><i class='fas fa-trash'></i></button>
                                          </td>";
                                    echo "</tr>";
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
    <!-- Add Category Modal -->
    <div id="addCategoryModal" class="modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); z-index: 1000;">
        <div class="modal-content" style="background-color: white; margin: 15% auto; padding: 20px; border-radius: 8px; width: 90%; max-width: 500px; position: relative;">
            <span class="close" onclick="closeAddCategoryModal()" style="position: absolute; right: 20px; top: 10px; font-size: 24px; cursor: pointer;">&times;</span>
            <h2 style="margin-bottom: 20px;">Manage Categories</h2>
            
            <form id="addCategoryForm" onsubmit="addNewCategory(event)" style="margin-bottom: 20px;">
                <div style="display: flex; gap: 10px; margin-bottom: 20px;">
                    <input type="text" id="newCategoryName" placeholder="Enter category name" required 
                           style="flex: 1; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                    <button type="submit" style="background: #e40754; color: white; border: none; padding: 8px 15px; border-radius: 4px; cursor: pointer;">
                        Add Category
                    </button>
                </div>
            </form>
            
            <div id="categoriesList" style="max-height: 300px; overflow-y: auto;">
                <!-- Categories will be populated here -->
            </div>
        </div>
    </div>

    <script src="admin.js"></script>
    <!-- Core JavaScript -->
    <script>
        // Image Preview Functionality
        function previewImage(input) {
            const preview = document.getElementById('preview');
            const previewDiv = document.getElementById('imagePreview');
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = e => {
                    preview.src = e.target.result;
                    previewDiv.style.display = 'block';
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Alert Management
        function showAlert(message, type) {
            const alertContainer = document.getElementById('alertContainer');
            alertContainer.className = `alert alert-${type}`;
            alertContainer.innerHTML = `
                <div class="alert-content">
                    <i class="fas fa-${type === 'success' ? 'check' : 'exclamation'}-circle"></i>
                    <span>${message}</span>
                </div>
                <span class="close-btn" onclick="closeAlert()">&times;</span>
            `;
            alertContainer.style.display = 'flex';
        }

        function closeAlert() {
            document.getElementById('alertContainer').style.display = 'none';
        }

        // Item Management
        function editItem(id) {
            fetch('get_menu_item.php?id=' + id)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('itemId').value = data.id;
                    document.querySelector('input[name="name"]').value = data.name;
                    document.querySelector('select[name="category"]').value = data.category;
                    document.querySelector('input[name="price"]').value = data.price;
                    document.querySelector('textarea[name="description"]').value = data.description;
                    
                    document.querySelector('button[type="submit"]').textContent = 'Update Item';
                    document.querySelector('input[name="image"]').removeAttribute('required');
                    document.querySelector('.add-item-form').scrollIntoView({ behavior: 'smooth' });
                })
                .catch(error => {
                    console.error('Error:', error);
                    showAlert('Error fetching item details', 'error');
                });
        }

        function deleteItem(id) {
            if (!confirm('Are you sure you want to delete this menu item?')) return;

            fetch('delete_menu_item.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'id=' + id
            })
            .then(response => response.json())
            .then(data => {
                showAlert(data.message, data.status);
                if (data.status === 'success') {
                    setTimeout(() => location.reload(), 2000);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('An error occurred while deleting the item', 'error');
            });
        }

        // Form Submission
        document.getElementById('menuForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const isUpdate = formData.get('id') !== '';
            
            fetch('process_menu.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                showAlert(data.message, data.status);
                if (data.status === 'success') {
                    if (!isUpdate) {
                        this.reset();
                        document.getElementById('imagePreview').style.display = 'none';
                    }
                    setTimeout(() => location.reload(), 2000);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('An error occurred while processing the request', 'error');
            });
        });

        // Category Management
        function showAddCategoryModal() {
            document.getElementById('addCategoryModal').style.display = 'block';
            updateCategoriesList();
        }

        function closeAddCategoryModal() {
            document.getElementById('addCategoryModal').style.display = 'none';
        }

        function updateCategoriesList() {
            const select = document.querySelector('select[name="category"]');
            const categoriesList = document.getElementById('categoriesList');
            categoriesList.innerHTML = '';
            
            Array.from(select.options).forEach(option => {
                const categoryDiv = document.createElement('div');
                categoryDiv.style.cssText = 'display: flex; justify-content: space-between; align-items: center; padding: 10px; border-bottom: 1px solid #eee;';
                categoryDiv.innerHTML = `
                    <span>${option.text}</span>
                    <button type="button" onclick="deleteCategory('${option.value}')" 
                            style="background: #ff4444; color: white; border: none; padding: 5px 10px; border-radius: 3px; cursor: pointer;">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                `;
                categoriesList.appendChild(categoryDiv);
            });
        }

        function deleteCategory(categoryValue) {
            const select = document.querySelector('select[name="category"]');
            if (select.options.length <= 1) {
                showAlert('Cannot delete the last category!', 'error');
                return;
            }
            // Add category deletion logic here
        }

        function addNewCategory(event) {
            event.preventDefault();
            const categoryInput = document.getElementById('newCategoryName');
            const categoryName = categoryInput.value.trim();
            const categoryValue = categoryName.toLowerCase().replace(/\s+/g, '_');
            
            const select = document.querySelector('select[name="category"]');
            
            // Check if category already exists
            if (Array.from(select.options).some(option => option.value === categoryValue)) {
                showAlert('This category already exists!', 'error');
                return;
            }
            
            // Add new option to select
            const option = new Option(categoryName, categoryValue);
            select.add(option);
            
            // Clear input and update categories list
            categoryInput.value = '';
            updateCategoriesList();
            showAlert('Category added successfully!', 'success');
        }
    </script>
</body>
</html>
