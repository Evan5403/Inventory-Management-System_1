<?php
  include('connection.php');
  $id = $_GET['id'];

  // fetch suppliers
  $stmt = $conn->prepare("
    SELECT supplier_name, suppliers.id
      FROM suppliers,productsupplier
      WHERE
        productsupplier.product=$id
          AND
        productsupplier.supplier = suppliers.id
  ");
  $stmt->execute();
  $suppliers = $stmt->fetchAll(PDO::FETCH_ASSOC);

  echo json_encode($suppliers);
 ?>
