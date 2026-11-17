<?php
session_start();
include(__DIR__ . "/../../includes/header.php");

?>

<link rel="stylesheet" href="<?php echo BASE_URL ?>/assets/css/info_pages/info_page.css">

<div class="page-wrapper">

    <h1>About Our Book Store</h1>

    <p>
        Welcome to our online book store — your one-stop destination for discovering,
        exploring, and buying books from a wide range of categories.
    </p>

    <p>
        Our mission is to make books easily accessible to everyone by providing a
        smooth shopping experience, affordable prices, and a growing collection of titles.
    </p>

    <div class="info-box">

        <div class="info-card">
            <h3>📚 Wide Collection</h3>
            <p>
                We offer books across multiple genres including academic,
                novels, self-help, technology, and more.
            </p>
        </div>

        <div class="info-card">
            <h3>🚀 Easy Shopping</h3>
            <p>
                Our platform is designed to help you browse, search,
                and order books quickly and securely.
            </p>
        </div>

        <div class="info-card">
            <h3>💡 Our Vision</h3>
            <p>
                We aim to build a trusted digital bookstore that connects
                readers with knowledge and inspiration.
            </p>
        </div>

    </div>

</div>

<?php include(__DIR__ . "/../../includes/footer.php"); ?>