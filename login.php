<?php
  session_start();
  // if (isset($_SESSION['user'])) header('location: dashboard.php');

  $err_msg ="";

  if($_POST){
    include("database/connection.php");

    $username = $_POST['username'];
    $password =$_POST['password'];

    $query = 'SELECT * FROM users WHERE users.email="'. $username .'" AND users.password="'. $password .'"';
    $stmt = $conn->prepare($query);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      $stmt->setFetchMode(PDO::FETCH_ASSOC);
      $user = $stmt->fetchAll()[0];
      $_SESSION['user'] = $user;

      header('Location: dashboard.php');
    }else $err_msg = "Please insert correct username and password";

  }
 ?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>IMS Login - Inventory Management Sysem</title>
    <link rel="stylesheet" type="text/css" href="css/login.css">
  </head>
  <body id="loginBody" style="margin:0;">
    <?php if (!empty($err_msg)) {?>
      <div id="errTag">
        <p><b>Error:</b><br> <?= $err_msg ?></p>
      </div>
      <?php }?>
    <div class="container">
      <div class="loginHeaderContainer">
        <h1>Chandaria</h1>
        <p>Inventory Management System</p>
      </div>

      <div class="loginBodyContainer">
        <form class="" action="login.php" method="post">
          <div class="loginInputContainer">
            <label for="">Username</label>
            <input placeholder="username" type="text" name="username" value="">
          </div>
          <div class="loginInputContainer">
            <label for="">Password</label>
            <input placeholder="password" type="password" name="password" value="">
          </div>
          <div class="loginbtnContainer">
            <input type="submit" name="submit" value="Login">
          </div>
        </form>
      </div>
    </div>
  </body>
</html>
