<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include('../config/constants.php');

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    header('Location: ' . SITEURL . 'user/login.php');
    exit();
}

// Check if the key is provided in the query string
if (isset($_GET['key'])) {
    $key = intval($_GET['key']); // Ensure the key is an integer

    // Check if the cart is set
    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
        // Check if the key exists in the cart
        if (isset($_SESSION['cart'][$key])) {
            // Remove the item from the cart
            unset($_SESSION['cart'][$key]);

            // Reindex the cart array to fix the index gaps
            $_SESSION['cart'] = array_values($_SESSION['cart']);
        }
    }
}

// Redirect back to the cart page
header('Location: ' . SITEURL . 'user/cart.php');
exit();
?>
