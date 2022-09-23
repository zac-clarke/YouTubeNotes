<!-- Modal -->
<?php require_once "incl/logic/login.php"; ?>

<div class="modal fade" id="login" tabindex="-1" aria-labelledby="LoginPopup" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <h3 class="text-dark">Login</h3>
        <p class="text-dark">No db connection yet so just type whatever username for now.</p>




        <form action="index.php" method="POST">
          <div class="input-group ">
            <input type="hidden" name="login">
            <input type="text" name="username" class="form-control" placeholder="Username" value="<?= isset($_POST['username']) ? $_POST['username']: '' ?>">
            <input type="password" name="password" class="form-control" placeholder="Password">
            <input type="submit" name="submit" class="btn btn-primary" onclick="" value="Login"></input>
          </div>
        </form>




      </div>
      <div class="modal-footer">
        some footer
      </div>
    </div>
  </div>
</div>

<?php if (isset($_POST['login'])) : ?>
  <script defer>
    $(document).ready(function() {
      $("#login").modal('show');
    });
  </script>

<?php endif;
