<?php
  include('connection.php');

  $stmt = $conn->prepare("SELECT id, supplier_name FROM suppliers");
  $stmt->execute();
  $rows = $stmt->fetchAll();

  $categories = [];
  $bar_chart_data = [];
  $colors = ['#ff0000', '#0000ff', '#add8e6', '#800080', '#00ff00', '#ffa500', '#800000'];
  $counter = 0;

  foreach ($rows as $key => $row) {
    $id = $row['id'];

    $categories[] = $row['supplier_name'];

    $stmt = $conn->prepare("SELECT COUNT(*) as p_count FROM productsupplier WHERE productsupplier.supplier='". $id ."'");
    $stmt->execute();
    $row = $stmt->fetch();

    $count = $row['p_count'];
    if (!isset($colors[$key])) $counter = 0;
    $bar_chart_data[] = [
      'y' => $count,
      'color' => $colors[$counter]
    ];
    $counter++;
  }




 ?>
