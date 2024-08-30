<?php
include('../config/constants.php');

// Start Session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is logged in or if OTP verification was completed
if (!isset($_SESSION['email'])) {
    header('Location: ' . SITEURL . 'user/register.php');
    exit();
}

// Initialize error variables
$register_error = "";

// Handle form submission
if (isset($_POST['submit'])) {
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $password = md5($_POST['password']); // Encrypt the password

    $email = $_SESSION['email'];

    // Save user data into the tbl_users table
    $sql = "INSERT INTO tbl_users (full_name, username, email, phone, address, password) VALUES ('$full_name', '$username', '$email', '$phone', '$address', '$password')";
    $res = mysqli_query($conn, $sql);

    if ($res) {
        // Clear OTP record from the database
        $sql_delete = "DELETE FROM tbl_email_verification WHERE email='$email'";
        mysqli_query($conn, $sql_delete);

        // User registered successfully
        $_SESSION['register'] = "<div class='success'>Registration successful. You can now log in.</div>";
        header('Location: ' . SITEURL . 'user/login.php');
        exit();
    } else {
        $register_error = "Failed to register user. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fill Information - Food Order System</title>
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>

<div class="login">
    <h1 class="text-center">Fill Your Information</h1>
    <br><br>

    <?php
    if (!empty($register_error)) {
        echo "<div class='error'>$register_error</div>";
    }
    ?>
    <br><br>

    <form action="" method="POST" class="text-center">
        Full Name: <br>
        <input type="text" name="full_name" placeholder="Enter Full Name" required><br><br>

        Username: <br>
        <input type="text" name="username" placeholder="Enter Username" required><br><br>

        Phone Number: <br>
        <input type="text" name="phone" placeholder="Enter Phone Number" required><br><br>

        Address: <br>
        <textarea name="address" placeholder="Enter Address" required></textarea><br><br>

        Password: <br>
        <input type="password" name="password" placeholder="Enter Password" required><br><br>

        <input type="submit" name="submit" value="Complete Registration" class="btn-primary">
        <br><br>
    </form>

    <p class="text-center">Already have an account? <a href="<?php echo SITEURL; ?>user/login.php">Login</a></p>
    <p class="text-center">Created By - <a href="https://www.linkedin.com/in/hemant-rajput-2b8a8123a/">Hemant</a></p>
</div>

</body>
</html>
