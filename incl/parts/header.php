<?php
require_once "config/db.php";
require_once ("incl/logic/auth.php");


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>YouTube Annotations</title>
    <!-- CSS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="css/style.css">
    
</head>

<body class="d-flex h-100 text-center text-bg-light" style="background-color:#E8E8E8!important">
    <div class="cover-container d-flex w-100 h-100 p-3 mx-auto flex-column">
        <!-- Header -->
        <header class="mb-auto">
            <div>
                <a href="index.php">
                    <h3 class="float-md-start mb-0">Logo</h3>
                </a>
                <!-- NAV -->
                <nav class="nav nav-masthead justify-content-center float-md-end">
                    
                    <?php if (!$loggedin) : ?>
                        <a class="nav-link btn fw-bold py-1 px-1" href="#" id="btn_login" data-bs-toggle="modal" data-bs-target="#login-modal">Login</a>
                        <a class="nav-link btn fw-bold py-1 px-1" href="#" data-bs-toggle="modal" data-bs-target="#signup">Signup</a>
                    <?php else : ?>
                        <a class="nav-link fw-bold py-1 px-1 active" aria-current="page" href="dashboard.php">Dashboard</a>
                        <a class="nav-link fw-bold py-1 px-1" href="incl/logic/logout.php">Logout</a>
                    <?php endif ?>
                </nav>
            </div>
        </header>