<?php
require_once("../config/db-pdo.php");
require_once("../incl/logic/auth.php");
$response = array();
try {
    if (!$loggedin)
        throw new Exception('Unauthorized!', 401);
    switch ($_SERVER['REQUEST_METHOD']) { // Check which request method was used. Get/Post/Put/Delete/...
        case 'GET':
            if (!empty($_GET['id']))
                $response["note"] = getNoteFromDb($_GET['id']);
            else if (!empty($_GET['videoid']))
                $response["notes"] = getNotesFromDb($_GET['videoid']);
            else
                throw new Exception('Missing Parameters', 422);
            break;
        case 'POST':
            if (empty($_REQUEST['videoid']) || empty($_REQUEST['title']) || empty($_REQUEST['timestamp']))
                throw new Exception('Missing Parameters', 422);
            else
                $response["note"] = addNoteToDb($_REQUEST['videoid'], $_REQUEST['title'], $_REQUEST['note'], $_REQUEST['timestamp']);
            break;
        case 'PUT':
            if (empty($_REQUEST['id']) || empty($_REQUEST['videoid']) || empty($_REQUEST['title']) || empty($_REQUEST['timestamp']))
                throw new Exception('Missing Parameters', 422);
            else
                $response["note"] = editNoteInDb($_REQUEST['id'], $_REQUEST['videoid'], $_REQUEST['title'], $_REQUEST['note'], $_REQUEST['timestamp']);
            break;
        case 'DELETE':
            if (!empty($_REQUEST['id']))
                $response["success"] = deleteNoteFromDb($_REQUEST['id']);
            else
                throw new Exception('Missing Parameters', 422);
            break;
    }
} catch (Exception $e) {
    if (preg_match("/[A-z]/", $e->getCode())) {
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
 * @return Object[] An array of notes
 */
function getNotesFromDb($videoid)
{
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM notes WHERE videoid=?;");

    if ($stmt->execute([$videoid]) && $stmt->rowCount())
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    else
        throw new Exception('No Notes found', 204);
}

function addNoteToDb($videoid, $title, $note, $timestamp)
{
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO notes (videoid, title, note, timestamp) VALUES (?,?,?,?)");
    if ($stmt->execute([$videoid, $title, $note, $timestamp]) && $stmt->rowCount()) {
        return getNoteFromDb($pdo->lastInsertId());
    } else
        throw new Exception('Unable to add note', 400);
}

function editNoteInDb($id, $videoid, $title, $note, $timestamp)
{
    global $pdo;
    $stmt = $pdo->prepare("UPDATE notes SET videoid=?, title=?, note=?, timestamp=? WHERE id=?");
    if ($stmt->execute([$videoid, $title, $note, $timestamp, $id]) && $stmt->rowCount()) {
        return getNoteFromDb($id);
    } else
        throw new Exception('Unable to update note', 400);
}

function deleteNoteFromDb($id)
{
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM notes WHERE id=?;");
    if ($stmt->execute([$id]) && $stmt->rowCount())
        return $stmt->fetchObject();
    else
        throw new Exception('No Notes found', 204);
}
