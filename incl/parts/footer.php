    <footer class="mt-auto">
        <p>Jaz </p>
    </footer>
</div>


<!-- JavaScript Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous"></script>

<?php 
if (!$loggedin) {
    require_once "incl/parts/login.php";
    require_once "incl/parts/signup.php";
} else {
    // require_once "incl/parts/add-edit-video.php";
}

?>

</body>
</html>