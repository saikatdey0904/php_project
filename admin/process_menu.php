<?php
include 'db_connect.php';

header('Content-Type: application/json');

try {
    $response = ['status' => '', 'message' => ''];
    
    // Check if this is an update (id exists) or new item
    $isUpdate = !empty($_POST['id']);
    
    if ($isUpdate) {
        // Update existing item
        $id = $_POST['id'];
        $name = $_POST['name'];
        $category = $_POST['category'];
        $price = $_POST['price'];
        $description = $_POST['description'];
        
        if (!empty($_FILES['image']['name'])) {
            // Handle new image upload
            $image = $_FILES['image']['name'];
            $target = "../uploads/" . basename($image);
            move_uploaded_file($_FILES['image']['tmp_name'], $target);
            
            $sql = "UPDATE menu_items SET name=?, category=?, price=?, description=?, image=? WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssdssi", $name, $category, $price, $description, $image, $id);
        } else {
            // Update without changing image
            $sql = "UPDATE menu_items SET name=?, category=?, price=?, description=? WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssdsi", $name, $category, $price, $description, $id);
        }
    } else {
        // Insert new item
        $name = $_POST['name'];
        $category = $_POST['category'];
        $price = $_POST['price'];
        $description = $_POST['description'];
        $image = $_FILES['image']['name'];
        
        // Upload image
        $target = "../uploads/" . basename($image);
        move_uploaded_file($_FILES['image']['tmp_name'], $target);
        
        $sql = "INSERT INTO menu_items (name, category, price, description, image) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssdss", $name, $category, $price, $description, $image);
    }
    
    if ($stmt->execute()) {
        $response['status'] = 'success';
        $response['message'] = $isUpdate ? 'Menu item updated successfully!' : 'Menu item added successfully!';
    } else {
        throw new Exception($stmt->error);
    }
    
} catch (Exception $e) {
    $response['status'] = 'error';
    $response['message'] = 'Error: ' . $e->getMessage();
}

echo json_encode($response);
?>