<?php
//Video validation rules
//TODO : Fix duplicate domain name error (youtu.be/youtube.com/ASDsdad)
define("VIDEO_URL_REGEX", "^(?:(?:https?:)?//)?(?:(?:www|m)\.)?(?:(?:youtube(?:-nocookie)?\.com|youtu.be))(?:/(?:[\w-]+\?v=|embed/|v/)?)(?<video_id>[\w-]+)(?:\S+)?$");
define("VIDEO_URL_MAX", "128");
define("VIDEO_TITLE_MAX", "128");