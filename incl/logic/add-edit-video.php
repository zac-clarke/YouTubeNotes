<!-- Modal -->
<?php require_once "incl/logic/sanitize.php";
if($_SERVER['REQUEST_METHOD'] === 'POST'){
$video_url = $video_title = "";
$video_url_error = $video_title_error  = "";
$valid = true;


if (isset($_POST['submit'])) {

  $video_url = $_POST['url'];
  $video_title = sanitize($_POST['title']);

  //validate
  if (empty($video_url)) {
    $valid = false;
    $video_url_error = "Required field";
  } else if (!preg_match('/^(https?\:\/\/)?((www\.)?youtube\.com|youtu\.be)\/.+$/', $video_url)) {
    $valid = false;
    $video_url_error = "Not a valid Youtube URL";
  }

  if (empty($video_title)) {
    $valid = false;
    $video_title_error  = "Required field";
  }


  $id = (int)$_POST['video_id'];

  if ($valid) {
  if($id) {
    // edit

$qry = "UPDATE videos SET url=?, title=? WHERE id=? AND userid=?";
$video_url_error = $video_title_error  = "";

    $stmt = mysqli_prepare($conn, $qry);
    mysqli_stmt_bind_param($stmt, "ssii",$video_url, $video_title, $id, $_SESSION['user_id']);
    mysqli_stmt_execute($stmt) or die("Server error : update video failed");

  } else {
    // add
    $video_url_error = $video_title_error  = "";
    
    $qry = "INSERT INTO videos (userid, url, title) VALUES (?,?,?)";
    $stmt = mysqli_prepare($conn, $qry);
    mysqli_stmt_bind_param($stmt, "iss", $_SESSION['user_id'], $video_url, $video_title);
    mysqli_stmt_execute($stmt) or die("Server error : add video failed");

  }

    //clear the form
   $_POST = array();
    $video_url = $video_title = "";
    $video_url_error = $video_title_error  = "";
  }
}
?>

<?php


//Show Modal on reload on incorrect login
if (isset($_POST['submit'])) : ?>
  <script defer>
    //show the logon modal on reload for invalid logins      
    document.onreadystatechange = function() {
      if(<?=$id?> == 0) {
        document.getElementById("btn_add_video").click();
      }else {
        document.querySelector('[data-id="<?=$id?>"]').click();
      }
    };
  </script>
<?php endif; 
}?>