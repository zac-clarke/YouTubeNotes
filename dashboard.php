<?php require "incl/parts/header.php";
require_once("config/constants.php");

//If user is not logged in stop the script and redirect
if (!$loggedin) {
    header('location: index.php');
    exit;
}
?>

<main class="px-3">
    <!-- Add New Video Section -->

    <div class="container container-sm mt-5">
        <div class="row justify-content-center">
            <div class="col-12 col-md-7">
                <h1 class="text-start">Hi <?= $_SESSION['username'] ?>!</h1>
                <p class="pt-5">Anotate your favorite Youtube Videos while you are watching</p>
                <button data-api="add" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#video-modal">
                    Add a new Video
                </button>
            </div>
        </div>
    </div>
    <!-- Display All User Videos Section -->
    <div class="container my-5">
        <h2 class="h4">Your Collection</h2>
        <p id="video-collection-message" class="mb-5">You have <span id="num-videos">?</span> videos in your collection </p>
        <!-- Videos Container -->
        <div id="videos-container" data-id="" class="d-flex flex-wrap justify-content-evenly" style="gap: 25px;">
            <!-- Video template -->
            <div data-role="video-template" class="d-none video " style="width:250px;">
               
                <!-- Go to Video Page image link -->
                <div class="video-thumb shine">
                    <div><a data-role="link" href=""><img data-role="thumb" src="" alt="" class="img-fluid"></a></div>
                    
                </div>
                <div class="d-flex flex-row justify-content-between align-items-center my-1">
                    <span data-role="date" class="text-muted fs-6 fst-italic">2022:11:11</span>
                    <p class="mb-0">
                        <!-- Edit Buttons -->
                        <i data-api="edit" data-id="" class="jaz-btn-icon btn-edit fa-solid fa-pen-to-square" data-bs-toggle="modal" data-bs-target="#video-modal"></i>
                        <i data-api="delete" data-id="" class="jaz-btn-icon btn-delete ms-2 fa-solid fa-trash"></i>
                    </p>
                </div>
                <p data-role="title" class="fs-5">Title</p>
            </div>
        </div>
    </div>
</main>
<?php require_once "incl/parts/video-modal.php"; ?>
<?php require "incl/parts/footer.php"; ?>
<script defer src="js/disableFormSubmission.js"></script>
<script defer src="js/videos.js"></script>