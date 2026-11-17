-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 16, 2026 at 10:52 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bookstore`
--

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `author` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `description` text NOT NULL,
  `image` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `stock` int(11) DEFAULT 1,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`id`, `title`, `author`, `price`, `description`, `image`, `created_at`, `stock`, `category_id`) VALUES
(4, 'Sirish Ko Phool', 'Parijaat', 500.00, 'Sirish Ko Phool (सिरीषको फूल) is a classic Nepali novel by Parijat that explores themes of love, ego, loneliness, and emotional conflict. The story revolves around Suyogbir, a proud and psychologically complex ex-soldier, and his intense but one-sided attraction to Sakambari, a bold, independent, and unconventional woman. Through their relationship, the novel highlights the struggle between **desire and dignity**, as well as the pain of unfulfilled love. Known for its deep psychological insight and strong female character, Sirish Ko Phool is considered a landmark work in Nepali literature.', '6938f35e32c16_38489009.jpg', '2026-02-14 21:14:44', 0, 1),
(12, 'Palpasa Cafe', 'Narayan Wagle', 545.00, 'Palpasa Café (पाल्पसा क्याफे)  is a contemporary Nepali novel by Narayan Wagle that explores the impact of the Maoist insurgency on ordinary lives in Nepal. The story follows Drishya, an artist, as he navigates love, friendship, and the harsh realities of a country torn by conflict. Set partly in Kathmandu and partly in the scenic countryside, the novel reflects on hope, loss, and the human cost of war, while also celebrating Nepali culture and landscapes. It is praised for its vivid storytelling, relatable characters, and social commentary.', '698da0e537214_image_8Aqq9M8.jpg', '2026-02-14 18:44:39', 0, 1),
(13, 'Jeevan Kada Ki Phool', 'Jhamak Ghimire', 500.00, 'Jeevan Kada Ki Phool (जीवन काँडा कि फूल) is a well-known Nepali novel by Jhamak Ghimire. The book is a deeply emotional and inspirational work that reflects the author’s own life experiences as a person living with physical disability. It explores themes of struggle, pain, determination, and hope, showing how life can be full of hardships (thorns) yet also beauty and meaning (flowers). Through powerful storytelling, the book highlights the importance of courage, self-respect, and perseverance, making it a motivating piece of Nepali literature.', '698da1c15e019_image.jpg', '2026-02-13 02:14:17', 0, 1),
(18, 'The Great Gatsby', 'Francis Scott Fitzgerald', 800.00, 'The Great Gatsby is a romantic novel written by american author F. Scott Fitzgerald. The story of a man who believes his lost romantic relationship can be regained by exhibiting his newly created wealth and social status. Gatsby an army officer, falls in love with Daisy. During his serving at world war, Daisy marries a rich but brutal man Tom Buchanan. Gatsby returns after world war and went on to create huge wealth through unethical practices.\r\nThe story of a man who believes his lost romantic relationship can be regained by exhibiting his newly created wealth and social status. Gatsby an army officer, falls in love with Daisy. During his serving at world war, Daisy marries a rich but brutal man Tom Buchanan. Gatsby returns after world war and went on to create huge wealth through unethical practices. \r\n\r\nTo grab the attention of her, he conducts many parties, ironically not attending them. He could manage to meet Daisy again, with the help of his neighbor Nick, who is happened to be cousin of Daisy and a world war veteran.They recoup of Gatsby and Daisy made them to fall in love again. Tom suspects the activities of Daisy, but unable to stop her as he himself unfaithful to his wife because of his affair with Myrtle Wilson, wife of a car mechanic George Wilson. \r\nWhile Gatsby and Daisy driving, they end up killing Myrtle. Though daisy was actually driving the car, Tom blames Gatsby for the killing. George came to know about this and shoots Gatsby to death and ends up killing himself.', '698f0fa11c9f8_TheGreatGatsby2013Poster.jpg', '2026-02-15 19:28:11', 2, 3);

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `book_id`, `quantity`, `created_at`) VALUES
(17, 8, 15, 3, '2026-02-12 19:07:05'),
(19, 8, 4, 6, '2026-02-12 19:07:11'),
(20, 8, 12, 1, '2026-02-12 19:39:26');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'Fiction'),
(3, 'Romance'),
(4, 'Children'),
(5, 'Science'),
(6, 'Business'),
(7, 'Learning');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address` text NOT NULL,
  `city` varchar(50) NOT NULL,
  `pincode` varchar(20) DEFAULT NULL,
  `payment_method` varchar(20) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `order_date` datetime NOT NULL DEFAULT current_timestamp(),
  `book_name` varchar(255) NOT NULL,
  `total_quantity` int(11) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `book_id`, `fullname`, `email`, `phone`, `address`, `city`, `pincode`, `payment_method`, `total_amount`, `order_date`, `book_name`, `total_quantity`, `status`) VALUES
(1, 9, 0, 'Krishna Maharjan', 'krishnamaharjan123@gmail.com', '2147483647', 'Kathmandu,Nepal', 'Kathmandu', '', 'COD', 500.00, '2026-02-13 02:47:34', '', 0, 'cancelled'),
(2, 9, 0, 'Krishna Maharjan', 'krishnamaharjan123@gmail.com', '2147483647', 'Kathmandu,Nepal', 'Kathmandu', '', 'COD', 500.00, '2026-02-13 02:47:54', '', 0, 'accepted'),
(3, 9, 0, 'Krishna Maharjan', 'krishnamaharjan123@gmail.com', '2147483647', 'Kathmandu,Nepal', 'Kathmandu', '', 'COD', 500.00, '2026-02-13 02:51:27', '', 0, 'cancelled'),
(4, 9, 0, 'Krishna Maharjan', 'krishnamaharjan123@gmail.com', '2147483647', 'Kathmandu,Nepal', 'Kathmandu', '', 'COD', 500.00, '2026-02-13 02:54:58', 'djfdfa', 1, 'accepted'),
(20, 9, 18, 'Krishna Maharjan', 'krishnamaharjan123@gmail.com', '2147483647', 'Kathmandu,Nepal', 'Kathmandu', '', 'COD', 800.00, '2026-02-16 15:14:19', 'The Great Gatsby', 1, 'accepted');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` int(10) NOT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `phone`, `address`, `city`, `role`, `created_at`) VALUES
(1, 'Ajita Oli', 'ajita124@gmail.com', '51c231be32500a8ad183ffebb9dccab8', 0, '', '', 'user', '2025-10-20 03:20:08'),
(5, 'admin', 'admin@gmail.com', '21232f297a57a5a743894a0e4a801fc3', 0, '', '', 'admin', '2026-01-09 02:05:08'),
(6, 'Krishna Chapagain', 'krishna@gmail.com', '243bd1ce0387f18005abfc43b001646a', 0, '', '', 'user', '2026-01-13 09:20:23'),
(7, 'aaru', 'aaru@gmail.com', 'cd3068baa014208091a8e9e905faaf08', 0, '', '', 'user', '2026-02-11 02:33:56'),
(8, 'Hari Bista', 'haribista@gmail.com', 'd477f322c8576d1dd87a032bd2508658', 0, '', '', 'user', '2026-02-12 16:00:02'),
(9, 'Krishna Maharjan', 'krishnamaharjan123@gmail.com', '243bd1ce0387f18005abfc43b001646a', 2147483647, 'Kathmandu,Nepal', 'Kathmandu', 'user', '2026-02-12 20:43:18'),
(10, 'Ajita Oli', 'ajitaoli97@gmail.com', '1ccd9d5fa8ee12254988237ca34aab77', 2147483647, 'Kathmandu,Nepal', 'Kathmandu', 'user', '2026-02-13 11:38:18');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_books_category` (`category_id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_cart` (`user_id`,`book_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

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
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `books`
--
ALTER TABLE `books`
  ADD CONSTRAINT `fk_books_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
