<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    
    if(isset($_FILES['image']) && $_FILES['image']['size'] > 0) {
        // Handle new image upload
        $target_dir = dirname(__DIR__) . "/uploads/gallery/";
        $image = time() . '_' . basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $image;
        
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            // Delete old image
            $sql = "SELECT image FROM gallery_items WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            
            if($row && $row['image']) {
                $old_image = $target_dir . $row['image'];
                if(file_exists($old_image)) {
                    unlink($old_image);
                }
            }
            
            // Update with new image
            $sql = "UPDATE gallery_items SET title=?, description=?, image=? WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssi", $title, $description, $image, $id);
        }
    } else {
        // Update without changing image
        $sql = "UPDATE gallery_items SET title=?, description=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $title, $description, $id);
    }
    
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Gallery item updated successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to update gallery item']);
    }
}

// Handle GET request to fetch item details
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM gallery_items WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $item = $result->fetch_assoc();
    echo json_encode($item);
}
?>