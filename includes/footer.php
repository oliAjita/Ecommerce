<?php $base_url="/ECOMMERCE";?>
<link rel="stylesheet" href="/ECOMMERCE/assets/css/footer.css" />
<footer class="site-footer">
    <div class="footer-container">
        <div class="footer-box">
            <h3>About BookStore</h3>
            <p>
                BookStore is your one-stop destination for quality books at affordable prices.
                Discover knowledge, imagination, and inspiration with us.
            </p>
        </div>

        <div class="footer-box">
            <h3>Quick Links</h3>
            <ul>
                <li><a href="../../index.php">Home</a></li>
                <!-- <li><a href="shop.php">Shop</a></li> -->
                <li><a href="<?php echo $base_url ?>/pages/cart/cart.php">Cart</a></li>
                <li><a href="<?php echo $base_url?>/pages/info/contact.php">Contact Us</a></li>
                <li><a href="<?php echo $base_url?>/pages/info/about.php">About Us</a></li>

            </ul>
        </div>


        <div class="footer-box">
            <h3>Contact Us</h3>
            <p>Email: ajita.bookstore@gmail.com</p>
            <p>Phone: +977-9860681672</p>
            <p>Location: Nepal</p>
        </div>

    </div>


    <div class="footer-bottom">
        <p>© <?php echo date("Y"); ?> BookStore. All Rights Reserved.</p>
    </div>
</footer>