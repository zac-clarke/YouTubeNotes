/** @type {YT.Player} */
var player;
var curTime = 0;
var videoid = getParam('videoid');

disableFormSubmission();
$('#modalNote').on('shown.bs.modal', onModalNoteShow);
$('#btn-submit').on('click', addNoteToDb);
showYoutubePlayer();
getNotesFromDb('trn_date DESC');

/**
 * EventListener for when modalNote is shown
 */
function onModalNoteShow() {
    pauseVideo();
    let modalNote = document.getElementById('modalNote');
    let form = modalNote.querySelector('form');
    let curTimestamp = player.getCurrentTime();
    form.querySelector('input[name="timestamp"]').value = curTimestamp;
    let modalTitle = document.getElementById('modalNoteLabel');
    modalTitle.textContent = 'Add Note @' + convertSecondsToString(curTimestamp);
    form.querySelectorAll('input, textarea').forEach(input => input.addEventListener('input', () => {
        if (input.checkValidity()) {
            input.classList.add("is-valid")
            input.classList.remove("is-invalid")
        } else {
            input.classList.add("is-invalid")
            input.classList.remove("is-valid")
        }
    }));
}

lastSort = "";
function orderByTimestamp() {
    if (lastSort == 'timestamp ASC') {
        lastSort = 'timestamp DESC'
    } else {
        lastSort = 'timestamp ASC'
    }
    getNotesFromDb(lastSort)
}

function orderByDate() {
    if (lastSort == 'trn_date ASC') {
        lastSort = 'trn_date DESC'
    } else {
        lastSort = 'trn_date ASC'
    }
    getNotesFromDb(lastSort)
}

let isAdding = false;
function addNoteToDb() {
    if (!isAdding && document.getElementById('form-note').checkValidity()) {
        isAdding = true;
        let title = $('input[name="title"]');
        let note = $('textarea[name="note"]');
        let timestamp = $('input[name="timestamp"]')
        $.ajax({
            method: 'POST',
            url: '../api/notes.php',
            data: { videoid: videoid, title: title.val(), note: note.val(), timestamp: timestamp.val() },
            timeout: 10000,
            beforeSend: function () {
                title.prop('disabled', true)
                note.prop('disabled', true)
                timestamp.prop('disabled', true)
            },
            error: function (xhr) {
                // "responseText": "{"error": "Missing Parameters"}"
                // "status": 422
                alert(JSON.stringify(xhr))
                //$('#notes').addClass('text-danger').html('<h2>An error occured while loading the Notes for this video</h2>')
            },
            success: function (/** @type {String} */data, textStatus, xhr) {
                let note = JSON.parse(data)["note"];
                addNoteBox(note);
                $('#modalNote').modal('hide');
                $('#modalNote input, #modalNote textarea').each(function () {
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

function getNotesFromDb(order) {
    $.ajax({
        method: 'GET',
        url: `../api/notes.php?videoid=${videoid}&order=${encodeURIComponent(order)}`,
        error: function (xhr) {
            // "responseText": "{"error": "Missing Parameters"}"
            // "status": 422
            $('#notes').html('<h2 class="text-danger">An error occured while loading the Notes for this video</h2>')
        },
        success: function (/** @type {String} */data, textStatus, xhr) {
            if (xhr.status == 204)
                return $('#notes').html('<h4 class="text-danger">You don\'t have any notes yet!<br>Click the button above to add one.</h4>')
            $('#notes')
                .html('') // Empty the div
                .removeClass('text-danger');
            JSON.parse(data)["notes"].forEach(note => {
                addNoteBox(note);
            });
        }
    });
}

function addNoteBox(note) {
    //TODO : Edit button
    //  onclick="openEditModal(${JSON.stringify(note).split('"').join("&quot;")})"
    let html =
        `<div id="note${note.id}" class="note p-4">
            <input name="title${note.id}" type="text" value="${note.title}" placeholder="Note Title" disabled>
            ${convertSecondsToString(note.timestamp)} &nbsp; | &nbsp; ${note.trn_date}<br>
            <textarea name="note${note.id}" type="text" rows="6" cols="60" placeholder="Note" disabled>${note.note}</textarea><br>
            <a class="btn-play btn text-info" onclick="player.seekTo(${note.timestamp}); player.playVideo();" title="Play at current timestamp"><i class="fa-solid fa-play"></i></a>
            <a class="btn-edit btn text-warning" onclick="makeNoteEditable(${JSON.stringify(note).split('"').join("&quot;")});" title="Edit note"><i class="fa-solid fa-pen"></i></a>
            <a class="btn-save btn text-success d-none" title="Update Note" onclick="saveEdit(${note.id}, ${note.timestamp});"><i class="fa-solid fa-check"></i></a>
            <a class="btn-cancel btn text-warning d-none" title="Cancel" onclick="cancelEdit(${note.id});"><i class="fa-solid fa-xmark"></i></a>
            <a class="btn-delete btn text-danger" onclick="deleteNoteBox(${note.id})" title="Delete Note"><i class="fa-solid fa-trash-can"></i></a>
        </div>`;
    $('#notes')
        .append(html);
}

var editing = new Map();
function makeNoteEditable(note) {
    // Get the id of the current div
    let id = note.id;
    let divID = '#note' + id;
    var fieldTitle = $(`${divID} input`);
    var fieldNote = $(`${divID} textarea`);
    // Make the input and textarea editable
    $(`${divID} input[disabled], ${divID} textarea[disabled]`).prop('disabled', false);
    // Replace the play and edit button with Save and Cancel + hide the delete button
    $(`${divID} .btn-play, ${divID} .btn-edit`).addClass('d-none');
    $(`${divID} .btn-save, ${divID} .btn-cancel`).removeClass('d-none');
    editing.set(id + 'title', fieldTitle.val());
    editing.set(id + 'note', fieldNote.val());
}

function saveEdit(id, timestamp) {
    let divID = '#note' + id;
    var fieldTitle = $(`${divID} input`);
    var fieldNote = $(`${divID} textarea`);
    if (fieldTitle.val().length == 0) {
        fieldTitle.addClass('has-error').prop('placeholder', 'The title is required!').focus();
    } else if (!editing.get(id)) {
        // Make temp vars to hold initial value of title and note
        fieldTitle.removeClass('has-error').prop('placeholder', 'Note Title');
        $.ajax({
            method: 'PUT',
            url: `../api/notes.php?id=${id}&videoid=${videoid}&title=${encodeURIComponent(fieldTitle.val())}&note=${encodeURIComponent(fieldNote.val())}&timestamp=${timestamp}`,
            beforeSend: function () {
                editing.set(id, true);
                $(`${divID} .btn-save`).removeClass('text-success').addClass('text-white');
                $(`${divID} .btn-cancel`).removeClass('text-warning').addClass('text-white');
            }, error: function (xhr) {
                alert(xhr.responseText)
            }, success: function (data) {
                clearEditing(id);
                fieldTitle.prop('disabled', true);
                fieldNote.prop('disabled', true);
                $(`${divID} .btn-play, ${divID} .btn-edit`).removeClass('d-none');
                $(`${divID} .btn-save, ${divID} .btn-cancel`).addClass('d-none');
            }, complete: function () {
                $(`${divID} .btn-save`).addClass('text-success').removeClass('text-white');
                $(`${divID} .btn-cancel`).addClass('text-warning').removeClass('text-white');
            }
        });
    }
}

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
        $(`${divID} .btn-play, ${divID} .btn-edit`).removeClass('d-none');
        $(`${divID} .btn-save, ${divID} .btn-cancel`).addClass('d-none');
        clearEditing(id);
    }
}

function clearEditing(id) {
    editing.delete(id);
    editing.delete(id + 'title');
    editing.delete(id + 'note');
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
            success: function (/** @type {String} */data, textStatus, xhr) {
                $(`#note${id}`).remove();
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
 * Disables form submissions if there are invalid fields
 * https://getbootstrap.com/docs/5.0/forms/validation/
 */
function disableFormSubmission() {
    'use strict'
    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    var forms = document.querySelectorAll('.needs-validation');

    // Loop over them and prevent submission
    Array.prototype.slice.call(forms)
        .forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }

                form.classList.add('was-validated')
            }, false)
        })
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
    var url_string = document.getElementById('player').dataset.url;
    curTime = document.getElementById('player').dataset.timestamp;
    let videoid = '';
    try {
        var url = new URL(url_string);
        videoid = url.searchParams.get("v");
    } catch (e) { }
    player = new YT.Player('player', {
        height: window.screen.height * 0.55,
        width: window.screen.width * 0.7,
        videoId: videoid,
        playerVars: {
            'playsinline': 1
        },
        events: {
            'onReady': onPlayerReady,
            'onStateChange': onPlayerStateChange
        }
    });
}

var shouldPause = false;
/**
 * The API will call this function when the video player is ready.
 * @param {*} event 
 */
function onPlayerReady(event) {
    if (curTime != 0) {
        player.seekTo(curTime);
        shouldPause = true;
    }
}


/**
 * The API calls this function when the player's state changes.
 * if (event.data == YT.PlayerState.PLAYING && !done)
 * @param {*} event 
 */
function onPlayerStateChange(event) {
    if (event.data == YT.PlayerState.PLAYING && shouldPause)
        player.pauseVideo();
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
