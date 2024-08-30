<?php
    // Include Constants for SITEURL
    include('../config/constants.php');

    // Start Session
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    // Check if user is logged in
    if(!isset($_SESSION['user'])) {
        // User is not logged in, redirect to login page
        header('Location: ' . SITEURL . 'user/login.php');
        exit();
    }

    include('../partials-front/menu.php');

    // Get the logged-in user's data from the database
    $username = $_SESSION['user'];

    $sql = "SELECT full_name, username, email, phone, address FROM tbl_users WHERE username='$username'";
    $res = mysqli_query($conn, $sql);

    if($res == TRUE) {
        $row = mysqli_fetch_assoc($res);

        $full_name = $row['full_name'];
        $username = $row['username'];
        $email = $row['email'];
        $phone = $row['phone'];
        $address = $row['address'];
    } else {
        // If user data could not be retrieved, redirect to an error page or handle accordingly
        $_SESSION['no-user'] = "<div class='error'>User not found.</div>";
        header('location:' . SITEURL . 'user/index.php');
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - Food Order System</title>
    <link rel="stylesheet" href="../css/admin.css">
</head>

<body>
    <div class="container">
        <h1 class="text-center">My Profile</h1>
        <br><br>

        <table class="tbl-30">
            <tr>
                <td>Full Name:</td>
                <td><?php echo htmlspecialchars($full_name); ?></td>
            </tr>

            <tr>
                <td>Username:</td>
                <td><?php echo htmlspecialchars($username); ?></td>
            </tr>

            <tr>
                <td>Email ID:</td>
                <td><?php echo htmlspecialchars($email); ?></td>
            </tr>

            <tr>
                <td>Phone Number:</td>
                <td><?php echo htmlspecialchars($phone); ?></td>
            </tr>

            <tr>
                <td>Address:</td>
                <td><?php echo htmlspecialchars($address); ?></td>
            </tr>
        </table>

        <br><br>
        <a href="<?php echo SITEURL; ?>user/update-profile.php" class="btn-primary">Update Profile</a>
    </div>
</body>
</html>

<?php include('../partials-front/footer.php'); ?>
