<?php
session_start();
include(__DIR__ . "/../../includes/db.php");
include(__DIR__ . "/../../includes/header.php");

$base_url="/ECOMMERCE";
$success="";
$error="";

if($_SERVER["REQUEST_METHOD"]=="POST"){
    $name=trim($_POST["name"]);
    $email=trim($_POST["email"]);
    $message=trim($_POST["message"]);

    if($name=="" || $email=="" || $message==""){
        $error="Please fill all fields.";
    }else{

        $stmt=$conn->prepare("INSERT INTO contact_messages(name,email,message) VALUES(?,?,?)");
        $stmt->bind_param("sss",$name,$email,$message);

        if($stmt->execute()){
            $success="Message sent successfully!";
        }else{
            $error="Something went wrong.";
        }
    }
}
?>

<link rel="stylesheet" href="<?php echo $base_url ?>/assets/css/info_pages/info_page.css">

<div class="page-wrapper">

    <h1>Contact Us</h1>
    <p>If you have any questions, feedback, or support requests, feel free to contact us.</p>

    <?php if($success!=""){ ?>
        <div class="msg success"><?php echo $success; ?></div>
    <?php } ?>

    <?php if($error!=""){ ?>
        <div class="msg error"><?php echo $error; ?></div>
    <?php } ?>

    <form class="contact-form" method="POST">

        <input type="text" name="name" placeholder="Your Name">

        <input type="email" name="email" placeholder="Your Email">

        <textarea name="message" placeholder="Write your message..."></textarea>

        <button type="submit">Send Message</button>

    </form>

    <div class="info-box">

        <div class="info-card">
            <h3>📍 Address</h3>
            <p>Kathmandu, Nepal</p>
        </div>

        <div class="info-card">
            <h3>📧 Email</h3>
            <p>support@bookstore.com</p>
        </div>

        <div class="info-card">
            <h3>📞 Phone</h3>
            <p>+977-98XXXXXXXX</p>
        </div>

    </div>

</div>

<?php include(__DIR__ . "/../../includes/footer.php"); ?>
