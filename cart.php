<?php
session_start();
if (isset($_SESSION["user_id"])) {
    $pdo = require __DIR__ . "/database.php";

    $sql = "SELECT * FROM tbl_users WHERE user_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$_SESSION["user_id"]]);

    $user = $stmt->fetch();
}
?>
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
    <title>Cart</title>
    <link rel="stylesheet" href="Styles/cart.css" type="text/css">
    <link rel="stylesheet" href="Styles/general.css" type="text/css">
    <script src="JavaScript/HamburgherMenu.js" defer></script>
    <script src="JavaScript/cart.js" defer></script>
    <script src="JavaScript/modals.js" defer></script>
</head>

<body>
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
    <main>
        <?php
        if (isset($_SESSION['user_id'])) : ?>
            <h1>Welcome <?= htmlspecialchars($user["user_full_name"]) ?> to your Shopping Cart</h1>
        <?php else : ?>
            <h1>Shopping Cart</h1>
        <?php endif; ?>
        <div id="cartSection">
        </div>
        <!-- The section here is shows the price -->
        <div id="price"></div>
        <!-- The section here is used to display a buy and clear button -->

        <div id="price"></div>
        <div id="total"></div>

        <div id="signin-signup">
            <p>Please <button id="signinBtn">sign in</button> or <button id="signupBtn">sign up</button> to checkout</p>
        </div>

        <?php if (!isset($_SESSION['user_id'])) {
            echo "<script>document.getElementById('total').style.display = 'none'</script>";
            echo "<script>document.getElementById('signin-signup').style.display = 'block'</script>";
        ?>

        <?php
        } else {
            echo "<script>document.getElementById('total').style.display = 'flex'</script>";
            echo "<script>document.getElementById('signin-signup').style.display = 'none'</script>";
        } ?>

        <?php
        $pdo = require __DIR__ . "/database.php";
        if (isset($_POST['buySubmit'])) {
            $userId = $_SESSION["user_id"];
            $productIds = $_GET["ids"];

            $sql = "SELECT * from tbl_orders";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $orderId = $stmt->rowCount() + 1;

            $sql = "INSERT into tbl_orders(order_id, user_id, product_ids) VALUES (:id,:usrId,:prodIds);";
            $stmt = $pdo->prepare($sql);

            $stmt->bindParam(':id', $orderId);
            $stmt->bindParam(':usrId', $userId);
            $stmt->bindParam(':prodIds', $productIds);
            $stmt->execute();

            echo '<script>
            localStorage.clear();
            window.location.href = "cart.php?ids=";
            </script>';
        }
        ?>

        <div id="signupModal" class="modal">
            <div class="modal-content">
                <h2>Sign Up</h2>
                <h3>Please enter info:</h3>
                <span class="close" onclick="signupClose()">&times;</span>
                <form method="POST" action="processAccount.php" id="signup" novalidate>
                    <label for="username">Enter Username:</label>
                    <input type="text" id="username" name="username"><br>
                    <label for="password">Enter Password:</label>
                    <input type="password" id="password" name="password"><br>
                    <label for="password_confirmation">Repeat password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation">
                    <label for="address">Enter address:</label>
                    <input type="text" id="address" name="address"><br>
                    <label for="email">Enter E-mail</label>
                    <input type="email" id="email" name="email"></input><br>
                    <button type="submit" value="Sign Up">Sign Up</button>
                </form>
            </div>
        </div>

        <!-- Modal to sign in -->

        <div id="signinModal" class="modal">
            <div class="modal-content">
                <h2>Sign In</h2>
                <h3>Please enter info:</h3>

                <span class="close" onclick="signinClose()">&times;</span>
                <form method="POST" id="signin">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?= htmlspecialchars($_POST["email"] ?? "") ?>"><br>
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password"><br>
                    <button type="submit" value="Sign In">Sign In</button>
                </form>
            </div>
        </div>
    </main>
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