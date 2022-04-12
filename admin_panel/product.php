<?php
require('connection.php');
$product_data=$conn->prepare("SELECT * FROM products");
$product_data->execute([]);
$product_result = $product_data->fetchAll(PDO::FETCH_ASSOC); 

if(isset($_POST['delete-product'])) {
  $product_id = $_POST['product-id'];
  $sql=$conn->prepare("DELETE FROM products_category WHERE product_id = ?");
  $sql->execute([$product_id]);
  
  $sql=$conn->prepare("DELETE FROM products WHERE product_id = ?");
  $sql->execute([$product_id]);
  header('location: product.php');
}

?>
<!DOCTYPE html>
<html dir="ltr" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta
      name="keywords"
      content="wrappixel, admin dashboard, html css dashboard, web dashboard, bootstrap 5 admin, bootstrap 5, css3 dashboard, bootstrap 5 dashboard, Ample lite admin bootstrap 5 dashboard, frontend, responsive bootstrap 5 admin template, Ample admin lite dashboard bootstrap 5 dashboard template"
    />
    <meta
      name="description"
      content="Ample Admin Lite is powerful and clean admin dashboard template, inpired from Bootstrap Framework"
    />
    <meta name="robots" content="noindex,nofollow" />
    <title>Products Listing</title>
    <link
      rel="canonical"
      href="https://www.wrappixel.com/templates/ample-admin-lite/"
    />
    <!-- Favicon icon -->
    <link
      rel="icon"
      type="image/png"
      sizes="16x16"
      href="plugins/images/favicon.png"
    />
    <!-- Custom CSS -->
    <link href="css/style.min.css" rel="stylesheet" />
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <style>
  .product-form{
      width: 40%;
      margin: auto;
  }
  
  </style>
  <body>
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <div class="preloader">
      <div class="lds-ripple">
        <div class="lds-pos"></div>
        <div class="lds-pos"></div>
      </div>
    </div>
    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <div
      id="main-wrapper"
      data-layout="vertical"
      data-navbarbg="skin5"
      data-sidebartype="full"
      data-sidebar-position="absolute"
      data-header-position="absolute"
      data-boxed-layout="full"
    >
      <!-- ============================================================== -->
      <!-- Topbar header - style you can find in pages.scss -->
      <!-- ============================================================== -->
      <?php include('header.php'); ?>
      <?php include('sidebar.php'); ?>


      <div class="page-wrapper">
        <!-- ============================================================== -->
        <!-- Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->
        <div class="page-breadcrumb bg-white">
          <div class="row align-items-center">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
              <h4 class="page-title">Product Table</h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
              <div class="d-md-flex"></div>
            </div>
          </div>
          <!-- /.col-lg-12 -->
        </div>
        <!-- ============================================================== -->
        <!-- End Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Container fluid  -->
        <!-- ============================================================== -->
        <div class="container-fluid">
          <!-- ============================================================== -->
          <!-- Start Page Content -->
          <!-- ============================================================== -->
 <br>
    <form class="product-form" action="upload.php" method="POST" enctype="multipart/form-data">
    <div class="form-group">
        <label for="product_name">Product ID</label>
        <input type="number" class="form-control" name= "product_id" id="exampleInputEmail1"  placeholder="Enter Product ID" required>
      </div>  
      <div class="form-group">
        <label for="product_name">Product Name</label>
        <input type="text" class="form-control" name= "product_name" id="exampleInputEmail1"  placeholder="Enter Product Name" required>
      </div>  
      <div class="form-group"> 
        <label for="product_name">Product Description</label> 
        <input type="text" class="form-control" name="product_desc" id="exampleInputPassword1" placeholder="Enter Description" required>
      </div>  
      <div class="form-group">
        <label for="product_name">Product Price</label>
        <input type="text" class="form-control" name="product_price" id="exampleInputPassword1" placeholder="Enter Price" required>
      </div>
      <div class="form-group">
        <label for="product_name">Product Quantity</label>
        <input type="text" class="form-control" name="product_quantity" id="exampleInputPassword1" placeholder="Enter Quantity" required>
      </div>
      <div class="form-group">
        <label for="product_name">Category IDs (Comma Seperated)</label>
        <input type="text" class="form-control" name="product_category" id="exampleInputPassword1" placeholder="1,2,3..." required>
      </div>
      <div class="form-group">
        <input type="file" id="image" name="file">
        <input type="submit" name="submit" class="btn btn-primary my-3" value="Submit">
      </div>
    </form>
  <br>
 

<br>
          <div class="row">
            <div class="col-sm-12">
              <div class="white-box">
                <h3 class="box-title">Table</h3>
                <div class="table-responsive">
                  <table class="table text-nowrap">
                    <thead>
                      <tr>
                        <th class="border-top-0">#</th>
                        <th class="border-top-0">Product Name</th>
                        <th class="border-top-0">Description</th>
                        <th class="border-top-0">Price</th>
                        <th class="border-top-0">Quantity</th>
                        <th class="border-top-0">Release Date</th>
                        <th class="border-top-0"></th>
                      </tr>
                    </thead>
                    <?php
                    foreach ($product_result as $key=>$value) {
                        echo 
                        '<tr>
                          <form method="POST">
                              <input type="hidden" value="'.$value["product_id"].'" name="product-id">
                              <td>'.$value["product_id"].'</td>
                              <td>'.$value["product_name"].'</td>
                              <td>'.$value["product_desc"].'</td>
                              <td>'.$value["product_price"].'</td>
                              <td>'.$value["product_quantity"].'</td>
                              <td>'.$value["release_date"].'</td>
                              <td><input type="submit" name="delete-product" value="Delete" class="btn btn-primary"></td>
                          </form>
                        </tr>';
                    }
                    ?>
                  </table>
                </div>
              </div>
            </div>
          </div>

        </div>
        <!-- ============================================================== -->
        <!-- End Container fluid  -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- footer -->
        <!-- ============================================================== -->
        <?php include('footer.php'); ?>
        <!-- ============================================================== -->
        <!-- End footer -->
        <!-- ============================================================== -->
      </div>
      <!-- ============================================================== -->
      <!-- End Page wrapper  -->
      <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- All Jquery -->
    <!-- ============================================================== -->
    <script src="plugins/bower_components/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/app-style-switcher.js"></script>
    <!--Wave Effects -->
    <script src="js/waves.js"></script>
    <!--Menu sidebar -->
    <script src="js/sidebarmenu.js"></script>
    <!--Custom JavaScript -->
    <script src="js/custom.js"></script>
  </body>
</html>
