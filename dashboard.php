<?php require "incl/parts/header.php";
   
if (!$loggedin) {
    header('location: index.php');
    exit;
}




?>

<main class="px-3">



    <div class="container">
        <h1><?= $_SESSION['username'] ?>'s Dashboard</h1>
        <p class="pt-5">Do you want to add a video?</p>

        <!-- Button trigger modal -->
        <button type="button" id="btn_add_video" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add-video-modal">
            Add a new Video
        </button>

        <?php require_once "incl/parts/add-edit-video.php"; ?>
        
    </div>
    <div class="container my-5">
        <h2>Your Collection</h2>
        <?php require_once "incl/parts/get-videos.php"; ?>
  
        <script defer src="js/get_video_data.js"></script>
    </div>


    

</main>
<?php require "incl/parts/footer.php"; ?>