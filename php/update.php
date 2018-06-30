<?php

require_once 'config.php';

$firstName = $lastName = $adrs = $cty = $postalCode = $phoneNumber = "";
$firstName_err = $lastName_err = $adrs_err = $cty_err = $postalCode_err = $phoneNumber_err = "";

// Processing form data when form is submitted
if(isset($_POST["id"]) && !empty($_POST["id"])){
    $id = $_POST["id"];

    // Validate inputs
    $input_firstName = trim($_POST["first_name"]);
    if(empty($input_firstName)){
        $firstName_err = "Vul een naam in a.u.b.";
        //safety, so limit chars
    } elseif(!filter_var(trim($_POST["first_name"]), FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z'-.\s ]+$/")))){
        $firstName_err = 'vul een geldige naam in a.u.b.';
    } else{
        $firstName = $input_firstName;
    }

    $input_lastName = trim($_POST["last_name"]);
    if(empty($input_lastName)){
        $lastName_err = "Vul een naam in a.u.b.";
        //safety, so limit chars
    } elseif(!filter_var(trim($_POST["last_name"]), FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z'-.\s ]+$/")))){
        $lastName_err = 'vul een geldige naam in a.u.b.';
    } else{
        $lastName = $input_lastName;
    }

    $input_adrs = trim($_POST["address"]);
    if(empty($input_adrs)){
        $adrs_err = 'Vul een adres in a.u.b.';
    } else{
        $adrs = $input_adrs;
    }

    $input_cty = trim($_POST["city"]);
    if(empty($input_cty)){
        $cty_err = 'Vul een adres in a.u.b.';
    } else{
        $cty = $input_cty;
    }

    $input_postalCode = trim($_POST["postal_code"]);
    if(empty($input_postalCode)){
        $postalCode_err = 'Vul een adres in a.u.b.';
    } else{
        $postalCode = $input_postalCode;
    }

    $input_phoneNumber = trim($_POST["phone_number"]);
    if(empty($input_phoneNumber)){
        $phoneNumber_err = "Vul een telefoonnummer in a.u.b.";
        //only numbers
    } elseif(!ctype_digit($input_phoneNumber)){
        $phoneNumber_err = 'Vul een geldige telefoonnummer in a.u.b. Gebruik alleen getallen en geen spaties.';
    } else{
        $phoneNumber = $input_phoneNumber;
    }

    // Check input errors before inserting in database
    if(empty($firstName_err) && empty($adrs_err) && empty($phoneNumber_err) && empty($lastName_err) && empty($cty_err) && empty($postalCode_err)){
        // Prepare an update statement
        $sqlUpdate = "UPDATE students SET first_name = ?, last_name = ?, address = ?, postal_code = ?, city = ?, phone_number = ? WHERE id=?";

        if($stmt = $db->prepare($sqlUpdate)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("ssssssi", $param_firstName, $param_lastName, $param_adrs, $param_postalCode, $param_cty, $param_phoneNumber, $param_id);

            // Set parameters
            $param_firstName = $firstName;
            $param_lastName = $lastName;
            $param_adrs = $adrs;
            $param_postalCode = $postalCode;
            $param_cty = $cty;
            $param_phoneNumber = $phoneNumber;
            $param_id = $id;

            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Records updated successfully. Redirect to admin index page
                header("location: ../admin.index.php");
                exit();
            } else{
                echo "Oeps, er is iets mis gegaam.";
            }
        }

        // Close statement
        $stmt->close();
    }

    // Close connection
    $db->close();
} else{
    // Check existence of id parameter before processing further
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
        // Get URL parameter
        $id =  trim($_GET["id"]);

        // Prepare a select statement
        $sql = "SELECT * FROM students WHERE id = ?";
        if($stmt = $db->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("i", $param_id);

            // Set parameters
            $param_id = $id;

            // Attempt to execute the prepared statement
            if($stmt->execute()){
                $result = $stmt->get_result();

                if($result->num_rows == 1){
                    /* Fetch result row as an associative array. Since the result set contains only one row, we don't need to use while loop */
                    $row = $result->fetch_array(MYSQLI_ASSOC);

                    // Retrieve individual field value
                    $firstName = $row['first_name'];
                    $lastName = $row['last_name'];
                    $adrs = $row['address'];
                    $cty = $row['city'];
                    $postalCode= $row['postal_code'];
                    $phoneNumber = $row['phone_number'];
                } else{
                    // URL doesn't contain valid id. Redirect to error page
                    header("location: error.php");
                    exit();
                }

            } else{
                echo "Oeps er is iets mis gegaam.";
            }
        }

        // Close statement
        $stmt->close();

        // Close connection
        $db->close();
    }  else{
        // URL doesn't contain id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<!-- Header -->
<?php
require_once("includes/include.php");
include ROOT_PATH . '/php/includes/header.php';
?>
<body>
<div class="wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="page-header">
                    <h2>Gegevens aanpassen</h2>
                </div>
                <p>Geef hier je wijzigingen door.</p>
                <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                    <div class="form-group <?php echo (!empty($firstName_err)) ? 'has-error' : ''; ?>">
                        <label>Voornaam</label>
                        <input type="text" name="first_name" class="form-control" value="<?php echo $firstName; ?>">
                        <span class="help-block"><?php echo $firstName_err;?></span>
                    </div>
                    <div class="form-group <?php echo (!empty($lastName_err)) ? 'has-error' : ''; ?>">
                        <label>Achternaam</label>
                        <input type="text" name="last_name" class="form-control" value="<?php echo $lastName; ?>">
                        <span class="help-block"><?php echo $lastName_err;?></span>
                    </div>
                    <div class="form-group <?php echo (!empty($adrs_err)) ? 'has-error' : ''; ?>">
                        <label>Adres</label>
                        <textarea name="address" class="form-control"><?php echo $adrs; ?></textarea>
                        <span class="help-block"><?php echo $adrs_err;?></span>
                    </div>
                    <div class="form-group <?php echo (!empty($cty_err)) ? 'has-error' : ''; ?>">
                        <label>Stad</label>
                        <textarea name="city" class="form-control"><?php echo $cty; ?></textarea>
                        <span class="help-block"><?php echo $cty_err;?></span>
                    </div>
                    <div class="form-group <?php echo (!empty($postalCode_err)) ? 'has-error' : ''; ?>">
                        <label>Postcode</label>
                        <textarea name="postal_code" class="form-control"><?php echo $postalCode; ?></textarea>
                        <span class="help-block"><?php echo $postalCode_err;?></span>
                    </div>
                    <div class="form-group <?php echo (!empty($phoneNumber_err)) ? 'has-error' : ''; ?>">
                        <label>Telefoonnummer</label>
                        <input type="text" name="phone_number" class="form-control" value="<?php echo $phoneNumber; ?>">
                        <span class="help-block"><?php echo $phoneNumber_err;?></span>
                    </div>
                    <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                    <input type="submit" class="btn btn-primary" value="Submit">
                    <a href="<?php echo BASE_URL; ?>admin.welcome.php" class="btn btn-default">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>