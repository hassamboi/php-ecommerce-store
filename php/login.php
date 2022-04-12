<?php
require('inc/connection.php');
require('inc/functions.php');

// IF SESSION IS ACTIVE YOU CANT GO BACK TO LOGIN PAGE
if(isset($_SESSION['USER_ID'])) {
   header('location: index.php'); 
}

// FOR LOGIN FORM
if(isset($_POST['login-submitted'])) {
    $email = get_safe_value($conn, $_POST['input-email']);
    $password = get_safe_value($conn, $_POST['input-password']);
    $password = md5($password);

    $sql = "SELECT * FROM user WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);
    
    if(mysqli_num_rows($result)) {
        $userData = mysqli_fetch_assoc($result);
        $returned_pass = $userData['password'];

        if($returned_pass ===  $password) {
            // Password matched
            $_SESSION['IS_ADMIN'] = $userData['is_admin'];
            $_SESSION['USER_ID'] = $userData['user_id'];
            header('location: index.php');
        } else {
            // Password didn't match  
        }
    } else {
        // email not found
    }
}

// FOR SIGN UP FORM
if(isset($_POST['signup-submitted'])) {
    // user table data to be inserted
    $fname = get_safe_value($conn, $_POST['fname']);
    $lname = get_safe_value($conn, $_POST['lname']);
    $signin_email = get_safe_value($conn, $_POST['email']);
    $signin_password = get_safe_value($conn, $_POST['password']);

    $sql = "INSERT INTO user(fname, lname, email, password) 
    VALUES('$fname', '$lname', '$signin_email',md5('$signin_password'))";

    if(!mysqli_query($conn, $sql)) {
        echo 'query error: '. mysqli_error($conn);
    } 
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login - XIT</title>
  <link rel="stylesheet" href="../css/style.css" />
  <link rel="icon" type="image/ico" href="../favicon/favicon.ico" />
</head>

<body>
    <?php include('inc/header.php'); ?>

    <section id="login">
        <div class="container">
            <form method="POST" class="login-form">
                <div>
                    <label for="input-email">Enter Email</label>
                    <input type="email" name="input-email" required/>
                </div>
                <div>
                    <label for="input-password">Enter Password</label>
                    <input type="password" name="input-password" required/>
                </div>
                <div>
                    <input type="submit" value="Log In" name="login-submitted"/>
                </div>
            </form>

            <form method="POST" action="login.php" class="login-form hide-form sign-in-form">
                <div>
                    <label>Name</label>
                </div>
                <div class="input-grid grid-2">
                    <div>
                        <input type="text" name="fname" placeholder="First Name" required>
                    </div>
                    <div>
                        <input type="text" name="lname" placeholder="Last Name" required>
                    </div>
                </div>
                <div>
                    <label>Login Information</label>
                </div>
                <div class="input-grid grid-2">
                    <div>
                        <input type="email" name="email" placeholder="Email" required>
                    </div>
                    <div>
                        <input type="password" name="password" placeholder="Password" required>
                    </div>
                </div>
                <div>
                    <input type="submit" value="Sign Up" name="signup-submitted"/>
                </div>
            </form>
          
            <div class="sign-in-info">
                <p class="sign-in-para">
                    Haven't signed up yet?
                    <button class="sign-in-btn">Sign Up</button> now
                </p>
                <p class="log-in-para login-btn-hide">
                    Already signed in?
                    <button class="log-in-btn">Log In</button> now
                </p>
            </div>
        </div>
    </section>

    <?php include('inc/footer.php'); ?>
    <script src="../js/login.js"></script>
</body>
