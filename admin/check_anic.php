<?php

// Import database
include("../connection.php");

// Check if NIC number exists in the database
$nic = $_GET['nic'];
$result = $database->query("SELECT * FROM admin WHERE anic = '$nic'");

echo json_encode(['exists' => $result->num_rows > 0]);
