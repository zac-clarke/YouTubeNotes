//video template elements
const $videosContainer = $("#videos-container");
const $template = $videosContainer.find('[data-role="video-template"]');
const $message = $("#video-collection-message");

//video modal form
const $modal = $("#video-modal");
const $form = $modal.find("form");
const $id = $modal.find("#id");
const $url = $modal.find("#url");
const $title = $modal.find("#title");
const $modal_title = $modal.find("#modal_title");
const $submit_video = $modal.find("#submit_video");
const $update_video = $modal.find("#update_video");
const $feedback = $modal.find("#server-feedback");

$(document).ready(function () {
  $('[data-api="add"]').on("click", function () {
    modifyForm();
  });

  $submit_video.on("click", function () {
    addVideo();
  });
  $update_video.on("click", function () {
    editVideo($id.val());
  });

  getVideos();
});

//REQUESTS
function getVideos() {
  $.ajax({
    method: "GET",
    url: "api/_videos.php",
    dataType: "json",
    error: function (xhr, textStatus, errorThrown) {
      $message.text(`An error occured while loading videos`);
    },
    success: function (data, textStatus, xhr) {
      if (xhr.status == 204) {
        $message.text(`you do not have any videos yet`);
      } else {
        $message.text(`You have ${data.videos.length} in your library.`);
        diplayAllVideos(data.videos);
      }
    },
  });
}

function addVideo() {
  if ($form[0].checkValidity()) {
    $.ajax({
      method: "POST",
      url: "api/_videos.php",
      dataType: "json",
      data: { url: $url.val(), title: $title.val() },
      error: function (xhr, textStatus, errorThrown) {
        $feedback.text(`An error occured while trying to add video`);
        $feedback.addClass("text-danger");
      },
      success: function (data, textStatus, xhr) {
        displayOneVideo(data.video);
        $feedback.text(`Video added sucessfully`);
        $feedback.addClass("text-success");
      },
    });
  }
}

function editVideo(id) {
  if ($form[0].checkValidity()) {
    $.ajax({
      type: "PUT",
      url: `api/_videos.php?id=${id}&url=${encodeURIComponent(
        $url.val()
      )}&title=${$title.val()}`,
      dataType: "json",
      error: function (xhr, textStatus, errorThrown) {
        $feedback.text("Server Error: Video not updated");
      },
      success: function (data, textStatus, xhr) {
        $video = $(`[data-role="video"][data-id="${id}"]`);
        displayOneVideo(data.video, $video);
        $feedback.text("Video updated successfully");
      },
    });
  }
}

function deleteVideo(id, $video) {
  //TODO: Confirmation modal
  $.ajax({
    method: "DELETE",
    url: `api/_videos.php?id=${id}`,
    success: function (data, textStatus, xhr) {
      $video.remove();
    },
  });
}

//DISPLAY FUNCTIONS

function diplayAllVideos(videos) {
  videos.forEach((video) => {
    displayOneVideo(video);
  });
}

function displayOneVideo(video, $video = null) {
  let updating = true;
  if ($video == null) {
    $video = $template.clone();
    $video.attr("data-role", "video");
    updating = false;
  }

  //get template html elements
  const $title = $video.find('[data-role="title"]');
  const $thumb = $video.find('[data-role="thumb"]');
  const $link = $video.find('[data-role="link"]');
  const $editBtn = $video.find('[data-api="edit"]');
  const $deleteBtn = $video.find('[data-api="delete"]');

  //set values
  $video.attr("data-id", `${video.id}`);
  $video.removeClass("d-none");
  $title.text(video.title);
  $thumb.attr("src", `https://img.youtube.com/vi/${video.yt_id}/hqdefault.jpg`);
  $link.attr("href", `video.php?id=${video.id}`);
  $editBtn.attr("data-id", `${video.id}`);
  $deleteBtn.attr("data-id", `${video.id}`);

  //add event listeners

  if (!updating) {
    $deleteBtn.on("click", function () {
      deleteVideo(video.id, $video);
    });
    $editBtn.on("click", function () {
      modifyForm(video.id);
    });

    //add video
    $video.appendTo($videosContainer);
  }
}

//MODAL FUNCTIONS
async function modifyForm(id = 0) {
  if (!id) {
    //new vedio
    //reset values
    $url.val("");
    if ($url.hasClass("is-invalid")) {
      $url.classList.remove("is-invalid");
    }
    $title.val("");
    if ($title.hasClass("is-invalid")) {
      $title.classList.remove("is-invalid");
    }
    $id.val(0);

    //change titles
    $modal_title.text("Add Video");

    //switch buttons
    if ($submit_video.hasClass("d-none")) {
      $submit_video.removeClass("d-none");
    }
    if (!$update_video.hasClass("d-none")) {
      $update_video.addClass("d-none");
    }
  } else {
    //updating video
    const response = await fetch(`api/_videos.php?id=${id}`);
    const data = await response.json();

    video = data.video;
    //set values
    $url.val(video.url);
    if ($url.hasClass("is-invalid")) {
      $url.classList.remove("is-invalid");
    }
    $title.val(video.title);

    if ($title.hasClass("is-invalid")) {
      $title.classList.remove("is-invalid");
    }
    $id.val(video.id);

    //change titles
    $modal_title.text("Edit Video");

    //switch buttons
    if ($update_video.hasClass("d-none")) {
      $update_video.removeClass("d-none");
    }
    if (!$submit_video.hasClass("d-none")) {
      $submit_video.addClass("d-none");
    }
  }
}

//Action on modal close
$("#video-modal").on("hide.bs.modal", function () {
  resetForm($form);
});

//reset form
function resetForm() {
  $form.removeClass("was-validated");

  //reset feedback
  $feedback.text("");
  if ($feedback.hasClass("text-danger")) $feedback.removeClass("text-danger");
  if ($feedback.hasClass("text-success")) $feedback.removeClass("text-success");
}
