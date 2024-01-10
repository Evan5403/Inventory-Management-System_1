<?php
  session_start();

  if (!isset($_SESSION['user'])) header('location: login.php');

  $show_table = 'products';
  $products = include('database/show.php');



 ?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title></title>
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="css/css/all.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap3-dialog/1.35.4/css/bootstrap-dialog.min.css" integrity="sha512-PvZCtvQ6xGBLWHcXnyHD67NTP+a+bNrToMsIdX/NUqhw+npjLDhlMZ/PhSHZN4s9NdmuumcxKHQqbHlGVqc8ow==" crossorigin="anonymous" referrerpolicy="no-referrer" />
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
                <h1 class="sectionHeader"><span class="icon"><i class="fa fa-navicon"></i> </span>Product List</h1>
                <div class="section_content">
                  <div class="users">
                    <table class="productsTable">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>Image</th>
                          <th>Product Name</th>
                          <th>Stock</th>
                          <th>Description</th>
                          <th>Suppliers</th>
                          <th>Created By</th>
                          <th>Created At</th>
                          <th>Modified At</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php foreach ($products as $index => $product) {?>
                          <tr>
                            <td><?= $index + 1 ?></td>
                            <td>
                              <img class="productImgs" src="uploads/products/<?=$product['image']?>" alt="">
                            </td>
                            <td><?= $product['product_name'] ?></td>
                            <td><?= number_format($product['stock']) ?></td>
                            <td><?= $product['description'] ?></td>
                            <td>

                              <?php
                              $supplier_list = 'not set';
                              $pid = $product['id'];
                              $stmt = $conn->prepare("
                                SELECT supplier_name
                                  FROM suppliers,productsupplier
                                  WHERE
                                    productsupplier.product=$pid
                                      AND
                                    productsupplier.supplier = suppliers.id
                              ");
                              $stmt->execute();
                              $row = $stmt->fetchAll(PDO::FETCH_ASSOC);

                              if ($row) {
                                $supplier_arr = array_column($row,'supplier_name');
                                $supplier_list = "<li class='try'>".implode("</li><li class='try'>",$supplier_arr);
                              }
                              echo $supplier_list;
                             ?>
                            </td>
                            <td>
                              <?php
                                $uid =$product['created_by'];
                                $stmt = $conn->prepare("SELECT * FROM users WHERE id=$uid");
                                $stmt->execute();
                                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                                $created_by_name = $row['first_name'].''.$row['last_name'];
                                echo "$created_by_name";
                               ?>
                            </td>
                            <td><?= date('M d,Y@h:i:s A',strtotime($product['created_at'])) ?></td>
                            <td><?= date('M d,Y@h:i:s A',strtotime($product['updated_at'])) ?></td>
                            <td>
                              <a href="#" class="updateProduct" data-pid="<?= $product['id']?>"><i class="fa fa-pencil"></i>Edit</a>
                              <a href="#" class="delProduct" data-name="<?= $product['product_name']?>" data-pid="<?= $product['id']?>"><i class="fa fa-trash"></i>Delete</a>
                            </td>
                          </tr>
                        <?php } ?>
                      </tbody>
                    </table>
                    <p class="totalUsers"><?= count($products); ?> Products</p>
                  </div>
                </div>
              </div>



            </div>
          </div>
        </div>
      </div>
    </div>
  </body>

  <?php include('partials/app-scripts.php'); ?>

  <?php
    include('partials/app-scripts.php');

    $show_table = 'suppliers';
    $suppliers = include('database/show.php');

    $suppliers_arr = [];

    foreach ($suppliers as $supplier) {
      $suppliers_arr[$supplier['id']] = $supplier['supplier_name'];
    }
    $suppliers_arr = json_encode($suppliers_arr);

   ?>

<script>
  var suppliersList = <?= $suppliers_arr ?>;

  function scripty() {
    var vm = this;

    this.registerEvents = function(){
      document.addEventListener('click',function(e) {
        targetElement = e.target //get the html target element
        classList = e.target.classList //return the classlists of the element

        if (classList.contains('delProduct')) {
          e.preventDefault(); // stops the page from reloading once a link is clicked

          pId = targetElement.dataset.pid;
          pname = targetElement.dataset.name;

          BootstrapDialog.confirm({
            type: BootstrapDialog.TYPE_DANGER,
            title: 'Delete Product',
            message: 'Are you sure to delete <strong>'+pname+'</strong> product?',
            callback: function(isDelete){
              if(isDelete){
                $.ajax({
                  method: 'POST',
                  data: {
                    id: pId,
                    table: 'products'
                  },
                  url: 'database/delete.php',
                  dataType: 'json',
                  success: function(data){
                    message = data.success ?
                      pname + ' succesfully deleted!' : 'Error processing your request!';

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

        if (classList.contains('updateProduct')) {
          e.preventDefault(); // stops the page from reloading once a link is clicked

          pId = targetElement.dataset.pid;
          // retrieving product details from the server
          vm.showEditDialog(pId);
        }

      })

      $('#editProductForm').on('submit',function(e){
        e.preventDefault()
      })

      document.addEventListener('submit',function(e){
        e.preventDefault()
        targetElement = e.target;

        if (targetElement.id === 'editProductForm') {
          vm.saveUpdatedData(targetElement);
        }
      })

    },

    this.saveUpdatedData = function(form){
      $.ajax({
        method: 'POST',
        data: new FormData(form),
        url: 'database/update-product.php',
        processData: false,
        contentType: false, //the two sth ensures we dont send our form data in a string format
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
      $.get('database/get-product.php',{id: id}, function(productDetails){
        let curSuppliers = productDetails['suppliers'];
        let supplierOption = '';
        selected = '';

        for(const [supId, supName] of Object.entries(suppliersList)) {
          // selected = suppliersList.hasOwnProperty(supId) ? 'selected' : '';
          // if(curSuppliers.indexOf(parseInt(supId)) > -1){
          //   selected = 'selected';
          // } else {
          //   select = '';
          // }
          selected = curSuppliers.indexOf(parseInt(supId)) > -1 ? 'selected' : '';
          supplierOption += "<option "+ selected +" value='"+ supId +"'>"+ supName +"</option>";
        }

        console.log(supplierOption);

        BootstrapDialog.confirm({
          title: 'Update <strong>' + productDetails.product_name+'</strong>',
          message: '<form action="database/userdb-add.php" method="post" enctype="multipart/form-data" id="editProductForm">\
            <div class="addInpTag">\
              <label for="product_name">Product Name</label>\
              <input type="text" class="addFormInp" id="product_name" name="product_name" value="'+productDetails.product_name+'">\
            </div>\
            <div class="addInpTag1">\
              <label for="description">Suppliers</label>\
              <select name="suppliers[]" class="" id="suppliersSelect" multiple="">\
                <option value="">Select Supplier</option>\
                ' + supplierOption + '\
              </select>\
            </div>\
            <div class="addInpTag">\
              <label for="description">Description</label>\
              <textarea  class="addFormInp productTxtArea" id="description" name="description">\
              '+productDetails.description+'</textarea>\
            </div>\
            <div class="addInpTag">\
              <label for="product_name">Product Image</label>\
              <input type="file" class="addFormInp" id="image" name="img">\
            </div>\
            <input type="hidden" name="pid" value="'+productDetails.id+'" />\
            <input type="submit" value="submit" id="editProductSubmitBtn" class="hidden"/>\
          </form>\
          ',
          callback: function(isUpdate){
            if (isUpdate) {
              document.getElementById('editProductSubmitBtn').click();
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
