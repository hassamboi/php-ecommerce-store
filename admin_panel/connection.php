<?php
session_start();

if(!isset($_SESSION['USER_ID'])) {
  header('location: ../php/login.php');
}

if($_SESSION) {
  if($_SESSION['IS_ADMIN'] == false) {
    header('location: ../php/user_profile.php');
  }
}

$session_user_id = $_SESSION['USER_ID']; 
$username = 'root';
$password = "";

try {
    $conn = new PDO("mysql:host=localhost;dbname=ecommerce_website", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(PDOException $e) {
echo "Connection failed: " . $e->getMessage();}

?>