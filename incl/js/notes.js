/** @type {YT.Player} */
var player;
var curTime = 0;

disableFormSubmission();
document.getElementById('addNoteModal').addEventListener('shown.bs.modal', onAddNoteModalShow);
showYoutubePlayer();


/**
 * EventListener for when AddNoteModal is shown
 */
function onAddNoteModalShow() {
    pauseVideo();
    let addNoteModal = document.getElementById('addNoteModal');
    let form = addNoteModal.querySelector('form');
    let curTimestamp = player.getCurrentTime();
    form.querySelector('input[name="timestamp"]').value = curTimestamp;
    // TODO: Maybe empty note field if timestamp different from last time user opened Modal
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
 * @returns 
 */
function convertSecondsToString(seconds) {
    let startIndex = seconds < 3600 ? 14 : 11;
    return new Date(seconds * 1000).toISOString().substring(startIndex, 19);
}

/**
 * Takes a HH::MM:SS string and returns the value in seconds
 * @param {String} string 
 * @returns {Number} in Seconds
 */
function convertStringToSeconds(string) {
    let arr = string.trim().split(':');
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
        height: '390',
        width: '640',
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
    //event.target.playVideo();
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