<?php
require("incl/parts/header.php");
require("incl/logic/notes.php");    ?>
<script src='/incl/js/notes.js' defer></script>
<div>
    <h1>
        Hi <?= $username ?><a id="btn_add" class="btn btn-primary mx-4" href="#" data-bs-toggle="modal" data-bs-target="#video_add">Add a video</a>
    </h1>
    <div class="d-none">
        <p>
        <h4>Your Collection</h4>
        <h6>Watch and manage your Collection and your Notes</h6>
        </p>
    </div>
    <div class="d-none">
        <p>
        <h4 class="text-info">You don't have any videos in your collection!'</h4>
        <h6>Click the button above to add some</h6>
        </p>
    </div>
</div>


<div class="modal fade text-black" id="video_add" tabindex="-1" aria-labelledby="VideAddPopup" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add a new Youtube Video</h5>
                <button type="button" class="btn btn-danger btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form-video-add" method="POST" action="<?= $_SERVER['PHP_SELF'] ?>" class="row g-3 needs-validation" novalidate>
                    <div>
                        <label for="url" class="form-label">Youtube link</label>
                        <input type="text" class="form-control" name="url" value="<?= !empty($url) ? $url : '' ?>" required>
                        <div class="valid-feedback">
                            Looks good!
                        </div>
                        <div class="invalid-feedback">
                            Looks bad!
                        </div>
                    </div>
                    <div>
                        <label for="title" class="form-label">Your custome title</label>
                        <input type="text" class="form-control" name="title" value="<?= !empty($title) ? $title : '' ?>" required>
                        <div class="valid-feedback">
                            Looks good!
                        </div>
                        <div class="invalid-feedback">
                            Looks bad!
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="form-video-add" class="btn btn-primary">Add</button>
            </div>
        </div>
    </div>
</div>

<?php require($_SERVER['DOCUMENT_ROOT'] . "/incl/parts/footer.php"); ?>