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
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Products</title>
    <link rel="stylesheet" href="Styles/products.css">
    <link rel="stylesheet" href="Styles/general.css">
    <script src="JavaScript/HamburgherMenu.js" defer></script>
    <script src="JavaScript/products.js" defer></script>
</head>

<body>
    <!-- HEADER SECTION OF THE PRODUCTS PAGE -->
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
            <h2 id=headerH2>Student Shop</h2>
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

    <div class="formSection">
        <h3>Search for whatever product you may like...</h3>  
        <form id="searchForm" action="" method="post">
            <input type="text" name="search" placeholder="Search products">
            <button class="submitButton" type="submit" name="searchSubmit">Search</button>
            <select id="productsNav" name="product_type">
                <option value="">All Products</option>
                <option value="UCLan Logo Tshirt">T-shirts</option>
                <option value="UCLan hoodie">Hoodies</option>
                <option value="UCLan Logo Jumper">Jumpers</option>
            </select>
        </form>
    </div>
    <!-- MAIN SECTION OF THE PRODUCTS PAGE -->
    <main>
    
        <!-- The products will be displayed via javascript, therefore all that is needed is a section -->
        <h1>GAY NIGGERS</h1>
        <div id="productsSection">
            <?php
            // get the database 
            //$pdo = require __DIR__ . "/database.php";
            require_once("database.php");
            // execute PDO
            if (isset($_POST['product_type']) && $_POST['search'] == "") {
                $product_type = $_POST['product_type'];
                $sql = "SELECT * FROM tbl_products WHERE product_title LIKE '%$product_type%'";
                $stmt = $pdo->prepare($sql);
                $stmt->execute();
                unset($_POST['product_type']);
            } else if (isset($_POST['searchSubmit'])) {
                $search = $_POST['search'];
                $sql = "SELECT * FROM tbl_products WHERE product_title LIKE '%$search%'";
                $stmt = $pdo->prepare($sql);
                $stmt->execute();
                unset($_POST['searchSubmit']);
            } else {
                $sql = "SELECT * FROM tbl_products";
                $stmt = $pdo->prepare($sql);
                $stmt->execute();
            }



            // counting the rows
            $rowCount = $stmt->rowCount();
            // fetch results as an array
            $results = $stmt->fetchAll();

            if ($rowCount == 0) {
                echo "No search results found.";
            } else {
                foreach ($results as $product) {
                    $productID = $product['product_id'];
                    $productTitle = $product['product_title'];
                    $productDesc = $product['product_desc'];
                    $productImage = $product['product_image'];
                    $productPrice = $product['product_price'];
                    $productType = $product['product_type'];

                    echo '<div class="item">';
                    echo '<div class="product">';
                    echo '<img src="' . $productImage . '">';
                    echo '<h3>' . $productTitle . '</h3>';
                    echo '<p class="description">' . $productDesc . '<a href="item.php?product_id=' . $productID . '" class="readMore">Read More</a>' . '</p>';
                    echo '<span>Price: $' . $productPrice . '</span>';
                    echo '<br>';

            ?>
                    <button class="button-17" onclick="AddToCart( '<?php echo $productTitle, ',', $productImage, ',', $productPrice, ',', $productID ?>')">Buy</button>
            <?php
                    echo '</div>';
                    echo '</div>';
                }
            }
            ?>

        </div>
        <!-- The a section here takes the user to the top of the page -->
        <a href="#" id="topButton" onclick="scrollToTop()">TOP</a>
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
    <!-- FOOTER SECTION OF THE PRODUCTS PAGE -->
</body>

</html>