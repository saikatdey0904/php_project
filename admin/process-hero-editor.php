<?php
session_start();
// Database connection
$conn = mysqli_connect("localhost", "root", "", "foodie_hunt");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $hero_title = mysqli_real_escape_string($conn, $_POST['hero-title']);
    $hero_subtitle = mysqli_real_escape_string($conn, $_POST['hero-subtitle']);
    
    // Handle image upload
    $image_path = "";
    if(isset($_FILES['hero-image']) && $_FILES['hero-image']['error'] == 0) {
        $target_dir = "../uploads/hero/";
        
        // Create directory if it doesn't exist
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        
        $file_extension = strtolower(pathinfo($_FILES["hero-image"]["name"], PATHINFO_EXTENSION));
        $new_filename = "hero_" . time() . "." . $file_extension;
        $target_file = $target_dir . $new_filename;
        
        // Check if image file is valid
        $allowed_types = array('jpg', 'jpeg', 'png', 'gif');
        if(in_array($file_extension, $allowed_types)) {
            if(move_uploaded_file($_FILES["hero-image"]["tmp_name"], $target_file)) {
                $image_path = "uploads/hero/" . $new_filename;
            }
        }
    }

    // Update database
    if($image_path != "") {
        $sql = "UPDATE hero_section SET 
                title = '$hero_title',
                subtitle = '$hero_subtitle',
                background_image = '$image_path'
                WHERE id = 1";
    } else {
        $sql = "UPDATE hero_section SET 
                title = '$hero_title',
                subtitle = '$hero_subtitle'
                WHERE id = 1";
    }

    if (mysqli_query($conn, $sql)) {
        $_SESSION['message'] = "Hero section updated successfully!";
        $_SESSION['message_type'] = "success";
    } else {
        $_SESSION['message'] = "Error updating hero section!";
        $_SESSION['message_type'] = "error";
    }
    
    header("Location: hero-editor.php");
    exit();
}

mysqli_close($conn);
?>