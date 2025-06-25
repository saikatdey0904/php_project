<?php
include 'db_connect.php';

if(isset($_POST['id'])) {
    $id = $_POST['id'];
    
    // Get image filename before deleting record
    $sql = "SELECT image FROM gallery_items WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    // Delete the record
    $sql = "DELETE FROM gallery_items WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        // Delete the image file
        if($row && $row['image']) {
            $image_path = dirname(__DIR__) . "/uploads/gallery/" . $row['image'];
            if(file_exists($image_path)) {
                unlink($image_path);
            }
        }
        echo json_encode(['status' => 'success', 'message' => 'Gallery item deleted successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to delete gallery item']);
    }
}
?>