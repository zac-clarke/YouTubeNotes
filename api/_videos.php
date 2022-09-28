<?php
require_once("../config/db-pdo.php");
require_once("../incl/logic/auth.php");
require_once("../incl/logic/sanitize.php");

//validation rules
define("VIDEO_URL_REGEX", "^(?:(?:https?:)?//)?(?:(?:www|m)\.)?(?:(?:youtube(?:-nocookie)?\.com|youtu.be))(?:/(?:[\w-]+\?v=|embed/|v/)?)(?<video_id>[\w-]+)(?:\S+)?$");
define("VIDEO_URL_MAX", "128");
define("VIDEO_TITLE_MAX", "128");

$response = array();
try {
    if (!$loggedin)
        throw new Exception('Unauthorized!', 401);
    switch ($_SERVER['REQUEST_METHOD']) { // Check which request method was used. Get/Post/Put/Delete/...
        case 'GET':
            if (!empty($_GET['id']))
                $response["video"] = getUserVideo($_GET['id']);
            else
                $response["videos"] = getUserVideos();
            break;
        case 'POST':
            $response["video"] = addVideo();
            break;
        case 'PUT':
            if (empty($_REQUEST['id']))
                throw new Exception('Missing Parameters', 422);
            else
                $response["video"] = editVideo();
            break;
        case 'DELETE':
            if (!empty($_REQUEST['id']))
                $response["success"] = deleteVideo();
            else
                throw new Exception('Missing Parameters', 422);
            break;
    }
} catch (Exception $e) {
    if (preg_match("/[A-z]/", $e->getCode())) { // if the error code has a letter in it, it was sent by the DB
        http_response_code(400);
        $response["error"] = $e->getCode() . ": " . $e->getMessage();
    } else {
        http_response_code($e->getCode());
        $response["error"] = $e->getMessage();
    }
} finally {
    echo json_encode($response, JSON_PRETTY_PRINT);
}



//CRUD FUNCTIONS
function getUserVideo($id)
{
    global $pdo;
    $id = sanitize($id);
    $userid = $_SESSION['user_id'];


    $stmt = $pdo->prepare("SELECT * FROM videos WHERE id=? AND userid=?;");
    //if query is executed successfully AND a video is found
    if ($stmt->execute([$id, $userid]) && $stmt->rowCount() === 1)
        return $stmt->fetchObject();
    else if ($stmt->execute([$id, $userid]) && $stmt->rowCount() > 1)
        throw new Exception("There is more than one video with this id($id)", 204);
    else
        throw new Exception("Video id($id) does not exist", 204);
}

function getUserVideos()
{
    global $pdo;
    $userid = $_SESSION['user_id'];
    $stmt = $pdo->prepare("SELECT * FROM videos WHERE userid=?;");
    //if query is executed successfully AND at least one video is found
    if ($stmt->execute([$userid]) && $stmt->rowCount())
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    else
        throw new Exception('No Videos found', 204);
}

function addVideo()
{
    global $pdo;
    $userid = $_SESSION['user_id'];
    $title = sanitize($_POST['title']);
    $url = sanitize($_POST['url']);
    $yt_id =  extract_id(VIDEO_URL_REGEX, $url);
    $valid = $valid = validateVideoinputs($url, $title);

    if ($valid) {
        $stmt = $pdo->prepare("INSERT INTO videos (userid, title, url, yt_id) VALUES (?,?,?,?)");
        if ($stmt->execute([$userid, $title, $url, $yt_id]) && $stmt->rowCount()) {
            return getUserVideo($pdo->lastInsertId());
        } else
            throw new Exception('Unable to add Video', 400);
    } else {
        throw new Exception('Input Failed validation', 422);
    }
}

function editVideo()
{
    global $pdo;
    $id = sanitize($_POST['id']);
    $userid = $_SESSION['user_id'];
    $title = sanitize($_POST['title']);
    $url = sanitize($_POST['url']);
    $yt_id =  extract_id(VIDEO_URL_REGEX, $url);
    $valid = validateVideoinputs($url, $title);

    if ($valid) {
        $stmt = $pdo->prepare("UPDATE videos SET userid=?, title=?=?, url=?=?, yt_id WHERE id=?");
        if ($stmt->execute([$userid, $title, $url, $yt_id, $id]) && $stmt->rowCount()) {
            return getUserVideo($id);
        } else
            throw new Exception('Unable to update video', 400);
    } else {
        throw new Exception('Input Failed validation', 422);
    }
}

function deleteVideo()
{
    global $pdo;
    $id = sanitize($_GET['id']);

    $stmt = $pdo->prepare("DELETE FROM videos WHERE id=?;");
    if ($stmt->execute([$id]) && $stmt->rowCount() > 0)
        return $stmt->rowCount();
    else
        throw new Exception("Video id($id) not deleted! Video does not exist.", 204);
}



//HELPER FUNCTIONS

//Extract youtube video ID from URI
function extract_id($pattern, $uri)
{
    $pattern = "{" . $pattern . "}";
    if (preg_match($pattern, $uri, $matches)) {
        return preg_replace($pattern, '$1', $uri);
    }
}

//Validate Video inputs
function validateVideoinputs($url, $title)
{
    if (empty($url) || strlen($url) > VIDEO_URL_MAX || !preg_match('{' . VIDEO_URL_REGEX . '}', $url)) {
        return false;
    } else {
        $yt_id = extract_id(VIDEO_URL_REGEX, "$url");
        $yt_id = sanitize($yt_id);
        if (!$yt_id) {
            return false;
        }
    }

    if (empty($title) || strlen($title) > VIDEO_TITLE_MAX) {
        return false;
    }

    return true;
}
