<?php
$con = mysqli_connect("localhost", "root", "", "xwalk");
if(mysqli_connect_error()) {
    echo "failed to connect: " . mysqli_connect_error();
}
include("includes/header.php");

?>

<html>
<head>
    <title>cross walk</title>
</head>
<body>
    Jesus Saves


    <?php echo $user['email']; ?>
</body>
</html>

