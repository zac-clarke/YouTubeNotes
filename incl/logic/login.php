<?php
//Login logic
if (isset($_POST['submit'])) {
    $user = $_POST['username'];
    $password = $_POST['password'];


    //temp user, later have to get from db
    $temp_user = [
        "username" => "jaz",
        "password" => "123",
        "user_id" => "1"
    ];

    //temp logic, later has to be based on db
    if ($user == $temp_user["username"] && $password == $temp_user["password"]) {
        //sucessful login
        $_SESSION['username'] = $user;
        $_SESSION['user_id'] = $temp_user["user_id"];
        header('location: index.php');
    }
}
?>

<?php
//Show Modal on reload on incorrect login
if (isset($_POST['login'])) : ?>
    <script defer>
        //show the logon modal on reload for invalid logins      
        document.onreadystatechange = function() {
            document.getElementById("btn_login").click();
        };
    </script>
<?php endif; ?>