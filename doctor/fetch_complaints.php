<?php
// Include your database connection file
include("../connection.php");

// Check if appoid is set in the GET request
if(isset($_GET['appoid'])) {
  $appoid = $_GET['appoid'];

  // Fetch pname and complaints for the specified appoid
  $query = "SELECT p.pname, a.complaints FROM appointment a
            JOIN patient p ON a.pid = p.pid
            WHERE a.appoid = '$appoid'";

  $result = $database->query($query);

  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $pname = $row["pname"];
    $complaints = $row["complaints"];

    // Echo out pname and complaints as JSON
    echo json_encode(array('pname' => $pname, 'complaints' => $complaints));
  } else {
    // If no data found, echo an empty JSON object
    echo json_encode(array());
  }
} else {
  // If appoid is not set, return an empty JSON object
  echo json_encode(array());
}

// Close database connection
$database->close();
?>
