<?php include('../partials-front/menu.php'); ?>

<?php
// Initialize the session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include('../config/constants.php');

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    header('Location: ' . SITEURL . 'user/login.php');
    exit();
}

if (isset($_GET['food_id'])) {
    $food_id = mysqli_real_escape_string($conn, $_GET['food_id']);
    $sql = "SELECT * FROM tbl_food WHERE id=$food_id";
    $res = mysqli_query($conn, $sql);
    $count = mysqli_num_rows($res);

    if ($count == 1) {
        $row = mysqli_fetch_assoc($res);
        $title = $row['title'];
        $price = $row['price'];
        $image_name = $row['image_name'];
    } else {
        header('location:' . SITEURL);
    }
} else {
    header('location:' . SITEURL);
}

// Get logged-in user details from session
$username = $_SESSION['user'];
$sql_user = "SELECT full_name, email, phone, address FROM tbl_users WHERE username='$username'";
$res_user = mysqli_query($conn, $sql_user);
$row_user = mysqli_fetch_assoc($res_user);

$full_name = $row_user['full_name'];
$phone = $row_user['phone'];
$email = $row_user['email'];
$address = $row_user['address']; // Fetch the address from profile
?>

<section class="food-search">
    <div class="container">
        <h2 class="text-center text-white">Fill this form to confirm your order.</h2>

        <form action="" method="POST" class="order">
            <fieldset>
                <legend>Selected Food</legend>

                <div class="food-menu-img">
                    <?php
                    if ($image_name == "") {
                        echo "<div class='error'>Image not Available.</div>";
                    } else {
                        ?>
                        <img src="<?php echo SITEURL; ?>images/food/<?php echo $image_name; ?>" alt="<?php echo $title; ?>" class="img-responsive img-curve">
                        <?php
                    }
                    ?>
                </div>

                <div class="food-menu-desc">
                    <h3><?php echo $title; ?></h3>
                    <input type="hidden" name="food" value="<?php echo $title; ?>">
                    <p class="food-price">₹<?php echo $price; ?></p>
                    <input type="hidden" name="price" value="<?php echo $price; ?>">

                    <div class="order-label">Quantity</div>
                    <input type="number" name="qty" class="input-responsive" value="1" required>
                </div>
            </fieldset>

            <fieldset>
                <legend>Delivery Details</legend>
                <div class="order-label">Full Name</div>
                <input type="text" name="full-name" value="<?php echo $full_name; ?>" class="input-responsive" readonly>

                <div class="order-label">Phone Number</div>
                <input type="tel" name="contact" value="<?php echo $phone; ?>" class="input-responsive" readonly>

                <div class="order-label">Email</div>
                <input type="email" name="email" value="<?php echo $email; ?>" class="input-responsive" readonly>

                <div class="order-label">Address</div>
                <textarea name="address" rows="10" class="input-responsive" required><?php echo $address; ?></textarea>

                <input type="submit" name="add_to_cart" value="Add to Cart" class="btn btn-primary">
                <a href="<?php echo SITEURL; ?>user/cart.php" class="btn btn-secondary">View Cart</a>
            </fieldset>
        </form>
    </div>
</section>

<?php include('../partials-front/footer.php'); ?>
