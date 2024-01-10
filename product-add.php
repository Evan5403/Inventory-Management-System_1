<?php
  session_start();

  if (!isset($_SESSION['user'])) header('location: login.php');
  $_SESSION['table'] = 'products';
  $_SESSION['redirect_to'] = 'product-add.php';

  $user = $_SESSION['user'];

 ?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title></title>
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="css/css/all.css">
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
                <h1 class="sectionHeader"><i class="fa fa-plus"></i> Create Product</h1>

                    <div class="appFormTag">
                      <form class="addForm" action="database/add.php" method="post" enctype="multipart/form-data">
                        <div class="addInpTag">
                          <label for="product_name">Product Name</label>
                          <input type="text" class="addFormInp" id="product_name" name="product_name" value="">
                        </div>
                        <div class="addInpTag">
                          <label for="description">Description</label>
                          <textarea  class="addFormInp productTxtArea" id="description" name="description" value="">
                          </textarea>
                        </div>
                        <div class="addInpTag1">
                          <label for="description">Suppliers</label>
                          <select name="suppliers[]" class="" id="suppliersSelect" multiple="">
                            <option value="">Select Supplier</option>
                            <?php
                              $show_table = 'suppliers';
                              $suppliers = include('database/show.php');

                              foreach ($suppliers as $supplier) {
                                echo "<option value='".$supplier['id']."'>".$supplier['supplier_name']."</option>";
                              }

                             ?>
                          </select>
                        </div>
                        <div class="addInpTag">
                          <label for="product_name">Product Image</label>
                          <input type="file" class="addFormInp" id="image" name="image" value="">
                        </div>
                        <button type="submit" class="commonBtn"><i class="fa fa-plus"></i>Create Product</button>
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
</html>
