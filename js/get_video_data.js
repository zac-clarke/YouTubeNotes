
document.querySelectorAll("#btn_add_video, .btn.edit_video").forEach((element) => {
  element.addEventListener("click", async function (event) {

console.log(element);
    let data_id = event.target.getAttribute("data-id");

    //form input in the add-edit modal
    let id = document.getElementById("video_id");
    let url = document.getElementById("video_url");
    let title = document.getElementById("video_title");
    let modal_title = document.getElementById("modal_video_title");
    let submit = document.getElementById("submit_video");

    //if it is an edit video button
    if (data_id) {
      //fetch data from api
      let response = await fetch(`/YouTubeNotes/api/videos.php?id=${data_id}`);
      let data = await response.json();

      //set data as value for form inputs
      url.value = data.url;
      title.value = data.title;
      id.value = data_id;

      //change titles
      modal_title.textContent = "Edit Video";
      submit.value = "Update";
    } else {//id its the add vodeo button
      //reset inputs
      url.value = "";
      title.value = "";
      id.value = 0;

      //change titles
      modal_title.textContent = "Add Video";
      submit.value = "Add";
    }
  });
});

//trying to reset modal validation on close, but it resets on reload too

document.querySelector('.modal').addEventListener('hidden.bs.modal', event => {
    document.getElementById("video_url").classList.remove("is-invalid");
    document.getElementById("video_title").classList.remove("is-invalid");
})