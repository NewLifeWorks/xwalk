<?php
//Declaring variables to prevent errors
$fname = " "; //First Name
$lname = " "; //Last Name
$em = " "; //email
$email2 = " "; //email 2
$password = " "; //Password
$password2 = " "; //Password 2
$date = " "; //Date user signed up
$error_array = array(); //Holds any error messages

if (isset($_POST['register_button'])) {

    //registration form values

    //First Name
    $fname = strip_tags($_POST['reg_fname']); //remove html tags
    $fname = str_replace(' ', ' ', $fname); //remove spaces
    $fname = ucfirst(strtolower($fname)); // Uppercase first initial and make the remaining lower case
    $_SESSION['reg_fname'] = $fname; //Stores the first name of the user variable

    //Lastt Name
    $lname = strip_tags($_POST['reg_lname']); //remove html tags
    $lname = str_replace(' ', ' ', $lname); //remove spaces
    $lname = ucfirst(strtolower($lname)); // Uppercase first initial and make the remaining lower case
    $_SESSION['reg_lname'] = $lname; //Stores the last name of the user variable

    //Email
    $em = strip_tags($_POST['reg_email']); //remove html tags
    $em = str_replace(' ', ' ', $em); //remove spaces
    $em = ucfirst(strtolower($em)); // Uppercase first initial and make the remaining lower case
    $_SESSION['reg_email'] = $em; //Stores the email of the user variable

    //Email 2 Confirmation Email
    $em2 = strip_tags($_POST['reg_email2']); //remove html tags
    $em2 = str_replace(' ', ' ', $em2); //remove spaces
    $em2 = ucfirst(strtolower($em2)); // Uppercase first initial and make the remaining lower case
    $_SESSION['reg_email2'] = $em2; //Stores the email confirmation of the user variable

    //Password security
    $password = strip_tags($_POST['reg_password']); //remove html tags
    //Password Confirm security
    $password2 = strip_tags($_POST['reg_password2']); //remove html tags

    $date = date("Y-m-d"); //Current Date

    //Matching e mail
    if ($em == $em2) {

        if (filter_var($em, FILTER_VALIDATE_EMAIL)) {
            $em = filter_var($em, FILTER_VALIDATE_EMAIL);

            //Check if e mail already exists

            $e_check = mysqli_query($con, "SELECT email FROM users1 WHERE email = '$em2' ");
            //Countnum of rows returned

            $num_rows = mysqli_num_rows($e_check);

            if ($num_rows > 0) {
                array_push($error_array, "Sorry, this email has already been taken<br>");
            }
        } else {
            array_push($error_array, "Invalid Email format<br>");
        }

        //Validate correct e mail format

    } else {
        array_push($error_array, "Sorry, emails don't match<br>");
    }

    if (strlen($fname) > 25 || strlen($fname) < 2) {
        array_push($error_array, "Your first name must be between 2 and 25 characters<br>");
    }
    if (strlen($lname) > 25 || strlen($lname) < 2) {
        array_push($error_array, "Your last name must be between 2 and 25 characters<br>");
    }

    if ($password != $password2) {
        array_push($error_array, "Sorry, Your passwords don't match<br>'");
    } else {
        if (preg_match('/[^A-Za-z0-9]/', $password)) {
            array_push($error_array, "Your password must only contain English characters or numbers<br>");
        }
    }

    if (strlen($password) > 30 || strlen($password) < 5) {
        array_push($error_array, "Your password must be between 5 and 30 characters<br>");
    }

    if (empty($error_array)) {
        $password = md5($password);    //encrypt password before sending to database

        //Generate user name by concatenating first and last name
        $username = strtolower($fname . "_" . $lname);
        $check_username_query = mysqli_query($con, "SELECT username FROM users1 WHERE username = '$username'");

        $i = 0;
        $temp_username = $username;
        //if username exists, add numbers
        while (mysqli_num_rows($check_username_query) != 0) {
            $temp_username = $username;
            //reset to original username
            $i++; //add 1 to i  
            $temp_username = $username . "_" . $i;
            $check_username_query = mysqli_query($con, "SELECT username FROM users WHERE username = '$temp_username''");
        }

        //will now use unique username
        $username = $temp_username;
        //Assiggnment of user profile picture
        $rand = rand(1, 2); //random profile pics
        if ($rand == 1)
            $profile_pic = "asets/images/profile_pics/defaults/head_deep_blue.png";
        else if ($rand == 2)
            $profile_pic = "asets/images/profile_pics/defaults/head_amethyst.png";

        $query = mysqli_query($con, "INSERT INTO users VALUES (' ', '$fname', '$lname', '$username', '$em',  '$password', '$date', '$profile_pic', '0', '0', 'no', ' , ' )");

        array_push($error_array, "<span style = 'color: green;' >You're all set! Go ahead and log in.</span><br>");

        //clear session variables
        $_SESSION['reg_fname'] = "";
        $_SESSION['reg_lname'] = "";
        $_SESSION['reg_email'] = "";
        $_SESSION['reg_email2'] = "";
    }
}
