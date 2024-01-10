<?php
  session_start();

  if (!isset($_SESSION['user'])) header('location: login.php');

  $user = $_SESSION['user'];

 ?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title></title>
    <link rel="stylesheet" href="css/login.css">
  </head>
  <body id="dashboard_page">
    <div id="main_container">

      <?php include('partials/app-sidebar.php') ?>

      <div class="content_container" id="content_container">

        <?php include('partials/app-topnav.php') ?>

        <div class="content">
          <div class="content_main">
          </div>
        </div>
      </div>
    </div>
  </body>
  <script type="text/javascript" src="script/index.js">
  </script>
</html>
