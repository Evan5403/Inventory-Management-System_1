<?php
  session_start();

  // capture table mappings
  include('table_columns.php');

  // capture table name in the database i.e the table name is dynamic
  $table = $_SESSION['table'];
  $columns = $table_columns_mapping[$table];

  // loop thru the cols
  $dbarr = [];
  $user = $_SESSION['user'];

  foreach ($columns as $col) {
    if(in_array($col,['created_at','updated_at'])) $value = date('Y-m-d H:i:s');
    else if ($col == 'created_by') $value = $user['id'];

    else if ($col == 'image'){
      // move our file to the project dir
      $target_dir = '../uploads/products/';
      $file_data = $_FILES[$col];

      $value = NULL;
      $file_data = $_FILES['image'];

      if ($file_data['tmp_name'] !== '') {
        $file_name = $file_data['name'];
        $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
        $file_name = 'product-'.time() ."." . $file_ext;

        $check = getimagesize($file_data['tmp_name']);

        // move the file...
        if ($check) {
          if(move_uploaded_file($file_data['tmp_name'], $target_dir . $file_name)){
            // saving the file in the dbase
            $value = $file_name;
          }
        }
      }
     }
    else $value = isset($_POST[$col]) ? $_POST[$col] : '';
    $dbarr[$col] = $value;
  }

  $table_properties = implode(", ", array_keys($dbarr));
  $table_placeholders = ':' . implode(", :", array_keys($dbarr));


  // Add record
  try {
    $sql = "INSERT INTO
                      $table($table_properties)
                      VALUES
                        ($table_placeholders)";

    include('connection.php');
    $stmt = $conn->prepare($sql);
    $stmt->execute($dbarr);

    // capture the saved product_id
    $product_id = $conn->lastInsertId();

    // add supplier
    if ($table === 'products') {
      $suppliers = isset($_POST['suppliers']) ? $_POST['suppliers'] : [];
      if ($suppliers) {
        // loop thru the suppliers and add the record
        foreach ($suppliers as $supplier) {
          $supplier_data = [
            'supplier_id' => $supplier,
            'product_id' => $product_id,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
          ];
          $sql = "INSERT INTO productsupplier
                              (supplier, product, created_at, updated_at)
                            VALUES
                              (:supplier_id, :product_id, :created_at, :updated_at)";
          $stmt = $conn->prepare($sql);
          $stmt->execute($supplier_data);
        }
      }
    }


    $response = [
      "success" => true,
      "message" => "Successfully added"
    ];
  } catch (PDOException $e) {
    $response = [
      "success" => true,
      "message" => $e->getMessage()
    ];
  }

  $_SESSION['response'] = $response;
  header('location: ../' . $_SESSION['redirect_to']);




 ?>
