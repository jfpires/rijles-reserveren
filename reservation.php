<?php
require_once 'php/config.php';

?>

<!DOCTYPE html>
<html lang="en">
<!--navbar-->
<?php
require_once("php/includes/include.php");
include ROOT_PATH . '/php/includes/header.php';
?>

<body>

<!--navbar-->
<?php include 'php/includes/navbar.php';?>

<div class="container">
    <div style="margin-top:50px;" class="mainbox col-md-auto">
        <div class="panel" >
            <div class="panel-heading">
                <div class="panel-title">Reserveren</div>
            </div>
            <div style="padding-top:30px" class="panel-body" >
                <?php
                require_once 'php/config.php';

                // Attempt select query execution
                $sqlSelect = "SELECT * FROM reservations";
                if($result = $db->query($sqlSelect)){
                    if($result->num_rows > 0){
                        echo "<table class='table table-bordered table-striped'>";
                        echo "<thead>";
                        echo "<tr>";
                        echo "<th>Tijd</th>";
                        echo "<th>Naam</th>";
                        echo "<th>Reserveren</th>";
                        echo "</tr>";
                        echo "</thead>";
                        echo "<tbody>";
                        while($row = $result->fetch_array()){
                            echo "<tr>";
                            echo "<td>" . $row['date'] . "</td>";
                            echo "<td>" . $row['name'] . "</td>";
                            echo "<td>";
                            echo "<a href='php/reservation.update.php?id=". $row['id'] ."' data-toggle='tooltip'>Reserveren </a>";
                            echo "</td>";
                            echo "</tr>";
                        }
                        echo "</tbody>";
                        echo "</table>";
                        // Free result set
                        $result->free();
                    } else{
                        echo "<p class='lead'><em>Geen data gevonden</em></p>";
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
<!--Footer and scripts-->
<?php include 'php/includes/footer.php';?>
</body>
</html>