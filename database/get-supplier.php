<?php
  include('connection.php');
  $id = $_GET['id'];

  $stmt = $conn->prepare("SELECT * FROM suppliers WHERE id=$id");
  $stmt->execute();
  $row = $stmt->fetch(PDO::FETCH_ASSOC);

  // fetch products
  $stmt = $conn->prepare("
    SELECT product_name, products.id
      FROM products,productsupplier
      WHERE
        productsupplier.supplier=$id
          AND
        productsupplier.product = products.id
  ");
  $stmt->execute();
  $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

  $row['products'] = array_column($products, 'id');

  echo json_encode($row);
 ?>
