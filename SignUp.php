<!-- CSCI 466 Group project 
Created by:
Ahmed Elfaki 
Matthew Gidron Noutai
Michael Ibikunle -->


<?php 
        include("UsrnamePasswrd.php"); 
?>
<!DOCTYPE html>
<html>
<style>
    nav {
  display: flex;
  justify-content: center;
  align-items: center;
  width: 100%;
  position: fixed;
  top: 0;
  left: 0;
  background-color: #ffffff; /*change the background color as per your requirement*/
  z-index: 9999;
}
nav ul {
  display: flex;
  justify-content: center;
  align-items: center;
  width: 100%;
  position: fixed;
  top: 0;
  left: 0;
  background-color: #ffffff; /*change the background color as per your requirement*/
  z-index: 9999;
}

nav li {
  margin-right: 10px; /* adjust the spacing between the links as per your requirement */
}

</style>

<head>
    <title> User Signup Page </title>
    <link href="https://students.cs.niu.edu/~z1877540/CSS_FILES/style.css" rel="stylesheet">
</head>
<nav class = "stylesheet">  
                <ul class="navbar-nav">
                <li class="nav-item">
                        <a class="nav-link active" href="https://students.cs.niu.edu/~z1877540/Homepage.html">Homepage</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="https://students.cs.niu.edu/~z1896460/PHP_FILES/Djpage.php">DJ page</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="https://students.cs.niu.edu/~z1877540/PHP_FILES/LoginIn.php">User Login</a>
                    </li>
                </ul>  
            </nav>


<div id="SignUp" class="page active">

        <h1><font color="red"><center>SignUp Page</center></font></h1>
        <title>Create Account</title>
       
    </head>
        <body>
            <h3><center>Create User Account</h3>
            <form method="post" action="">
                <label for="UserName">Username:</label>
                <input type="text" id="UserName" name="UserName">
                <label for="UserPass">Password:</label>
                <input type="password" id="UserPass" name="UserPass" required>
                <label for="UserEmail">Email:</label>
                <input type="email" id="UserEmail" name="UserEmail" required>
                <input type="submit" name="createAccount" value="createAccount">
            </form>
        </body> 

 <?php
session_start(); 
ob_start();
if (isset($_POST['createAccount'])) {
    // get the form data
    $username = $_POST['UserName'];
    $password = $_POST['UserPass'];
    $email = $_POST['UserEmail'];

    //preapre SQL statement
    $stmt = $pdo->prepare("INSERT INTO `User` (UserName, UserPass, UserEmail) VALUES (:username, :password, :email);");
    // $stmt_select = $pdo->prepare("SELECT `UserID` FROM User WHERE UserName = :username AND UserPass = :password");

    //bind the param 
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':email', $email);

    // execute the statement
    $stmt->execute();

    // Get the last inserted ID
    $user_id = $pdo->lastInsertId();

    if ($user_id) {
        // display success message
        echo '<span style="color: white;">User created successfully. You have been assigned UserID: '.$user_id.'</span>';
        echo '<span style="color: white;">You are going to need your UserID to login and select a song</span>';

        // display button to redirect the user to a different page
        echo '<br><br><a href="LoginIn.php" class="button">login</a>';
        
    } else {
        // display error message
        echo "There was an error creating the user account.";
    }
}
ob_end_flush();
?>
    </div>
</html>