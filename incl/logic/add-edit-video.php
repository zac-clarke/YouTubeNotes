<!-- Modal -->
<?php require_once "incl/logic/sanitize.php";

//CONSTANTS

//validation rules
define("VIDEO_URL_REGEX", "^(?:(?:https?:)?//)?(?:(?:www|m)\.)?(?:(?:youtube(?:-nocookie)?\.com|youtu.be))(?:/(?:[\w-]+\?v=|embed/|v/)?)(?<video_id>[\w-]+)(?:\S+)?$");
define("VIDEO_URL_MAX", "128");
define("VIDEO_TITLE_MAX", "128");

//FUNCTIONS

//Extract youtube video ID from URI
function extract_id($pattern, $uri)
{
  $pattern = "{" . $pattern . "}";
  if (preg_match($pattern, $uri, $matches)) {
    return preg_replace($pattern, '$1', $uri);
  }
}



//REQUESTS
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $video_url = $video_title = ""; //
  $video_url_error = $video_title_error  = "";
  $valid = true;


  //if the form was submited
  if (isset($_POST['submit'])) {

    //sanitize input
    $video_url = filter_var($_POST['url'], FILTER_SANITIZE_URL);
    $video_title = sanitize($_POST['title']);

    //VALIDATION
    if (empty($video_url) || strlen($video_url) > VIDEO_URL_MAX || !preg_match('{' . VIDEO_URL_REGEX . '}', $video_url)) {
      $valid = false;
    } else {
      $video_ytid = extract_id(VIDEO_URL_REGEX, "$video_url");
      $video_ytid = sanitize($video_ytid);
      if (!$video_ytid) {
        $valid = false;
      }
    }

    if (empty($video_title) || strlen($video_title) > VIDEO_TITLE_MAX) {
      $valid = false;
    }

    if ($valid) {
      $id = (int)$_POST['video_id'];

      if ($id) {
        // UPDATE
        $qry = "UPDATE videos SET url=?, yt_id=?, title=? WHERE id=? AND userid=?";
        $video_url_error = $video_title_error  = "";
        $stmt = mysqli_prepare($conn, $qry);
        mysqli_stmt_bind_param($stmt, "sssii", $video_url, $video_ytid, $video_title, $id, $_SESSION['user_id']);
        mysqli_stmt_execute($stmt) or die("Server error : update video failed");
      } else {
        // CREATE
        $video_url_error = $video_title_error  = "";
        $qry = "INSERT INTO videos (userid, yt_id, url, title) VALUES (?,?,?,?)";
        $stmt = mysqli_prepare($conn, $qry);
        mysqli_stmt_bind_param($stmt, "isss", $_SESSION['user_id'], $video_ytid, $video_url, $video_title);
        mysqli_stmt_execute($stmt) or die("Server error : add video failed");
      }

      //clear the form
      $_POST = array();
      $video_url = $video_title = "";
      $video_url_error = $video_title_error  = "";
    }
  }
}

?>