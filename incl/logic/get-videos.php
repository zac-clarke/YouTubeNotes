<?php

$qry = "SELECT id, yt_id, url, title FROM videos WHERE userid=?";
$stmt = mysqli_prepare($conn, $qry);
mysqli_stmt_bind_param($stmt, "i", $_SESSION['user_id']);
mysqli_stmt_execute($stmt) or die("Server error : update video failed");
mysqli_stmt_store_result($stmt);

if (mysqli_stmt_num_rows($stmt) > 0) {

    mysqli_stmt_bind_result($stmt, $id, $yt_id, $url, $title);
    $videos = [];
    while (mysqli_stmt_fetch($stmt)) {
        $video = [
            'id' => $id,
            'yt_id' => $yt_id,
            'url' => $url,
            'title' => $title
            
        ];
        array_push($videos, $video);
    }
    
    mysqli_stmt_close($stmt);
}