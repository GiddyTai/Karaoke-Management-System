<!-- CSCI 466 Group project 
Created by:
Ahmed Elfaki 
Matthew Gidron Noutai
Michael Ibikunle -->

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
<nav>  
                <ul class="navbar-nav">
                <li class="nav-item">
                        <a class="nav-link active" href="https://students.cs.niu.edu/~z1877540/Homepage.html">Homepage</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="https://students.cs.niu.edu/~z1877540/PHP_FILES/SignUp.php">Sign up</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="https://students.cs.niu.edu/~z1896460/PHP_FILES/Djpage.php">DJ page</a>
                    </li>
                </ul>  
            </nav>
    <div id="LoginIn" class="page active">
    
    <head>
    <title>User login page </title>
    <link href="https://students.cs.niu.edu/~z1877540/CSS_FILES/style.css" rel="stylesheet">
    </head>

    <p><center>Please enter your user login credentials:</p>

    <form action="" method="POST">
        <label for="UserID">User ID:</label>
        <input type="text" id="UserID" name="UserID" required><br><br>
        <label for="UserPass">Password:</label>
        <input type="password" id="UserPass" name="UserPass" required><br><br>
        <input type="submit" name="Login" value="Login">
    </form>
    <?php 

include("UsrnamePasswrd.php"); 
?> 
<?php
    // check if form submitted
    if (isset($_POST['Login'])) {
        // get username and password from form
        $user_id = $_POST['UserID'];
        $password = $_POST['UserPass'];

        // prepare and execute SQL query
        $stmt = $pdo->prepare("SELECT UserID FROM User WHERE UserID = :user_id AND UserPass = :password");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':password', $password);
        $stmt->execute();

        // fetch result
        $result = $stmt->fetch();
    
        // check if user found and password match
        if ($result) {
            // start session and store UserID
            session_start();
            $_SESSION['UserID'] = $result['UserID'];
        
            // redirect to songsignup.php with UserID in query string
            header("Location: Songsignup.php?UserID=" . urlencode($result['UserID']));
            exit();
        } else {
            echo "Invalid username or password.";
        }
    }
    ?>
    </div>
</html>