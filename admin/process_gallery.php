<?php
include 'db_connect.php';

header('Content-Type: application/json');
$response = ['success' => false, 'message' => ''];

if (!isset($_POST['action'])) {
    $response['message'] = 'No action specified';
    echo json_encode($response);
    exit;
}

$action = $_POST['action'];

switch ($action) {
    case 'add':
        if (isset($_FILES['image'])) {
            $target_dir = "../uploads/gallery/";
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
            
            $file_extension = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
            $new_filename = uniqid() . '.' . $file_extension;
            $target_file = $target_dir . $new_filename;
            
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $sql = "INSERT INTO gallery_images (image_path) VALUES (?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("s", $new_filename);
                
                if ($stmt->execute()) {
                    $response['success'] = true;
                    $response['message'] = 'Image uploaded successfully';
                } else {
                    $response['message'] = 'Error inserting into database';
                }
                $stmt->close();
            } else {
                $response['message'] = 'Error uploading file';
            }
        } else {
            $response['message'] = 'No image file received';
        }
        break;

    case 'edit':
        if (!isset($_POST['id'])) {
            $response['message'] = 'No ID provided';
            break;
        }

        $id = $_POST['id'];
        $update_needed = false;
        $new_filename = null;

        if (isset($_FILES['image']) && $_FILES['image']['size'] > 0) {
            // Get the old image path to delete it later
            $sql = "SELECT image_path FROM gallery_images WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $old_image = $result->fetch_assoc();
            
            // Upload new image
            $target_dir = "../uploads/gallery/";
            $file_extension = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
            $new_filename = uniqid() . '.' . $file_extension;
            $target_file = $target_dir . $new_filename;
            
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $update_needed = true;
                // Delete old image
                if ($old_image && file_exists($target_dir . $old_image['image_path'])) {
                    unlink($target_dir . $old_image['image_path']);
                }
            } else {
                $response['message'] = 'Error uploading new image';
                break;
            }
        }

        if ($update_needed) {
            $sql = "UPDATE gallery_images SET image_path = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $new_filename, $id);
            
            if ($stmt->execute()) {
                $response['success'] = true;
                $response['message'] = 'Gallery item updated successfully';
            } else {
                $response['message'] = 'Error updating database';
            }
            $stmt->close();
        } else {
            $response['success'] = true;
            $response['message'] = 'No changes made';
        }
        break;

    case 'delete':
        if (!isset($_POST['id'])) {
            $response['message'] = 'No ID provided';
            break;
        }

        $id = $_POST['id'];
        
        // Get the image path before deleting
        $sql = "SELECT image_path FROM gallery_images WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $image = $result->fetch_assoc();
        
        // Delete from database
        $sql = "DELETE FROM gallery_images WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            // Delete the image file
            if ($image && file_exists("../uploads/gallery/" . $image['image_path'])) {
                unlink("../uploads/gallery/" . $image['image_path']);
            }
            $response['success'] = true;
            $response['message'] = 'Gallery item deleted successfully';
        } else {
            $response['message'] = 'Error deleting from database';
        }
        $stmt->close();
        break;

    default:
        $response['message'] = 'Invalid action';
        break;
}

echo json_encode($response);
?>