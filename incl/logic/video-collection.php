<?php
require_once "incl/logic/sanitize.php";

$qry = "SELECT id, url, title FROM videos WHERE userid=?";
$stmt = mysqli_prepare($conn, $qry);
mysqli_stmt_bind_param($stmt, "i", $_SESSION['user_id']);
mysqli_stmt_execute($stmt) or die("Server error : update video failed");
mysqli_stmt_store_result($stmt);

if (mysqli_stmt_num_rows($stmt) > 0) {

    mysqli_stmt_bind_result($stmt, $id, $url, $title);
    while (mysqli_stmt_fetch($stmt)) : ?>
        <h4><?= $title ?></h4>
        <p><?=$url?></p>
        <button type="button" data-id="<?= $id ?>" class="btn_add_video btn btn-primary" data-bs-toggle="modal" data-bs-target="#add-video-modal">
            Edit
        </button>
<?php endwhile;
} else {
    echo "no videos";
}
