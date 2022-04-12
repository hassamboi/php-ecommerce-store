<?php 
require('inc/connection.php');
require('inc/functions.php');

if(!isset($_SESSION['IS_ADMIN'])) {
    header('location: login.php');
}

header('location: ../admin_panel/dashboard.php');
?>