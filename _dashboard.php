<?php require "incl/parts/header.php";

//If user is not logged in stop the script and redirect
if (!$loggedin) {
    header('location: index.php');
    exit;
}
?>

<main class="px-3">
    <!-- Add New Video Section -->
    <div class="container">
        <h1><?= $_SESSION['username'] ?>'s Dashboard</h1>
        <p class="pt-5">Do you want to add a video?</p>
        <button data-api="add" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#video-modal">
            Add a new Video
        </button>
    </div>
    <!-- Display All User Videos Section -->
    <div class="container my-5">
        <h2>Your Collection</h2>
        <p id="video-collection-message" class="mb-5">You have <span id="num-videos">?</span> videos in your collection </p>
        <!-- Videos Container -->
        <div id="videos-container" data-id="" class="d-flex flex-wrap justify-content-evenly" style="gap: 25px;">
        <!-- Video template -->
            <div data-role="video" class="video" style="width:250px;">
                <h5 data-role="title" class="title">Title</h5>
                <!-- Go to Video Page image link -->
                <a data-role="link" href=""><img data-role="thumb" src="" alt="" class="img-fluid"></a>
                <!-- Edit Video Button -->
                <i data-api="edit" data-id="" class="btn fa-solid fa-pen-to-square text-warning" data-bs-toggle="modal" data-bs-target="#video-modal"></i>
                <!-- Delete Video Button -->
                <!-- TODO : Add delete confirmation modal -->
                <i data-api="delete" data-id="" class="btn fa-solid fa-trash text-danger"></i>
                </p>
            </div>
        </div>
    </div>
</main>
<?php require_once "incl/parts/video-modal.php"; ?>
<?php require "incl/parts/footer.php"; ?>
<script defer src="js/disableFormSubmission.js"></script>
<script defer src="js/videos.js"></script>