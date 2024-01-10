<?php
  $purchase_orders = $_POST['payload'];

  include('connection.php');

  try {
    foreach ($purchase_orders as $po) {
      $delivered = (int) $po['qtyDelivered'];
      // zero delivered data is not saved
      if ($delivered > 0) {
        $curr_qty_received = (int) $po['qtyReceived'];
        $status = $po['status'];
        $row_id = $po['id'];
        $qty_ordered = (int) $po['qtyOrdered'];
        $product_id = (int) $po['pid'];

        // update qty recieved
        $updated_qty_received = $curr_qty_received + $delivered;
        $qty_remaining = $qty_ordered - $updated_qty_received;

        $sql = "UPDATE order_product
                  SET
                    quantity_received=?, status=?, quantity_remaining=?
                  WHERE id=?";

        $stmt = $conn->prepare($sql);
        $stmt->execute([$updated_qty_received, $status, $qty_remaining, $row_id]);

        // insert script adding to the order_product_history_table
        $delivery_history = [
          'order_product_id' => $row_id,
          'qty_received' => $delivered,
          'date_received' => date('Y-m-d H:i:s'),
          'date_updated' => date('Y-m-d H:i:s')
        ];
        $sql = "INSERT INTO order_product_history
                            (order_product_id, qty_received, date_received, date_updated)
                          VALUES
                            (:order_product_id, :qty_received, :date_received, :date_updated)";
        $stmt = $conn->prepare($sql);
        $stmt->execute($delivery_history);

        // script for updating the order quantity
        // select statement - to pull the current quantity of product

        $stmt = $conn->prepare("
          SELECT products.stock FROM products
          WHERE
            id = $product_id
        ");
        $stmt->execute();
        $product = $stmt->fetch();

        $cur_stock = (int) $product['stock'];

        // update statement - to add the delivered product to the curr quantity
        $updated_stock = $cur_stock + $delivered;
        $sql = "UPDATE products
                  SET
                    stock=?
                  WHERE id=?";

        $stmt = $conn->prepare($sql);
        $stmt->execute([$updated_stock, $product_id]);

      }
      $new_status = $po['status'];
      $the_row_id = $po['id'];
      $stmt = $conn->prepare("UPDATE order_product SET status = :new_status WHERE id = :the_row_id");
      $stmt->bindParam(':new_status', $new_status);
      $stmt->bindParam(':the_row_id', $the_row_id);
      $stmt->execute();

    }
    $response = [
      "success" => true,
      "message" => "Purchase order Successfully updated!"
    ];
  } catch (\Exception $e) {
    $response = [
      "success" => false,
      "message" => "Error processing your request!"
    ];
  }

  echo json_encode($response);




 ?>
