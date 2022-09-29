<?php
require_once("incl/logic/sanitize.php");
// TODO : Check pw pattern

//init error messages
$email_error = $password_error = $username_error =  "&nbsp;";
//init storage variabales
$email = $password = $username = "";

$isValid = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_REQUEST['signup'])) {
        $isValid = validateForm();
        if ($isValid) {
            $qry = "INSERT into `users` (username, password, email)
                        VALUES (?,?,?) ";

            $stmt = mysqli_prepare($conn, $qry);
            $hashed_password = md5($password);

            mysqli_stmt_bind_param($stmt, "sss", $username, $hashed_password, $email);

            try {
                $result = mysqli_stmt_execute($stmt);
            } catch (Exception $e) {
                if ($e->getCode() == 1062) {
                    if (str_contains($e->getMessage(), "username")) {
                        $username_error = "this username is already taken";
                    } else if (str_contains($e->getMessage(), "email")) {
                        $email_error = "this email is already taken";
                    }
                } //Show Modal on reload on incorrect login
                if (isset($_POST['signup'])) : ?>
                    <script defer>
                        //show the signup modal on reload for invalid signup
                        document.onreadystatechange = function() {
                            document.getElementById("btn_signup").click();
                        };
                    </script>
<?php
                endif;
            } finally {
                // $result = mysqli_query($conn,$query);
                if (!empty($result)) {
                    $qry = "SELECT id, username, password FROM users WHERE username=?";
                    $stmt = mysqli_prepare($conn, $qry);
                    mysqli_stmt_bind_param($stmt, "s", $username);
                    mysqli_stmt_execute($stmt) or die("Server error : login failed");
                    mysqli_stmt_store_result($stmt);
                    if (mysqli_stmt_num_rows($stmt) == 1) {
                        mysqli_stmt_bind_result($stmt, $id, $username, $password);
                        mysqli_stmt_fetch($stmt);

                        if ($password == $hashed_password) {
                            //get user data
                            $user = [
                                "username" => $username,
                                "user_id" => $id
                            ];

                            //save used data to session 
                            //TODO : set session timeout (ex: 30 min) and extend that every time tehre is a request
                            //reference: https://stackoverflow.com/questions/520237/how-do-i-expire-a-php-session-after-30-minutes
                            $_SESSION['user'] =  $user;
                            $_SESSION['username'] = $username;
                            $_SESSION['user_id'] = $id;

                            mysqli_stmt_close($stmt);
                            echo "<script>window.location.href='dashboard.php?first'</script>";
                        } else
                            die("Registration was successful, but wasn't able to log in!");
                    }
                }
            }
        }
    }
}

//Upon Posting
function validateForm()
{
    global $email_error, $password_error, $username_error;
    global $email, $password, $username;
    $isValid = true;


    if (empty($_POST["email"])) {
        $email_error = "Email is required";
        $isValid = false;
    } else {
        $email_error = "&nbsp;";
        $email = sanitize($_POST["email"]);

        if (!preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/", $email)) {
            $email_error = "Invalid email format";
            $isValid = false;
        }
    }
    if (empty($_POST["password"])) {
        $password_error = "Password is required";
        $isValid = false;
    } else {
        $password_error = "";
        $password = sanitize($_POST["password"]);
        if (!preg_match("^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$^", $password)) {
            $password_error = "The password must contain at least: one uppercase and one lowercase letter, one number, and 1 speacial character";
            $isValid = false;
        }
    }

    if (empty($_POST["username"])) {
        $username_error = "Username name is required";
        $isValid = false;
    } else {
        $usernameerror = "&nbsp;";
        $username = sanitize($_POST["username"]);

        if (!preg_match("/.*?/", $username)) {
            $username_error = "Invalid name format, must be all capitals with no space, up to 10 characters";
            $isValid = false;
        }
    }
    return $isValid;
}
?>