<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include('../config/constants.php');

if(isset($_POST['submit'])) {
    $food_id = $_POST['food_id'];
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;

    // Fetch food details from the database using $food_id
    $sql = "SELECT * FROM tbl_food WHERE id='$food_id' AND active='Yes'";
    $res = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($res);

    if ($row) {
        $food_title = $row['title'];
        $price = $row['price'];

        $cart_item = array(
            'id' => $food_id,
            'title' => $food_title,
            'price' => $price,
            'qty' => $quantity
        );

        // Check if the cart is already set
        if(isset($_SESSION['cart'])) {
            $item_found = false;

            // Iterate over the cart items
            foreach($_SESSION['cart'] as &$item) {
                if($item['id'] == $food_id) {
                    // Update the quantity if the item is already in the cart
                    $item['qty'] += $quantity;
                    $item_found = true;
                    break;
                }
            }

            // If the item was not found in the cart, add it as a new entry
            if(!$item_found) {
                $_SESSION['cart'][] = $cart_item;
            }
        } else {
            // If no cart exists, create a new cart array
            $_SESSION['cart'] = array($cart_item);
        }
    } else {
        // Handle case where food item is not found
        echo "<div class='error text-center'>Food item not found.</div>";
    }

    // Redirect back to menu or cart page
    header('Location: '.SITEURL.'user/cart.php');
    exit();
}
?>
