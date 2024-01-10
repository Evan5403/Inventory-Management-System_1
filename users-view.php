<?php
  session_start();

  if (!isset($_SESSION['user'])) header('location: login.php');
  $_SESSION['table'] = 'users';
  $user = $_SESSION['user'];

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
                <h1 class="sectionHeader"><span class="icon"><i class="fa fa-navicon"></i></span>List Of Users</h1>
                <div class="section_content">
                  <div class="users">
                    <table>
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>First Name</th>
                          <th>Last Name</th>
                          <th>Email</th>
                          <th>Created At</th>
                          <th>Modified At</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php foreach ($users as $index => $user) {?>
                          <tr>
                            <td><?= $index + 1 ?></td>
                            <td class="firstName"><?= $user['first_name'] ?></td>
                            <td class="lastName"><?= $user['last_name'] ?></td>
                            <td class="email"><?= $user['email'] ?></td>
                            <td><?= date('M d,Y@h:i:s A',strtotime($user['created_at'])) ?></td>
                            <td><?= date('M d,Y@h:i:s A',strtotime($user['updated_at'])) ?></td>
                            <td>
                              <a href="#" class="updateUser" data-userid="<?= $user['id']?>"><i class="fa fa-pencil"></i>Edit</a>
                              <a href="#" class="delUser" data-userid="<?= $user['id']?>" data-fname="<?= $user['first_name']?>" data-lname="<?= $user['last_name']?>"><i class="fa fa-trash"></i>Delete</a>
                            </td>
                          </tr>
                        <?php } ?>
                      </tbody>
                    </table>
                    <p class="totalUsers"><?= count($users); ?> Users</p>
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

    <!-- Latest compiled and minified CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

  <!-- Optional theme -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

  <!-- Latest compiled and minified JavaScript -->
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap3-dialog/1.35.4/js/bootstrap-dialog.js" integrity="sha512-AZ+KX5NScHcQKWBfRXlCtb+ckjKYLO1i10faHLPXtGacz34rhXU8KM4t77XXG/Oy9961AeLqB/5o0KTJfy2WiA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>



  <script>
    function scripty() {

      this.initialize = function() {
        this.registerEvents();
      },

      this.registerEvents = function() {
        document.addEventListener('click',function(e) {
          targetElement = e.target
          classList = e.target.classList

          if (classList.contains('delUser')) {
            e.preventDefault(); // stops the page from reloading once a link is clicked
            userId = targetElement.dataset.userid;
            fname = targetElement.dataset.fname;
            lname = targetElement.dataset.lname;
            fullname = fname + ' ' + lname

            BootstrapDialog.confirm({
              type: BootstrapDialog.TYPE_DANGER,
              title: 'Delete User',
              message: 'Are you sure to delete <strong>'+fullname+'</strong>?',
              callback: function(isDelete){
                if(isDelete){
                  $.ajax({
                    method: 'POST',
                    data: {
                      id: userId,
                      table: 'users'
                    },
                    url: 'database/delete.php',
                    dataType: 'json',
                    success: function(data){
                      message = data.success ?
                        '<strong>'+fullname+'</strong>' + ' succesfully deleted!' : 'Error processing your request!';

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

          if (classList.contains('updateUser')) {
            e.preventDefault(); //prevents the page from reloading

            // get data
            userId = targetElement.dataset.userid
            firstName = targetElement.closest('tr').querySelector('td.firstName').innerHTML
            lastName = targetElement.closest('tr').querySelector('td.lastName').innerHTML
            email = targetElement.closest('tr').querySelector('td.email').innerHTML
            fullname = firstName + ' ' + lastName
            // console.log(fullname,email);

            BootstrapDialog.confirm({
              title: 'Update ' + firstName + ' ' + lastName,
              message: '<form action="">\
                    <div class="form-group">\
                     <label for="firstName">First Name:</label>\
                     <input type="text" class="form-control" id="firstName" value="'+firstName+'">\
                     </div>\
                     <label for="lastName">Last Name:</label>\
                     <input type="text" class="form-control" id="lastName" value="'+lastName+'">\
                     </div>\
                     <label for="email">Email address:</label>\
                     <input type="email" class="form-control" id="emailUpdate" value="'+email+'">\
                     </div>\
                     </form>',
              callback: function(isUpdate){
                if (isUpdate) {
                  $.ajax({
                    method: 'POST',
                    data: {
                      user_id: userId,
                      f_name: document.getElementById('firstName').value,
                      l_name: document.getElementById('lastName').value,
                      email: document.getElementById('emailUpdate').value
                    },
                    url: 'database/update-user.php',
                    dataType: 'json',
                    success: function(data) {
                      if(data.success){
                        BootstrapDialog.alert({
                          type: BootstrapDialog.TYPE_SUCCESS,
                          message: data.message,
                          callback: function() {
                            location.reload()
                          }
                        })
                      } else {
                        BootstrapDialog.alert({
                          type: BootstrapDialog.TYPE_DANGER,
                          message: data.message
                        })
                      }
                    }
                  })
                }
              }
            })
          }
        })
      }
    }

    var scripty = new scripty;
    scripty.initialize();
  </script>
</html>
