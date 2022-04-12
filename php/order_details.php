<?php 
require('inc/connection.php');
require('inc/functions.php');

if(!isset($_SESSION['USER_ID'])) {
    header('location: login.php');
}
$session_user_id = get_safe_value($conn, $_SESSION['USER_ID']);

$sql = "SELECT * FROM order_details WHERE user_id = '$session_user_id' ORDER BY created_at desc";
$result = mysqli_query($conn, $sql);
$orders = mysqli_fetch_all($result, MYSQLI_ASSOC);
$total_price = 0;

$order_items = array();
foreach($orders as $k=>$v) {
    $order_id = $v['order_id'];
    $sql = "SELECT * FROM order_items NATURAL JOIN products WHERE order_id = '$order_id'";
    $result = mysqli_query($conn, $sql);
    
    array_push($order_items ,mysqli_fetch_all($result, MYSQLI_ASSOC));
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details - XIT</title>
    <link rel="icon" type="image/ico" href="../favicon/favicon.ico" />
    <link rel="stylesheet" href="../css/style.css" />
</head>

<body>
    <?php include('inc/header.php'); ?>
    <section id="orders" style="user-select: none">
        <div class="container">
            <div class="orders-title">
                <h2>Your Orders</h2>
            </div>
            <?php 
            if(!array_filter($orders)) {
                echo '<div class="primary" style="margin-bottom: 25rem">You have no past order records</div>';
            }
            $i = 0;
            foreach($order_items as $order_item) {
                echo '
                <div class="dropdown">
                <div class="trigger">
                    <span class="order-id">Order id: '.$orders[$i]['order_id'].'</span>
                    <small><span>Created at:</span>'.$orders[$i]['created_at'].'</small>       
                </div>
                <div class="content">
                    <div class="order-details">
                        <table class="cart-table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>';
                                foreach($order_item as $k=>$v) {
                                    $total_price += $v['quantity']*$v['product_price'];
                                    echo '
                                    <tr>
                                        <td class="cart-product-name">'.$v['product_name'].' &#215 '.$v['quantity'].'</td>
                                        <td>$'.$v['quantity']*$v['product_price'].'</td>
                                    </tr>';
                                } echo'
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Total</th>
                                    <td>$
                                        '.$total_price.'
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
                ';
                $i++;
                $total_price = 0;
            }?>
        </div>
    </section>
    <?php include('inc/footer.php'); ?>
    <script type="text/javascript" src="../js/dropdown.js"></script>
</body>

</html>