<?php
require('inc/connection.php');

// write query to get all the products, the prices and the categories
$sql = 'SELECT product_id, product_name, product_price, product_img,
GROUP_CONCAT(category_name) AS "categories"
FROM products
NATURAL JOIN products_category
NATURAL JOIN category
GROUP BY product_id';

$count = 'SELECT
SUM(CASE WHEN category_name = "clothings" THEN 1 ELSE 0 END) AS "clothings_count",
SUM(CASE WHEN category_name = "shirts" THEN 1 ELSE 0 END) AS "shirts_count",
SUM(CASE WHEN category_name = "shorts" THEN 1 ELSE 0 END) AS "shorts_count",
SUM(CASE WHEN category_name = "shoes" THEN 1 ELSE 0 END) AS "shoes_count",
SUM(CASE WHEN category_name = "accessories" THEN 1 ELSE 0 END) AS "accessories_count"
FROM products NATURAL JOIN products_category NATURAL JOIN category;
';
// make query and get result
$result = mysqli_query($conn, $sql);
// fetch the resulting rows as an array
$products = mysqli_fetch_all($result, MYSQLI_ASSOC);

$result = mysqli_query($conn, $count);
$category_count = mysqli_fetch_all($result, MYSQLI_ASSOC);

$sql = "SELECT product_id, COUNT(product_id) as count 
FROM order_items 
GROUP BY product_id 
ORDER BY count desc 
LIMIT 4";

$result = mysqli_query($conn, $sql);
$featured_item_count = mysqli_fetch_all($result, MYSQLI_ASSOC);

$featured_items = array();
foreach($featured_item_count as $k=>$v) {
    $product_id = $v['product_id'];

    $sql = "SELECT product_id, product_name, product_price, product_img
    FROM products
    WHERE product_id = '$product_id'";

    $result = mysqli_query($conn, $sql);
    array_push($featured_items, mysqli_fetch_assoc($result));
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop - XIT</title>
    <link rel="icon" type="image/ico" href="../favicon/favicon.ico" />
    <link rel="stylesheet" href="../css/style.css" />
</head>
<body>
    <?php include('inc/header.php'); ?>
   
    <section id="shop">
        <div class="container">
            <div class="shop-title">
                <a href="shop.php"><h1>Shop</h1></a>
                <!-- add path -->
            </div>
            <div class="search">
                <input type="text" placeholder="Search products...">
            </div>
            <div class="shop-content">
                <!-- left grid of filters -->
                <div class="left-content">
                    <div class="product-categories">
                        <label for="product-category-names">Product Categories</label>
                        <div class="product-category-names">

                            <a href="product_category.php?categoryname=<?php echo 'accessories'; ?>">
                                <p>Accessories</p>
                                <span><?php print_r($category_count[0]['accessories_count']); ?></span>
                            </a>

                            <a href="product_category.php?categoryname=<?php echo 'clothings'; ?>">
                                <p>Clothings</p>
                                <span><?php print_r($category_count[0]['clothings_count']); ?></span>
                            </a>

                            <a href="product_category.php?categoryname=<?php echo 'shirts'; ?>">
                                <p>Shirts</p>
                                <span><?php print_r($category_count[0]['shirts_count']); ?></span>
                            </a>

                            <a href="product_category.php?categoryname=<?php echo 'shoes'; ?>">
                                <p>Shoes</p>
                                <span><?php print_r($category_count[0]['shoes_count']); ?></span>
                            </a>

                            <a href="product_category.php?categoryname=<?php echo 'shorts'; ?>">
                                <p>Shorts</p>
                                <span><?php print_r($category_count[0]['shorts_count']); ?></span>
                            </a>
                        </div>
                    </div>
                    <?php if(array_filter($featured_items)) {?>
                    <div class="top-rated-products">
                        <label for="top-rated-product-names">Featured Products</label>
                        <div class="top-rated-product-names">
                            <?php 
                            foreach($featured_items as $k=>$v) {
                                echo '
                                <div class="grid">
                                    <img src="../images/'.$v['product_img'].'" alt="">
                                    <a href="product_details.php?productid='.$v['product_id'].'">
                                        <h4>'.$v['product_name'].'</h4>
                                        <p>'.$v['product_price'].'</p>
                                    </a>
                                </div>';
                            }
                            ?>
                        </div>
                    </div>
                    <?php }?>
                </div>
                        
                <!-- right grid of products -->
                <div class="right-content">
                    <div class="items-grid">
                    <?php
                    foreach($products as $k=>$v) {
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
        </div>
    </section>

    <?php include('inc/footer.php'); ?>
</body>
</html>