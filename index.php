<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Foodie Hunt - Restaurant</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>

    <!-- Header Section -->
    <header class="main-header">
        <nav>
            <div class="logo">
                <a href="index.php"><img src="uploads/IMG_20250425_112659.png-removebg-preview.png" 
                alt="Foodie Hunt Logo" style="max-width: 150px; height: auto;"></a>
            </div>
            <div class="nav-links">
                <a href="#home">Home</a>
                <a href="#menu">Menu</a>
                <a href="#gallery">Gallery</a>
                <a href="#about">About Us</a>
                <a href="#contact">Contact</a>
                <a href="Order.php">Orders Now</a>
                <?php if(isset($_SESSION['user_id'])): ?>
                    <a href="admin/dashboard.php" class="dashboard-btn btn btn-primary"><i class="fas fa-user-circle"></i> Dashboard</a>
                    <div class="user-menu">
                        <a href="logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</a>
                    </div>
                <?php else: ?>
                    <a href="admin/login.php" class="dashboard-btn"><i class="fas fa-user-circle"></i> Dashboard</a>
                    <a href="login.php" class="login-btn"><i class="fas fa-sign-in-alt"></i> Login</a>
                <?php endif; ?>
            </div>
            <div class="hamburger">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </nav>
    </header>

    <?php
    // Database connection
    $conn = mysqli_connect("localhost", "root", "", "foodie_hunt");
    
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    
    // Fetch hero section data
    $sql = "SELECT * FROM hero_section WHERE id = 1";
    $result = mysqli_query($conn, $sql);
    $hero = mysqli_fetch_assoc($result);
    ?>
    <!-- Header Section End -->

    <!-- Hero Section -->
    <section id="home" class="hero" style="background-image: url('<?php echo $hero['background_image']; ?>'); min-height: 80vh; background-size: cover; background-position: center; margin-bottom: 80px;">
        <div class="hero-content">
            <h1><?php echo htmlspecialchars($hero['title']); ?></h1>
            <p><?php echo htmlspecialchars($hero['subtitle']); ?></p>
            <a href="Order.php" class="btn btn-lg" style="background-color: #e40754; color: white;">Order Now</a>
        </div>
    </section>
    <!-- Hero Section End -->

    <!-- Menu Section -->
<section id="menu" class="menu" style="padding-top: 80px; padding-bottom: 80px;">
    <div class="container">
        <h2>Our Menu</h2>
        <div class="menu-tabs">
            <button class="tab-btn active" data-category="all">All</button>
            <?php
            include 'admin/db_connect.php';
            $sql = "SELECT DISTINCT category FROM menu_items ORDER BY category";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $display_text = ucwords(str_replace('_', ' ', $row['category']));
                    echo '<button class="tab-btn" data-category="' . $row['category'] . '">' . $display_text . '</button>';
                }
            }
            ?>
        </div>

        <div class="menu-grid">
            <?php
            include 'admin/db_connect.php';
            $sql = "SELECT * FROM menu_items";
            $result = $conn->query($sql);
            $count = 0;

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $hideClass = $count >= 8 ? 'menu-item-hidden' : '';
                    echo '<div class="menu-item ' . $hideClass . '" data-category="' . $row['category'] . '" style="border: 1px solid #ddd; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">';
                    echo '<img src="uploads/' . $row['image'] . '" alt="' . $row['name'] . '" style="width: 100%; height: 250px; object-fit: cover;">';
                    echo '<h3>' . $row['name'] . '</h3>';
                    echo '<p>' . $row['description'] . '</p>';
                    echo '<div class="price-order-container" style="display: flex; justify-content: space-between; align-items: center; margin-top: 10px;">';
                    echo '<span class="price">₹' . number_format($row['price'], 2) . '</span>';
                    echo '<a href="Order.php?item_id=' . $row['id'] . '" class="order-btn" style="background-color: #e40754; color: white; padding: 8px 15px; border-radius: 5px; text-decoration: none; display: flex; align-items: center; gap: 5px;">';
                    echo '<i class="fas fa-shopping-cart"></i> Order</a>';
                    echo '</div>';
                    echo '</div>';
                    $count++;
                }
            }
            ?>
        </div>
        <?php if ($count > 8): ?>
        <div class="text-center mt-4">
            <button id="viewMoreBtn" class="btn" style="background-color: #e40754; color: white;" onclick="toggleMenuItems()">View More</button>
        </div>
        <?php endif; ?>
        <script>
        function toggleMenuItems() {
            const hiddenItems = document.querySelectorAll('.menu-item-hidden');
            const viewMoreBtn = document.getElementById('viewMoreBtn');
            
            hiddenItems.forEach(item => {
                if (item.style.display === 'none' || !item.style.display) {
                    item.style.display = 'block';
                    viewMoreBtn.textContent = 'Show Less';
                } else {
                    item.style.display = 'none';
                    viewMoreBtn.textContent = 'View More';
                }
            });
        }

        // Initially hide items
        document.addEventListener('DOMContentLoaded', function() {
            const hiddenItems = document.querySelectorAll('.menu-item-hidden');
            hiddenItems.forEach(item => {
                item.style.display = 'none';
            });
        });
        </script>
</section>

     <!-- Menu Section End-->

    <!-- Gallery Section -->
    <section class="gallery" id="gallery" style="padding-top: 80px; padding-bottom: 80px; background-color:rgb(255, 255, 255);">
        <div class="container">
            <h2>Our Gallery</h2>
            <div class="row row-cols-1 row-cols-md-3 row-cols-lg-4 g-4">
                <?php
                include 'admin/db_connect.php';
                $sql = "SELECT * FROM gallery_images ORDER BY created_at DESC";
                $result = $conn->query($sql);
                
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo '<div class="gallery-item" style="border: 1px solid #ddd; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">';
                        echo '<img src="uploads/gallery/' . $row['image_path'] . '" alt="' . $row['title'] . '" style="width: 100%; height: 250px; object-fit: cover;">';
                        echo '</div>';
                    }
                }
                ?>
            </div>
    </section>
     <!-- Gallery Section End -->



    <!-- About Section -->
    <section id="about" class="about-section" style="padding: 80px 0; background-color: #f9f9f9;">
        <div class="container">
            <h2 class="text-center mb-5">About Us</h2>
            <div class="row">
                <div class="col-lg-6 mb-4">
                    <div class="about-content">
                        <h3 style="color: #e40754; margin-bottom: 20px;">Welcome to Foodie Hunt</h3>
                        <p style="font-size: 16px; line-height: 1.8; margin-bottom: 20px;">
                            At Foodie Hunt, we believe in creating memorable dining experiences that tantalize your taste buds and warm your soul. Our passion for food drives us to craft dishes that combine traditional flavors with modern culinary innovation.
                        </p>
                        <div class="about-features">
                            <div class="feature-item" style="margin-bottom: 15px;">
                                <i class="fas fa-check-circle" style="color: #e40754; margin-right: 10px;"></i>
                                <span>Fresh, locally-sourced ingredients</span>
                            </div>
                            <div class="feature-item" style="margin-bottom: 15px;">
                                <i class="fas fa-check-circle" style="color: #e40754; margin-right: 10px;"></i>
                                <span>Expert chefs with international experience</span>
                            </div>
                            <div class="feature-item" style="margin-bottom: 15px;">
                                <i class="fas fa-check-circle" style="color: #e40754; margin-right: 10px;"></i>
                                <span>Cozy and welcoming atmosphere</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="about-images">
                        <div class="row g-3">
                            <div class="col-6">
                                <img src="uploads/about/clem-onojeghuo-zlABb6Gke24-unsplash.jpg" alt="Chef Cooking" class="img-fluid rounded shadow" style="height: 200px; width: 100%; object-fit: cover;">
                            </div>
                            <div class="col-6">
                                <img src="uploads/about/cory-bjork--V0YGn1pjzE-unsplash.jpg" alt="Restaurant Interior" class="img-fluid rounded shadow" style="height: 200px; width: 100%; object-fit: cover;">
                            </div>
                            <div class="col-6">
                                <img src="uploads/about/daan-evers-tKN1WXrzQ3s-unsplash.jpg" alt="Food Preparation" class="img-fluid rounded shadow" style="height: 200px; width: 100%; object-fit: cover;">
                            </div>
                            <div class="col-6">
                                <img src="uploads/about/prithiviraj-a-vDlt9BQND-o-unsplash.jpg" alt="Dining Experience" class="img-fluid rounded shadow" style="height: 200px; width: 100%; object-fit: cover;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="contact" style="padding-top: 80px; padding-bottom: 80px;">
        <div class="container">
            <h2 class="text-center mb-5">Get in Touch</h2>
            <?php if(isset($_SESSION['contact_message'])): ?>
                <div class="alert alert-<?php echo $_SESSION['contact_status']; ?> alert-dismissible fade show" role="alert">
                    <?php 
                        echo $_SESSION['contact_message'];
                        unset($_SESSION['contact_message']);
                        unset($_SESSION['contact_status']);
                    ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="contact-info">
                        <h3 class="text-center mb-4" style="color: #e40754;">Contact Us</h3>
                        <div class="info-item mb-3">
                            <p style="font-size: 18px; font-weight: bold; font-family: 'Arial', sans-serif;">
                                <i class="fas fa-phone" style="color: #e40754;"></i> 
                                Call us at: +91 8597565181
                            </p>
                            <p style="font-size: 18px; font-weight: bold; font-family: 'Arial', sans-serif;">
                                <i class="fas fa-map-marker-alt" style="color: #e40754;"></i> 
                                Visit us at: 85 Restaurant Street, Foodie City, Kolkata 700154
                            </p>
                        </div>
                        <div class="map-container" style="height: 300px;">
                            <iframe src=https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d8762.52478106557!2d88.36548734944414!3d22.572076910697586!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2sin!4v1746820387986!5m2!1sen!2sin"
                                width="100%" 
                                height="100%" 
                                style="border:0; border-radius: 8px;" 
                                allowfullscreen="" 
                                loading="lazy">
                            </iframe>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="contact-form">
                        <form class="p-4 rounded bg-light shadow-sm" action="process_contact.php" method="POST">
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Your Name" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="Your Email" required>
                            </div>
                            <div class="mb-3">
                                <label for="subject" class="form-label">Subject</label>
                                <input type="text" class="form-control" id="subject" name="subject" placeholder="Subject" required>
                            </div>
                            <div class="mb-3">
                                <label for="message" class="form-label">Message</label>
                                <textarea class="form-control" id="message" name="message" rows="4" placeholder="Your Message" required></textarea>
                            </div>
                            <button type="submit" class="btn w-100 py-2" style="background-color: #e40754; color: white;">Send Message</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Contact Section End -->

    <!-- Footer Section -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>Foodie Hunt</h3>
                    <p>Discover the finest culinary experiences with us. Your journey to exceptional taste begins here.</p>
                    <div class="social-icons">
                        <a href="https://www.facebook.com/S.saikatdey/" class="social-icon"><i class="fab fa-facebook-f" style="color: #e40754;"></i></a>
                        <a href="" class="social-icon"><i class="fab fa-twitter" style="color: #e40754;"></i></a>
                        <a href="https://www.instagram.com/__saikat_r_i_i_143__/" class="social-icon"><i class="fab fa-instagram" style="color: #e40754;"></i></a>
                        <a href="https://www.youtube.com/@otblackleaf1027" class="social-icon"><i class="fab fa-youtube" style="color: #e40754;"></i></a>
                        <a href="https://pin.it/1AcfoTTt7" class="social-icon"><i class="fab fa-pinterest" style="color: #e40754;"></i></a>
                    </div>
                </div>
                <div class="footer-section">
                    <h3>Quick Links</h3>
                    <ul class="footer-links">
                        <li><a href="#home">Home</a></li>
                        <li><a href="#menu">Menu</a></li>
                        <li><a href="#gallery">Gallery</a></li>
                        <li><a href="#about">About Us</a></li>
                        <li><a href="#contact">Contact</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h3>Opening Hours</h3>
                    <ul class="opening-hours">
                        <li>Monday - Friday: 9:00 AM - 10:00 PM</li>
                        <li>Saturday - Sunday: 10:00 AM - 11:00 PM</li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 Foodie Hunt. All rights reserved.</p>
            </div>
        </div>
    </footer>
    <!-- Footer Section End -->

    <!-- Fast Scroll & Reload Buttons -->
    <button id="scrollToTop" style="position: fixed; bottom: 40px; right: 40px; display: none; z-index: 9999; background: #e40754; color: #fff; border: none; border-radius: 50%; width: 50px; height: 50px; font-size: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.2); cursor: pointer;">
        ↑
    </button>
    <button id="reloadPage" style="position: fixed; bottom: 100px; right: 40px; z-index: 9999; background: #222; color: #fff; border: none; border-radius: 50%; width: 50px; height: 50px; font-size: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.2); cursor: pointer;">
        ⟳
    </button>

    <!-- Add this before closing body tag -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Fast Scroll to Top Button functionality
        const scrollToTopBtn = document.getElementById('scrollToTop');
        window.addEventListener('scroll', () => {
            if (window.pageYOffset > 300) {
                scrollToTopBtn.style.display = 'block';
            } else {
                scrollToTopBtn.style.display = 'none';
            }
        });
        scrollToTopBtn.addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'auto' }); // instant scroll
        });
        // Fast Reload Button functionality
        const reloadBtn = document.getElementById('reloadPage');
        reloadBtn.addEventListener('click', () => {
            window.location.reload(true); // force reload from server
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const hamburger = document.querySelector('.hamburger');
            const navLinks = document.querySelector('.nav-links');
            
            // Hamburger menu toggle
            hamburger.addEventListener('click', function() {
                hamburger.classList.toggle('active');
                navLinks.classList.toggle('active');
            });

            // Smooth scrolling for navigation links
            document.querySelectorAll('.nav-links a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    
                    // Close mobile menu if open
                    hamburger.classList.remove('active');
                    navLinks.classList.remove('active');
                    
                    const targetId = this.getAttribute('href');
                    const targetSection = document.querySelector(targetId);
                    
                    if (targetSection) {
                        targetSection.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            const tabButtons = document.querySelectorAll('.tab-btn');
            const menuItems = document.querySelectorAll('.menu-item');

            tabButtons.forEach(button => {
                button.addEventListener('click', () => {
                    tabButtons.forEach(btn => btn.classList.remove('active'));
                    button.classList.add('active');

                    const category = button.getAttribute('data-category');

                    menuItems.forEach(item => {
                        if (category === 'all' || item.getAttribute('data-category') === category) {
                            item.style.display = 'block';
                        } else {
                            item.style.display = 'none';
                        }
                    });
                });
            });
        });
    </script>
</body>
</html>