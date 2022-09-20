<?php
$conn = mysqli_connect("localhost", "root", "");
$db_name = "yt_notes";

mysqli_query($conn, "CREATE DATABASE IF NOT EXISTS $db_name;");
mysqli_select_db($conn, $db_name);

//Create TABLES
mysqli_query($conn, "CREATE TABLE IF NOT EXISTS `users` (
    id INT AUTO_INCREMENT NOT NULL,
    username VARCHAR(15) NOT NULL,
    email VARCHAR(35) NOT NULL,
    password VARCHAR(32) NOT NULL,
    trn_date DATETIME NOT NULL DEFAULT (CURRENT_DATE),
    CONSTRAINT pk_users_id PRIMARY KEY (id asc),
    CONSTRAINT uq_users_username UNIQUE (username),
    CONSTRAINT uq_users_email UNIQUE (email)
);");

mysqli_query($conn, "CREATE TABLE IF NOT EXISTS `videos` (
    id int AUTO_INCREMENT NOT NULL,
    userid int NOT NULL,
    url VARCHAR(128) NOT NULL,
    title VARCHAR(128) NOT NULL,
    trn_date DATETIME NOT NULL DEFAULT (CURRENT_DATE),
    CONSTRAINT pk_videos_id PRIMARY KEY (id asc),
    CONSTRAINT fk_videos_userid FOREIGN KEY (userid) REFERENCES `users` (id)
);");

mysqli_query($conn, "CREATE TABLE IF NOT EXISTS `notes` (
    id int AUTO_INCREMENT NOT NULL,
    videoid int NOT NULL,
    note TEXT NOT NULL,
    timestamp float NOT NULL,
    trn_date DATETIME NOT NULL DEFAULT (CURRENT_DATE),
    CONSTRAINT pk_notes_id PRIMARY KEY (id asc),
    CONSTRAINT fk_notes_videoid FOREIGN KEY (videoid) REFERENCES `videos` (id)
);");
