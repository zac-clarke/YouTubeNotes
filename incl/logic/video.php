<?php
try {
    if (!$loggedin) {
        header("location: index.php");
    } else if (!empty($_REQUEST['videoid'])) {
        $videoid = $_REQUEST['videoid']; // get videoid from request
        if ($video = getVideoInfo($videoid)) {
            loadPage($video);
        }
    } else
        throw new Exception();
} catch (Exception $e) {
    showErrorNoVideoMatch('No videos found! Click <a href="javascript:history.go(-1)">here</a> to go back');
} finally {
    require($_SERVER['DOCUMENT_ROOT'] . "/incl/parts/footer.php");
}

/**
 * Loads the page
 * @param Object video
 */
function loadPage($video)
{ ?>
    <script src='js/video.js' class="<?php global $user_id;
                                        echo $user_id ?>" defer></script>
<?php
    loadVideoSection($video); // load video section (up to Add Note button)
    loadNotesSection($video); // load notes section (after Add Note button)
}

/**
 * Gets video info from DB
 * @param Integer $videoid The videoid in the DB
 * @return Object
 */
function getVideoInfo($videoid)
{
    //TODO: Move this to Julieta's video api
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM videos WHERE id=?;"); // get the current video from DB
    if ($stmt->execute([$videoid]) && $stmt->rowCount())
        return $stmt->fetchObject(); // Video found
    else
        throw new Exception(); // No video found in DB
}
?>