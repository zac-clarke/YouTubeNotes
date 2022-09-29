<!-- SIGNUP MODAL -->
<?php require_once "incl/logic/signup.php"; ?>
<script defer src="JS/dis_sub.js"></script>



<div class="modal" id="login-modal" tabindex="-1" aria-labelledby="LoginPopup" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header border-0">
      
        <button type="button" class="btn-close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <h3 class="text-dark">Signup</h3>
        <p class="text-dark">Get started with our YouTube study aid!</p>




        <form class="needs-validation" action="index.php" method="POST" novalidate>

          <input type="hidden" name="signup">
          <div class="form-group ">
            <input type="text" name="username" class="form-control <?php if($username_error!='&nbsp;')echo 'is-invalid';?> "placeholder="Username" value="<?=$username?>" required>
            <div class="valid-feedback">
              Valid Username
            </div>
            <div class="invalid-feedback">
              Invalid Username
            </div>
            <p class="text-danger"><?= $username_error ?></p>
          </div>
          <div class="form-group ">
            <input type="email" name="email" class="form-control <?php if($email_error!='&nbsp;')echo 'is-invalid';?>" placeholder="Email" value= "<?=$email?>" required>
            <div class="valid-feedback">
              Valid Email
            </div>
            <div class="invalid-feedback">
              Invalid Email
            </div>
            <p class="text-danger"><?= $email_error ?></p>
          </div>
          <div class="form-group">
            <input type="password" name="password" class="form-control " placeholder="Password" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$" required>
            <div class="valid-feedback">
              Valid Password
            </div>
            <div class="invalid-feedback">
              Invalid Password(must contain at least: one special character, one number and one uppercase & lowercase letter)
            </div>
            <p class="text-danger"><?= $password_error ?></p>
          </div>
          <div class="form-group ">
            <input type="submit" name="submit" class="btn btn-primary" onclick="" value="Sign Up"></input>
          </div>

        </form>




          <input type="submit" name="submit" class="btn btn-primary mt-3" onclick="" value="Login"></input>
      </div>
      </form>
    </div>
    <div class="modal-footer">
      some footer
    </div>
  </div>
</div>
</div>