<?php include('partials/menu.php'); ?>

<div class="main-content">
    <div class="wrapper">
        <h1>Update Order</h1>
        <br><br>

        <?php
            // Check whether id is set or not
            if(isset($_GET['id']))
            {
                // Get the Order Details
                $id = $_GET['id'];

                // SQL Query to get the order details
                $sql = "SELECT * FROM tbl_orders WHERE id=$id";
                // Execute Query
                $res = mysqli_query($conn, $sql);
                // Count Rows
                $count = mysqli_num_rows($res);

                if($count == 1)
                {
                    // Detail Available
                    $row = mysqli_fetch_assoc($res);

                    $username = $row['username'];
                    $full_name = $row['full_name'];
                    $phone = $row['phone'];
                    $email = $row['email'];
                    $address = $row['address'];
                    $total_amount = $row['total_amount'];
                    $order_date = $row['order_date'];
                    $status = $row['status'];
                }
                else
                {
                    // Detail not Available
                    // Redirect to Manage Order
                    header('location:' . SITEURL . 'admin/manage-order.php');
                    exit();
                }
            }
            else
            {
                // Redirect to Manage Order Page
                header('location:' . SITEURL . 'admin/manage-order.php');
                exit();
            }
        ?>

        <form action="" method="POST">
            <table class="tbl-30">
                <tr>
                    <td>Username</td>
                    <td><b><?php echo htmlspecialchars($username); ?></b></td>
                </tr>

                <tr>
                    <td>Full Name</td>
                    <td><b><?php echo htmlspecialchars($full_name); ?></b></td>
                </tr>

                <tr>
                    <td>Phone</td>
                    <td><b><?php echo htmlspecialchars($phone); ?></b></td>
                </tr>

                <tr>
                    <td>Email</td>
                    <td><b><?php echo htmlspecialchars($email); ?></b></td>
                </tr>

                <tr>
                    <td>Address</td>
                    <td><b><?php echo htmlspecialchars($address); ?></b></td>
                </tr>

                <tr>
                    <td>Total Amount</td>
                    <td><b>₹<?php echo htmlspecialchars($total_amount); ?></b></td>
                </tr>

                <tr>
                    <td>Order Date</td>
                    <td><b><?php echo htmlspecialchars($order_date); ?></b></td>
                </tr>

                <tr>
                    <td>Status</td>
                    <td>
                        <select name="status">
                            <option <?php if($status == "Ordered") { echo "selected"; } ?> value="Ordered">Ordered</option>
                            <option <?php if($status == "On Delivery") { echo "selected"; } ?> value="On Delivery">On Delivery</option>
                            <option <?php if($status == "Delivered") { echo "selected"; } ?> value="Delivered">Delivered</option>
                            <option <?php if($status == "Cancelled") { echo "selected"; } ?> value="Cancelled">Cancelled</option>
                        </select>
                    </td>
                </tr>

                <tr>
                    <td colspan="2">
                        <input type="hidden" name="id" value="<?php echo $id; ?>">
                        <input type="submit" name="submit" value="Update Order" class="btn-secondary">
                    </td>
                </tr>
            </table>
        </form>

        <?php
            // Check whether Update Button is Clicked or Not
            if(isset($_POST['submit']))
            {
                // Get All the Values from Form
                $id = $_POST['id'];
                $status = $_POST['status'];

                // Update the Order in tbl_orders
                $sql2 = "UPDATE tbl_orders SET
                    status = '$status'
                    WHERE id=$id
                ";

                // Execute the Query
                $res2 = mysqli_query($conn, $sql2);

                // Check whether update or not
                // And Redirect to Manage Order with Message
                if($res2 == true)
                {
                    // Updated
                    $_SESSION['update'] = "<div class='success'>Order Updated Successfully.</div>";
                    header('location:' . SITEURL . 'admin/manage-order.php');
                    exit();
                }
                else
                {
                    // Failed to Update
                    $_SESSION['update'] = "<div class='error'>Failed to Update Order.</div>";
                    header('location:' . SITEURL . 'admin/manage-order.php');
                    exit();
                }
            }
        ?>
    </div>
</div>

<?php include('partials/footer.php'); ?>
