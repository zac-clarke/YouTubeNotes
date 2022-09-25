<?php
require($_SERVER['DOCUMENT_ROOT'] . "/config/db-pdo.php");
require($_SERVER['DOCUMENT_ROOT'] . "/incl/logic/auth.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>YouTube Annotations</title>
    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
</head>

<body class="d-flex h-100 text-center text-bg-dark">
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
                        <a class="nav-link btn fw-bold py-1 px-1" id="btn_login" href="#" data-bs-toggle="modal" data-bs-target="#login">Login</a>
                        <a class="nav-link btn fw-bold py-1 px-1" href="#" data-bs-toggle="modal" data-bs-target="#signup">Signup</a>
                    <?php else : ?>
                        <a class="nav-link fw-bold py-1 px-1 active" aria-current="page" href="dashboard.php">Dashboard</a>
                        <a class="nav-link fw-bold py-1 px-1" href="incl/logic/logout.php">Logout</a>
                    <?php endif ?>
                </nav>
            </div>
        </header>