<?php
require('../../config/db.php');
require('auth.php');
require('sanitize.php');

//restrict access
if (!$loggedin) {
  http_response_code(401);
  die;
}


if ($_SERVER['REQUEST_METHOD'] === 'GET') {

  if (isset($_GET['id'])) {
    $id = sanitize($_GET['id']);

    $qry = "DELETE FROM videos WHERE id=?";
    $stmt = mysqli_prepare($conn, $qry);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt) or die("Server error : Delete failed");
    
  }
}

header('location: ../../dashboard.php');