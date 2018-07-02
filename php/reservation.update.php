<?php

require_once 'config.php';

$username = "";
$username_err = "";

// Processing form data when form is submitted
if(isset($_POST["id"]) && !empty($_POST["id"])){
    $id = $_POST["id"];

    // Validate name
    $input_username = trim($_POST["first_name"]);
    if(empty($input_username)){
        $name_err = "Vul een naam in a.u.b.";
        //safety, so limit chars
    } elseif(!filter_var(trim($_POST["first_name"]), FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z'-.\s ]+$/")))){
        $name_err = 'vul een geldige naam in a.u.b.';
    } else{
        $username = $input_username;
    }

    // Check input errors before inserting in database
    if(empty($username_err)){
        // Prepare an update statement
        $sqlUpdate = "UPDATE reservations SET name=? WHERE id=?";

        if($stmt = $db->prepare($sqlUpdate)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("si", $param_name, $param_id);

            // Set parameters
            $param_name = $username;
            $param_id = $id;

            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Records updated successfully. Redirect to landing page
                header("location: ../reservation.php");
                exit();
            } else{
                echo "Er is iets mis gegaan. Probeer het straks weer.";
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
                    /* Fetch result row as an associative array.
                    Only one row, so no need to use while loop */
                    $row = $result->fetch_array(MYSQLI_ASSOC);

                    // Retrieve individual field value
                        $username = $row['username'];

                } else{
                    // URL doesn't contain valid id. Redirect to error page
                    header("location: error.php");
                    exit();
                }

            } else{
                echo "Er is iets mis gegaan. Probeer het straks weer.";
            }
        }

        // Close statement
        $stmt->close();

        // Close connection
        $db->close();
    }  else{
        // URL doesn't contain id parameter. Redirect to error page
        header("location: ../error.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Record</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        .wrapper{
            width: 500px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="page-header">
                    <h2>Update Record</h2>
                </div>
                <p>Voeg je naam toe om een les te reserveren</p>
                <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                    <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                        <label>Naam</label>
                        <input type="text" name="first_name" class="form-control" value="<?php echo $username; ?>">
                        <span class="help-block"><?php echo $username_err;?></span>
                    </div>
                    <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                    <input type="submit" class="btn btn-primary" value="Submit">
                    <a href="../students.welcome.php" class="btn btn-default">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>