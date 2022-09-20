<?php
require 'db.php';

mysqli_query($conn, "DROP DATABASE IF EXISTS `$db_name`");