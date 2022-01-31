<?php
if (isset($_POST['login_button'])) {
    $email =filter_var($_POST['log_email'],  FILTER_SANITIZE_EMAIL); //sanitize e mail
    
    $_SESSION['log_email'] = $email; //store e mail into session
    $password = md5($_POST['log_password']); //get password from session

    $check_database_query = mysqli_query($con, "SELECT * FROM users WHERE email='$email' AND password='$password'");
    $check_login_query = mysqli_num_rows($check_database_query);

    if($check_login_query == 1) {
        $row = mysqli_fetch_array($check_database_query); //login error where I can log in regardless of correct e mail or password
        $username = $row['username']; //get username from

        $user_closed_query = mysqli_query($con, "SELECT * FROM users WHERE EMAIL= '$email' AND user_closed='yes'");
        if(mysqli_num_rows($user_closed_query) == 1) {
            $reopen_account = mysqli_query($con, "UPDATE users SET user_closed='no' WHERE email='$email'");
        }

        $_SESSION['username'] = $username;
        header("Location: index.php");
        exit();
    }
    else {
        array_push($error_array, "Email or Password  was incorrect<br>");
    }

    }
