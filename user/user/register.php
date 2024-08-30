<?php
include('../config/constants.php');
include('../config/otp_functions.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Start Session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Initialize error message
$register_error = "";

// Handle form submission
if (isset($_POST['submit'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    // Generate OTP
    $otp = rand(100000, 999999);
    $_SESSION['otp'] = $otp;
    $_SESSION['email'] = $email;

    // Store OTP in the database
    $sql = "INSERT INTO tbl_email_verification (email, otp) VALUES ('$email', '$otp')";
    $res = mysqli_query($conn, $sql);

    if ($res) {
        // Send OTP using PHPMailer
        require '../vendor/autoload.php'; // Assuming PHPMailer is installed via Composer

        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com'; // Replace with your SMTP host
            $mail->SMTPAuth   = true;
            $mail->Username   = 'your@gmail.com'; // Replace with your SMTP username
            $mail->Password   = 'your_password'; // Replace with your SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            //Recipients
            $mail->setFrom('your_email@example.com', 'Food Order System');
            $mail->addAddress($email); // Add a recipient

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Your OTP for Food Order System';
            $mail->Body    = "Your OTP is: $otp";

            $mail->send();
            // Redirect to verify-otp.php
            header('Location: ' . SITEURL . 'user/verify-otp.php');
            exit();
        } catch (Exception $e) {
            $register_error = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        $register_error = "Failed to generate OTP. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Food Order System</title>
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>

<div class="login">
    <h1 class="text-center">Register</h1>
    <br><br>

    <?php
    if (!empty($register_error)) {
        echo "<div class='error'>$register_error</div>";
    }
    ?>
    <br><br>

    <form action="" method="POST" class="text-center">
        Email: <br>
        <input type="email" name="email" placeholder="Enter Email" required><br><br>

        <input type="submit" name="submit" value="Register" class="btn-primary">
        <br><br>
    </form>

    <p class="text-center">Already have an account? <a href="<?php echo SITEURL; ?>user/login.php">Login</a></p>
    <p class="text-center">Created By - <a href="https://www.linkedin.com/in/hemant-rajput-2b8a8123a/">Hemant</a></p>
</div>

</body>
</html>
