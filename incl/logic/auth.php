<?php
session_start();
$loggedin = false;
if(isset($_SESSION['username']) && isset($_SESSION['user_id'])){
    $loggedin = true;
    $username = $_SESSION['username'];
    $user_id = $_SESSION['user_id'];
}
