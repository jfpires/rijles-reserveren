<?php session_start(); ?>

<?php
// Include config file
require_once 'config.php';

// Define variables and initialize with empty values
$username = $password = $confirm_password = $firstName = $lastName = $adrs = $phoneNumber = $postalCode = $cty = "";
$username_err = $password_err = $confirm_password_err = $firstName_err = $lastName_err = $adrs_err = $phoneNumber_err = $postalCode_err = $cty_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Validate username
    if(empty(trim($_POST["username"]))){
        $username_err = "Geef een gebruikersnaam a.u.b.";
    } else{
        // Prepare a select statement
        $sql = "SELECT id FROM students WHERE username = ?";

        if($stmt = $db->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("s", $param_username);

            // Set parameters
            $param_username = trim($_POST["username"]);

            // Attempt to execute the prepared statement. Error if username already exists
            if($stmt->execute()){
                // store result
                $stmt->store_result();

                if($stmt->num_rows == 1){
                    $username_err = "Gebruikersnaam bestaat al";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Oeps! Er is iets mis gegaan. Probeer het opnieuw";
            }
        }

        // Close statement
        $stmt->close();
    }

    // Validate password
    if(empty(trim($_POST['password']))){
        $password_err = "Voer een wachtwoord in.";
    } elseif(strlen(trim($_POST['password'])) < 6){
        $password_err = "Het wachtwoord moet tenminste 6 tekens bevatten.";
    } else{
        $password = trim($_POST['password']);
    }

    // Confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = 'Valideer je wachtwoord a.u.b';
    } else{
        $confirm_password = trim($_POST['confirm_password']);
        if($password != $confirm_password){
            $confirm_password_err = 'Wachtwoord komt niet overeen';
        }
    }
    // Validate de rest
    if(empty(trim($_POST["first_name"]))){
        $firstName_err = "Vul een naam in a.u.b.";
        //safety, so limit chars
    } elseif(!filter_var(trim($_POST["first_name"]), FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z'-.\s ]+$/")))){
        $firstName_err = 'vul een geldige naam in a.u.b.';
    } else{
        $firstName = trim($_POST["first_name"]);
    }

    if(empty(trim($_POST["last_name"]))){
        $lastName_err = "Vul een naam in a.u.b.";
        //safety, so limit chars
    } elseif(!filter_var(trim($_POST["last_name"]), FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z'-.\s ]+$/")))){
        $lastName_err = 'vul een geldige naam in a.u.b.';
    } else{
        $lastName = trim($_POST["last_name"]);
    }

    if(empty(trim($_POST["address"]))){
        $adrs_err = 'Vul een adres in a.u.b.';
    } else{
        $adrs = trim($_POST["address"]);
    }

    if(empty(trim($_POST["city"]))){
        $cty_err = 'Vul een adres in a.u.b.';
    } else{
        $cty = trim($_POST["city"]);
    }

    if(empty(trim($_POST["postal_code"]))){
        $postalCode_err = 'Vul een adres in a.u.b.';
    } else{
        $postalCode = trim($_POST["postal_code"]);
    }

    if(empty(trim($_POST["phone_number"]))){
        $phoneNumber_err = "Vul een telefoonnummer in a.u.b.";
        //only numbers
    } elseif(!ctype_digit(trim($_POST["phone_number"]))){
        $phoneNumber_err = 'Vul een geldige telefoonnummer in a.u.b. Gebruik alleen getallen en geen spaties.';
    } else{
        $phoneNumber = trim($_POST["phone_number"]);
    }

    // Check input errors before inserting in database
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err) && empty($firstName_err)
        && empty($adrs_err) && empty($phoneNumber_err) && empty($lastName_err) && empty($cty_err) && empty($postalCode_err)){

        //captcha check
        include_once $_SERVER['DOCUMENT_ROOT'] . '/securimage/securimage.php';
        $securimage = new Securimage();
        if ($securimage->check($_POST['captcha_code']) == false) {
            // the code was incorrect
            // you should handle the error so that the form processor doesn't continue

            // or you can use the following code if there is no validation or you do not know how
            echo "The security code entered was incorrect.<br /><br />";
            echo "Please go <a href='javascript:history.go(-1)'>back</a> and try again.";
            exit;
        }

        // Prepare an insert statement
        $sqlCreate = "INSERT INTO students (username, password, first_name, last_name, address, city, postal_code, phone_number) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        if($stmt = $db->prepare($sqlCreate)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("ssssssss", $param_username, $param_password, $param_firstName, $param_lastName, $param_adrs, $param_postalCode, $param_cty, $param_phoneNumber);

            // Set parameters
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            $param_firstName = $firstName;
            $param_lastName = $lastName;
            $param_adrs = $adrs;
            $param_postalCode = $postalCode;
            $param_cty = $cty;
            $param_phoneNumber = $phoneNumber;

            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Redirect to login page
                header("location: ../students.loginpage.php");
            } else{
                echo "Er is iets mis gegegaan. Probeer het straks opnieuw.";
            }
        }

        // Close statement
        $stmt->close();
    }

    // Close connection
    $db->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<!--header-->
<?php
require_once("includes/include.php");
include ROOT_PATH . '/php/includes/header.php';
?>
<body class ="login">
<div class="container">
    <div class="py-5 text-center">
        <img src="../css/images/logo.png">
    </div>
    <div style="margin-top:50px;" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
        <div class="panel" >
            <div class="panel-heading">
                <div class="panel-title">Registreren</div>
            </div>
            <div style="padding-top:30px" class="panel-body" >
                <!--Form for register-->
                <form id="loginform" class="form-horizontal" role="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div style="margin-bottom: 25px" class="input-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>" >
                        <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i><?php echo $username_err; ?></span>
                        <input id="login-username" type="text" class="form-control" name="username" value="<?php echo $username; ?>" placeholder="Gebruikersnaam">
                    </div>
                    <div style="margin-bottom: 25px" class="input-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i><?php echo $password_err; ?></span>
                        <input id="login-password" type="password" class="form-control" name="password" value="<?php echo $password; ?>" placeholder="Wachtwoord">
                    </div>
                    <div style="margin-bottom: 25px" class="input-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i><?php echo $confirm_password_err; ?></span>
                        <input id="login-password" type="password" class="form-control" name="confirm_password" value="<?php echo $confirm_password; ?>" placeholder="Wachtwoord bevestigen">
                    </div>
                    <div style="margin-bottom: 25px" class="input-group <?php echo (!empty($firstName_err)) ? 'has-error' : ''; ?>">
                        <span class="input-group-addon"><?php echo $firstName_err; ?></span>
                        <input id="login-password" type="text" class="form-control" name="first_name" value="<?php echo $firstName; ?>" placeholder="Voornaam">
                    </div>
                    <div style="margin-bottom: 25px" class="input-group <?php echo (!empty($lastName_err)) ? 'has-error' : ''; ?>">
                        <span class="input-group-addon"><?php echo $lastName_err; ?></span>
                        <input id="login-password" type="text" class="form-control" name="last_name" value="<?php echo $lastName; ?>" placeholder="Achternaam">
                    </div>
                    <div style="margin-bottom: 25px" class="input-group <?php echo (!empty($adrs_err)) ? 'has-error' : ''; ?>">
                        <span class="input-group-addon"><?php echo $adrs_err; ?></span>
                        <input id="login-password" type="text" class="form-control" name="address" value="<?php echo $adrs; ?>" placeholder="Straat en huisnummer">
                    </div>
                    <div style="margin-bottom: 25px" class="input-group <?php echo (!empty($cty_err)) ? 'has-error' : ''; ?>">
                        <span class="input-group-addon"><?php echo $cty_err; ?></span>
                        <input id="login-password" type="text" class="form-control" name="city" value="<?php echo $cty; ?>" placeholder="Stad">
                    </div>
                    <div style="margin-bottom: 25px" class="input-group <?php echo (!empty($postalCode_err)) ? 'has-error' : ''; ?>">
                        <span class="input-group-addon"><?php echo $postalCode_err; ?></span>
                        <input id="login-password" type="text" class="form-control" name="postal_code" value="<?php echo $postalCode; ?>" placeholder="Postcode">
                    </div>
                    <div style="margin-bottom: 25px" class="input-group <?php echo (!empty($phoneNumber_err)) ? 'has-error' : ''; ?>">
                        <span class="input-group-addon"><?php echo $phoneNumber_err; ?></span>
                        <input id="login-password" type="text" class="form-control" name="phone_number" value="<?php echo $phoneNumber; ?>" placeholder="Telefoonnummer">
                    </div>
                    <!--captcha image-->
                    <div>
                        <p>Type de tekst op de afbeelding</p>
                        <img id="captcha" src="../securimage/securimage_show.php" alt="CAPTCHA Image"/>
                        <input type="text" name="captcha_code" size="10" maxlength="6"/>
                        <a href="#" onclick="document.getElementById('captcha').src = '../securimage/securimage_show.php?' + Math.random(); return false">[ Andere Afbeelding ]</a>
                    </div>
                    <!-- Buttons -->
                    <div style="margin-top:10px" class="form-group">
                        <div class="col-sm-12 controls">
                            <input type="submit" class="btn btn-primary" value="Submit">
                            <input type="reset" class="btn btn-default" value="Reset">
                        </div>
                    </div>
                    <!-- Al een account -->
                    <div class="form-group">
                        <div class="col-md-12 control">
                            <div style="border-top: 1px solid#888; padding-top:15px; font-size:85%" >
                                <p>Heb je al een account? <a href="../students.loginpage.php">Hier inloggen</a>.</p>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--Footer and scripts-->
<?php include 'includes/footer.php';?>
</body>
</html>
