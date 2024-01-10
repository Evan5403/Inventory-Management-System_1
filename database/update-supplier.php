<?php
  $supplier_name = isset($_POST['supplier_name']) ? $_POST['supplier_name'] : '';
  $supplier_location = isset($_POST['supplier_location']) ? $_POST['supplier_location'] : '';
  $email = isset($_POST['email']) ? $_POST['email'] : '';

  $supplier_id = $_POST['sid'];


  try {
    // update the product method
    $sql = "UPDATE suppliers
              SET
                supplier_name=?, supplier_location=?, email=?
              WHERE id=?";

    include('connection.php');
    $stmt = $conn->prepare($sql);
    $stmt->execute([$supplier_name, $supplier_location, $email, $supplier_id]);

    // deletes the old values in the conjuction table
    $sql = "DELETE FROM productsupplier WHERE supplier=?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$supplier_id]);

    // get products
    $products = isset($_POST['products']) ? $_POST['products'] : [];
    foreach ($products as $product) {
      $supplier_data = [
        'supplier_id' => $supplier_id,
        'product_id' => $product,
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

    $response = [
      "success" => true,
      "message" => "<strong>$supplier_name</strong> successfully updated!"
    ];
  } catch (\Exception $e) {
    $response = [
      "success" => false,
      "message" => "Error processing your request!"
    ];
  }

  echo json_encode($response);

 ?>
