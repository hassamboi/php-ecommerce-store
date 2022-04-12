
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OrderError - XIT</title>
    <link rel="icon" type="image/ico" href="../favicon/favicon.ico" />
    <link rel="stylesheet" href="../css/style.css" />
</head>
<body>
<?php
    require('inc/functions.php');
    $error_msg = '<div class="failure">Your order cannot be added, The added quantity is not available anymore.<a href="cart.php"> Go back</a> to your cart</div>';
    pr($error_msg);
?>
</body>
</html>
