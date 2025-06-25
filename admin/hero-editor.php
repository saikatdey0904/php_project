<!DOCTYPE html>
<?php
session_start();

// Add authentication check
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Add database connection and fetch hero data
$conn = mysqli_connect("localhost", "root", "", "foodie_hunt");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Initialize $hero with default values in case no data exists
$hero = [
    'title' => '',
    'subtitle' => '',
    'background_image' => '',
    'cta_text' => '',
    'cta_link' => ''
];

// Fetch hero data
$sql = "SELECT * FROM hero_section WHERE id = 1";
$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    $hero = mysqli_fetch_assoc($result);
}
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hero Section Editor - Foodie Hunt Admin</title>
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

        /* Add styles for hero image preview */
        .image-preview {
            margin: 10px 0;
            max-width: 300px;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .hero-preview-image {
            width: 100%;
            height: 150px;
            object-fit: cover;
            display: block;
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
                <h1>Hero Section Editor</h1>
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

            <div class="editor-form">
                <form action="process-hero-editor.php" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="hero-title">Hero Title</label>
                        <input type="text" id="hero-title" name="hero-title" value="<?php echo htmlspecialchars($hero['title']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="hero-subtitle">Hero Subtitle</label>
                        <input type="text" id="hero-subtitle" name="hero-subtitle" value="<?php echo htmlspecialchars($hero['subtitle']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="hero-image">Hero Background Image</label>
                        <?php if($hero['background_image']): ?>
                            <div class="image-preview">
                                <img src="../<?php echo $hero['background_image']; ?>" alt="Current hero image" class="hero-preview-image">
                            </div>
                        <?php endif; ?>
                        <input type="file" id="hero-image" name="hero-image" accept="image/*">
                        </div>
                    <button type="submit" class="btn-submit">Update Hero Section</button>
                </form>
            </div>
        </main>
    </div>
</body>
</html>
