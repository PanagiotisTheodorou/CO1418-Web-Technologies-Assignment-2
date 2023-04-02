<?php

$is_invalid = false;

// once the user logs in the method will change from get to post
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // get the database 
    $pdo = require __DIR__ . "/database.php";

    // get the email from the form and prepare the statement
    $email = $_POST["email"];
    $sql = "SELECT * FROM tbl_users WHERE user_email = ?";

    // execute the call
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    // if a record is found for the email then continue
    if ($user) {
        // verify that the hashed password is correct
        if (password_verify($_POST["password"], $user["user_pass"])) {
            // if everything is correct then start the session
            session_start();
            session_regenerate_id();
            $_SESSION["user_id"] = $user["user_id"];
            $_SESSION['action'] = 'signin';
            // redirect user
            header("Location: index.php");
            exit;
        }
    }
    // variable cahnges based on if something in the form is invalid or not
    $is_invalid = true;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Account</title>
    <script src="JavaScript/HamburgherMenu.js" defer></script>
    <script src="JavaScript/modals.js" defer></script>
    <link rel="stylesheet" href="Styles/general.css" type="text/css">
    <script src="https://unpkg.com/just-validate@latest/dist/just-validate.production.min.js" defer></script>
    <!--<script src="JavaScript/validation.js" defer></script>-->
</head>

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
        </nav>
    </div>
</header>

<body>
    <div id="userBtns">
        <h1>Hello guest</h1>
        <div id="UpHide">
            <p>If you don't have an account we heavily prompt you to create one:</p>
            <button id="signupBtn">Sign up</button>
        </div>
        <div id="InHide">
            <p>If you do have an account</p>
            <button id="signinBtn">Sign in</button>
        </div>
    </div>
    <?php
    session_start();

    if (isset($_SESSION["user_id"])) {
        $pdo = require __DIR__ . "/database.php";

        $sql = "SELECT * FROM tbl_users WHERE user_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$_SESSION["user_id"]]);

        $user = $stmt->fetch();
    ?>

        <!-- HTML for signed-in users -->

        <h1>Hello
            <?php
            echo $user['user_full_name'];
            ?>
        </h1>
        <!-- <p>If you want to log out you can click <a href="?logout=true">here</a></p> -->
        <button class="logoutBtn" onclick="window.location.href='?logout=true'">LOG OUT</button>
    <?php
        echo "<script>document.getElementById('userBtns').style.display = 'none'</script>";
    } else {
    ?>

        <!-- HTML for non-signed-in users -->

        <?php
        $_SESSION['action'] = 'signup';
        ?>

    <?php
    }

    // Functionality for logout
    if (isset($_GET['logout']) && $_GET['logout'] == true) {
        $_SESSION['action'] = 'logout';
        session_destroy();
        header("Location: index.php");
        exit;
    }
    ?>

    <!-- Modal to sign up -->

    <div id="signupModal" class="modal">
        <div class="modal-content">
            <h2>Sign Up</h2>
            <h3>Please enter info:</h3>
            <span class="close" onclick="signupClose()">&times;</span>
            <form method="POST" action="processAccount.php" id="signup" novalidate>
                <label for="username">Enter Username:</label>
                <input type="text" id="username" name="username">
                <label for="password">Enter Password:</label>
                <input type="password" id="password" name="password">
                <label for="password_confirmation">Repeat password</label>
                <input type="password" id="password_confirmation" name="password_confirmation">
                <label for="address">Enter address:</label>
                <input type="text" id="address" name="address">
                <label for="email">Enter E-mail</label>
                <input type="email" id="email" name="email"></input>
                <button type="submit" value="Sign Up">Sign Up</button>

            </form>
        </div>
    </div>

    <!-- Modal to sign in -->

    <div id="signinModal" class="modal">
        <div class="modal-content">
            <h2>Sign In</h2>
            <h3>Please enter info:</h3>

            <?php if ($is_invalid) : ?>
                <em>Invalid Login</em>
            <?php endif; ?>

            <span class="close" onclick="signinClose()">&times;</span>
            <form method="POST" id="signin">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($_POST["email"] ?? "") ?>">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password">
                <button type="submit" value="Sign In">Sign In</button>
            </form>
        </div>
    </div>
</body>

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

</html>