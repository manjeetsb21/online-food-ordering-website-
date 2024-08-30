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

// Get logged-in user details from session
$username = $_SESSION['user'];

// Use prepared statements for security
$stmt = $conn->prepare("SELECT full_name, email, phone, address FROM tbl_users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$res_user = $stmt->get_result();
$row_user = $res_user->fetch_assoc();

$full_name = $row_user['full_name'];
$phone = $row_user['phone'];
$email = $row_user['email'];
$address = $row_user['address']; // Fetch the address from profile

// Check if cart is empty
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header('Location: ' . SITEURL);
    exit();
}

// Handle order submission
if (isset($_POST['submit'])) {
    // Get order details
    $order_date = date("Y-m-d H:i:s");
    $order_status = "Ordered"; // Set initial status as Ordered
    $total_amount = 0;

    // Calculate total amount
    foreach ($_SESSION['cart'] as $item) {
        $total_amount += $item['price'] * $item['qty'];
    }

    // Save order details into the database using prepared statements
    $stmt = $conn->prepare("INSERT INTO tbl_orders (username, full_name, phone, email, address, total_amount, order_date, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssdss", $username, $full_name, $phone, $email, $address, $total_amount, $order_date, $order_status);
    $res_order = $stmt->execute();

    if ($res_order) {
        // Get the last inserted order ID
        $order_id = $conn->insert_id;

        // Save each cart item to the order details table using prepared statements
        $stmt = $conn->prepare("INSERT INTO tbl_order_details (order_id, food_name, qty, price, total) VALUES (?, ?, ?, ?, ?)");
        foreach ($_SESSION['cart'] as $item) {
            $food_name = $item['title'];
            $qty = $item['qty'];
            $price = $item['price'];
            $total = $qty * $price;

            $stmt->bind_param("isidd", $order_id, $food_name, $qty, $price, $total);
            $stmt->execute();
        }

        // Clear the cart
        unset($_SESSION['cart']);

        echo "<div class='success text-center'>Order placed successfully.</div>";
    } else {
        echo "<div class='error text-center'>Failed to place order.</div>";
    }
}
?>

<section class="checkout">
    <div class="container">
        <h2 class="text-center">Checkout</h2>

        <form action="" method="POST">
            <fieldset>
                <legend>Delivery Details</legend>
                <div class="order-label">Full Name</div>
                <input type="text" name="full_name" value="<?php echo htmlspecialchars($full_name); ?>" class="input-responsive" readonly>

                <div class="order-label">Phone Number</div>
                <input type="tel" name="phone" value="<?php echo htmlspecialchars($phone); ?>" class="input-responsive" readonly>

                <div class="order-label">Email</div>
                <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" class="input-responsive" readonly>

                <div class="order-label">Address</div>
                <textarea name="address" rows="10" class="input-responsive" readonly><?php echo htmlspecialchars($address); ?></textarea>
            </fieldset>

            <fieldset>
                <legend>Cart Items</legend>
                <table class="tbl-full">
                    <tr>
                        <th>Food Name</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Total</th>
                    </tr>

                    <?php
                    $total = 0;
                    foreach ($_SESSION['cart'] as $item) {
                        $total += $item['price'] * $item['qty'];
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['title']); ?></td>
                            <td><?php echo htmlspecialchars($item['qty']); ?></td>
                            <td>₹<?php echo htmlspecialchars($item['price']); ?></td>
                            <td>₹<?php echo htmlspecialchars($item['price'] * $item['qty']); ?></td>
                        </tr>
                        <?php
                    }
                    ?>
                    <tr>
                        <td colspan="3">Total</td>
                        <td>₹<?php echo $total; ?></td>
                    </tr>
                </table>
            </fieldset>

            <input type="submit" name="submit" value="Place Order" class="btn btn-primary">
        </form>
    </div>
</section>

<?php include('../partials-front/footer.php'); ?>
