<?php 
require('connection.php');
$sql =$conn->prepare("SELECT SUM(total_price) as 'sales' FROM order_details;");
$sql->execute([]);
$total_sales = $sql->fetch(PDO::FETCH_ASSOC); 

$sql =$conn->prepare("SELECT * FROM order_details;");
$sql->execute([]);
$orders = $sql->fetchAll(PDO::FETCH_ASSOC); 
?>

<!DOCTYPE html>
<html dir="ltr" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Admin Panel</title>
    <link
      rel="canonical"
      href="https://www.wrappixel.com/templates/ample-admin-lite/"
    />
  
    <link
      rel="icon"
      type="image/png"
      sizes="16x16"
      href="plugins/images/favicon.png"
    />
 
    <link
      href="plugins/bower_components/chartist/dist/chartist.min.css"
      rel="stylesheet"
    />
    <link
      rel="stylesheet"
      href="plugins/bower_components/chartist-plugin-tooltips/dist/chartist-plugin-tooltip.css"
    />
  
    <link href="css/style.min.css" rel="stylesheet" />
  </head>

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
       
        
        <div class="page-breadcrumb bg-white">
          <div class="row align-items-center">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
              <h4 class="page-title">Dashboard</h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
              <div class="d-md-flex">
              </div>
            </div>
          </div>
          <!-- /.col-lg-12 -->
        </div>
  
        <div class="container-fluid">
    
          <div class="row justify-content-center">
            <div class="">
              <div class="white-box analytics-info">
                <h3 class="box-title">Total Sales</h3>
                <ul class="list-inline two-part d-flex align-items-center mb-0">
                  <li>
                    <div id="sparklinedash">
                      <canvas
                        width="67"
                        height="30"
                        style="
                          display: inline-block;
                          width: 67px;
                          height: 30px;
                          vertical-align: top;
                        "
                      ></canvas>
                    </div>
                  </li>
                  <li class="ms-auto">
                    <span class="counter text-success">$<?php print_r(number_format($total_sales['sales'], 2, '.', '')); ?></span>
                  </li>
                </ul>
              </div>
            </div> 
          </div>
         
  
          
          <!-- ============================================================== -->
          <!-- RECENT SALES -->
          <!-- ============================================================== -->
          <div class="row">
            <div class="col-md-12 col-lg-12 col-sm-12">
              <div class="white-box">
                <div class="d-md-flex mb-3">
                  <h3 class="box-title mb-0">Order details</h3>
                </div>
                <div class="table-responsive">
                  <table class="table no-wrap">
                    <thead>
                      <tr>
                        <th class="border-top-0">Order id</th>
                        <th class="border-top-0">User id</th>
                        <th class="border-top-0">Order notes</th>
                        <th class="border-top-0">Date</th>
                        <th class="border-top-0">Price</th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php
                      foreach($orders as $k=>$v) {
                        $order_notes = strlen($v['order_notes']) > 50 ? substr($v['order_notes'], 0, 50)."..." : $v['order_notes'];
                        echo '
                        <tr>
                          <td>'.$v['order_id'].'</td>
                          <td class="txt-oflo">'.$v['user_id'].'</td>
                          <td>'.$order_notes.'</td>
                          <td class="txt-oflo">'.$v['created_at'].'</td>
                          <td><span class="text-info">$'.$v['total_price'].'</span></td>
                        </tr>
                        ';
                      }
                      ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
 
          <?php include('footer.php'); ?>
        <!-- ============================================================== -->
        <!-- End footer -->
        
        <!-- ============================================================== -->
      </div>
      <!-- ============================================================== -->
      <!--
         End Page wrapper  -->
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
    <script src="plugins/bower_components/jquery-sparkline/jquery.sparkline.min.js"></script>
    <!--Wave Effects -->
    <script src="js/waves.js"></script>
    <!--Menu sidebar -->
    <script src="js/sidebarmenu.js"></script>
    <!--Custom JavaScript -->
    <script src="js/custom.js"></script>
    <!--This page JavaScript -->
    <!--chartis chart-->
    <script src="plugins/bower_components/chartist/dist/chartist.min.js"></script>
    <script src="plugins/bower_components/chartist-plugin-tooltips/dist/chartist-plugin-tooltip.min.js"></script>
    <script src="js/pages/dashboards/dashboard1.js"></script>
  </body>
</html>
