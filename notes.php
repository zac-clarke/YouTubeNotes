<?php
require("config/db-pdo.php");
require("incl/parts/header.php");
require("incl/logic/notes.php"); // The main logic behind the Notes page
?>

<!-- Modal for the 'Notes' button -->
<div class="modal fade text-dark" id="modalNote" tabindex="-1" aria-labelledby="modalNoteLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalNoteLabel">Add Note</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form-note" class="needs-validation" novalidate>
                    <input type="hidden" class="form-control" name="timestamp">
                    <div class="mb-4">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control" name="title" maxlength="256" required>
                        <div class="invalid-feedback">
                            The title is required
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="note" class="form-label">Note</label>
                        <textarea type="text" class="form-control" name="note"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" id="btn-submit" class="btn btn-primary">Add</button>
            </div>
        </div>
    </div>
</div>

<!--
 --
 -- The remainder of this file contains functions used in this file or in the logic/notes.php file
 --
 -->

<?php
/**
 * Shows an error in case the videoid was not provided to the page
 */
function showErrorNoVideoMatch($msg)
{ ?>
    <div>
        <p class="text-danger">
            <?= $msg ?>
        </p>
    </div>
<?php
}

/**
 * Fetches and displays the video object details
 * @Video {"id":1, "userid":1, "url":"https:\/\/www.youtube.com\/watch?v=z3pZdmJ64jo", "title":"Load Classes Automatically", "trn_date":"2022-09-25 15:24:25"}
 * @param Object $video
 */
function loadVideoSection($video)
{ ?>
    <div class="container-fluid my-5">
        <div class="row justify-content-center">

            <div class="col-12 col-md-6 col-xl-8">
                <h2 class="h4 mb-3 text-start"><?= $video->title ?></h2>
                <h4 class="d-none">TODO: Metada from Youtube API if possible</h4>
                <!-- The <iframe> (and video player) will replace this <div> tag. -->
                <div id="videoContainer">
                    <div id="player" data-yt-id="<?= $video->yt_id ?>"></div>
                </div>
                <div class="d-grid gap-2 d-flex justify-content-between">
                    <button id="btn-add-note" class="btn btn-primary px-4 py-2 mt-3" data-bs-toggle="modal" data-bs-target="#modalNote" title="Pauses the video and adds a note at the current timestamp">
                        Add Note
                    </button>
                    <small class="fst-italic text-muted">
                        <?= substr($video->trn_date, 0, 10) ?>
                    </small>
                </div>
            </div>


            <div class="col-12 col-md-6 col-xl-4">
            <?php }
                /**
                 * Displays the Notes section - below the 'Add Note' button
                 */
                function loadNotesSection($video)
                { ?>
                        <div class="container d-flex flex-column">
                            <span class="h5 mt-5 flex-grow-1 text-start">Your Notes</span>
                            <span class="text-start">Order by: <a class="fw-bold" href="javascript:void(null);" onclick="orderByTimestamp()">Timestamp</a> | <a class="fw-bold" href="javascript:void(null);" onclick="orderByDate()">Date</a></span>
                        </div>
                        <!-- All notes go in the following div -->
                        <div class="accordion container mt-3" id="notes"></div>
                    <?php
                }
                    ?>
            </div>
        </div>
    </div>