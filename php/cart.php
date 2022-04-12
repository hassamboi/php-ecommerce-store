<?php
require('inc/connection.php');
require('inc/functions.php');

$userId = $_SESSION['USER_ID'];

$sql = "SELECT * FROM cart_items NATURAL JOIN products WHERE user_id = '$userId'";
$result = mysqli_query($conn, $sql);
$cart_items = mysqli_fetch_all($result, MYSQLI_ASSOC);
$total_price = 0;

if(isset($_POST['remove-cart-items'])) {
    $product_id = get_safe_value($conn, $_POST['product-id']);
    $sql = "DELETE FROM cart_items WHERE product_id = '$product_id'";
    $result = mysqli_query($conn, $sql);
    if($result){
        header("location: cart.php");
    }
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart - XIT</title>
    <link rel="icon" type="image/ico" href="../favicon/favicon.ico" />
    <link rel="stylesheet" href="../css/style.css" />
</head>

<body>
    <?php include('inc/header.php'); ?>
    <section id="cart">
        <div class="container">
            <div class="shop-title">
                <a href="shop.php"><h1>Shop</h1></a>
                <!-- add path -->
            </div>
        </div>
        <div class="cart-wrapper">
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>&nbsp;</th>
                        <th>&nbsp;</th>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                   
                    foreach($cart_items as $k=>$v) {
                        $total_price += $v['quantity']*$v['product_price'];
                        echo '
                        <tr>
                            <td>
                            <form method="POST" class="delete-item-form">
                                <input type="hidden" name="product-id" value="'.$v['product_id'].'">
                                <button type="submit" name="remove-cart-items">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                            </td>
                            <td class="img-section">
                                <div>
                                    <img src="../images/'.$v['product_img'].'">
                                </div>
                            </td>
                            <td class="cart-product-name">'.$v['product_name'].'</td>
                            <td>$'.$v['product_price'].'</td>
                            <td style="font-weight: bold">'.$v['quantity'].'</td>
                            <td>$'.$v['quantity']*$v['product_price'].'</td>
                        </tr>';
                    }
                    ?>
                </tbody>
            </table>

            <div class="cart-totals">
                <div></div>
                <div>
                    <div class="cart-total-items">
                        <h2>Cart totals</h2>
                        <div>
                            <span class="cart-total-title">Total</span>
                            <span class="cart-total-price"><?php echo '$'.$total_price; ?></span>
                        </div>
                        <?php if(!$cart_items) {
                            echo' <input type="submit" value="Proceed To Checkout" disabled="disabled" class="disabled-btn">';
                        } else {?>
                        <a href="checkout.php"><input type="submit" value="Proceed To Checkout" name="place-order"></a>
                        <?php }?>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php include('inc/footer.php'); ?>
</body>

</html>