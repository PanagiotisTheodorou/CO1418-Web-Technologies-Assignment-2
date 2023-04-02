<?php
// This page will be used as a simplicity page, meaning that it will be used in all other pages so that
// The lines of code below are not used throughout the entire project

// assigning the database cridentials to php variables
$host = "vesta.uclan.ac.uk";
$username = "ptheodorou";
$password = "ADK7zivM";
$dbname = "ptheodorou";

// using PDO to prevent SQL injections
try {
    // execute the PDO to form a connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // return the result
    return $pdo;
} catch (PDOException $e) {
    // if there was a problem with the SQL then display appropriate error message
    die("Connection error: " . $e->getMessage());
}