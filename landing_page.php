<?php
session_start();
include("includes/db.php");

$base_url = "/ECOMMERCE";

// Fetch books dynamically for sections
$new_arrivals = mysqli_query($conn, "SELECT * FROM books ORDER BY id DESC LIMIT 10");
$most_read = mysqli_query($conn, "SELECT * FROM books ORDER BY id DESC LIMIT 10"); // assuming read_count column
$most_loved = mysqli_query($conn, "SELECT * FROM books ORDER BY id DESC LIMIT 10"); // assuming love_count column
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bookstore Landing Page</title>
    <link rel="stylesheet" href="<?php echo $base_url; ?>/assets/css/style.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link rel="stylesheet" href="<?php echo $base_url; ?>/assets/css/main.css">
    <script src="<?php echo $base_url; ?>/assets/js/main.js" defer></script>

</head>

<body>
x`x`
    <!-- Navbar -->
    <div class="navbar">
        <div class="logo">BookStore</div>
        <div class="nav-links">
            <a href="#home">Home</a>
            <a href="#shop">Shop</a>
            <a href="#about">About Us</a>
            <a href="#contact">Contact</a>
        </div>
    </div>

    <!-- Hero Section -->
    <section class="hero" id="home">
        <div class="text">
            <h1>Discover Your Next Favorite Book</h1>
            <p>Explore new arrivals, bestsellers, and timeless classics.</p>
            <a href="index.php">Visit Shop</a>
        </div>
    </section>

    <!-- New Arrivals -->
    <h2 class="section-title">New Arrivals</h2>
    <div class="marquee-container">
        <div class="marquee-track">
            <?php while ($book = mysqli_fetch_assoc($new_arrivals)) { ?>
                <div>
                    <img src="<?php echo $base_url; ?>/admin/images/<?php echo $book['image']; ?>"
                        alt="<?php echo $book['title']; ?>">
                </div>
            <?php } ?>
            <!-- Duplicate for continuous scroll -->
            <?php mysqli_data_seek($new_arrivals, 0);
            while ($book = mysqli_fetch_assoc($new_arrivals)) { ?>
                <div>
                    <img src="<?php echo $base_url; ?>/admin/images/<?php echo $book['image']; ?>"
                        alt="<?php echo $book['title']; ?>">
                </div>
            <?php } ?>
        </div>
    </div>

    <!-- Most Read -->
    <h2 class="section-title">Most Read</h2>
    <div class="marquee-container">
        <div class="marquee-track">
            <?php while ($book = mysqli_fetch_assoc($most_read)) { ?>
                <div>
                    <img src="<?php echo $base_url; ?>/admin/images/<?php echo $book['image']; ?>"
                        alt="<?php echo $book['title']; ?>">
                </div>
            <?php } ?>
            <?php mysqli_data_seek($most_read, 0);
            while ($book = mysqli_fetch_assoc($most_read)) { ?>
                <div>
                    <img src="<?php echo $base_url; ?>/admin/images/<?php echo $book['image']; ?>"
                        alt="<?php echo $book['title']; ?>">
                </div>
            <?php } ?>
        </div>
    </div>

    <!-- Most Loved -->
    <h2 class="section-title">Most Loved</h2>
    <div class="marquee-container">
        <div class="marquee-track">
            <?php while ($book = mysqli_fetch_assoc($most_loved)) { ?>
                <div>
                    <img src="<?php echo $base_url; ?>/admin/images/<?php echo $book['image']; ?>"
                        alt="<?php echo $book['title']; ?>">
                </div>
            <?php } ?>
            <?php mysqli_data_seek($most_loved, 0);
            while ($book = mysqli_fetch_assoc($most_loved)) { ?>
                <div>
                    <img src="<?php echo $base_url; ?>/admin/images/<?php echo $book['image']; ?>"
                        alt="<?php echo $book['title']; ?>">
                </div>
            <?php } ?>
        </div>
    </div>

    <!-- About Us -->
    <section class="about" id="about">
        <h2>About Us</h2>
        <p>Welcome to BookStore! We are passionate about connecting readers with the perfect books. From the latest
            releases to timeless classics, we provide a wide range of books for all ages and interests. Our mission is
            to inspire, educate, and entertain readers everywhere.</p>
    </section>

    <!-- Contact Us -->
    <section class="about" id="contact">
        <h2>Contact Us</h2>
        <div class="contact-form">
            <form action="<?php echo $base_url; ?>/pages/contact/submit_contact.php" method="POST">
                <input type="text" name="name" placeholder="Your Name" required>
                <input type="email" name="email" placeholder="Email" required>
                <textarea name="message" rows="5" placeholder="Your Message" required></textarea>
                <button type="submit">Send Message</button>
            </form>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="footer-container">
            <div>
                <h4>BookStore</h4>
                <p>Your one-stop online bookstore. Explore, discover, and enjoy books from around the world.</p>
            </div>
            <div>
                <h4>Quick Links</h4>
                <a href="#home">Home</a>
                <a href="#shop">Shop</a>
                <a href="#about">About Us</a>
                <a href="#contact">Contact</a>
            </div>
            <div>
                <h4>Contact Info</h4>
                <p>Email: info@bookstore.com</p>
                <p>Phone: +977 1234567890</p>
                <p>Address: Kathmandu, Nepal</p>
            </div>
        </div>
        <p style="text-align:center; margin-top:20px;">&copy; <?php echo date('Y'); ?> BookStore. All rights reserved.
        </p>
    </footer>

    <script>
        // JS Marquee effect (alternative smooth scroll)
        document.querySelectorAll('.marquee-track').forEach(track => {
            track.innerHTML += track.innerHTML; // duplicate for seamless scroll
        });
    </script>

</body>

</html>