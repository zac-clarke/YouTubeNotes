/**
 * Disables form submissions if there are invalid fields
 * https://getbootstrap.com/docs/5.0/forms/validation/
 */
// alert ('disable form submissionsss');
function disableFormSubmission() {
  "use strict";
  // Fetch all the forms we want to apply custom Bootstrap validation styles to
  var forms = document.querySelectorAll(".needs-validation");

  // Loop over them and prevent submission
  Array.prototype.slice.call(forms).forEach(function (form) {
    form.addEventListener(
      "submit",
      function (event) {
        event.preventDefault();
        event.stopPropagation();
        form.classList.add("was-validated");
      },
      false
    );
  });
}

disableFormSubmission();
