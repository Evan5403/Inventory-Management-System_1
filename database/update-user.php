<?php
  date_default_timezone_set('Africa/Nairobi');

  $data = $_POST;
  $user_id = (int) $data['user_id'];
  $f_name = $data['f_name'];
  $l_name = $data['l_name'];
  $email = $data['email'];

  try {
    $sql = "UPDATE users SET  email=?, first_name=?, last_name=?, updated_at=? WHERE id=?";
    include('connection.php');
    $conn->prepare($sql)->execute([$email, $f_name, $l_name, date('Y-m-d h:i:s'), $user_id]);

    echo json_encode([
      'success' => true,
      'message' =>  "<strong>$f_name $l_name</strong> updated succesfully"
    ]);
  } catch (PDOException $e) {
    echo json_encode([
      'success' => false,
      'message' => 'error occured!'.$e->getMessage()
    ]);
  };
 ?>
