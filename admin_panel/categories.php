<?php
require('connection.php');
$cat_data=$conn->prepare("SELECT * FROM category");
$cat_data->execute([]);
$cat_result = $cat_data->fetchAll(PDO::FETCH_ASSOC); 

if(isset($_POST['cat_done']))
{   
 $id = $_POST['category_id'];
 $name = $_POST['category_name'];
 $desc = $_POST['category_desc'];
 $product_data=$conn->prepare("INSERT INTO category values(?,?,?)");
 $product_data->execute([$id,$name,$desc]);
 header('location: categories.php');
}

if(isset($_POST['delete-category'])) {
  $category_id = $_POST['category-id'];
  $sql=$conn->prepare("DELETE FROM products_category WHERE category_id = ?");
  $sql->execute([$category_id]);

  $sql=$conn->prepare("DELETE FROM category WHERE category_id = ?");
  $sql->execute([$category_id]);

  // the items which have no category should be removed
  // $sql=$conn->prepare("DELETE FROM products WHERE product_id NOT IN(SELECT product_id FROM products_category)");
  // $sql->execute([]); 

  header('location: categories.php');
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
  .category-form{
      width: 40%;
      margin: auto;
  }
  
  </style>
  <body>
    <div class="preloader">
      <div class="lds-ripple">
        <div class="lds-pos"></div>
        <div class="lds-pos"></div>
      </div>
    </div>

    <div
      id="main-wrapper"
      data-layout="vertical"
      data-navbarbg="skin5"
      data-sidebartype="full"
      data-sidebar-position="absolute"
      data-header-position="absolute"
      data-boxed-layout="full"
    >

      <?php include('header.php'); ?>
  
      <?php include('sidebar.php'); ?>

      <div class="page-wrapper">
        <!-- ============================================================== -->
        <!-- Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->
        <div class="page-breadcrumb bg-white">
          <div class="row align-items-center">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
              <h4 class="page-title">Category Table</h4>
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




    <form method="POST" class="category-form">
      <div class="form-group">
        <label for="category_id">ID</label>
        <input type="text" class="form-control" name="category_id" id="exampleInputPassword1" placeholder="Enter Category ID">
      </div>
      <div class="form-group">
        <label for="category_name">Name</label>
        <input type="text" class="form-control" name="category_name" id="exampleInputPassword1" placeholder="Enter Category Name">
      </div>
      <div class="form-group">
        <label for="category_desc">Description</label>
        <input type="text" class="form-control" name="category_desc" id="exampleInputPassword1" placeholder="Enter Category Description">
      </div>
      <input type="submit" name="cat_done"class="btn btn-primary" value="submit">
    </form>
 
    <br>
 

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
                        <th class="border-top-0">Category Name</th>
                        <th class="border-top-0">Description</th>
                        <th class="border-top-0"></th>
                      </tr>
                    </thead>
                    <?php
                    foreach ($cat_result as $key=>$value) {
                        echo 
                        '<tr>
                          <form method="POST">
                              <input type="hidden" value="'.$value["category_id"].'" name="category-id">
                              <td>'.$value["category_id"].'</td>
                              <td>'.$value["category_name"].'</td>
                              <td>'.$value["category_desc"].'</td>
                              <td>
                                <input type="submit" value="Delete" class="btn btn-primary" name="delete-category">
                              </td>
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
