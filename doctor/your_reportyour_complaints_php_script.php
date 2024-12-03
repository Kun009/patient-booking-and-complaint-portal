<?php
include("../connection.php");

// Check the connection
if ($database->connect_error) {
    die("Connection failed: " . $database->connect_error);
}

// Get pname and complaints from POST data
$pname = $_POST["pname"];
$complaints = $_POST["complaints"];

// Find PID by pname in the patient table
$query = "SELECT pid FROM patient WHERE pname = ?";
$stmt = $database->prepare($query);

// Check if the query preparation fails
if (!$stmt) {
    die("Error preparing statement: " . $database->error);
}

$stmt->bind_param("s", $pname);
$stmt->execute();
$stmt->bind_result($pid);
$stmt->fetch();

// Close the statement
$stmt->close();

// Prepare and execute an SQL query to insert the complaints into the complaints table
$query = "INSERT INTO complaints (pid, complaints) VALUES (?, ?)";
$stmt = $database->prepare($query);

// Check if the query preparation fails
if (!$stmt) {
    die("Error preparing statement: " . $database->error);
}

$stmt->bind_param("is", $pid, $complaints);
$stmt->execute();

// Close the statement
$stmt->close();

// Close the database connection
$database->close();

// Send a response to the client
echo "Complaints recorded successfully for patient: " . $pname;
?>
