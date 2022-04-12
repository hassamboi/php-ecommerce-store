<?php 
require('inc/connection.php');
require('inc/functions.php');

if(!isset($_SESSION['USER_ID'])) {
    header('location: login.php');
}

$session_user_id = $_SESSION['USER_ID'];

if(isset($_GET['order-notes'])) {
    $orderNotes = $_GET['order-notes'];
}

// check if user has updated their address and billing info
$sql = "SELECT * FROM address WHERE user_id = '$session_user_id'";
$result = mysqli_query($conn, $sql);
$user_address = mysqli_fetch_assoc($result);

$sql = "SELECT * FROM billing_info WHERE user_id = '$session_user_id'";
$result = mysqli_query($conn, $sql);
$user_billing = mysqli_fetch_assoc($result);

if(isset($_POST['update-address'])) {
    // when the update address btn is clicked
    $street = get_safe_value($conn, $_POST['street']);
    $zip = get_safe_value($conn, $_POST['zip']);
    $city = get_safe_value($conn, $_POST['city']);
    $state = get_safe_value($conn, $_POST['state']);
    $payment = get_safe_value($conn, $_POST['payment']);
    $cardNum = get_safe_value($conn, $_POST['card-no']);
    $expDate = get_safe_value($conn, $_POST['exp-date']);
    $cvv = get_safe_value($conn, $_POST['exp-date']);
    $cvv = md5($cvv);

    $sql = "CALL update_user_address('$street', '$city', '$state', '$zip', '$session_user_id')";
    $result = mysqli_query($conn, $sql);

    $sql = "CALL update_user_billing_info('$payment', '$cardNum', '$expDate', '$cvv', '$session_user_id')";
    $result = mysqli_query($conn, $sql);

    header('location: user_profile.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile - XIT</title>
    <link rel="icon" type="image/ico" href="../favicon/favicon.ico" />
    <link rel="stylesheet" href="../css/style.css" />
</head>
<body>
    <?php include('inc/header.php'); ?>

    <section id="user-profile">
        <div class="container">
            <div class="shop-title">
                <a href="user_profile.php"><h1 style="width: 11rem !important;">User Information</h1></a>
                <!-- add path -->
            </div>
            <?php if(!$user_address || !$user_billing) {
                    echo '<div class="failure" style="margin-bottom: 15rem">No information to show, Update your address from below or on the checkout page.</div>';
                } else {?>
            <div class="checkout-grid grid-2 address-info">
                
                <div class="user-info">
                    <h2 class="order-id">Address Info</h2>
                    <?php 
                        echo '
                        <div><label style="display:block !important">Street Address:</label>'.$user_address['street_add'].'</div>
                        <div><label>City:</label>'.$user_address['city'].'</div>
                        <div><label>State</label>'.$user_address['state'].'</div>
                        <div><label>Zip Code:</label>'.$user_address['zip_code'].'</div>';
                    ?>
                </div>
                <div class="user-info">
                    <h2 class="order-id">Billing Details</h2>
                    <?php 
                        echo '
                        <div><label>Pay Method:</label>'.$user_billing['pay_method'].'</div>
                        <div><label>Card Number:</label>**************'.substr($user_billing['card_no'],12).'</div>
                        <div><label>Expiry Date:</label>'.$user_billing['exp_date'].'</div>';
                    ?>
                </div>
                
            </div>
            <?php }?>
            <div class="dropdown">
                <div class="trigger">
                    <span class="order-id">Update Address</span>  
                </div>
                <div class="content">
                    <div class="checkout-grid" style="width: 50%; margin:auto;">
                        <form method="POST" class="user-info-form">
                            <h3>Address</h3>
                            <div>
                                <label for="street">Street address</label>
                                <input type="text" name="street" required>
                            </div>
                            <div>
                                <label for="zip">Postcode / ZIP</label>
                                <input type="text" name="zip" required>
                            </div>
                            <div>
                                <label for="city">Town / City</label>
                                <input type="text" name="city" required>
                            </div>
                            <div>
                                <label for="state">State</label>
                                <input type="text" name="state" required>
                            </div>
                            <h3>Credit Card</h3>
                            <div>
                                <label for="payment">Payment method</label>
                                <select id="payment" name="payment">
                                    <option>VISA</option>
                                    <option>MASTER'S CARD</option>
                                    <option>AMERICAN EXPRESS</option>
                                </select>
                            </div>
                            <div>
                                <label for="card-no">Card number</label>
                                <input type="text" name="card-no" maxlength="16" required>
                            </div>
                            <div class="grid-2">
                                <div>
                                    <label for="exp-date">Expiry date</label>
                                    <input type="text" name="exp-date" placeholder="MM/YY" maxlength="5" required>
                                </div>
                                <div>
                                    <label for="cvv">CVV</label>
                                    <input type="password" name="cvv" maxlength="3" required>
                                </div>
                            </div>
                            <div class="btn-width">
                                <input type="submit" value="Update Address" name="update-address">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
   
    <?php include('inc/footer.php'); ?>
    <script type="text/javascript" src="../js/dropdown.js"></script>
</body>
</html>