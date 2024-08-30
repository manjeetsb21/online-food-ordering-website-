<?php
include('../config/constants.php');

// Check if user is logged in
if(!isset($_SESSION['user'])) {
    // User is not logged in, redirect to registration page
    header('Location: ' . SITEURL . 'user/register.php');
    exit();
}

// Regenerate session ID to prevent session hijacking
session_regenerate_id(true);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Food Order System</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

    <?php include('../partials-front/menu.php'); ?>

    <!-- Food Search Section Starts Here -->
    <section class="food-search text-center">
        <div class="container">
            <form action="<?php echo htmlspecialchars(SITEURL); ?>user/food-search.php" method="POST">
                <input type="search" name="search" placeholder="Search for Food.." required>
                <input type="submit" name="submit" value="Search" class="btn btn-primary">
            </form>
        </div>
    </section>
    <!-- Food Search Section Ends Here -->

    <?php
        if(isset($_SESSION['order'])) {
            echo htmlentities($_SESSION['order']);
            unset($_SESSION['order']);
        }
    ?>

    <!-- Categories Section Starts Here -->
    <section class="categories">
        <div class="container">
            <h2 class="text-center">Explore Foods</h2>

            <?php
                // Create SQL Query to Display Categories from Database
                $sql = "SELECT * FROM tbl_category WHERE active='Yes' AND featured='Yes' LIMIT 3";
                // Execute the Query
                $res = mysqli_query($conn, $sql);
                // Count rows to check whether the category is available or not
                $count = mysqli_num_rows($res);

                if($count > 0) {
                    // Categories Available
                    while($row = mysqli_fetch_assoc($res)) {
                        // Get the Values like id, title, image_name
                        $id = htmlentities($row['id']);
                        $title = htmlentities($row['title']);
                        $image_name = htmlentities($row['image_name']);
                        ?>

                        <a href="<?php echo htmlspecialchars(SITEURL); ?>user/category-foods.php?category_id=<?php echo $id; ?>">
                            <div class="box-3 float-container">
                                <?php
                                    // Check whether Image is available or not
                                    if($image_name == "") {
                                        // Display Message
                                        echo "<div class='error'>Image not Available</div>";
                                    } else {
                                        // Image Available
                                        ?>
                                        <img src="<?php echo htmlspecialchars(SITEURL); ?>images/category/<?php echo $image_name; ?>" alt="<?php echo $title; ?>" class="img-responsive img-curve">
                                        <?php
                                    }
                                ?>

                                <h3 class="float-text text-white"><?php echo $title; ?></h3>
                            </div>
                        </a>

                        <?php
                    }
                } else {
                    // Categories not Available
                    echo "<div class='error'>Category not Added.</div>";
                }
            ?>

            <div class="clearfix"></div>
        </div>
    </section>
    <!-- Categories Section Ends Here -->

    <!-- Food Menu Section Starts Here -->
    <section class="food-menu">
        <div class="container">
            <h2 class="text-center">Food Menu</h2>

            <?php
                // Getting Foods from Database that are active and featured
                // SQL Query
                $sql2 = "SELECT * FROM tbl_food WHERE active='Yes' AND featured='Yes' LIMIT 6";
                // Execute the Query
                $res2 = mysqli_query($conn, $sql2);
                // Count Rows
                $count2 = mysqli_num_rows($res2);

                // Check whether food is available or not
                if($count2 > 0) {
                    // Food Available
                    while($row = mysqli_fetch_assoc($res2)) {
                        // Get all the values
                        $id = htmlentities($row['id']);
                        $title = htmlentities($row['title']);
                        $price = htmlentities($row['price']);
                        $description = htmlentities($row['description']);
                        $image_name = htmlentities($row['image_name']);
                        ?>

                        <div class="food-menu-box">
                            <div class="food-menu-img">
                                <?php
                                    // Check whether image is available or not
                                    if($image_name == "") {
                                        // Image not Available
                                        echo "<div class='error'>Image not available.</div>";
                                    } else {
                                        // Image Available
                                        ?>
                                        <img src="<?php echo htmlspecialchars(SITEURL); ?>images/food/<?php echo $image_name; ?>" alt="<?php echo $title; ?>" class="img-responsive img-curve">
                                        <?php
                                    }
                                ?>
                            </div>

                            <div class="food-menu-desc">
                                <h4><?php echo $title; ?></h4>
                                <p class="food-price">₹<?php echo $price; ?></p>
                                <p class="food-detail">
                                    <?php echo $description; ?>
                                </p>
                                <br>

                                <!-- Add to Cart Form -->
                                <form action="<?php echo htmlspecialchars(SITEURL); ?>user/add-to-cart.php" method="POST" class="add-to-cart-form">
                                    <input type="hidden" name="food_id" value="<?php echo $id; ?>">
                                    <label for="quantity_<?php echo $id; ?>">Quantity:</label>
                                    <input type="number" name="quantity" id="quantity_<?php echo $id; ?>" min="1" value="1" required>
                                    <input type="submit" name="submit" value="Add to Cart" class="btn btn-primary">
                                </form>
                            </div>
                        </div>

                        <?php
                    }
                } else {
                    // Food Not Available
                    echo "<div class='error'>Food not available.</div>";
                }
            ?>

            <div class="clearfix"></div>
        </div>

        <p class="text-center">
            <a href="#">See All Foods</a>
        </p>
    </section>
    <!-- Food Menu Section Ends Here -->

    <?php include('../partials-front/footer.php'); ?>

</body>
</html>
