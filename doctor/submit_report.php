<?php
// Include your database connection file
include("../connection.php");

// Check if appoid is set in the POST request
if(isset($_POST['appoid'])) {
    $appoid = $_POST['appoid'];
    $diagnosis = $_POST["diagnosis"];
    $prescription = $_POST["prescription"];

    // Fetch PID and patient name (pname) based on appoid from the appointment table
    $query = "SELECT a.pid, p.pname FROM appointment a
              JOIN patient p ON a.pid = p.pid
              WHERE a.appoid = ?";
    $stmt = $database->prepare($query);
    $stmt->bind_param("i", $appoid);
    $stmt->execute();
    $stmt->bind_result($pid, $pname);
    $stmt->fetch();
    $stmt->close();

    if ($pid !== null) { 
        // Insert the report into the reports table
        $query = "INSERT INTO reports (pid, pname, diagnosis, prescription) VALUES (?, ?, ?, ?)";
        $stmt = $database->prepare($query);
        $stmt->bind_param("isss", $pid, $pname, $diagnosis, $prescription);
        $stmt->execute();
        $stmt->close();

        // Close the database connection
        $database->close();

        // Send a response to the client
        echo "Report submitted successfully for patient: " . $pname;
    } else {
        // Handle the case where no patient is found
        echo "Error: Appointment not found";
    }
} else {
    // If appoid is not set, return an error message
    echo "Error: Appoid not provided";
}
?>
