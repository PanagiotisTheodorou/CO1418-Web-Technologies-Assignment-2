<?php

// This file does validation and sign up (send the user info to the database)
// This php file is used to do the server side validation, the following lines of code raise an error when: 

// the user hasn't inputed a username
if (empty($_POST["username"])) {
    die("Name is required");
}

// the email provided is invalid
if ( ! filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
    die("Email is not valid");
}

// the password is less than 8 charachters long
if (strlen($_POST["password"] < 8)) {
    die ("Password needs to be at least 8 characters long");
}

// the password does not have a letter inside
if ( ! preg_match("/[a-z]/i", $_POST["password"])) {
    die("Password must contain at least one letter");
}

// the password does not have a number inside
if ( ! preg_match("/[0-9]/i", $_POST["password"])) {
    die("Password must contain at least one number");
}

// the 2 passwords do not match
if ($_POST["password"] !== $_POST["password_confirmation"]) {
    die ("Password do not match");
}

// create the hashed password
$password_hash = password_hash($_POST["password"], PASSWORD_DEFAULT);

// require the database info
require __DIR__ . "/database.php";

// The following lines of code are used to check if the email allready exists or not
$email = $_POST["email"];
$stmt = $pdo->prepare("SELECT COUNT(*) FROM tbl_users WHERE user_email=?");
$stmt->execute([$email]);
$count = $stmt->fetchColumn();

// if the count of a certain email is more than one then it allready exists
if ($count > 0) {
    // if email exists, display error message
    die("Email already exists");
}else {
    // if the email does not exist then proceed to insert the info into the table
    $sql = "INSERT INTO tbl_users (user_full_name, user_address, user_email, user_pass) 
    VALUES (?, ?, ?, ?)";
    try {
        // execute the PDO
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$_POST["username"], $_POST["address"], $_POST["email"], $password_hash]);
        header("Location: index.php"); // redirect the user
        exit;
    } catch (PDOException $e) {
        // If there was an error in the SQL then display apporpriate error message
        die ("SQL error: " . $e->getMessage());
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pdo = require __DIR__ . "/database.php";

    // Check if the email and password fields are set
    if (isset($_POST["email"]) && isset($_POST["password"])) {
        $email = $_POST["email"];
        $password = $_POST["password"];

        // Check if the email and password match the database records
        $stmt = $pdo->prepare("SELECT * FROM tbl_users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user["password"])) {
            // If the email and password match, create a session for the user
            $_SESSION["user_id"] = $user["user_id"];
            header("Location:index.php");
            exit;
        }
    }
}