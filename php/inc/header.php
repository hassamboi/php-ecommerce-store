<header>
    <nav>
      <div class="nav-container">
        <div class="logo">
          <a href="./index.php"><img src="../images/Logo-min.png" alt="Website's Logo" /></a>
        </div>
        <ul class="nav-links">
          <li><a href="./index.php">Home</a></li>
          <li><a href="./index.php#categories">Categories</a></li>
          <li><a href="./index.php#latest-items">Products</a></li>
          <li class="shop-options">
            <a href="./shop.php">Shop <i class="fas fa-caret-down"></i></a>
            <ul>
              <li><a href="./product_category.php?categoryname=accessories">Accessories</a></li>
              <li><a href="./product_category.php?categoryname=clothings">Clothings</a></li>
              <li><a href="./product_category.php?categoryname=shirts">Shirts</a></li>
              <li><a href="./product_category.php?categoryname=shoes">Shoes</a></li>
              <li><a href="./product_category.php?categoryname=shorts">Shorts</a></li>
            </ul>
          </li>
          <li>|</li>
          <li>
            <a href="./cart.php"><i class="fas fa-shopping-cart"></i></a>
          </li>
          <?php
            if(isset($_SESSION['USER_ID'])) {
                echo '<li><a href="./order_details.php"><i class="fas fa-list"></i></i></a></li>';
              echo '  <li><a href="./logout.php"><i class="fas fa-sign-out-alt"></i></a></li>';
            } else {
              echo '<li><a href="./login.php"><i class="fas fa-sign-in-alt"></i></i></a></li>';
            }
            if($_SESSION) {
              if($_SESSION['IS_ADMIN'] == true) {
                echo '<li><a href="./admin_panel.php"><i class="fas fa-user-shield"></i></a></li>';
              } else {
                echo '<li><a href="./user_profile.php"><i class="fas fa-user"></i></a></li>';
              }
            } else {
              echo '<li><a href="./login.php"><i class="fas fa-user"></i></a></li>';
            }
          ?>
        </ul>
      </div>
    </nav>
  </header>