<?php   
session_start(); //to ensure you are using same session
session_unset(); //unset all session variables
session_destroy(); //destroy the session
header("location: index.php"); //to redirect back to "index.php" after logging out
exit();
?>