<!-- Modal -->
<?php require_once "incl/logic/add-edit-video.php"; ?>
<div class="modal" id="add-video-modal" tabindex="-1" aria-labelledby="AddVideoPopup" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header border-0">
        <button type="button" class="btn-close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <h3 class="text-dark" id="modal_video_title">Add a Video</h3>
        <form action="dashboard.php" method="POST" class="px-5" novalidate>

          <input type="hidden" id="video_id" name="video_id" value="0">
          <div class="input-group has-validation">
            <input type="text" id="video_url" name="url" class="form-control has-validation <?= ($video_url_error == '') ? '' : 'is-invalid' ?>" placeholder="http://" required value="<?= isset($video_url) ? $video_url : '' ?>">
            <div class="invalid-feedback mb-3" aria-describedby="video_url">
              <?= $video_url_error ?>
            </div>
          </div>
          <div class="input-group">
            <input type="text" id="video_title" name="title" class="form-control mt-3 has-validation <?= ($video_title_error == '') ? '' : 'is-invalid' ?>" placeholder="your title..." required value="<?= isset($video_title) ? $video_title : '' ?>">
            <div class="invalid-feedback mb-3" aria-describedby="video_url">
              <?= $video_title_error ?>
            </div>
          </div>

          <input type="submit" name="submit" id="submit_video" class="btn btn-primary mt-3" onclick="" value="Add"></input>
        </form>
      </div>
    </div>

  </div>
</div>