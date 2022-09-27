<?php
require("incl/parts/header.php");
require("incl/logic/video.php"); // The main logic behind the Notes page
?>

<!-- Modal for the 'Add Note' button -->
<div class="modal fade text-dark" id="modalNote" tabindex="-1" aria-labelledby="modalNoteLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalNoteLabel">Add Note</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form-add-note" class="needs-validation" novalidate>
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
    <div>
        <div>
            <h1><?= $video->title ?></h1>
            <h4 class="d-none">TODO: Metada from Youtube API if possible</h4>
            <!-- The <iframe> (and video player) will replace this <div> tag. -->
            <div id="test"></div>
            <div id="player" data-url="<?= $video->url ?>" data-timestamp="<?= (!empty($_REQUEST['timestamp'])) ? $_REQUEST['timestamp'] : 0 ?>"></div>
        </div>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNote" title="Pauses the video and adds a note at the current timestamp">
            Add Note
        </button>
    </div>
<?php
}

function loadNotesSection($video)
{ ?>
    <div class="container d-flex">
        <span class="h2 flex-grow-1">Your Notes</span>
        <span class="d-none">TODO: Order by: Timestamp | date</span>
    </div>
    <div id="notes" class="container">

    </div>
<?php
}