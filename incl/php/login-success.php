<?php 
//TESTING
session_start();
$_SESSION['user'] = 'juju'; 
header('location: ../../index.php');