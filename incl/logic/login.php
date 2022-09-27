<?php
//Login logic
$username = $password = "";
$username_error = $password_error = $auth_error = "";
$valid = true;

if (isset($_POST['submit'])) {
   
    require_once ("incl/logic/sanitize.php");

    //get input values
    $username = sanitize($_POST['username']);
    $password =  sanitize($_POST['password']);
    $valid = true;

    //validate
  if (empty($username)) {
    $valid = false;
    $username_error = "Required field";
  } 

  if (empty($password)) {
    $valid = false;
    $password_error  = "Required field";
  }

    //check database for matches
    if ($valid) {

        $qry = "SELECT id, username, password FROM users WHERE username=?";
        $stmt = mysqli_prepare($conn, $qry);
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt) or die("Server error : login failed");
        mysqli_stmt_store_result($stmt);
        if (mysqli_stmt_num_rows($stmt) == 1) {

            mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
            mysqli_stmt_fetch($stmt);

            $password = md5($password);

            if ($password == $hashed_password) {
                //sucessful login
                //get user data
                $user = [
                    "username" => $username,
                    "user_id" => $id
                ];

                //save used data to session 
                //TODO : set session timeout (ex: 30 min) and extend that every time tehre is a request
                //reference: https://stackoverflow.com/questions/520237/how-do-i-expire-a-php-session-after-30-minutes
                $_SESSION['user'] =  $user;
                $_SESSION['username'] = $user["username"];
                $_SESSION['user_id'] = $user["user_id"];

                mysqli_stmt_close($stmt);
                header('location: dashboard.php');
            } else {
              
                $auth_error = "wrong password";
            }
        } else {
           
            $auth_error = "wrong username";
        }
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