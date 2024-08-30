<?php include('../config/constants.php'); ?>

<html>
    <head>
        <title>Login - Food Order System</title>
        <link rel="stylesheet" href="../css/admin.css">
    </head>

    <body>

        <div class="login">
            <h1 class="text-center">Login</h1>
            <br><br>

            <?php
                if(isset($_SESSION['login']))
                {
                    echo $_SESSION['login'];
                    unset($_SESSION['login']);
                }

                if(isset($_SESSION['no-login-message']))
                {
                    echo $_SESSION['no-login-message'];
                    unset($_SESSION['no-login-message']);
                }
            ?>
            <br><br>

            <!-- Login Form Starts Here -->
            <form action="" method="POST" class="text-center">
                Username/Email/Phone: <br>
                <input type="text" name="identifier" placeholder="Enter Username, Email, or Phone Number" required><br><br>

                Password: <br>
                <input type="password" name="password" placeholder="Enter Password" required><br><br>

                <input type="submit" name="submit" value="Login" class="btn-primary">
                <br><br>
            </form>
            <!-- Login Form Ends Here -->

            <p class="text-center">Don't have an account? <a href="<?php echo SITEURL; ?>user/register.php">Sign Up</a></p>
            <p class="text-center">Created By - <a href="https://www.linkedin.com/in/hemant-rajput-2b8a8123a/">Hemant</a></p>
        </div>

    </body>
</html>

<?php

    // Check whether the Submit Button is Clicked or Not
    if(isset($_POST['submit']))
    {
        // Process for Login
        $identifier = mysqli_real_escape_string($conn, $_POST['identifier']);
        $raw_password = md5($_POST['password']);
        $password = mysqli_real_escape_string($conn, $raw_password);

        // SQL to check whether the password exists for any user
        $sql_password_check = "SELECT * FROM tbl_users WHERE password='$password'";
        $res_password_check = mysqli_query($conn, $sql_password_check);
        $count_password_check = mysqli_num_rows($res_password_check);

        // SQL to check whether the user with email, phone, or username exists
        $sql_check = "SELECT * FROM tbl_users WHERE (username='$identifier' OR email='$identifier' OR phone='$identifier')";
        $res_check = mysqli_query($conn, $sql_check);
        $count_check = mysqli_num_rows($res_check);

        if($count_check == 1) {
            // User exists, now check for password match
            $row = mysqli_fetch_assoc($res_check);

            if($row['password'] == $password) {
                // Password matched
                $_SESSION['login'] = "<div class='success'>Login Successful.</div>";
                $_SESSION['user'] = $row['username']; // Store username in session

                // Redirect to Home Page/Dashboard
                header('location:'.SITEURL.'user/index.php');
            } else {
                // Password does not match
                $_SESSION['login'] = "<div class='error text-center'>Password is incorrect.</div>";
                header('location:'.SITEURL.'user/login.php');
            }
        } elseif ($count_password_check == 1) {
            // Password is correct but identifier doesn't exist
            $_SESSION['login'] = "<div class='error text-center'>Username, Email, or Phone Number is incorrect.</div>";
            header('location:'.SITEURL.'user/login.php');
        } else {
            // User does not exist
            $_SESSION['login'] = "<div class='error text-center'>User does not exist. Please register first.</div>";
            header('location:'.SITEURL.'user/register.php');
        }
    }

?>
