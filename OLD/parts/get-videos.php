<?php require_once "incl/logic/get-videos.php"; ?>

<?php if (count($videos) > 0) : ?>
    <p class="mb-5">You have <?= count($videos) ?> videos in your collection </p>
    <div class="d-flex flex-wrap  justify-content-evenly" style="gap: 25px;">
        <?php foreach ($videos as $video) : ?>
            <div class="video" style="width:250px;">
                <h5><?= $video['title'] ?></h5>
                <a href="video.php?id=<?= $video['id'] ?>"><img src="https://img.youtube.com/vi/<?=$video['yt_id']?>/hqdefault.jpg" alt="" class="img-fluid"></a>
                        <a href="video.php?id=<?= $video['id'] ?>"><i class="btn fa-brands fa-youtube text-info"></i></a>
                        <i data-id="<?=$video['id']?>" class="btn edit_video fa-solid fa-pen-to-square text-warning" data-bs-toggle="modal" data-bs-target="#add-video-modal"></i>
                        <!-- TODO : Add delete confirmation modal -->
                        <a href="incl/logic/delete-video.php?id=<?= $video['id'] ?>"><i class="btn fa-solid fa-trash text-danger"></i></a>
                    </p>
            </div>
        <?php endforeach ?>
    </div>
<?php else : ?>
    <h3>No Videos </h3>
<?php endif ?>