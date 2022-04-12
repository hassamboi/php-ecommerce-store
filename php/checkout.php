<?php
require('inc/connection.php');
require('inc/functions.php');

if(!isset($_SESSION['USER_ID'])) {
    header('location: login.php');
}

$session_user_id = $_SESSION['USER_ID'];
$orderNotes = '';
$total_price = 0;

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

$sql = "SELECT * FROM cart_items NATURAL JOIN products WHERE user_id = '$session_user_id'";
$result = mysqli_query($conn, $sql);
$cart_items = mysqli_fetch_all($result, MYSQLI_ASSOC);

if(!$cart_items) {
    header('location: cart.php');
}

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

    $sql = "INSERT INTO address(street_add, city, state, zip_code, user_id) VALUES
    ('$street', '$city', '$state', '$zip', '$session_user_id')";
    $result = mysqli_query($conn, $sql);

    $sql = "INSERT INTO billing_info(pay_method, card_no, exp_date, cvv, user_id) VALUES
    ('$payment', '$cardNum', '$expDate', '$cvv', '$session_user_id')";
    $result = mysqli_query($conn, $sql);

    header('location: checkout.php');
}

if(isset($_POST['place-order'])) {
    $total_price = get_safe_value($conn, $_POST['total-price']);
    $order_id = create_order($conn, $session_user_id, $total_price, $orderNotes);
    if($order_id === -1) {
        // order cannot be created
        header('location: order_error.php');
    } else {
        // order was created
        header('location: order_details.php');
    }
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - XIT</title>
    <script src="https://code.jquery.com/jquery-3.6.0.slim.min.js" integrity="sha256-u7e5khyithlIdTpu22PHhENmPcRdFiHRjhAuHcs05RI=" crossorigin="anonymous"></script>
    <link rel="icon" type="image/ico" href="../favicon/favicon.ico" />
    <link rel="stylesheet" href="../css/style.css" />
</head>

<body> 
    <?php include('inc/header.php'); ?>
    
    <section id="checkout">
        <div class="container">
            <div class="checkout-grid <?php if($user_address){echo 'checkout-grid-user';}?>">
                <?php if(!$user_address) { ?>
                <form method="POST" class="user-info-form">
                    <div>
                        <h2>Billing Details</h2>
                    </div>
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
                <?php } else {?>
                <div class="user-info">
                    <h2>Address Info</h2>
                    <?php 
                        echo '
                        <div><label style="display:block !important">Street Address:</label>'.$user_address['street_add'].'</div>
                        <div><label>City:</label>'.$user_address['city'].'</div>
                        <div><label>State</label>'.$user_address['state'].'</div>
                        <div><label>Zip Code:</label>'.$user_address['zip_code'].'</div>';
                    ?>
                </div>
                <div class="user-info">
                    <h2>Billing Details</h2>
                    <?php 
                        echo '
                        <div><label>Pay Method:</label>'.$user_billing['pay_method'].'</div>
                        <div><label>Card Number:</label>**************'.substr($user_billing['card_no'],12).'</div>
                        <div><label>Expiry Date:</label>'.$user_billing['exp_date'].'</div>';
                    ?>
                </div>
                <?php } ?> 
                <form class="order-notes-form">
                    <div>
                        <h2>Additional Information</h2>
                    </div>
                    <label for="order-notes">Order Notes (Optional)</label>
                    <textarea name="order-notes" id="order-notes" cols="30" rows="10"></textarea>
                    <div class="btn-width">
                        <button type="submit" name="order-notes-submit" id="order-notes-submit">Add The Notes</button>
                    </div>
                </form>
            </div>
            <div class="order-details">
                <div>
                    <h2>Your Order</h2>
                </div>
                <table class="cart-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                    
                        foreach($cart_items as $k=>$v) {
                            $total_price += $v['quantity']*$v['product_price'];
                            echo '
                            <tr>
                                <td class="cart-product-name">'.$v['product_name'].' &#215 '.$v['quantity'].'</td>
                                <td>$'.$v['quantity']*$v['product_price'].'</td>
                            </tr>';
                        }
                        ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Total</th>
                            <td>$<?php echo $total_price; ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="grid-2">
                <div></div>
                <form method="POST" class="place-order-btn">
                    <?php if($user_address) {?>
                        <input type="hidden" value="<?php echo $total_price ?>" name="total-price">
                        <input type="submit" value="Place Order" name="place-order">
                    <?php } else {?>
                        <input type="submit" value="Proceed To Checkout" disabled="disabled" class="disabled-btn">
                    <?php }?>
                </form>
            </div>
        </div>
    </section>

    <?php include('inc/footer.php'); ?>

    <script type="text/javascript">
        $(document).ready(function() {
            $("#order-notes-submit").click(function(){
                var ordernotes = $("#order-notes").val();

                $.ajax({
                    method: "GET",
                    url: "checkout.php",
                    data: {notes: ordernotes}
                })
            });
        });
    </script>
</body>

</html>