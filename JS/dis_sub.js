
(function () {
    'use strict'
 

    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    var forms = document.querySelectorAll('.needs-validation')
  
    // Loop over them and prevent submission
    Array.prototype.slice.call(forms)
        .forEach(function (form) {
            form.querySelectorAll("input").forEach(element=>{
                element.addEventListener('input', function (event) {
                    if (!element.checkValidity()) {
                       element.classList.add("is-invalid")
                       element.classList.remove("is-valid")
                    }else{
                        element.classList.add("is-valid")
                        element.classList.remove("is-invalid")
                    }
    
                   
                  
                }, false)
            })
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }

                form.classList.add('was-validated')
              
            }, false)
        })
})()

document.getElementById('signup').addEventListener('shown.bs.modal', onModalNoteShow);
function onModalNoteShow() {
  let form = modalNote.querySelector('form');
  if (!form.checkValidity()) {
    modalNote.querySelector('button[type="submit"]').click();
  }
}