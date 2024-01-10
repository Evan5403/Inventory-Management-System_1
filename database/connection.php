

<?php
  $dsn = "mysql:host=localhost;dbname=inventory2;port=3307";
  $servername = 'localhost';
  $username = 'root';
  $password = '';
  $database = 'inventory2';

  try {
    $conn = new PDO($dsn,$username,$password);
    $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
  } catch (PDOException $e) {
    $err_msg = $e->getMessage();
  }

  // $conn = new mysqli($servername,$username,$password);
  // $err = $conn->connect_error;
  // if ($err) {
  //   die("Connection failed: ". $err);
  // }

  // try {
  //   $conn = new mysqli($servername,$username,$password);
  //   $err = $conn->connect_error;
  //   if ($err) {
  //     throw new Exception("Connection failed", $err);
  //   }
  // } catch (Exception $e) {
  //   $err = $e->getMessage();
  //
  // }



?>
