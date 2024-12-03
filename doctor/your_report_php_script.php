<?php
include("../connection.php");

// Check the connection
if ($database->connect_error) {
    die("Connection failed: " . $database->connect_error);
}

// Get pname, diagnosis, and prescription from POST data
$pname = $_POST["pname"];
$diagnosis = $_POST["diagnosis"];
$prescription = $_POST["prescription"];

// Find PID by pname in the patient table
$query = "SELECT pid FROM patient WHERE pname = ?";
$stmt = $database->prepare($query);
$stmt->bind_param("s", $pname);
$stmt->execute();
$stmt->bind_result($pid);
$stmt->fetch();

// Close the statement
$stmt->close();

// Prepare and execute an SQL query to insert the diagnosis and prescription into the reports table
$query = "INSERT INTO reports (pname, pid, diagnosis, prescription) VALUES (?, ?, ?, ?)";
$stmt = $database->prepare($query);
$stmt->bind_param("siss", $pname, $pid, $diagnosis, $prescription);
$stmt->execute();

// Close the statement
$stmt->close();

// Close the database connection
$database->close();

// Send a response to the client
echo "Diagnosis and prescription recorded successfully for patient: " . $pname;
?>