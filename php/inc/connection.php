<?php
session_start(); 
$conn = mysqli_connect('localhost', 'root', '', 'ecommerce_website');
if(!$conn) {
    echo 'Connection error: '.mysqli_connect_error();
}
?>
