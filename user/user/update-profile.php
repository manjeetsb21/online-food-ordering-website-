<?php
    // Include Constants for SITEURL and database connection
    include('../config/constants.php');

    // Start Session
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    // Check if user is logged in
    if (!isset($_SESSION['user'])) {
        // User is not logged in, redirect to login page
        header('Location: ' . SITEURL . 'user/login.php');
        exit();
    }

    // Get the logged-in user's data
    $username = $_SESSION['user'];

    // Fetch the current user data
    $sql = "SELECT full_name, username, email, phone, address FROM tbl_users WHERE username='$username'";
    $res = mysqli_query($conn, $sql);

    if ($res == TRUE) {
        $row = mysqli_fetch_assoc($res);
        $current_full_name = $row['full_name'];
        $current_email = $row['email'];
        $current_phone = $row['phone'];
        $current_address = $row['address'];
    } else {
        header('location:' . SITEURL . 'user/my-profile.php');
        exit();
    }

    // Initialize error variables
    $username_error = $email_error = $phone_error = "";

    // Handle form submission
    if (isset($_POST['submit'])) {
        $new_full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
        $new_email = mysqli_real_escape_string($conn, $_POST['email']);
        $new_phone = mysqli_real_escape_string($conn, $_POST['phone']);
        $new_address = mysqli_real_escape_string($conn, $_POST['address']);
        $new_username = mysqli_real_escape_string($conn, $_POST['username']);

        // Check if the new username is unique (if changed)
        if ($new_username !== $username) {
            $sql = "SELECT username FROM tbl_users WHERE username='$new_username'";
            $res = mysqli_query($conn, $sql);
            if (mysqli_num_rows($res) > 0) {
                $username_error = "Username already taken.";
            }
        }

        // Check if the new email is unique (if changed)
        if ($new_email !== $current_email) {
            $sql = "SELECT email FROM tbl_users WHERE email='$new_email'";
            $res = mysqli_query($conn, $sql);
            if (mysqli_num_rows($res) > 0) {
                $email_error = "Email ID already taken.";
            }
        }

        // Check if the new phone number is unique (if changed)
        if ($new_phone !== $current_phone) {
            $sql = "SELECT phone FROM tbl_users WHERE phone='$new_phone'";
            $res = mysqli_query($conn, $sql);
            if (mysqli_num_rows($res) > 0) {
                $phone_error = "Phone number already taken.";
            }
        }

        // If no errors, update the profile
        if (empty($username_error) && empty($email_error) && empty($phone_error)) {
            $sql = "UPDATE tbl_users SET
                        full_name='$new_full_name',
                        email='$new_email',
                        phone='$new_phone',
                        address='$new_address',
                        username='$new_username'
                    WHERE username='$username'";
            $res = mysqli_query($conn, $sql);

            if ($res == TRUE) {
                // Update session username if changed
                if ($new_username !== $username) {
                    $_SESSION['user'] = $new_username;
                }
                // Redirect to my-profile.php after updating
                header('Location: ' . SITEURL . 'user/my-profile.php');
                exit();
            } else {
                // Redirect back if there was an error updating
                header('Location: ' . SITEURL . 'user/update-profile.php');
                exit();
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile - Food Order System</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
    <!-- Include the menu -->
    <?php include('../partials-front/menu.php'); ?>

    <div class="container">
        <h2 class="text-center">Update Profile</h2>
        <br><br>

        <form action="" method="POST" class="order">
            <fieldset>
                <div class="order-label">Full Name:</div>
                <input type="text" name="full_name" value="<?php echo htmlspecialchars($current_full_name); ?>" required class="input-responsive">
                <div> </div>

                <div class="order-label">Username:</div>
                <input type="text" name="username" value="<?php echo htmlspecialchars($username); ?>" required class="input-responsive">
                <div class="error"><?php echo $username_error; ?></div>

                <div class="order-label">Email ID:</div>
                <input type="email" name="email" value="<?php echo htmlspecialchars($current_email); ?>" required class="input-responsive">
                <div class="error"><?php echo $email_error; ?></div>

                <div class="order-label">Phone Number:</div>
                <input type="text" name="phone" value="<?php echo htmlspecialchars($current_phone); ?>" required class="input-responsive">
                <div class="error"><?php echo $phone_error; ?></div>

                <div class="order-label">Address:</div>
                <input type="text" name="address" value="<?php echo htmlspecialchars($current_address); ?>" required class="input-responsive">
                <div> </div>


                <br><br>
                <input type="submit" name="submit" value="Update Profile" class="btn btn-primary">
            </fieldset>
        </form>
    </div>

    <!-- Include the footer -->
    <?php include('../partials-front/footer.php'); ?>
</body>
</html>
