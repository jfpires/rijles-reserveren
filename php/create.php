<?php
/**
 * Created by PhpStorm.
 * User: Jersson
 * Date: 26-6-2018
 * Time: 23:04
 */

require_once 'config.php';

$sqlInsert = "INSERT INTO students (username, first_name, last_name, address, phone_number, postal_code, city)
              VALUES (?,?,?,?,?,?,?)";

if($stmt = $db->prepare($sqlInsert)) {
    // Bind variables to the prepared statement as parameters
    $stmt->bind_param("sssssss", $username, $firstName, $lastName, $adrs, $phoneNumber, $postalCode, $cty);

    // Set parameters
    if (isset($_POST['submit'])) {
        $username = $db->real_escape_string($_POST['username']);
        $firstName = $db->real_escape_string($_POST['first_name']);
        $lastName = $db->real_escape_string($_POST['last_name']);
        $adrs = $db->real_escape_string($_POST['address']);
        $phoneNumber = $db->real_escape_string($_POST['phone_number']);
        $postalCode = $db->real_escape_string($_POST['postal_code']);
        $cty = $db->real_escape_string($_POST['city']);

        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            echo "Records inserted successfully.";
        } else {
            echo "ERROR: Could not execute query: $sqlInsert. " . $db->error;
        }
    }
} else {
    echo "ERROR: Could not prepare query: $sqlInsert. " . $db->error;
}


// Close statement
$stmt->close();

// Close connection
$db->close();