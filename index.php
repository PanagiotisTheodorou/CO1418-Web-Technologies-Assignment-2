<?php 
session_start();
if (isset($_SESSION["user_id"])) {
    $pdo = require __DIR__ . "/database.php";
    
    $sql = "SELECT * FROM tbl_users WHERE user_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$_SESSION["user_id"]]);
    
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Display a personalised message to the user

    if (isset($_SESSION['action'])) {
        switch ($_SESSION['action']) {
            case 'signup':
                echo "<script>alert('Thank you for signing up!')</script>";
                break;
            case 'signin':
                echo "<script>alert('Welcome back!')</script>";
                break;
            case 'logout':
                echo "<script>alert('You have been logged out.')</script>";
                break;
        }
        unset($_SESSION['action']); // Remove the session variable
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Home</title>
    <link rel="stylesheet" href="Styles/general.css" type="text/css">
    <link rel="stylesheet" href="Styles/index.css" type="text/css">
    <link rel="stylesheet" href="Styles/offers.css" type="text/css">
    <script src="JavaScript/HamburgherMenu.js" defer></script>
</head>
<body>
<!-- HEADER SECTION OF THE HOME PAGE -->
    <header>
        <!-- The header will be split into two sections in order to align items easily -->
        <!-- First section of the header contains the links to all pages -->
        <nav id="right-header">
            <a href="index.php">Home</a>
            <a href="products.php">Products</a>
            <a href="cart.php">Cart</a>
            <a href="account.php">Account</a>
        </nav>
        <!-- second section of the header contains the logo and the title -->
        <section id="left-header">
            <img id="logo" src="uclan-logo.png" alt="logo">
            <h2>Student Shop</h2>
        </section>
        <!-- hamburgher menu section -->
        <a id="icon" onclick="myFunction()">
            Menu
        </a>
        <div id="topnav">
            <nav id="myLinks">
                <a href="index.php">Home</a>
                <a href="products.php">Products</a>
                <a href="cart.php">Cart</a>
                <a href="account.php">Account</a>
            </nav>
        </div>
    </header>
<!-- MAIN SECTION OF THE HOME PAGE -->
    
    <main>

        <!-- PHP here is responsible for the user message -->

        <?php if (isset($user)): ?>
            <h4>Hello <?= htmlspecialchars($user["user_full_name"]) ?></h4>
        <?php else: ?>
            <h4>Hello user</h4>
        <?php endif;?>

        <!-- PHP here is responsible for the offers -->

        <?php
        // get the database 
        $pdo = require __DIR__ . "/database.php";
        // execute PDO
        $sql = "SELECT * FROM tbl_offers";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $offers = $stmt->fetchAll();
        // displaying the offers
    ?>
        <div id=offers>
            <h1>Offers</h1>
            <div class="offer-container">
                <?php 
                foreach ($offers as $offer) {
                    echo "<div class='offer-item'>";
                    echo "<h3 id='offer-title'>" . $offer['offer_title'] . "</h3>";
                    echo "<p id='offer-desc'>" . $offer['offer_dec'] . "</p>";
                    echo "</div>";
                }
                ?>   
            </div>
        </div>
        <h1>Where opportunity creates success</h1>
        <p>
            Every student at The University of Central
            Lancashire is automaticly a member of the
            Student's Union. We're here to make life
            better for students - inspiring you to
            succeed and achieve your goals.
        </p>
        <h2>Together</h2>
        <video id="video" controls>
            <source src="UCLan Together.mp4" type="video/mp4">
        </video>
        <h2>Join our global community</h2>
        <iframe id="frame" src="https://www.youtube.com/embed/i2CRunZv9CU"></iframe>
    </main>
<!-- FOOTER SECTION OF THE HOME PAGE -->
    <footer>
        <!-- The footer will be split into three sections for easier management -->
        <!-- First section of the footer -->
        <section>
            <h2>Links</h2>
            <p><a href="#">Student's Union</a></p>
        </section>
        <!-- Second section of the footer -->
        <section>
            <h2>Contact</h2>
            <p>Email: suinformation@uclan.ac.uk</p>
            <p>Phone: 01772 89 3000</p>
        </section>
        <!-- Third section of the footer -->
        <section>
            <h2>Location</h2>
            <p>
                University of Central Lancashire Student's Union
                Fylde Road Preston, PR1 7BY
                Registered in England
                Company Number: 7623917
                Registered Charity Number: 1142616
            </p>
        </section>
    </footer>
</body>
</html>