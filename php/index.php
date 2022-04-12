<?php
  require('inc/connection.php');
  require('inc/functions.php');

  $sql = "SELECT product_id, COUNT(product_id) as count 
  FROM order_items 
  GROUP BY product_id 
  ORDER BY count desc 
  LIMIT 8";

  $result = mysqli_query($conn, $sql);
  $featured_item_count = mysqli_fetch_all($result, MYSQLI_ASSOC);

  $featured_items = array();
  foreach($featured_item_count as $k=>$v) {
      $product_id = $v['product_id'];

      $sql = "SELECT product_id, product_name, product_price, product_img,
      GROUP_CONCAT(category_name) AS \"categories\"
      FROM products
      NATURAL JOIN products_category
      NATURAL JOIN category
      WHERE product_id = '$product_id'
      GROUP BY product_id";

      $result = mysqli_query($conn, $sql);
      array_push($featured_items, mysqli_fetch_assoc($result));
  }
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <title>XIT | The Global Merchandise Store</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description"
    content="XIT Store offers a wide range of high quality branded products from apparel and casual street wear to accessories, shoes and more." />
  <meta name="keywords" content="ECOMMERCE, MERCHANDISE, ONLINE, STORE, SHOP" />
  <link rel="icon" type="image/ico" href="../favicon/favicon.ico" />
  <link rel="stylesheet" href="../css/style.css" />
  <script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@latest/dist/js/splide.min.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@splidejs/splide@latest/dist/css/splide.min.css">
  <link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/@splidejs/splide@2.4.21/dist/css/themes/splide-skyblue.min.css"
    integrity="sha256-iKLupyKsBxuiVANiNvCu9m7yHijYpoFheW1dgHB92us=" crossorigin="anonymous">
</head>

<body>
<?php include('inc/header.php'); ?>
  <!-- showcase section -->
  <section id="showcase">
    <div class="splide">
      <div class="splide__track">
        <ul class="splide__list">
          <li class="splide__slide"><img src="../images/slide2-min.jpg" alt=""></li>
          <li class="splide__slide"><img src="../images/slide1-min.jpg" alt=""></li>
        </ul>
      </div>
      <div class="splide__progress">
        <div class="splide__progress__bar">
        </div>
      </div>
    </div>
  </section>
  
  <!-- categories section -->
  <section id="categories">
    <div class="container">
      <div class="category-grid"> 
        <div class="category-card">
          <a href="product_category.php?categoryname=clothings">
            <div class="filter">
              <h3>Clothings</h3>
            </div>
          </a>
        </div>
        
        <div class="category-card">
          <a href="product_category.php?categoryname=accessories">
            <div class="filter">
              <h3>Accessories</h3>
            </div>
          </a>
        </div>
        
      </div>
      <div class="sale-countdown">
        <h2>Sale for this fall starts in</h2>
        <div class="line-divider"></div>
        <div class="timer">
          <div class="weeks">
            <span>00</span>
            <div>Weeks</div>
          </div>
          <div class="days">
            <span>00</span>
            <div>Days</div>
          </div>
          <div class="hours">
            <span>00</span>
            <div>Hours</div>
          </div>
          <div class="minutes">
            <span>00</span>
            <div>Minutes</div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Featured items section -->
  <section id="latest-items">
    <div class="container">
      <div class="latest-items-title">
        <h2>Check out what's new</h2>
        <p>Latest of the trends we have to offer</p>
      </div>
      <div class="line-divider"></div>
      <ul class="filter-btns">
        <li data-name="all" class="filter-btn filter-btn-active">All</li>
        <li data-name="accessories" class="filter-btn">Accessories</li>
        <li data-name="clothings" class="filter-btn">Clothings</li>
        <li data-name="shirts" class="filter-btn">Shirts</li>
        <li data-name="shoes" class="filter-btn">Shoes</li>
        <li data-name="shorts" class="filter-btn">Shorts</li>
      </ul>
      <p class="no-item no-item-hide">No featured items to show in this category.</p>
      <div class="items-grid">
        <?php 
        foreach($featured_items as $k=>$v) {
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
  </section>

<?php include('inc/footer.php') ?>

<script src="../js/index.js" crossorigin="anonymous"></script>

</body>