// TODO: Store notes in an array for quicker sort
/** @type {YT.Player} The YouTube player object*/
var player;
/** @type {Number} The db id of the current video*/
var videoid = getParam('videoid');
/** @type {String} Keeps track of the last sort method used*/
var lastSort = "trn_date DESC";

$('#modalNote')
    .on('shown.bs.modal', onModalNoteShow);
$('#btn-submit')
    .on('click', addNoteToDb);
showYoutubePlayer();
getNotesFromDb(lastSort);

/**
 * EventListener for when modalNote is shown
 */
function onModalNoteShow() {
    pauseVideo();
    let curTimestamp = player.getCurrentTime();
    $('#modalNote form input[name="timestamp"]').val(curTimestamp);
    $('#modalNoteLabel').text('Add Note @' + convertSecondsToString(curTimestamp));
    document.querySelectorAll('#modalNote form input, #modalNote form textarea').forEach(input => input.addEventListener('input', () => {
        if (input.checkValidity()) {
            input.classList.add("is-valid")
            input.classList.remove("is-invalid")
        } else {
            input.classList.add("is-invalid")
            input.classList.remove("is-valid")
        }
    }));
}

/**
 * Gets note from the DB ordered by timestamp. Toggles ASC/DESC if lastSort was timestamp
 */
function orderByTimestamp() {
    lastSort = lastSort == 'timestamp ASC' ? 'timestamp DESC' : 'timestamp ASC';
    getNotesFromDb(lastSort)
}

/**
 * Gets note from the DB ordered by trn_date. Toggles ASC/DESC if lastSort was trn_date
 */
function orderByDate() {
    lastSort = lastSort == 'trn_date ASC' ? 'trn_date DESC' : 'trn_date ASC';
    getNotesFromDb(lastSort)
}

/**
 * Calculates the number of rows and columns to be displayed for a textArea
 * @param {Number} id ID of Note
 */
function calcTextAreaHeight(id) {
    $textarea = $(`textarea[name="note${id}"]`);
    let noteLength = $textarea
        .val()
        .length;
    let cols = 60;
    let rows = Math.ceil(noteLength / cols);
    const lines = $textarea.val().split("\n").length;
    if (lines > rows)
        rows = lines;
    $textarea
        .prop('cols', cols)
        .prop('rows', rows > 10 ? 10 :
            rows < 3 ? 3 :
            rows);
}

/** @type {Boolean} Throttle for the Add button*/
let isAdding = false;
/**
 * Checks validity and adds the values of the Modal Form to the DB
 */
function addNoteToDb() {
    if (!isAdding && $('#form-note')[0].checkValidity()) {
        isAdding = true;
        let title = $('input[name="title"]');
        let note = $('textarea[name="note"]');
        let timestamp = $('input[name="timestamp"]')
        $.ajax({
            method: 'POST',
            url: '../api/notes.php',
            data: {
                videoid: videoid,
                title: title.val(),
                note: note.val(),
                timestamp: timestamp.val()
            },
            timeout: 10000,
            beforeSend: function () {
                title.prop('disabled', true)
                note.prop('disabled', true)
                timestamp.prop('disabled', true)
            },
            error: function (xhr) {
                // "responseText": "{"error": "Missing Parameters"}"
                // "status": 422
                alert(xhr.status + ': ' + JSON.parse(xhr.responseText).error)
            },
            success: function ( /** @type {String} */ data, textStatus, xhr) {
                let note = JSON.parse(data)["note"];
                addNoteBox(note);
                $('#modalNote')
                    .modal('hide');
                $('#modalNote input, #modalNote textarea')
                    .each(function () {
                        $(this)
                            .val('')
                            .removeClass('is-valid');
                    });
            },
            complete: function () {
                isAdding = false;
                title.prop('disabled', false)
                note.prop('disabled', false)
                timestamp.prop('disabled', false)
            }
        });
    } else if (!isAdding) {
        $('#form-note')
            .removeClass('needs-validation')
            .addClass('was-validated');
    }
}

/**
 * Gets all the Notes for the current videoID and calls the addNoteBox function for each note
 * @param {String} order The ORDER BY value
 */
function getNotesFromDb(order) {
    $.ajax({
        method: 'GET',
        url: `../api/notes.php?videoid=${videoid}&order=${encodeURIComponent(order)}`,
        error: function (xhr) {
            // "responseText": "{"error": "Missing Parameters"}"
            // "status": 422
            $('#notes')
                .html('<h2 class="text-danger">An error occured while loading the Notes for this video</h2>')
        },
        success: function ( /** @type {String} */ data, textStatus, xhr) {
            if (xhr.status == 204)
                return $('#notes')
                    .html('<h4 class="text-danger">You don\'t have any notes yet!<br>Click the button above to add one.</h4>')
            $('#notes')
                .html('') // Empty the div
                .removeClass('text-danger');
            JSON.parse(data)["notes"].forEach(note => {
                addNoteBox(note);
            });
        }
    });
}

/**
 * Creates a new div and fills it with a note
 * @param {Object} note 
 */
function addNoteBox(note) {
    let html =
        `<div id="note${note.id}" class="note p-4">
            <input name="title${note.id}" type="text" value="${note.title}" placeholder="Note Title" disabled>
            ${convertSecondsToString(note.timestamp)} &nbsp; | &nbsp; ${note.trn_date}<br>
            <textarea name="note${note.id}" type="text" placeholder="Note" oninput="calcTextAreaHeight(${note.id});" disabled>${note.note}</textarea><br>
            <a class="btn-play btn text-info" onclick="player.seekTo(${note.timestamp}); player.playVideo();" title="Play at current timestamp"><i class="fa-solid fa-play"></i></a>
            <a class="btn-edit btn text-warning" onclick="makeNoteEditable(${JSON.stringify(note).split('"').join("&quot;")});" title="Edit note"><i class="fa-solid fa-pen"></i></a>
            <a class="btn-save btn text-success d-none" title="Update Note" onclick="saveEdit(${note.id}, ${note.timestamp});"><i class="fa-solid fa-check"></i></a>
            <a class="btn-cancel btn text-warning d-none" title="Cancel" onclick="cancelEdit(${note.id});"><i class="fa-solid fa-xmark"></i></a>
            <a class="btn-delete btn text-danger" onclick="deleteNoteBox(${note.id})" title="Delete Note"><i class="fa-solid fa-trash-can"></i></a>
        </div>`;
    $('#notes')
        .append(html);
    calcTextAreaHeight(note.id);
}

/** @type {Map} Keeps track of each note being edited. Throttles it and stores initial values in case of a cancel*/
var editing = new Map();

/**
 * Removes the note from the editing Map
 * @param {Number} id 
 */
function clearEditing(id) {
    editing.delete(id);
    editing.delete(id + 'title');
    editing.delete(id + 'note');
}

/**
 * Makes the input and the textarea of the current note editable by removing the disabled property.
 * Also, hides the play and edit button + shows the save and cancel buttons
 * @param {Object} note 
 */
function makeNoteEditable(note) {
    // Get the id of the current div
    let id = note.id;
    let divID = '#note' + id;
    var fieldTitle = $(`${divID} input`);
    var fieldNote = $(`${divID} textarea`);
    // Make the input and textarea editable
    $(`${divID} input[disabled], ${divID} textarea[disabled]`)
        .prop('disabled', false);
    // Replace the play and edit button with Save and Cancel + hide the delete button
    $(`${divID} .btn-play, ${divID} .btn-edit`)
        .addClass('d-none');
    $(`${divID} .btn-save, ${divID} .btn-cancel`)
        .removeClass('d-none');
    editing.set(id + 'title', fieldTitle.val());
    editing.set(id + 'note', fieldNote.val());
}

/**
 * Attempts to save the edited note to the DB
 * @param {Number} id 
 * @param {Number} timestamp 
 */
function saveEdit(id, timestamp) {
    let divID = '#note' + id;
    var fieldTitle = $(`${divID} input`);
    var fieldNote = $(`${divID} textarea`);
    if (fieldTitle.val().length == 0) {
        fieldTitle.addClass('has-error')
            .prop('placeholder', 'The title is required!')
            .focus();
    } else if (!editing.get(id)) {
        // Make temp vars to hold initial value of title and note
        fieldTitle
            .removeClass('has-error')
            .prop('placeholder', 'Note Title');
        $.ajax({
            method: 'PUT',
            url: `../api/notes.php?id=${id}&videoid=${videoid}&title=${encodeURIComponent(fieldTitle.val())}&note=${encodeURIComponent(fieldNote.val())}&timestamp=${timestamp}`,
            beforeSend: function () {
                editing.set(id, true);
                $(`${divID} .btn-save`)
                    .removeClass('text-success')
                    .addClass('text-white');
                $(`${divID} .btn-cancel`)
                    .removeClass('text-warning')
                    .addClass('text-white');
            },
            error: function (xhr) {
                alert(xhr.status + ': ' + JSON.parse(xhr.responseText).error)
            },
            success: function (data) {
                clearEditing(id);
                calcTextAreaHeight(id);
                fieldTitle.prop('disabled', true);
                fieldNote.prop('disabled', true);
                $(`${divID} .btn-play, ${divID} .btn-edit`)
                    .removeClass('d-none');
                $(`${divID} .btn-save, ${divID} .btn-cancel`)
                    .addClass('d-none');
            },
            complete: function () {
                $(`${divID} .btn-save`)
                    .addClass('text-success')
                    .removeClass('text-white');
                $(`${divID} .btn-cancel`)
                    .addClass('text-warning')
                    .removeClass('text-white');
            }
        });
    }
}

/**
 * Cancels the note edit and reverts back to initial values.
 * Also swaps back to the play and edit buttons under the note
 * @param {Number} id 
 */
function cancelEdit(id) {
    if (!editing.get(id)) {
        let divID = '#note' + id;
        let fieldTitle = $(`${divID} input`);
        let fieldNote = $(`${divID} textarea`);
        fieldTitle.val(editing.get(id + 'title'));
        fieldNote.val(editing.get(id + 'note'));
        fieldTitle.prop('disabled', true);
        fieldNote.prop('disabled', true);
        fieldTitle.removeClass('has-error').prop('placeholder', 'Note Title');
        $(`${divID} .btn-play, ${divID} .btn-edit`)
            .removeClass('d-none');
        $(`${divID} .btn-save, ${divID} .btn-cancel`)
            .addClass('d-none');
        clearEditing(id);
    }
}

var deleting = new Map();

function deleteNoteBox(id) {
    if (!deleting.get(id))
        $.ajax({
            method: 'DELETE',
            url: '../api/notes.php?id=' + id,
            beforeSend: function () {
                deleting.set(id, true);

            },
            error: function (xhr) {
                // "responseText": "{"error": "Missing Parameters"}"
                // "status": 422
                alert(xhr.status + ': ' + JSON.parse(xhr.responseText).error)
            },
            success: function ( /** @type {String} */ data, textStatus, xhr) {
                $(`#note${id}`)
                    .remove();
            },
            complete: function () {
                deleting.delete(id);
            }
        });
}

/**
 * Returns the value of a request parameter with the key
 * @param {String} key 
 * @returns {String} The Value of the [key] parameter
 */
function getParam(key) {
    if (key = (new RegExp('[?&]' + encodeURIComponent(key) + '=([^&]*)')).exec(location.search))
        return decodeURIComponent(key[1]);
}

/**
 * Takes number of seconds and converts it to HH:MM:SS format
 * @param {Number} seconds 
 * @returns {String} time in MM:SS or HH:MM:SS format
 */
function convertSecondsToString(seconds) {
    let startIndex = seconds < 3600 ? 14 : 11;
    return new Date(seconds * 1000).toISOString().substring(startIndex, 19);
}

/**
 * Takes a HH:MM:SS string and returns the value in seconds
 * @param {String} input as MM:SS or HH:MM:SS format
 * @returns {Number} in Seconds
 */
function convertStringToSeconds(input) {
    let arr = input.trim().split(':');
    let seconds = parseInt(arr.pop());
    let minutes = parseInt(arr.pop());
    let hours = parseInt(arr.pop());
    return seconds + (minutes * 60) + (hours * 60 * 60);
}

/**
 * This function loads the IFrame Player API code asynchronously.
 */
function showYoutubePlayer() {
    var tag = document.createElement('script');
    tag.src = "https://www.youtube.com/iframe_api";
    var firstScriptTag = document.getElementsByTagName('script')[1];
    firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
}

/**
 * This function creates an <iframe> (and YouTube player) after the API code downloads.
 */
function onYouTubeIframeAPIReady() {
    let ytId = $('#player').data('yt-id');
    player = new YT.Player('player', {
        height: window.screen.height * 0.55,
        width: window.screen.width * 0.7,
        videoId: ytId,
        playerVars: {
            'playsinline': 1
        },
        events: {
            'onReady': onPlayerReady,
            'onStateChange': onPlayerStateChange
        }
    });
}

/**
 * The API will call this function when the video player is ready.
 * @param {*} event 
 */
function onPlayerReady(event) {}


/**
 * The API calls this function when the player's state changes.
 * if (event.data == YT.PlayerState.PLAYING)
 * @param {*} event 
 */
function onPlayerStateChange(event) {
    // if (event.data == YT.PlayerState.PLAYING)
    //     player.pauseVideo();
}

/**
 * Pauses the video
 */
function pauseVideo() {
    player.pauseVideo();
}

/**
 * Stops the Video
 */
function stopVideo() {
    player.stopVideo();
}