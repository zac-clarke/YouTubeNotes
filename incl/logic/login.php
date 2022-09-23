<?php
//check in user in database (login logic)


if(isset($_POST['submit'])){
  $user = $_POST['username'];
  $password = $_POST['password'];
  if(!empty($user) && $password == "123") {
    //sucessful login
    $_SESSION['username'] = $user; 
    header('location: index.php');
  }
}
?>