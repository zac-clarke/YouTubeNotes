<?php
$db_name = 'yt_notes';
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$conn = mysqli_connect($db_host, $db_user, $db_pass) or die("connection error");

echo "connected to database";