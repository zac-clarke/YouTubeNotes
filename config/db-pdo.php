<?php
$dbname = 'yt_notes';
$host = 'localhost';
$user = 'root';
$password = '';

/* PDO (PHP Data Object) connection*/
//set Data Source Name

$dsn = "mysql:host=$host;dbname=$dbname;";


try {
    //create PDO object
$pdo = new PDO($dsn, $user, $password);

} catch (PDOException $e) {
    //echo $e->getMessage();
    die("Could not connect to database");
}