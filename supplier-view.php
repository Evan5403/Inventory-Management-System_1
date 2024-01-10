<?php
  session_start();

  if (!isset($_SESSION['user'])) header('location: login.php');

  $show_table = 'suppliers';
  $suppliers = include('database/show.php');


 ?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title></title>

    <?php include('partials/app-header-script.php') ?>

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
                <h1 class="sectionHeader"><span class="icon"><i class="fa fa-navicon"></i> </span>Supplier List</h1>
                <div class="section_content">
                  <div class="users">
                    <table class="productsTable">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>Supplier Name</th>
                          <th>Supplier Location</th>
                          <th>Contact Details</th>
                          <th>Products</th>
                          <th>Created By</th>
                          <th>Created At</th>
                          <th>Modified At</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php foreach ($suppliers as $index => $supplier) {?>
                          <tr>
                            <td><?= $index + 1 ?></td>
                            <td>
                              <?= $supplier['supplier_name'] ?>
                            </td>
                            <td><?= $supplier['supplier_location'] ?></td>
                            <td><?= $supplier['email'] ?></td>
                            <td>
                              <?php
                              $product_list = 'not set';
                              $sid = $supplier['id'];
                              $stmt = $conn->prepare("
                                SELECT product_name
                                FROM products,productsupplier
                                WHERE
                                  productsupplier.supplier=$sid
                                    AND
                                  productsupplier.product = products.id
                              ");
                              $stmt->execute();
                              $row = $stmt->fetchAll(PDO::FETCH_ASSOC);

                              if ($row) {
                                $product_arr = array_column($row,'product_name');
                                $product_list = "<li class='try'>".implode("</li><li class='try'>",$product_arr);
                              }
                              echo $product_list;
                             ?>
                            </td>
                            <td>
                              <?php
                                $uid = $supplier['created_by'];
                                $stmt = $conn->prepare("SELECT * FROM users WHERE id=$uid");
                                $stmt->execute();
                                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                                $created_by_name = $row['first_name'].' '.$row['last_name'];
                                echo $created_by_name;
                               ?>
                            </td>
                            <td><?= date('M d,Y@h:i:s A',strtotime($supplier['created_at'])) ?></td>
                            <td><?= date('M d,Y@h:i:s A',strtotime($supplier['updated_at'])) ?></td>
                            <td>
                              <a href="#" class="updateSupplier" data-sid="<?= $supplier['id']?>"><i class="fa fa-pencil"></i>Edit</a>
                              <a href="#" class="delSupplier" data-name="<?= $supplier['supplier_name']?>" data-sid="<?= $supplier['id']?>"><i class="fa fa-trash"></i>Delete</a>
                            </td>
                          </tr>
                        <?php } ?>
                      </tbody>
                    </table>
                    <p class="totalUsers"><?= count($suppliers); ?> Suppliers</p>
                  </div>
                </div>
              </div>



            </div>
          </div>
        </div>
      </div>
    </div>
  </body>
  <script type="text/javascript" src="script/index.js"></script>
  <script src="script/jquery/jquery-3.7.1.js"></script>

    <?php include('partials/app-scripts.php') ?>

    <?php
      include('partials/app-scripts.php');

      $show_table = 'products';
      $products = include('database/show.php');

      $product_arr = [];

      foreach ($products as $product) {
        $product_arr[$product['id']] = $product['product_name'];
      }
      $product_arr = json_encode($product_arr);

     ?>

  <script>
    var productList = <?= $product_arr ?>;

    function scripty() {
      var vm = this;

      this.registerEvents = function(){
        document.addEventListener('click',function(e) {
          targetElement = e.target //get the html target element
          classList = e.target.classList //return the classlists of the element

          if (classList.contains('delSupplier')) {
            e.preventDefault(); // stops the page from reloading once a link is clicked

            sId = targetElement.dataset.sid;
            supplierName = targetElement.dataset.name;

            BootstrapDialog.confirm({
              type: BootstrapDialog.TYPE_DANGER,
              title: 'Delete Supplier',
              message: 'Are you sure to delete <strong>'+supplierName+'</strong>?',
              callback: function(isDelete){
                if(isDelete){
                  $.ajax({
                    method: 'POST',
                    data: {
                      id: sId,
                      table: 'suppliers'
                    },
                    url: 'database/delete.php',
                    dataType: 'json',
                    success: function(data){
                      message = data.success ?
                        supplierName + ' succesfully deleted!' : 'Error processing your request!';

                      BootstrapDialog.alert({
                        type: data.success ? BootstrapDialog.TYPE_SUCCESS : BootstrapDialog.TYPE_DANGER,
                        message: message,
                        callback: function(){
                          if(data.success) location.reload();
                        }
                      })
                    }
                  })
                }
              }
            })

          }

          if (classList.contains('updateSupplier')) {
            e.preventDefault(); // stops the page from reloading once a link is clicked

            sId = targetElement.dataset.sid;
            // retrieving product details from the server
            vm.showEditDialog(sId);
          }

        })
        // //
        // // $('#editProductForm').on('submit',function(e){
        // //   e.preventDefault()
        // })

        document.addEventListener('submit',function(e){
          e.preventDefault()
          targetElement = e.target;

          if (targetElement.id === 'editSupplierForm') {
            vm.saveUpdatedData(targetElement);
          }
        })

      },

      this.saveUpdatedData = function(form){
        $.ajax({
          method: 'POST',
          data: {
            supplier_name: document.getElementById('supplier_name').value,
            supplier_location: document.getElementById('supplier_location').value,
            email: document.getElementById('email').value,
            products: $('#products').val(),
            sid: $('#sid').val()
          },
          url: 'database/update-supplier.php',
          dataType: 'json',
          success: function(data) {
            BootstrapDialog.alert({
              type: data.success ? BootstrapDialog.TYPE_SUCCESS : TYPE_DANGER,
              message: data.message,
              callback: function(){
                if(data.success) location.reload();
              }
            });
          }
        })
      },


      this.showEditDialog = function(id){
        $.get('database/get-supplier.php',{id: id}, function(supplierDetails){
          let curProducts = supplierDetails['products'];
          let productOptions = '';
          let selected = '';

          for(const [pId, pName] of Object.entries(productList)) {
            // selected = curProducts.indexOf(1) > -1 ? 'selected' : '';
            // productOptions += "<option "+ selected +" value='"+ pId +"'>"+ pName +"</option>";
            selected = curProducts.indexOf(parseInt(pId)) > -1 ? 'selected' : '';
            productOptions += "<option "+ selected +" value='"+ pId +"'>"+ pName +"</option>";
          }


          BootstrapDialog.confirm({
            title: 'Update <strong>' + supplierDetails.supplier_name+'</strong>',
            message: '<form action="database/add.php" method="post" enctype="multipart/form-data" id="editSupplierForm">\
              <div class="addInpTag">\
                <label for="supplier_name">Supplier Name</label>\
                <input type="text" class="addFormInp" id="supplier_name" value="'+ supplierDetails.supplier_name +'" name="supplier_name">\
              </div>\
              <div class="addInpTag">\
                <label for="supplier_location">Location</label>\
                <input type="text"  class="addFormInp productTxtArea" id="supplier_location" name="supplier_location" value="'+ supplierDetails.supplier_location +'">\
              </div>\
              <div class="addInpTag1">\
                <label for="email">Email</label>\
                <input type="text"  class="addFormInp productTxtArea" id="email" name="email" value="'+ supplierDetails.email +'">\
              </div>\
              <div class="addInpTag1">\
                <label for="description">Products</label>\
                <select name="products[]" id="products"\
                style="\
                  display: block;\
                  width: 100%;\
                  max-height:70px;\
                  height: 100%;\
                  border-color: #d2d2d2;"\
                multiple="">\
                  <option value="">Select Product</option>\
                  ' + productOptions + '\
                </select>\
              <input type="hidden" name="sid" id="sid" value="'+ supplierDetails.id +'" />\
              <input type="submit" value="submit" id="editSupplierSubmitBtn" class="hidden"/>\
            </form>\
            ',
            callback: function(isUpdate){
              if (isUpdate) {
                document.getElementById('editSupplierSubmitBtn').click();
              }
            }
          })
        }, 'json');
      }

      this.initialize = function(){
        this.registerEvents()
      }

    }

  var scripty = new scripty;
  scripty.initialize();
  </script>
</html>
