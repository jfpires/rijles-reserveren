<?php
// Check existence of id parameter before processing further
if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
    // Include config file
    require_once 'config.php';

    // Prepare a select statement
    $sqlRead = "SELECT * FROM students WHERE id = ?";

    if($stmt = $db->prepare($sqlRead)){
        // Bind variables to the prepared statement as parameters
        $stmt->bind_param("i", $param_id);

        // Set parameters
        $param_id = trim($_GET["id"]);

        // Attempt to execute the prepared statement
        if($stmt->execute()){
            $result = $stmt->get_result();

            if($result->num_rows == 1){
                /* Fetch result row as an associative array. Since the result set contains only one row, we don't need to use while loop */
                $row = $result->fetch_array(MYSQLI_ASSOC);

                // Retrieve individual field value
                $name = $row['first_name'] .' '. $row['last_name'];
                $address = $row['address'] .' '. $row['city'];
                $phoneNumber = $row['phone_number'];
            } else{
                // URL doesn't contain valid id parameter. Redirect to error page
                header("location: error.php");
                exit();
            }

        } else{
            echo "Er is iets mis gegaan. Probeer straks opnieuw.";
        }
    }

    // Close statement
    $stmt->close();

    // Close connection
    $db->close();
} else{
    // URL doesn't contain id parameter. Redirect to error page
    header("location: error.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Record</title>
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
                    <h1>View Record</h1>
                </div>
                <div class="form-group">
                    <label>Naam</label>
                    <p class="form-control-static"><?php echo $row['first_name'] .' '. $row['last_name']; ?></p>
                </div>
                <div class="form-group">
                    <label>Adres</label>
                    <p class="form-control-static"><?php echo $row['address'] .' '. $row['city']; ?></p>
                </div>
                <div class="form-group">
                    <label>Telefoonnummer</label>
                    <p class="form-control-static"><?php echo $row['phone_number']; ?></p>
                </div>
                <p><a href="../admin.index.php" class="btn btn-primary">Back</a></p>
            </div>
        </div>
    </div>
</div>
</body>
</html>