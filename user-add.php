<?php
  session_start();

  if (!isset($_SESSION['user'])) header('location: login.php');
  $_SESSION['table'] = 'users';
  $_SESSION['redirect_to'] = 'user-add.php';

  $show_table = 'users';
  $users = include('database/show.php');



 ?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title></title>
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="css/css/all.css">
    <link rel="stylesheet" href="bootstrap-4.0.0-dist/css/bootstrap-grid.min.css">
    <link rel="stylesheet" href="bootstrap-4.0.0-dist/css/bootstrap.min.css">
  </head>
  <body id="dashboard_page">
    <div id="main_container">

      <?php include('partials/app-sidebar.php') ?>
      <div class="content_container" id="content_container">

        <?php include('partials/app-topnav.php') ?>
        <div class="content">
          <div class="content_main">
            <div class="row">

              <div class="col-12">
                <h1 class="sectionHeader"><span class="icon"><i class="fa fa-plus"></i></span> Add User</h1>
                    <div class="appFormTag">
                      <form class="addForm" action="database/add.php" method="post">
                        <div class="addInpTag">
                          <label for="first_name">First Name</label>
                          <input type="text" class="addFormInp" id="fname" name="first_name" value="">
                        </div>
                        <div class="addInpTag">
                          <label for="last_name">Last Name</label>
                          <input type="text" class="addFormInp" id="lname" name="last_name" value="">
                        </div>
                        <div class="addInpTag">
                          <label for="email">Email</label>
                          <input type="text" class="addFormInp" id="email" name="email" value="">
                        </div>
                        <div class="addInpTag">
                          <label for="password">Password</label>
                          <input type="password" class="addFormInp" id="password" name="password" value="">
                        </div>
                        <button type="submit" class="commonBtn"><b>&#43;</b>Add User</button>
                      </form>
                      <?php
                        if (isset($_SESSION['response'])) {
                          $response_msg = $_SESSION['response']['message'];
                          $success = $_SESSION['response']['success'];
                      ?>
                        <div class="responseMsg">
                          <p class="responseMsg <?= $success ? 'responseMsg_success' : 'responseMsg_error'?>">
                            <?= $response_msg; ?>
                          </p>
                        </div>
                      <?php unset($_SESSION['response']); } ?>
                    </div>
                </div>

          </div>
        </div>
      </div>
    </div>
  </body>
  <script type="text/javascript" src="script/index.js"></script>
  <script src="script/jquery/jquery-3.7.1.js"></script>

  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  <script src="bootstrap-4.0.0-dist/js/bootstrap.min.js"></script>
</html>
