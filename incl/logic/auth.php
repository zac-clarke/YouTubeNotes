<?php
session_start();
$loggedin = false;
if(isset($_SESSION['username'])){
    $loggedin = true;
} 

?>