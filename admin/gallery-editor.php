<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gallery Editor - Foodie Hunt Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="admin-style.css">
    <style>
        .alert {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px;
            border-radius: 5px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            min-width: 300px;
            z-index: 1000;
            animation: slideIn 0.3s ease-in-out;
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .alert-content {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .close-btn {
            cursor: pointer;
            padding: 0 5px;
        }

        #imagePreview {
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
        }

        #preview {
            object-fit: contain;
        }

        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
            padding: 20px;
            margin-top: 20px;
        }

        .gallery-item {
            position: relative;
            border: 1px solid #ddd;
            border-radius: 5px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease;
        }

        .gallery-info {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            background: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 15px;
            transform: translateY(-100%);
            transition: transform 0.3s ease;
        }

        .gallery-item:hover .gallery-info {
            transform: translateY(0);
        }

        .gallery-info h4 {
            margin: 0 0 10px 0;
            font-size: 1.1em;
        }

        .gallery-info p {
            margin: 0 0 8px 0;
            font-size: 0.9em;
            opacity: 0.9;
        }

        .gallery-info .category {
            display: inline-block;
            background: #ff4d4d;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 0.8em;
            text-transform: capitalize;
            margin-bottom: 15px;
        }
            margin-bottom: 15px;
        }

        .gallery-item img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .gallery-actions {
            position: absolute;
            top: 10px;
            right: 10px;
            display: flex;
            flex-direction: column;
            gap: 5px;
            z-index: 10;
        }

        .gallery-actions button {
            padding: 8px;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            background: rgba(255, 255, 255, 0.9);
            color: #333;
            width: 35px;
            height: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .gallery-actions button:hover {
            background: #fff;
            transform: scale(1.1);
        }

        .gallery-actions .edit-btn {
            background-color: #ffc107;
            color: #fff;
        }

        .gallery-actions .delete-btn {
            background-color: #dc3545;
            color: #fff;
            padding: 5px 10px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }

        /* Add modal styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            z-index: 1000;
        }

        .modal-content {
            background-color: #fff;
            margin: 15% auto;
            padding: 20px;
            border-radius: 5px;
            width: 50%;
            position: relative;
        }

        .close {
            position: absolute;
            right: 20px;
            top: 10px;
            font-size: 28px;
            cursor: pointer;
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
                <h1>Gallery Editor</h1>
            </header>
            
            <div id="alertContainer" class="alert" style="display: none;"></div>

            <div class="editor-container">
                <div class="add-item-form">
                    <h3>Add New Gallery Image</h3>
                    <form id="galleryForm" action="process_gallery.php" method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label>Image</label>
                            <input type="file" name="image" accept="image/*" required onchange="previewImage(this)">
                            <div id="imagePreview" style="margin-top: 10px; display: none;">
                                <img id="preview" style="max-width: 200px; max-height: 200px;">
                            </div>
                        </div>
                        <button type="submit">Add Image</button>
                    </form>
                </div>

                <div class="gallery-items-list">
                    <h3>Current Gallery Images</h3>
                    <div class="gallery-grid">
                        <?php
                        include 'db_connect.php';
                        
                        $sql = "SELECT * FROM gallery_images ORDER BY created_at DESC";
                        $result = $conn->query($sql);
                        
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo "<div class='gallery-item' data-gallery-id='" . $row['id'] . "'>";
                                echo "<img src='../uploads/gallery/" . $row['image_path'] . "' alt='Gallery Image'>";
                                echo "<div class='gallery-actions'>";
                                echo "<button class='edit-btn' onclick='editGalleryItem(" . $row['id'] . ")'><i class='fas fa-edit'></i></button>";
                                echo "<button class='delete-btn' onclick='deleteGalleryItem(" . $row['id'] . ")'><i class='fas fa-trash'></i></button>";
                                echo "</div>";
                                echo "</div>";
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Edit Gallery Image</h2>
            <form id="editForm">
                <input type="hidden" id="edit_id" name="id">
                <div class="form-group">
                    <label>New Image (optional)</label>
                    <input type="file" name="image" accept="image/*">
                </div>
                <button type="submit">Update Image</button>
            </form>
        </div>
    </div>

    <script>
    // Function to show alert messages
    function showAlert(message, type) {
        const alertContainer = document.getElementById('alertContainer');
        alertContainer.className = `alert alert-${type}`;
        alertContainer.innerHTML = `
            <div class="alert-content">
                <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i>
                <span>${message}</span>
            </div>
            <span class="close-btn" onclick="this.parentElement.style.display='none'">&times;</span>
        `;
        alertContainer.style.display = 'block';
        setTimeout(() => {
            alertContainer.style.display = 'none';
        }, 5000);
    }

    // Function to handle image preview
    function previewImage(input) {
        const preview = document.getElementById('preview');
        const previewContainer = document.getElementById('imagePreview');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                previewContainer.style.display = 'block';
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Function to edit gallery item
    function editGalleryItem(id) {
        const modal = document.getElementById('editModal');
        const editForm = document.getElementById('editForm');
        document.getElementById('edit_id').value = id;
        modal.style.display = 'block';
    
        // Close modal when clicking the X
        document.querySelector('.close').onclick = function() {
            modal.style.display = 'none';
        }
    
        // Close modal when clicking outside
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }
    
        // Handle form submission
        editForm.onsubmit = function(e) {
            e.preventDefault();
            const formData = new FormData(editForm);
            formData.append('action', 'edit');
    
            fetch('process_gallery.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('Gallery item updated successfully!', 'success');
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    showAlert('Error updating gallery item: ' + data.message, 'error');
                }
                modal.style.display = 'none';
            })
            .catch(error => {
                showAlert('Error updating gallery item: ' + error, 'error');
                modal.style.display = 'none';
            });
        };
    }

    // Function to delete gallery item
    function deleteGalleryItem(id) {
        if (confirm('Are you sure you want to delete this gallery item?')) {
            const formData = new FormData();
            formData.append('id', id);
            formData.append('action', 'delete');
    
            fetch('process_gallery.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('Gallery item deleted successfully!', 'success');
                    const galleryItem = document.querySelector(`.gallery-item[data-gallery-id="${id}"]`);
                    if (galleryItem) {
                        galleryItem.remove();
                    }
                } else {
                    showAlert('Error deleting gallery item: ' + data.message, 'error');
                }
            })
            .catch(error => {
                showAlert('Error deleting gallery item: ' + error, 'error');
            });
        }
    }

    // Handle the main gallery form submission
    document.getElementById('galleryForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        formData.append('action', 'add');
    
        fetch('process_gallery.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('Gallery item added successfully!', 'success');
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            } else {
                showAlert('Error adding gallery item: ' + data.message, 'error');
            }
        })
        .catch(error => {
            showAlert('Error adding gallery item: ' + error, 'error');
        });
    });
    </script>
    