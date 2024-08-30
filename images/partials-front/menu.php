<?php
// Include constants.php for site URL and database connection
include('../config/constants.php');

// Start the session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <!-- Important to make website responsive -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurant Website</title>

    <!-- Link our CSS file -->
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
    <!-- Navbar Section Starts Here -->
    <section class="navbar">
        <div class="container">
            <div class="logo">
                <a href="#" title="Logo">
                    <img src="images/logo.png" alt="Restaurant Logo" class="img-responsive">
                </a>
            </div>

            <div class="menu text-right">
                <ul>
                    <?php if(isset($_SESSION['user'])): ?>
                        <!-- Links for logged-in users -->
                        <li>
                            <a href="<?php echo SITEURL; ?>user/index.php">Home</a>
                        </li>
                        <li>
                            <a href="<?php echo SITEURL; ?>user/categories.php">Categories</a>
                        </li>
                        <li>
                            <a href="<?php echo SITEURL; ?>user/foods.php">Foods</a>
                        </li>
                        <li>
                            <a href="#">Contact</a>
                        </li>
                        <li><a href="<?php echo SITEURL; ?>user/my-profile.php">My Profile</a></li>

                        <li>
                        <a href="<?php echo SITEURL; ?>user/cart.php">Cart
                        (<?php echo isset($_SESSION['cart']) ? count($_SESSION['cart']) : '0'; ?>)
                        </a>
                        </li>

                        <li>
                            <a href="<?php echo SITEURL; ?>user/logout.php">Logout</a>
                        </li>
                    <?php else: ?>
                        <!-- Links for guests -->
                        <li>
                            <a href="<?php echo SITEURL; ?>user/login.php">Login</a>
                        </li>
                        <li>
                            <a href="<?php echo SITEURL; ?>user/register.php">Register</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>

            <div class="clearfix"></div>
        </div>
    </section>
    <!-- Navbar Section Ends Here -->
