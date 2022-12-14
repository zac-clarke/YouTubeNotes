<?php
// Requiring pdo and auth again.. in case we want to use postman to use the api
require_once("../config/db-pdo.php");
require_once("../incl/logic/auth.php");
$response = array();
try {
    if (!$loggedin || !isset($_REQUEST['user_id']))
        throw new Exception('Unauthorized!', 401);

    $user_id = $_REQUEST['user_id'];
    switch ($_SERVER['REQUEST_METHOD']) { // Check which request method was used. Get/Post/Put/Delete/...
        case 'GET':
            if (!empty($_GET['id']))
                $response["note"] = getNoteFromDb(htmlspecialchars($_GET['id']));
            else if (!empty($_GET['videoid']))
                $response["notes"] = getNotesFromDb(htmlspecialchars($_GET['videoid']), htmlspecialchars($_GET['order']));
            else
                throw new Exception('Missing Parameters', 422);
            break;
        case 'POST':
            if (empty($_REQUEST['videoid']) || empty($_REQUEST['title']) || !isset($_REQUEST['timestamp']))
                throw new Exception('Missing Parameters', 422);
            else
                $response["note"] = addNoteToDb(htmlspecialchars($_REQUEST['videoid']), htmlspecialchars($_REQUEST['title']), htmlspecialchars($_REQUEST['note']), htmlspecialchars($_REQUEST['timestamp']));
            break;
        case 'PUT':
            if (empty($_REQUEST['id']) || empty($_REQUEST['videoid']) || empty($_REQUEST['title']) || !isset($_REQUEST['timestamp']))
                throw new Exception('Missing Parameters', 422);
            else
                $response["note"] = editNoteInDb(htmlspecialchars($_REQUEST['id']), htmlspecialchars($_REQUEST['videoid']), htmlspecialchars($_REQUEST['title']), htmlspecialchars($_REQUEST['note']), htmlspecialchars($_REQUEST['timestamp']));
            break;
        case 'DELETE':
            if (empty($_REQUEST['id']))
                throw new Exception(json_encode($_SERVER) . 'Missing Parameters', 422);
            else
                $response["success"] = deleteNoteFromDb(htmlspecialchars($_REQUEST['id']));
            break;
    }
} catch (Exception $e) {
    if (preg_match("/[A-z]/", $e->getCode())) { // if the error code has a letter in it, it was sent by MySQL
        http_response_code(400);
        $response["error"] = $e->getCode() . ": " . $e->getMessage();
    } else {
        http_response_code($e->getCode());
        $response["error"] = $e->getMessage();
    }
} finally {
    echo json_encode($response, JSON_PRETTY_PRINT);
}

/**
 * Fetches a note from the database
 * @param number $id
 * @return Object
 */
function getNoteFromDb($id)
{
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM notes WHERE id=?;");
    if ($stmt->execute([$id]) && $stmt->rowCount())
        return $stmt->fetchObject();
    else
        throw new Exception('No Notes found', 204);
}

/**
 * Fetches notes linked to a videoid from the database
 * @param number $videoid The id of the video
 * @param String $order The ORDER BY value
 * @return Object[] An array of notes
 */
function getNotesFromDb($videoid, $order = "date desc")
{
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM notes WHERE videoid=? ORDER BY $order");
    if ($stmt->execute([$videoid]) && $stmt->rowCount())
        return $stmt->fetchAll();
    else
        throw new Exception('No Notes found', 204);
}

/**
 * Inserts a note to the database
 * @param Number $videoid The video ID linked to this note
 * @param String $title Title of the note
 * @param String $note The content of the note
 * @param Number $timestamp The timestamp where the note is saved (in seconds (float))
 */
function addNoteToDb($videoid, $title, $note, $timestamp)
{
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO notes (videoid, title, note, timestamp) VALUES (?,?,?,?)");
    if ($stmt->execute([$videoid, $title, $note, $timestamp]) && $stmt->rowCount()) {
        return getNoteFromDb($pdo->lastInsertId());
    } else
        throw new Exception('Unable to add note', 400);
}

/**
 * Updates a note in the database
 * @param Number $id The ID of the note
 * @param Number $videoid The video ID linked to this note
 * @param String $title Title of the note
 * @param String $note The content of the note
 * @param Number $timestamp The timestamp where the note is saved (in seconds (float))
 */
function editNoteInDb($id, $videoid, $title, $note, $timestamp)
{
    global $pdo;
    $stmt = $pdo->prepare("UPDATE notes SET videoid=?, title=?, note=?, timestamp=? WHERE id=?");
    if ($stmt->execute([$videoid, $title, $note, $timestamp, $id])) {
        return getNoteFromDb($id);
    } else
        throw new Exception('Unable to update note', 400);
}

/**
 * Deletes a note from the database
 * @param Number $id The ID of the note
 */
function deleteNoteFromDb($id)
{
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM notes WHERE id=?;");
    if ($stmt->execute([$id]))
        return $stmt->rowCount();
    else
        throw new Exception('No Notes found', 204);
}
