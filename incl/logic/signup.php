<?php

$trn_date = date("Y-m-d H:i:s");

//init error messages
$email_error = $password_error = $username_error =  "&nbsp;";
//init storage variabales
$email = $password = $username = "";

$isValid = false;

//Upon Posting
function ValidateForm()
{
    global $email_error, $password_error, $username_error;
    global $email, $password, $username;
    global $isValid;
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
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
            if (!preg_match("/.*?/", $password)) {
                $password_error = "The password should be at least 10 chars and include exactly one special
                    character, one uppercase letter and one digit";
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
    }
}

function sanitize($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

ValidateForm();






// $query = "INSERT into `users` (username, password, email, trn_date)
//     VALUES ('$username', '" . md5($password) . "', '$email', '$trn_date')";
if ($isValid) {
    $qry = "INSERT into `users` (username, password, email)
                VALUES (?,?,?) ";

    $stmt = mysqli_prepare($conn, $qry);
    $password = md5($password);

    mysqli_stmt_bind_param($stmt, "sss", $username, $password, $email);
  
    try {
        $result = mysqli_stmt_execute($stmt);
    } catch (Exception $e) {
        if ($e->getCode() == 1062) {
            if (str_contains($e->getMessage(), "username")) {
                $username_error = "this username is already taken";
            }else if (str_contains($e->getMessage(), "email")) {
                $email_error = "this email is already taken";
            }
        }
        // if($e->getCode() == 1062){
        //     $username_error= "this username is already taken";
        // }


        // $email_error = $e->getMessage();

    }

    // $result = mysqli_query($conn,$query);
    if (!empty($result)) {
        // $findId = "SELECT id from users where username = '.$username.'";
        // $result2 = mysqli_query($conn,$findId);
        // $row = mysqli_fetch_assoc($result2);




        $_SESSION['username'] = $username;
        // $_SESSION['user_id'] = $row[0];
        header('location: index.php');
    }
}
?>
<?php
//Show Modal on reload on incorrect login
if (isset($_POST['signup'])) : ?>

    <script defer>
        //show the logon modal on reload for invalid logins      
        document.onreadystatechange = function() {
            var myModal = new bootstrap.Modal(document.getElementById("signup"), {});
            myModal.show();
        };
    </script>
<?php endif; ?>