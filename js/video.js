/** @type {YT.Player} */
var player;
var curTime = 0;
var videoid = getParam('videoid');
var notes = [];

disableFormSubmission();
$('#modalNote').on('shown.bs.modal', onModalNoteShow);
$('#btn-submit').on('click', addNoteToDb);
showYoutubePlayer();
getNotesFromDb();
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
    // TODO: Maybe empty note field if timestamp different from last time user opened Modal
}

let isAdding = false;
function addNoteToDb() {
    if (!isAdding && modalNote.querySelector('form').checkValidity()) {
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

function getNotesFromDb() {
    $.ajax({
        method: 'GET',
        url: '../api/notes.php',
        data: "videoid=" + videoid,
        error: function (xhr) {
            // "responseText": "{"error": "Missing Parameters"}"
            // "status": 422
            $('#notes').html('<h2 class="text-danger">An error occured while loading the Notes for this video</h2>')
        },
        success: function (/** @type {String} */data, textStatus, xhr) {
            if (xhr.status == 204)
                return $('#notes').html('<h4 class="text-danger">You don\'t have any notes yet!<br>Click the button above to add one.</h4>')

            // TODO: Convert to Hashmap instead

            JSON.parse(data)["notes"].forEach(note => {
                notes[note.id] = note
            });
            populateNotes();
        }
    });
}

function populateNotes() {
    $('#notes')
        .html('') // Empty the div
        .removeClass('text-danger');
    //notes.forEach(addNote);
    notes.forEach(addNoteBox)
}

function addNoteBox(note) {
    //TODO : Edit button
    let html =
        `<div id="note${note.id}" class="p-4">
            <h4>${note.title}</h4>
            ${convertSecondsToString(note.timestamp)} &nbsp; | &nbsp; ${note.trn_date}<br>
            ${note.note}<br>
            <a class="btn text-info" onclick="player.seekTo(${note.timestamp}); player.playVideo();"><i class="fa-solid fa-play"></i></a>
            <a class="btn text-warning" data-bs-toggle="modal" data-bs-target="#modalNote" data-><i class="fa-solid fa-pen"></i></a>
            <a class="btn text-danger" onclick="deleteNoteBox(${note.id})"><i class="fa-solid fa-trash-can"></i></a>
        </div>`;
    $('#notes')
        .append(html);
}

//TODO: Throttle spam - If user clicks the button several times
function deleteNoteBox(id) {
    $.ajax({
        method: 'DELETE',
        url: '../api/notes.php?id=' + id,
        error: function (xhr) {
            // "responseText": "{"error": "Missing Parameters"}"
            // "status": 422
            alert(xhr.status + ': ' + JSON.parse(xhr.responseText).error)
        },
        success: function (/** @type {String} */data, textStatus, xhr) {
            $(`#note${id}`).remove();
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
var isFullscreen = false;
/**
 * The API will call this function when the video player is ready.
 * @param {*} event 
 */
function onPlayerReady(event) {
    //event.target.playVideo();
    if (curTime != 0) {
        player.seekTo(curTime);
        shouldPause = true;
    }

    // TODO: Add button in fullscreen mode
    // player.g.onfullscreenchange = (e) => {
    //     isFullscreen = !isFullscreen

    //     // console.log(e);
    //     // console.log(player);
    // };
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

