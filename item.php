<?php
session_start();
if (isset($_SESSION["user_id"])) {
    $pdo = require __DIR__ . "/database.php";

    $sql = "SELECT * FROM tbl_users WHERE user_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$_SESSION["user_id"]]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Item</title>
    <link rel="stylesheet" href="Styles/general.css" type="text/css">
    <link rel="stylesheet" href="Styles/item.css" type="text/css">
    <script src="JavaScript/HamburgherMenu.js" defer></script>
    <script src="JavaScript/products.js" defer></script>
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
        <!-- The product will be displayed via JavaScript, therefore only a section is needed -->
        <div id="itemSection">
            <?php
            if (isset($_GET["product_id"])) {
                $pdo = require __DIR__ . "/database.php";

                $productID = $_GET["product_id"];
                $sql = "SELECT * FROM tbl_products WHERE product_id = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$productID]);

                $product = $stmt->fetch();
            }

            if ($product) {
                $productTitle = $product['product_title'];
                $productDesc = $product['product_desc'];
                $productImage = $product['product_image'];
                $productPrice = $product['product_price'];
                $productType = $product['product_type'];

                echo '<div class="item">';
                echo '<img src="' . $productImage . '">';
                echo '<h3>' . $productTitle . '</h3>';
                echo '<p class="description">' . $productDesc . '</p>';
                echo '<span class="price-quantity">Price: $' . $productPrice . '</span>';
            ?>
                <button onclick="AddToCart( '<?php echo $productTitle, ',', $productImage, ',', $productPrice, ',', $productID ?>')">Buy</button>
            <?php
                echo '</div>';
            } else {
                echo 'Product ID not specified.';
            }
            ?>

            <div id="Productreviews">
                <h1>REVIEWS</h1>
                <?php
                $productId = $_GET['product_id'];
                $sql = "SELECT * FROM tbl_reviews WHERE product_id = '$productId'";
                $stmt = $pdo->prepare($sql);
                $stmt->execute();
                $reviews = $stmt->fetchAll();

                if ($stmt->rowCount() > 0) {
                    foreach ($reviews as $review) {
                        $userId = $review['user_id'];
                        $title = $review['review_title'];
                        $desc = $review['review_desc'];
                        $rating = $review['review_rating'];
                        $time = $review['review_timestamp'];
                ?>
                        <div class="review">
                            <h2><u><?php echo $title; ?></u></h2>
                            <div class="review-info">
                                <h3>User Id: <?php echo $userId; ?></h3>
                                <p>Description: <?php echo $desc; ?></p>
                                <p>Rating: <strong><?php echo $rating; ?></strong></p>
                                <p>Time of submit: <?php echo $time; ?></p>
                            </div>
                        </div>
                <?php
                    }
                }
                ?>
            </div>                
            <h3>Leave A Review</h3>
            <form method="post" action="" id="reviewForm">
                <label for="review-title">Title: </label>
                <input type="text" name="review-title">
                <label for="review-desc">Description: </label>
                <input type="text" name="review-desc">
                <label for="review-rating">Rating:</label>
                <input type="number" name="review-rating" min=1 max=5>
                <button type="submit" name="revSub">Submit</button>
            </form>

            <?php
            if (isset($user)) {
                echo "<script>document.getElementById('reviewForm').style.display = 'flex'</script>";
            } else echo "<script>document.getElementById('reviewForm').style.display = 'none'</script>";

            if (isset($_POST['revSub'])) {
                $productId = $_GET['product_id'];
                $title = $_POST['review-title'];
                $desc = $_POST['review-desc'];
                $rating = $_POST['review-rating'];

                $userId = $_SESSION["user_id"];

                $sql = "INSERT into tbl_reviews( user_id, product_id, review_title, review_desc, review_rating) 
            VALUES (:usrId,:prodId,:Title,:Desc,:rating );";

                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':usrId', $userId);
                $stmt->bindParam(':prodId', $productId);
                $stmt->bindParam(':Title', $title);
                $stmt->bindParam(':Desc', $desc);
                $stmt->bindParam(':rating', $rating);
                $stmt->execute();
                unset($_POST['revSub']);
            }

            ?>
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