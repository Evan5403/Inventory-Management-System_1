<?php
  $data = $_POST;
  $id = (int) $data['id'];
  $table = $data['table'];

  try {
    include('connection.php');

    // delete the junction table i.e productsupplier table
    if ($table === 'suppliers') {
      $supplier_id = $id;

      $command = "DELETE FROM productsupplier WHERE supplier={$id}";
      $conn->exec($command);
    }

    if ($table === 'products') {
      $product_id = $id;

      $command = "DELETE FROM productsupplier WHERE product={$id}";
      $conn->exec($command);
    }

    // delete main table
    $command = "DELETE FROM $table WHERE id={$id}";
    $conn->exec($command);

    echo json_encode([
      'success' => true,
    ]);
  } catch (PDOException $e) {
    echo json_encode([
      'success' => false,
    ]);
  };
 ?>
