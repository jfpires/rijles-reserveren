<?php

require_once 'php/login.php'

?>

<!DOCTYPE html>
<html lang="en">
<!--header-->
<?php
require_once("php/includes/include.php");
include ROOT_PATH . '/php/includes/header.php';
?>
<body class ="login">
<div class="container">
    <div class="py-5 text-center">
        <img src="css/images/logo.png">
    </div>
    <div style="margin-top:50px;" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
        <div class="panel" >
            <div class="panel-heading">
                <div class="panel-title">Leerling login</div>
            </div>
            <div style="padding-top:30px" class="panel-body" >
                <form id="loginform" class="form-horizontal" role="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div style="margin-bottom: 25px" class="input-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>" >
                        <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i><?php echo $username_err; ?></span>
                        <input id="login-username" type="text" class="form-control" name="username" value="<?php echo $username; ?>" placeholder="Gebruikersnaam">
                    </div>

                    <div style="margin-bottom: 25px" class="input-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i><?php echo $password_err; ?></span>
                        <input id="login-password" type="password" class="form-control" name="password" placeholder="Wachtwoord">
                    </div>

                    <div style="margin-top:10px" class="form-group">
                        <!-- Button -->
                        <div class="col-sm-12 controls">
                            <input type="submit" class="btn btn-primary" value="Login">
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-12 control">
                            <div style="border-top: 1px solid#888; padding-top:15px; font-size:85%" >
                                <p>Geen account? <a href="php/register.php">Registreer hier</a>.</p>
                                <a href="admin.loginpage.php">Admin login</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--Footer and scripts-->
<?php include 'php/includes/footer.php';?>
</body>
</html>