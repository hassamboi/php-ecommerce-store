<?php
require('connection.php');
$user_data=$conn->prepare("SELECT user_id, fname, lname, email FROM user where is_admin = 0");
$user_data->execute([]);
$user_result = $user_data->fetchAll(PDO::FETCH_ASSOC); 

if(isset($_POST['add_button']))
{
  $id=$_POST['send_id'];
  $remove=$conn->prepare("UPDATE user SET is_admin = 1 WHERE user_id like ?");
  if($remove->execute([$id]))
  {
    header('location: users.php');
  }
}

?>

<!DOCTYPE html>
<html dir="ltr" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>All Users</title>
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
    <!-- Custom CSS -->
    <link href="css/style.min.css" rel="stylesheet" />
  </head>

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
      <!-- ============================================================== -->
      <!-- End Topbar header -->
      <!-- ============================================================== -->
      <!-- ============================================================== -->
      <!-- Left Sidebar - style you can find in sidebar.scss  -->
      <!-- ============================================================== -->
     <?php include('sidebar.php'); ?> 
      <!-- ============================================================== -->
      <!-- End Left Sidebar - style you can find in sidebar.scss  -->
      <!-- ============================================================== -->
      <!-- ============================================================== -->
      <!-- Page wrapper  -->
      <!-- ============================================================== -->
      <div class="page-wrapper">
        <!-- ============================================================== -->
        <!-- Bread crumb and right sidebar toggle -->
        <!-- ============================================================== -->
        <div class="page-breadcrumb bg-white">
          <div class="row align-items-center">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
              <!-- <h4 class="page-title">Categories Table</h4> -->
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
          <div class="row">
            <div class="col-sm-12">
              <div class="white-box">
                <h3 class="box-title">Users Table</h3>
                <div class="table-responsive">
                  <table class="table text-nowrap">
                    <thead>
                      <tr>
                        <th class="border-top-0">#</th>
                        <th class="border-top-0">First Name</th>
                        <th class="border-top-0">Last Name</th>
                        <th class="border-top-0">Email</th>
                        <th class="border-top-0"></th>
                      </tr>
                    </thead>
                    <?php
                    foreach ($user_result as $key=>$value) {
                        echo 
                        '<tr>
                         <form action method="POST">
                         <input type="hidden" name="send_id" value="'.$value['user_id'].'">
                            <td>'.$value["user_id"].'</td>
                            <td>'.$value["fname"].'</td>
                            <td>'.$value["lname"].'</td>
                            <td>'.$value["email"].'</td>
                            <td><input type="submit" value= "Make Admin" name="add_button" class="btn btn-primary" ></td>
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
