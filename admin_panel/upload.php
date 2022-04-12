<?php
// Include the database configuration file
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
$username= 'root';
$password = '';

try {
$db = new PDO("mysql:host=localhost;dbname=ecommerce_website", $username, $password);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
echo "Connection failed: " . $e->getMessage();}

$statusMsg = '';

// File upload path
$targetDir = "../images/";
$fileName = basename($_FILES["file"]["name"]);
$targetFilePath = $targetDir . $fileName;
$fileType = pathinfo($targetFilePath,PATHINFO_EXTENSION);

$product_id = $_POST['product_id'];
$product_name = $_POST['product_name'];
$product_desc = $_POST['product_desc'];
$product_price = $_POST['product_price'];
$product_quantity = $_POST['product_quantity'];
$product_category = $_POST['product_category'];

$categories = explode(',', $product_category);

if(isset($_POST["submit"]) && !empty($_FILES["file"]["name"])){
    // Allow certain file formats
    $allowTypes = array('jpg','png','jpeg','gif','pdf');
    if(in_array($fileType, $allowTypes)){
        // Upload file to server
        if(move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath)){
            // Insert image file name into database
            $insert = $db->query("INSERT INTO products
            (product_id, product_name, product_desc,product_price, product_quantity, product_img, release_date) 
            VALUES (
            '".$product_id."',
            '".$product_name."',
            '".$product_desc."',
            '".$product_price."',
            '".$product_quantity."',
            '".$fileName."',
            NOW())");
            
            foreach($categories as $category) {
                $sql=$db->prepare("INSERT INTO products_category(product_id, category_id) VALUES (?,?)");
                $sql->execute([$product_id, $category]);
            }
           
            if($insert){
                $statusMsg = "The file ".$fileName. " has been uploaded successfully.";
            }else{
                $statusMsg = "File upload failed, please try again.";
            } 
        }else{
            $statusMsg = "Sorry, there was an error uploading your file.";
        }
    }else{
        $statusMsg = 'Sorry, only JPG, JPEG, PNG, GIF, & PDF files are allowed to upload.';
    }
}else{
    $statusMsg = 'Please select a file to upload.';
}

// Display status message
echo $statusMsg;

header('location: product.php');
?>