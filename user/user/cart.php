<?php
include('../partials-front/menu.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    header('Location: ' . SITEURL . 'user/login.php');
    exit();
}

// Check if cart is not empty
if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    $total = 0;
    ?>

    <div class="container">
        <h2 class="text-center">Your Cart</h2>
        <table class="tbl-full">
            <tr>
                <th>Food Name</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Total</th>
                <th>Action</th>
            </tr>

            <?php
            foreach ($_SESSION['cart'] as $key => $item) {
                // Check if necessary keys exist before using them
                $title = isset($item['title']) ? $item['title'] : 'Unknown';
                $qty = isset($item['qty']) ? $item['qty'] : 0;
                $price = isset($item['price']) ? $item['price'] : 0;

                $total += $price * $qty;
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($title); ?></td>
                    <td><?php echo htmlspecialchars($qty); ?></td>
                    <td>₹<?php echo htmlspecialchars($price); ?></td>
                    <td>₹<?php echo htmlspecialchars($price * $qty); ?></td>
                    <td><a href="remove-from-cart.php?key=<?php echo $key; ?>" class="btn-danger">Remove</a></td>
                </tr>
                <?php
            }
            ?>

            <tr>
                <td colspan="3">Total</td>
                <td>₹<?php echo htmlspecialchars($total); ?></td>
                <td><a href="checkout.php" class="btn-primary">Checkout</a></td>
            </tr>
        </table>
    </div>
    <?php
} else {
    echo "<div class='error text-center'>Your cart is empty.</div>";
}
?>

<?php include('../partials-front/footer.php'); ?>
