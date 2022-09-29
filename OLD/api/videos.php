<?php

use LDAP\Result;

require('../config/db.php');
require('../incl/logic/auth.php');

//restrict access
if (!$loggedin) {
  http_response_code(401);
  die;
}


if ($_SERVER['REQUEST_METHOD'] === 'GET') {

  if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $qry = "SELECT url, title FROM videos WHERE id=?";
    $stmt = mysqli_prepare($conn, $qry);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt) or die("Server error : login failed");
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) == 1) {
      mysqli_stmt_bind_result($stmt, $url, $title);
      mysqli_stmt_fetch($stmt);
      echo json_encode(['url' => $url, 'title' => $title]);
    }
  } else {
    // not using this currently
    $qry = "SELECT id, url, title FROM videos WHERE userid=?";
    $stmt = mysqli_prepare($conn, $qry);
    mysqli_stmt_bind_param($stmt, "i", $_SESSION['user_id']);
    mysqli_stmt_execute($stmt) or die("Server error : update video failed");
    $result = mysqli_stmt_get_result($stmt);
    $data = mysqli_fetch_all($result, MYSQLI_ASSOC);
    echo json_encode($data);
  }
}
