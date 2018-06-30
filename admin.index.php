<?php
// Start session inlog
session_start();

// Holds the username
$fName = htmlspecialchars($_SESSION['username']);

// If someone, that's not the admin, try's to log in, send to error
if($fName != 'Admin'){ //TODO while loop voor more admins
header("location: php/error.php");
}

?>

<!DOCTYPE html>
<html lang="en">
<!-- Header -->
<?php
require_once("php/includes/include.php");
include ROOT_PATH . '/php/includes/header.php';
?>

<body>
<!--navbar-->
<?php include 'php/includes/admin.navbar.php';?>

<div class="container">
    <div style="margin-top:50px;" class="mainbox col-md-auto">
        <div class="panel" >
            <div class="panel-heading">
                <div class="panel-title">Leerlingen</div>
            </div>
            <div style="padding-top:30px" class="panel-body" >
                <?php
                // Include config file
                require_once 'php/config.php';

                // Attempt select query execution
                $sqlSelect = "SELECT * FROM students";
                if($result = $db->query($sqlSelect)){
                    if($result->num_rows > 0){
                        echo "<table class='table table-bordered table-striped'>";
                        echo "<thead>";
                        echo "<tr>";
                        echo "<th>Gebruikersnaam</th>";
                        echo "<th>Naam</th>";
                        echo "<th>Adres</th>";
                        echo "<th>Telefoonnummer</th>";
                        echo "<th>Actie</th>";
                        echo "</tr>";
                        echo "</thead>";
                        echo "<tbody>";
                        while($row = $result->fetch_array()){
                            echo "<tr>";
                            echo "<td>" . $row['username'] . "</td>";
                            echo "<td>" . $row['first_name'] .' '. $row['last_name']. "</td>";
                            echo "<td>" . $row['address'] .' '. $row['city'].' '. $row['postal_code']. "</td>";
                            echo "<td>" . $row['phone_number'] . "</td>";
                            echo "<td>";
                                echo "<a href='php/delete.php?id=". $row['id'] ."' data-toggle='tooltip'>Verwijderen</a>";
                                echo "<a href='php/update.php?id=". $row['id'] ."' data-toggle='tooltip' style='float: right'>Aanpassen</a>";
                            echo "</td>";
                            echo "</tr>";
                        }
                        echo "</tbody>";
                        echo "</table>";
                        // Free result set
                        $result->free();
                    } else{
                        echo "<p class='lead'><em>Geen leerlingen gevonden</em></p>";
                    }
                } else{
                    echo "ERROR: Could not able to execute $sqlSelect. " . $db->error;
                }

                // Close connection
                $db->close();
                ?>
            </div>
        </div>
    </div>
</div>
<!-- Footer and scripts -->
<?php include 'php/includes/footer.php';?>
</body>
</html>