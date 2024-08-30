<?php
include('../config/constants.php');

// Start Session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if email is in session
if (!isset($_SESSION['email'])) {
    header('Location: ' . SITEURL . 'user/register.php');
    exit();
}

// Initialize error message
$error_message = "";

// Handle OTP verification
if (isset($_POST['verify'])) {
    $input_otp = mysqli_real_escape_string($conn, $_POST['otp']);
    $email = $_SESSION['email'];

    // Check if the OTP matches
    $sql = "SELECT otp FROM tbl_email_verification WHERE email='$email' ORDER BY created_at DESC LIMIT 1";
    $res = mysqli_query($conn, $sql);

    if ($res && mysqli_num_rows($res) > 0) {
        $row = mysqli_fetch_assoc($res);
        $stored_otp = $row['otp'];

        if ($input_otp === $stored_otp) {
            // OTP is correct, redirect to fill-info page
            header('Location: ' . SITEURL . 'user/fill-info.php');
            exit();
        } else {
            // OTP is incorrect
            $error_message = "Invalid OTP. Please try again.";
        }
    } else {
        $error_message = "No OTP found for this email.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP - Food Order System</title>
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>

<div class="login">
    <h1 class="text-center">Verify OTP</h1>
    <br><br>

    <?php
    if (!empty($error_message)) {
        echo "<div class='error'>$error_message</div>";
    }
    ?>
    <br><br>

    <form action="" method="POST" class="text-center">
        <div>
            <p>An OTP has been sent to your email: <?php echo htmlspecialchars($_SESSION['email']); ?></p>
        </div>
        OTP: <br>
        <input type="text" name="otp" placeholder="Enter OTP" required><br><br>
        <input type="submit" name="verify" value="Verify OTP" class="btn-primary">
        <br><br>
    </form>

    <p class="text-center">If you did not receive the OTP, <a href="<?php echo SITEURL; ?>user/register.php">register again</a>.</p>
    <p class="text-center">Created By - <a href="https://www.linkedin.com/in/hemant-rajput-2b8a8123a/">Hemant</a></p>
</div>

</body>
</html>
