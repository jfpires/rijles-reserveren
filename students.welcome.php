<?php

// Initialize the session
session_start();

// If session variable is not set it will redirect to login page
if(!isset($_SESSION['username']) || empty($_SESSION['username'])){
    header("location: php/login.php");
    exit;
}
$fName = htmlspecialchars($_SESSION['username']);

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
<?php include 'php/includes/navbar.php';?>

<div class="container">
    <div class="py-5 text-center">
        <h2>Welkom <b><?php echo $fName; ?></b></h2>
    </div>
    <br>
</div>

<?php include 'php/includes/footer.php';?>
<!-- Optional JavaScript -->
<script src="js/formValidation.js"></script>
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>
</body>
</html>