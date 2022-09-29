<?php require "incl/parts/header.php";?>
<main class="px-3">

    <div class="mt-5">
        <h1>Annotate your favorite YouTube videos!</h1>
    </div>
    <div class="mt-5 mb-5 container w-75">
        <p>
            Long have we waited for a web service which allowed us to add notes to the videos we use
            to study! Sign up now to use are indespensible web application to help you keep track of
            what you're watching and what you're learning!
        </p>
    </div>
    <div class="mb-5 container">
        <h2>How does it work?</h2>
    </div>

    <div class="container-sm">

        <div class="row container-sm">
            <div class=" col mb-4">
                <img src="img/addVideo.png" alt="add video modal" width=400>
                <h3>Add videos!</h3>
                <p>
                    Using a youtube link you can upload videos to your profile,
                    allowing you to keep track of videos you need to study!
                </p>
            </div>
            <div class=" col mb-4">
                <img src="img/video-placeholder.jpg" width=400 height=242>
                <h3>Add notes to videos!</h3>
                <p>
                    You will be able to add a notes to videos! This not will
                    keep the time in the video that you wrote it!
                </p>
            </div>
            <div class=" col mb-4">
                <img src="img/video-placeholder.jpg" width=400 height=242>
                <h3>Review your notes!</h3>
                <p>
                    All notes will be saved with the video you took them during. you will be able to review
                    them all on one page. You can even set the video back to where you took the note with on click!
                </p>
            </div>
        </div>
    </div>
    <?php if (!$loggedin): ?>
    <div class="container mb-5 mt-5">
        <h3>Sign up now!</h3>
        <a class="btn btn-primary" href="#" id="btn_signup" data-bs-toggle="modal" data-bs-target="#signup">Signup</a>
    </div>
    <?php else: ?>
    <div class="container mb-5 mt-5">
        <h3>Let's get started'!</h3>
        <a class="btn btn-primary" href="dashboard.php">Add Videos</a>
    </div>
    <?php endif;?>
</main>
<?php require "incl/parts/footer.php";?>