<?php
function pr($arr) {
    echo '<pre>';
    print_r($arr);
}

function prx($arr) {
    echo '<pre>';
    print_r($arr);
    die();
}

function get_safe_value($conn, $str) {
    if($str!='') {
        return mysqli_real_escape_string($conn, $str);
    }
}

function get_valid_quantity($conn, $str) {
    if(is_numeric($str)) { 
        return mysqli_real_escape_string($conn, trim($str));
    }
    return NULL;
}

function create_order($conn, $session_user_id, $total_price, $order_notes) {
    // get all the cart items for this user
    $sql = "SELECT product_id, quantity FROM cart_items WHERE user_id = '$session_user_id'";
    $result = mysqli_query($conn, $sql);
    $cart_items = mysqli_fetch_all($result, MYSQLI_ASSOC);


    // check available quantity for all the products 
    // in case an order has already been made by some other user
    $go_back = false;

    foreach($cart_items as $k=>$v) {
        $qty = $v['quantity'];
        $product_id = $v['product_id'];
        // for each cart item, get the available quantity for that item
        $sql = "SELECT product_quantity FROM products WHERE product_id = '$product_id'";
        $result = mysqli_query($conn, $sql);
        $available_qty = mysqli_fetch_assoc($result);
        
        if($qty > $available_qty['product_quantity']) {
            //  delete that cart item for this user
            $sql = "DELETE FROM cart_items WHERE user_id = '$session_user_id' AND product_id = '$product_id'";
            $result = mysqli_query($conn, $sql);
            $go_back = true;
        }
    }

    if($go_back == true) {
        return -1;
    } 
    
    // create an order with the user id, 
    // total price and any notes passed by the checkout page 
    $sql = "INSERT INTO order_details(user_id, total_price, order_notes, created_at) VALUES
    ('$session_user_id', '$total_price', '$order_notes', NOW())";
    $result = mysqli_query($conn, $sql);
    $last_id = mysqli_insert_id($conn);

    // transfer each cart item for this user to order items
    foreach($cart_items as $k => $v) {
        $product_id = $v['product_id'];
        $qty = $v['quantity'];

        $sql = "INSERT INTO order_items(order_id, product_id, quantity) VALUES
        ('$last_id', '$product_id', '$qty')";
        mysqli_query($conn, $sql);
    }

    // finally delete all the cart items for this user
    $sql = "DELETE FROM cart_items WHERE user_id = '$session_user_id'";
    $result = mysqli_query($conn, $sql);

    // return the order id
    return $last_id;
}
?> 