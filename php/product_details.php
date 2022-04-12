<?php
require('inc/connection.php');
require('inc/functions.php');

if(!isset($_GET['productid'])) {
    header('location: shop.php');
}

$product_id = $_GET['productid'];
$not_enough_quantity = '';
$enter_valid_quantity = '';
$items_are_added = '';
$available_qty_label = 0;

// when the add to cart is button is clicked
if(isset($_POST['add-to-cart'])) {
    // if the user is not logged in, send them to login page
    if(!isset($_SESSION['USER_ID'])) {
        header('location: login.php');
    } 
    // check if the quantity entered is a valid number
    $quantity = get_valid_quantity($conn, $_POST['quantity']);

    // if it's a valid quantity
    if($quantity) {
        // If the available quantity is greater than the quantity entered by the user then
        $session_user_id = $_SESSION['USER_ID'];

        // call the procedure to insert the products to the user's cart
        $sql = "CALL insert_into_cart('$session_user_id', '$product_id', '$quantity')"; 
        $result = mysqli_query($conn, $sql);  

        if($result) {
            $items_are_added = 'Your items have been added to the cart <a href="cart.php">Check</a> the cart';
        } 
    }
    else {
        $enter_valid_quantity = 'Enter a valid quantity';
    }
}

$sql = "SELECT product_id, product_name, product_desc, product_price, product_quantity, product_img,
GROUP_CONCAT(category_name) AS \"categories\"
FROM products
NATURAL JOIN products_category
NATURAL JOIN category
WHERE product_id = '$product_id'
GROUP BY product_id";

$other_product_query = "SELECT product_id, product_name, product_desc, product_price, product_img,
GROUP_CONCAT(category_name) AS \"categories\"
FROM products
NATURAL JOIN products_category
NATURAL JOIN category
WHERE product_id NOT IN ('$product_id')
GROUP BY product_id
ORDER BY rand() 
LIMIT 4";

$result = mysqli_query($conn, $sql);
$product = mysqli_fetch_assoc($result);
$result = mysqli_query($conn, $other_product_query);
$other_products = mysqli_fetch_all($result, MYSQLI_ASSOC);

//default value for qty-label will be the available qty
$available_qty_label = $product['product_quantity']; 

if(isset($_SESSION['USER_ID'])) {
    // check the qty of product this user has added to their cart
    $session_user_id = $_SESSION['USER_ID'];
    $sql = "SELECT quantity FROM cart_items WHERE product_id = '$product_id' AND user_id = '$session_user_id'";
    $result = mysqli_query($conn, $sql);
    $qty_added = mysqli_fetch_assoc($result);
    if($qty_added != NULL) {
        $available_qty_label = $product['product_quantity']  - $qty_added['quantity'];
    } 
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product - XIT</title>
    <link rel="stylesheet" href="../css/style.css" />
    <link rel="icon" type="image/ico" href="../favicon/favicon.ico" />
</head>

<body>
    <?php include('inc/header.php'); ?>

    <section id="product">
        <div class="container">
            <div class="shop-title">
                <a href="shop.php"><h1>Shop</h1></a>
                <!-- add path -->
            </div>
            <div class="product-wrapper">
                <div class="product-img">
                    <img src="../images/<?php print_r($product['product_img']); ?>" alt="">
                </div>
                <div class="product-details">
                    <div class="product-title product-info">
                        <h2><?php print_r($product['product_name']); ?></h2>
                    </div>
                    <div class="product-price product-info">
                        <h2>$<?php print_r($product['product_price']); ?></h2>
                    </div>
                    <div class="product-desc product-info">
                        <p><?php print_r($product['product_desc']); ?></p>
                    </div>
                    <div class="product-cart product-info">
                        <form method="POST">
                            <input class="quantity-input" type="number" name="quantity" placeholder="Enter Quantity" min="1" max="<?php echo $available_qty_label?>">
                            <input class="cart-btn" type="submit" name="add-to-cart" value="ADD TO CART">
                        </form>
                    </div>
                    <div class="product-category product-info">
                        <div>
                            <span class="product-category-title">Categories: </span>
                            <span><?php print_r($product['categories']); ?></span>
                        </div>
                        <div>
                            <span class="product-category-title">Available quantity: </span>
                            <span><?php 
                                if($available_qty_label <= '0') {
                                    echo 'OUT OF STOCK';
                                } else {
                                    echo $available_qty_label;
                                }
                            ?>
                            </span>
                        </div>
                        <div class="success"><?php print_r($items_are_added); ?></div>
                        <div class="failure"> <?php print_r($enter_valid_quantity); ?></div>
                        <div class="failure"> <?php print_r($not_enough_quantity); ?></div>
                    </div>
                </div>
            </div>
            <div class="other-products">
                <div class="other-products-title">
                    <h2>Other Products</h2>
                </div>
                <div class="items-grid">
                <?php
                    foreach($other_products as $k=>$v) {
                        $combined_categories = $v['categories'];
                        $categories = explode(',', $combined_categories);
                        
                        echo '
                        <div class="item-box';foreach($categories as $c){echo " ".$c;} echo'">
                            <a href="product_details.php?productid='.$v['product_id'].'">
                                <img src="../images/'.$v['product_img'].'">
                            </a>
                            <div class="item-box-content-wrapper">
                                <a href="product_details.php?productid='.$v['product_id'].'">
                                    <div class="item-box-title">
                                        <h2>'.$v['product_name'].'</h2>
                                        <p>';foreach($categories as $c){echo $c." ";} echo '</p>
                                    </div>
                                </a>
                                <div class="item-box-line-divider"></div>
                                <div class="item-box-price">
                                    <span>$'.$v['product_price'].'</span>
                                </div>
                            </div>
                        </div>';
                    }  
                    ?>
                </div>
            </div>
        </div>
    </section>

    <?php include('inc/footer.php'); ?>
</body>

</html>