<!-- Modal -->
<?php require_once "incl/logic/login.php"; ?>



<div class="modal" id="login-modal" tabindex="-1" aria-labelledby="LoginPopup" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header border-0">

        <button type="button" class="btn-close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <h3 class="text-dark">Login</h3>
        <form action="index.php " method="POST" class="px-5 needs-validation" novalidate>
          <input type="hidden" name="login">
          <div class="text-danger mb-3 <?= ($auth_error == '') ? '' : 'is-invalid' ?>">
            <?= $auth_error ?>
          </div>

          <div class="input-group has-validation">
            <input type="text" name="username" id="username" class="form-control mb-3 has-validation <?= ($username_error == '') ? '' : 'is-invalid' ?>" placeholder="Username" required value="<?= isset($_POST['username']) ? $_POST['username'] : '' ?>">
            <div class="invalid-feedback mb-3" aria-describedby="username">
              <?= $username_error ? $username_error : "Username is required" ?>
            </div>
          </div>
          <div class="input-group has-validation">
            <input type="password" name="password" id="password" class="form-control mb-3 has-validation <?= ($password_error == '') ? '' : 'is-invalid' ?>" required placeholder="Password">
            <div class="invalid-feedback mb-3" aria-describedby="password">
              <?= $password_error ? $password_error : "Password is required" ?>
            </div>
          </div>

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