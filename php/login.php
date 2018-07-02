<?php
// Include config file
require_once 'config.php';


// Define variables and initialize with empty values
$username = $password = ""; //for the correct data
$username_err = $password_err = ""; // for error messages

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Check if username is empty
    if(empty(trim($_POST["username"]))){
        $username_err = 'Vul een gebruikersnaam in.';
    } else{
        $username = trim($_POST["username"]);
    }

    // Check if password is empty
    if(empty(trim($_POST['password']))){
        $password_err = 'Geef een wachtwoord.';
    } else{
        $password = trim($_POST['password']);
    }

    // Validate credentials
    if(empty($username_err) && empty($password_err)){
        // Prepare a select statement
        $sql = "SELECT username, password FROM students WHERE username = ?";

        if($stmt = $db->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("s", $param_username);

            // Set parameters
            $param_username = $username;

            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Store result
                $stmt->store_result();

                // Check if username exists, if yes then verify password
                if($stmt->num_rows == 1){
                    // Bind result variables
                    $stmt->bind_result($username, $hashed_password);
                    if($stmt->fetch()){
                        if(password_verify($password, $hashed_password)){
                            /* Password is correct, so start a new session and
                            save the username to the session */
                            session_start();
                            $_SESSION['username'] = $username;
                            header("location: students.welcome.php"); // send to welcome page
                        } else{
                            // Display an error message if password is not valid
                            $password_err = ' Wachtwoord is niet correct';
                        }
                    }
                } else{
                    // Display an error message if username doesn't exist
                    $username_err = 'Gebruikersnaam bestaat niet.';
                }
            } else{
                // Send error message
                echo "Er is iets mis gegaan";
            }
        }

        // Close statement
        $stmt->close();
    }

    // Close connection
    $db->close();
}


