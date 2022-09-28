function getVideos() {
  $.ajax({
    method: "GET",
    url: "api/_videos.php",
    dataType: "json",
    error: function (xhr, textStatus, errorThrown) {
      this.showfeedback(`An error occured while loading videos`, true);
    },
    success: function (data, textStatus, xhr) {
      //   if (data.videos.length < 1)
      if (xhr.status == 204) {
        this.showfeedback(`you do not have any videos yet`);
      } else {
        this.showfeedback(`You have ${data.videos.length} in your library.`);
        diplayVideos(data.videos);
      }
    },
    showfeedback: function (msg, iserror = false) {
      const $message = $("#video-collection-message");
      $message.text(msg);
      if (iserror) {
        $message.addClass("text-danger");
      }
    },
  });
}

function diplayVideos(videos) {
  const $videosContainer = $("#videos-container");
  const $template = $videosContainer.find('[data-role="video"]').first();

  if (videos.length < 1) {
    $template.remove();
  } else {
    //get template html elements
    const $title = $template.find('[data-role="title"]');
    const $thumb = $template.find('[data-role="thumb"]');
    const $link = $template.find('[data-role="link"]');
    const $editBtn = $template.find('[data-api="edit"]');
    const $deleteBtn = $template.find('[data-api="delete"]');

    videos.forEach((video) => {
      //set values
      $template.attr("data-id", `${video.id}`);
      $title.text(video.title);
      $thumb.attr(
        "src",
        `https://img.youtube.com/vi/${video.yt_id}/hqdefault.jpg`
      );
      $link.attr("href", `video.php?id=${video.id}`);
      $editBtn.attr("data-id", `${video.id}`);
      $deleteBtn.attr("data-id", `${video.id}`);

      //add video
      $template.clone().appendTo($videosContainer);
    });

    $template.remove();
    setActions();
    console.log("test");
  }
}

$(document).ready(function () {
  getVideos();
});

function setActions() {
  console.log("test");
  const $editBtn = $('[data-api="edit"]');
  const $addBtn = $('[data-api="add"]');
  const $deleteBtn = $('[data-api="delete"]');

  $addBtn.each(function () {
    $(this).on("click", function () {
      configModal();
    });
  });

  $editBtn.each(function () {
    const id = $(this).attr("data-id");
    $(this).on("click", function () {
      configModal(id);
    });
  });

  $deleteBtn.each(function () {
    const id = $(this).attr("data-id");
    const $video = $(`[data-role="video"][data-id="${id}"]`);
    $(this).on("click", function () {
      deleteVideo(id, $video);
    });
  });
}

async function configModal(id = 0) {
  //video modal
  const $modal = $("#video-modal");
  //form input in video modal
  const $id = $modal.find("#id");
  const $url = $modal.find("#url");
  const $title = $modal.find("#title");
  const $modal_title = $modal.find("#modal_title");
  const $submit = $modal.find("#submit");
  const $server_feedback = $modal.find("#server-feedback");

  if (!id) {
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
    $submit.text("Add");
    $submit.on("click", addVideo);
  } else {
    const response = await fetch(`/YouTubeNotes/api/_videos.php?id=${id}`);
    const data = await response.json();

    video = data.video;
    //set values
    $url.val(video.url);
    if ($url.hasClass("is-invalid")) {
      $url.classList.remove("is-invalid");
    }
    $title.val(video.title);
    console.log("test");
    if ($title.hasClass("is-invalid")) {
      $title.classList.remove("is-invalid");
    }
    $id.val(video.id);

    //change titles
    $modal_title.text("Edit Video");
    $submit.text("Update");
    $submit.on("click", function () {
      editVideo(id);
    });
  }
}

function addVideo() {
  //video modal
  const $modal = $("#video-modal");
  const $form = $modal.find("form");

  if ($form[0].checkValidity()) {
    const $url = $form.find("input[name='url']");
    const $title = $form.find("input[name='title']");

    $.ajax({
      method: "POST",
      url: "api/_videos.php",
      dataType: "json",
      data: { url: $url.val(), title: $title.val() },
      error: function (xhr, textStatus, errorThrown) {
        this.showfeedback(`An error occured while trying to add video`, true);
      },
      success: function (data, textStatus, xhr) {
        showVideo(data.video);
        this.showfeedback(`Video added sucessfully`);
      },
      showfeedback: function (msg, iserror = false) {
        const $message = $form.find("#server-feedback");
        console.log($message);
        $message.text(msg);
        if (iserror) {
          $message.addClass("text-danger");
        } else {
          $message.addClass("text-success");
        }
      },
    });
  }
}

function editVideo(id) {
  //video modal

  const $modal = $("#video-modal");
  const $form = $modal.find("form");

  if ($form[0].checkValidity()) {
    const $url = $form.find("input[name='url']");
    const $title = $form.find("input[name='title']");


    console.log(id);
    console.log($url.val());
    console.log($title.val());

 
    $.ajax({
      type: "PUT",
      // ?id=${id}&url=${$url.val()}&title=${$title.val()}
      url: `api/_videos.php`,
      dataType: "json",
      data: { id: id, url: $url.val(), title: $title.val() },
      error: function (xhr, textStatus, errorThrown) {
        this.showfeedback(`An error occured while trying to edit video`, true);
      },
      success: function (data, textStatus, xhr) {
        showVideo(data.video);
        this.showfeedback(`Video updated sucessfully`);
      },
      showfeedback: function (msg, iserror = false) {
        const $message = $form.find("#server-feedback");
        $message.text(msg);
        if (iserror) {
          $message.addClass("text-danger");
        } else {
          $message.addClass("text-success");
        }
      },
    });
  }
}

function showVideo(video) {
  const $videosContainer = $("#videos-container");
  const $template = $videosContainer.find('[data-role="video"]').first();

  //get template html elements
  const $title = $template.find('[data-role="title"]');
  const $thumb = $template.find('[data-role="thumb"]');
  const $link = $template.find('[data-role="link"]');
  const $editBtn = $template.find('[data-api="edit"]');
  const $deleteBtn = $template.find('[data-api="delete"]');

  //set values
  $template.attr("data-id", `${video.id}`);
  $title.text(video.title);
  $thumb.attr("src", `https://img.youtube.com/vi/${video.yt_id}/hqdefault.jpg`);
  $link.attr("href", `video.php?id=${video.id}`);
  $editBtn.attr("data-id", `${video.id}`);
  $deleteBtn.attr("data-id", `${video.id}`);

  //add video
  $template.clone().appendTo($videosContainer);

  $template.remove();
  setActions();
}

function deleteVideo(id, $element) {
  //TODO: Confirmation modal
  $.ajax({
    method: "DELETE",
    url: `api/_videos.php?id=${id}`,
    success: function (data, textStatus, xhr) {
      $element.remove();
    },
  });
}

//Action on modal close
$("#video-modal").on("hide.bs.modal", function () {
  console.log("closing modal");
  const $form = $(this).find("form");
  resetForm($form);
});

//reset form
function resetForm($form) {
  $form.removeClass("was-validated");
  $inputs = $form.find("input");
  $server_feedback = $form.find("#server-feedback");
  $server_feedback.removeClass("text-danger text-success");
  $server_feedback.text("");
}
