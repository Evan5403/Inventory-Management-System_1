<?php
  session_start();

  if (!isset($_SESSION['user'])) header('location: login.php');

  $show_table = 'products';
  $products = include('database/show.php');
  $products = json_encode($products);

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
                <h1 class="sectionHeader"><i class="fa fa-plus"></i> Order Product</h1>

                <div>
                  <form class="" action="database/save-order.php" method="post">
                    <div class="alignRight">
                      <input type="button" name="orderProductBtn" id="orderProductBtn" value="Add Another Product" class="orderBtn orderProductBtn">
                    </div>

                    <div id="orderProductLst">
                      <p id="noData" style="color: #9f9f9f">No order selected.</p>
                    </div>

                    <div class="alignRight marginTop-15">
                      <input type="submit" name="orderProductBtn" value="Submit Order" class="orderBtn submitOrderProductBtn">
                    </div>
                  </form>
                </div>

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

    <script type="text/javascript">
      let products = <?= $products ?>;

      function script() {
        var vm = this;
        var counter = 0;

        let productOptions = '\
          <div class="" style="margin-bottom: 7px;">\
          <label for="product_name">PRODUCT NAME</label>\
          <select class="productNameSelect" name="products[]" id="product_name">\
            <option value="">Select Product</option>\
            INSERTPRODUCTHERE\
          </select>\
          <input class="appbtn removeOrderBtn" type="submit" value="Remove">\
          </div>';

        this.initialize = function() {
          this.registerEvents();
          this.renderproductOptions();
        },

        this.renderproductOptions = function() {
          let optionHtml = '';
          products.forEach((product) => {
            optionHtml += '<option value="'+product.id+'">'+product.product_name+'</option>'
          });
          productOptions = productOptions.replace('INSERTPRODUCTHERE',optionHtml)
        }

        this.registerEvents = function(){

            document.addEventListener('click',function(e) {
              targetElement = e.target //get the html target element
              classList = e.target.classList //return the classlists of the element

              // Add new product order event
              if (targetElement.id === 'orderProductBtn') {
                document.getElementById('noData').style.display = 'none';
                let orderProductLstContainer = document.getElementById('orderProductLst');

                orderProductLst.innerHTML +=  '\
                  <div class="orderProductRow">\
                     '+productOptions+'\
                     <div class="suppliersRows" id="supplierRows_'+counter+'"data-counter="'+counter+'"></div>\
                  </div>';

                  counter++;
              }

              // if remove btn is clicked
              if (targetElement.classList.contains('removeOrderBtn')) {
                let orderRow = targetElement.closest('div.orderProductRow');

                // remove element
                console.log(orderRow);
                orderRow.remove();
              }

            });
            document.addEventListener('change',function(e) {
              targetElement = e.target //get the html target element
              classList = e.target.classList //return the classlists of the element

              // Add Supplier row on product option change
              if(classList.contains('productNameSelect')){
                let pid = targetElement.value;
                counterId = targetElement.closest('div.orderProductRow').querySelector('.suppliersRows').dataset.counter;

                $.get('database/get-product-suppliers.php', {id:pid}, function(suppliers) {
                  vm.rendersupplierRows(suppliers,counterId);
                }, 'json');
              }

            });
        },
        this.rendersupplierRows = function(suppliers, counterId){
          let supplierRows = '';

          suppliers.forEach((supplier) => {
            supplierRows += '\
              <div class="row">\
                <div class="col-6">\
                  <p class="supName">'+supplier.supplier_name+'</p>\
                </div>\
                <div class="col-6">\
                  <label for="quantity" class="blacklbl">Quantity: </label>\
                  <input type="number" class="addFormInp orderProductQty" id="quantity" name="quantity['+ counterId +']['+supplier.id+']" placeholder="Enter quantity">\
                </div>\
              </div>'
          })
          // append to container
          let supplierRowContainer = document.getElementById('supplierRows_'+counterId);
          supplierRowContainer.innerHTML = supplierRows;
        }
      }

      (new script()).initialize();
    </script>
  </body>
  <script type="text/javascript" src="script/index.js"></script>
  <script src="script/jquery/jquery-3.7.1.js"></script>
</html>
