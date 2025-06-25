-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 25, 2025 at 07:04 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `foodie_hunt`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_users`
--

CREATE TABLE `admin_users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_users`
--

INSERT INTO `admin_users` (`id`, `username`, `password`, `profile_image`, `created_at`) VALUES
(3, 'Riku1234', '$2y$10$Pkh8N7L68kqSkpYvYgKg7un/APR.N6aA6s9o7CvqBqw2pxMDctTmi', 'admin_profiles/685af3af3b1ef.jpg', '2025-06-24 18:51:27');

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `subject` varchar(200) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `gallery_images`
--

CREATE TABLE `gallery_images` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gallery_images`
--

INSERT INTO `gallery_images` (`id`, `title`, `image_path`, `created_at`) VALUES
(5, '', '682da5c01e0a8.jpg', '2025-05-21 10:06:56'),
(6, '', '682da93a22231.jpg', '2025-05-21 10:21:46'),
(7, '', '6852374ff2d7c.jpg', '2025-06-18 03:49:35'),
(8, '', '68523760ed801.jpg', '2025-06-18 03:49:52'),
(9, '', '685515de715fc.jpg', '2025-06-20 08:03:42'),
(10, '', '685515f8d40a9.jpg', '2025-06-20 08:04:08'),
(11, '', '6855161992095.jpg', '2025-06-20 08:04:41'),
(12, '', '6855164422275.jpg', '2025-06-20 08:05:24');

-- --------------------------------------------------------

--
-- Table structure for table `hero_section`
--

CREATE TABLE `hero_section` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `subtitle` text DEFAULT NULL,
  `background_image` varchar(255) DEFAULT NULL,
  `cta_text` varchar(100) DEFAULT NULL,
  `cta_link` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hero_section`
--

INSERT INTO `hero_section` (`id`, `title`, `subtitle`, `background_image`, `cta_text`, `cta_link`) VALUES
(1, 'Welcome to Foodie Hunt', 'Discover the finest culinary experiences', 'uploads/hero/hero_1748533050.jpg', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `menu_items`
--

CREATE TABLE `menu_items` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `category` varchar(100) NOT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu_items`
--

INSERT INTO `menu_items` (`id`, `name`, `description`, `price`, `category`, `image`) VALUES
(7, 'Cheesy Pepperoni Pizza', 'A person getting a piece of cheesy pepperoni pizza', 159.00, 'pizza', 'ivan-torres-MQUqbmszGGM-unsplash.jpg'),
(8, 'Pepperoni Pizza', 'Pepperoni pizza with a slice taken out with appetizing cheese pull', 169.00, 'pizza', 'vit-ch-Oxb84ENcFfU-unsplash.jpg'),
(9, 'Homemade Pepperoni Pizza', 'Homemade pepperoni pizza with cheese pull on a dark wooden surface', 189.00, 'pizza', 'shourav-sheikh-a66sGfOnnqQ-unsplash.jpg'),
(10, 'Hamburger or Burger', 'Homemade hamburger or burger with fresh vegetables and cheese', 120.00, 'burger', 'amirali-mirhashemian-JqnuWlHmDfE-unsplash.jpg'),
(11, 'Fresh tasty Burger', 'Fresh tasty burger and french fries on wooden table', 138.00, 'burger', 'ilya-mashkov-mkVa2hLJgnI-unsplash.jpg'),
(12, 'Grilled rib Burger', 'Grilled rib burger in a rustic setting', 135.00, 'burger', 'gabrielle-cepella-HAD-gT1kS40-unsplash.jpg'),
(13, 'Beef patty Burger', 'Beef patty burger with vegetables and lettuce on white background. File contains clipping path.', 149.00, 'burger', 'haseeb-jamil-J9lD6FS6_cs-unsplash.jpg'),
(14, 'Pasta Penne', 'Pasta penne with roasted tomato, sauce, mozzarella cheese. Grey stone background. Top view.', 155.00, 'pasta', 'pixzolo-photography-aeESmmFKH0M-unsplash.jpg'),
(16, 'Italian fettuccine with prawns', 'Italian fettuccine with prawns, salmon and herbs. Flat lay top-down composition on dark green background.', 155.00, 'pasta', 'aleksandra-tanasiienko-0y6eMd8vevA-unsplash.jpg'),
(17, 'Pasta alfredo', 'Pasta alfredo with chicken, spinach and cheese. Italian food', 169.00, 'pasta', 'emanuel-ekstrom-qxvhDhjFy4o-unsplash.jpg'),
(18, 'Healthy Chicken Pasta', 'Healthy Chicken Pasta Salad with Avocado, Tomato, and olive oil and vinegar dressing in black bowl on dark wood table with ingredients, vertical view from above', 179.00, 'pasta', 'ben-lei-flFd8L7_B3g-unsplash.jpg'),
(19, 'Caramel coffee cake', 'Caramel coffee cake in a glaze with nuts on plate over white background, top view', 80.00, 'dessert', 'brooke-lark-of0pMsWApZE-unsplash.jpg'),
(20, 'Conchas', 'Conchas on a cutting board and black background', 70.00, 'dessert', 'toa-heftiba-OSwea3yxjT0-unsplash.jpg'),
(21, 'Raspberry Nousse', 'Four dessert jars filled with homemade raspberry nousse, topped with whipped cream, raspberries and chocolate', 90.00, 'dessert', 'joyful-vT5xrj3z1OE-unsplash.jpg'),
(22, 'Chocolate cake with raspberry', 'Chocolate cake with raspberry and mint on a black plate.', 149.00, 'dessert', 'junel-mujar-Wq0tcKzIa0M-unsplash.jpg'),
(23, 'Indian Fish Platter or thali', 'Indian Fish Platter or thali - Popular sea food, Non vegetarian meal from Mumbai, Konkan, Maharashtra, Goa, Bengal, Kerala served in a steel plate or over banana leaf', 250.00, 'bengali_thali', 'abhishek-sanwa-limbu-5Q-7kgG7xbo-unsplash.jpg'),
(24, ' Tawa Roti and Salad in Disposable Plate', 'North indian food thali, shahi paneer with tawa roti and salad in disposable plate', 169.00, 'bengali_thali', 'daniel-arriola-v-UlAEJTSVw-unsplash.jpg'),
(25, 'Traditional Bengali cuisine served in rice ceremony', 'illustration of Traditional Bengali cuisine served in rice ceremony of West Bengal India', 350.00, 'bengali_thali', 'nidhin-k-s-m7ltD98UTHY-unsplash.jpg'),
(26, 'Indian vegetarian Food Thali', 'Indian vegetarian Food Thali or Parcel food-tray with compartments in which Dal tarka, dry aloo sabji, chapati and rice', 150.00, 'bengali_thali', 'amit-chang-j6qTlOSxQ1k-unsplash.jpg'),
(27, 'Chilli Garlic Hakka Noodles', 'Chilli Garlic Hakka Noodles in black bowl isolated on white background. Indo-Chinese vegetarian cuisine dish. Indian veg noodles with vegetables. Classic Asian meal', 199.00, 'noodles', 'riccardo-bergamini-Xe14NrSK9io-unsplash.jpg'),
(28, 'Schezwan Noodles', 'Schezwan Noodles or vegetable Hakka Noodles or chow mein is a popular Indo-Chinese recipes, served in a bowl or plate with wooden chopsticks. selective focus', 250.00, 'noodles', 'orijit-chatterjee-wEBg_pYtynw-unsplash.jpg'),
(29, 'Spaghetti with Tomato Sauce and Mussels', 'Healthy delicious lunch - spaghetti with tomato sauce and mussels on a wooden table, top view. Flat lay', 189.00, 'noodles', 'lindsay-moe-UP5jWpuIvZI-unsplash.jpg'),
(30, 'Delicious Chinese Stir Fried from Chow Mein Noodles with Vegetables', 'Delicious chinese stir fried from chow mein noodles with vegetables close-up on a plate on the table. horizontal', 179.00, 'noodles', 'benjamin-cheng-KQ6n-t_2Pkc-unsplash.jpg'),
(31, 'Chicken biryani', 'Chicken biryani Spicy Indian Malabar biryani Hyderabadi biryani, Dum Biriyani pulao golden bowl Kerala India Sri Lanka Pakistan basmati rice mixed rice dish with meat curry ', 130.00, 'biriyani', 'mario-raj-ysmeQt1dzcw-unsplash.jpg'),
(32, 'Delicious Chicken Dum biriyani', 'Delicious Chicken Dum biriyani with herbs and spices', 150.00, 'biriyani', 'shourav-sheikh-j9lowNcnl04-unsplash.jpg'),
(33, 'Chicken Tikka boti Biryani', 'Chicken Tikka boti Biryani with pickle served in dish isolated on dark background side view', 179.00, 'biriyani', 'omkar-jadhav-o5XB6XwTb1I-unsplash.jpg'),
(34, 'Mutton Biriyani', 'Mutton Biriyani close up image in a restaurant food stock photo', 165.00, 'biriyani', 'rashpal-singh-3tpobXvtAEw-unsplash.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `phone_number` varchar(20) NOT NULL,
  `delivery_address` text NOT NULL,
  `special_notes` text DEFAULT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `phone` varchar(15) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `status` varchar(50) DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `customer_name`, `phone_number`, `delivery_address`, `special_notes`, `total_amount`, `created_at`, `phone`, `address`, `notes`, `status`) VALUES
(9, 'Saikat Saikat Dey Dey', '', '', NULL, 0.00, '2025-06-24 16:35:15', '08597565181', 'Narkeldanga, Kolkata, West Bengal 700011\r\nKolkata', 'defefefe', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `menu_item_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `item_id`, `quantity`, `price`, `menu_item_id`) VALUES
(9, 9, 13, 1, 0.00, 0),
(10, 9, 17, 1, 0.00, 0),
(11, 9, 10, 1, 0.00, 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fullname`, `email`, `password`, `created_at`) VALUES
(4, 'Riku1234', '2004@gmail.com', '$2y$10$zQIVU.IIdQ8RZa2EyQwNqOjTPBP.gXUs/VmeGs40Ev.6kK3QyRRiK', '2025-06-24 18:52:03');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gallery_images`
--
ALTER TABLE `gallery_images`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hero_section`
--
ALTER TABLE `hero_section`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `menu_items`
--
ALTER TABLE `menu_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `item_id` (`item_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `gallery_images`
--
ALTER TABLE `gallery_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `hero_section`
--
ALTER TABLE `hero_section`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `menu_items`
--
ALTER TABLE `menu_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `menu_items` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
