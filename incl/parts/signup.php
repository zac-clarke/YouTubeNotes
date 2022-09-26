<!-- Modal -->
<?php require_once "incl/logic/signup.php"; ?>

<div class="modal fade" id="signup" tabindex="-1" aria-labelledby="SignupPopup" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <h3 class="text-dark">Signup</h3>
        <p class="text-dark">Get started with our YouTube study aid!</p>




        <form class = "was-validated" action="index.php" method="POST" >
          
            <input type="hidden" name="signup">
            <div class="form-group ">
              <input type="text" name="username" class="form-control is-invalid" placeholder="Username" required>
              <div class = "valid-feedback">
                Valid Username
              </div>
              <p class="text-danger"><?= $username_error ?></p>
            </div>
            <div class="form-group ">
              <input type="email" name="email" class="form-control is-invalid" placeholder="Email" required>
              <div class = "valid-feedback">
                Valid email
              </div>
              <p class="text-danger"><?= $email_error ?></p>
            </div>
            <div class="form-group ">
              <input type="password" name="password" class="form-control is-invalid" placeholder="Password" required>
              <div class = "valid-feedback">
                Valid Password
              </div>
              <p class="text-danger"><?= $password_error ?></p>
            </div>
            <div class="form-group ">
              <input type="submit" name="submit" class="btn btn-primary" onclick="" value="Sign Up"></input>
            </div>
          
        </form>




      </div>
      <div class="modal-footer">
        some footer
      </div>
    </div>
  </div>
</div>