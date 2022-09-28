<!-- Modal -->
<?php require_once "incl/logic/add-edit-video.php"; 
?>

<div class="modal" id="video-modal" tabindex="-1" aria-labelledby="AddVideoPopup" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header border-0">
        <button type="button" class="btn-close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <h3 class="text-dark" id="modal_title">Add a Video</h3>


        <!-- CLIENT SIDE ERROR HANDLING -->
        <form action="dashboard.php" method="POST" class="px-5 needs-validation" novalidate>

          <input type="hidden" id="id" name="id" value="0">
          <div class="input-group has-validation">
         
            <input pattern="<?= VIDEO_URL_REGEX ?>" max=<?= VIDEO_URL_MAX ?> type="text" id="url" name="url" class="form-control has-validation" placeholder="http://"  value="" pattern="^(https?\:\/\/)?((www\.)?youtube\.com|youtu\.be)\/.+$" title="A youtube video url" required>
            <div class="invalid-feedback mb-3" aria-describedby="url">
              Please enter a valid Youtube URL (max <?= VIDEO_URL_MAX ?> characters)
            </div>
            <div class="valid-feedback mb-3" aria-describedby="url">
              Youtube URL OK!
            </div>
          </div>
          <div class="input-group">
            <input type="text" maxlength=<?= VIDEO_TITLE_MAX ?> id="title" name="title" class="form-control mt-3 has-validation" placeholder="your title..." required value="">
            <div class="invalid-feedback mb-3" aria-describedby="video_title">
              Please add a title (max <?= VIDEO_TITLE_MAX ?> characters)
            </div>
            <div class="valid-feedback mb-3" aria-describedby="title">
              Title OK!
            </div>
          </div>

          <input type="submit" name="submit" id="submit" class="btn btn-primary mt-3" onclick="" value="Add"></input>
        </form>

        



      </div>
    </div>

  </div>
</div>