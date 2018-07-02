<?php
// Initialize the session
session_start();

// If session variable is not set it will redirect to login page
if(!isset($_SESSION['username']) || empty($_SESSION['username'])){
    header("location: php/admin.login.php");
    exit;
}

// Holds the username
$fName = htmlspecialchars($_SESSION['username']);

// If someone, that's not the admin, try's to log in, send to error
if($fName != 'Admin'){ //TODO: loop voor more admins
    header("location: php/error.php");
}

?>
<!doctype html>
<html lang="en">

<!--header-->
<?php
require_once("php/includes/include.php");
include ROOT_PATH . '/php/includes/header.php';
?>

<body>
<!--navbar-->
<?php include 'php/includes/admin.navbar.php';?>

<div class="container">
    <div class="py-5 text-center">
        <h2>Welkom <b><?php echo $fName; ?></b></h2>
    </div>
    <br>
</div>
<?php include 'php/includes/footer.php';?>
</html>
