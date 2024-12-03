<?php

session_start();

if(isset($_SESSION["user"])){
    if(($_SESSION["user"])=="" or $_SESSION['usertype']!='a'){
        header("location: ../login.php");
    }
} else {
    header("location: ../login.php");
}

if($_POST){
    //import database
    include("../connection.php");
    $title = $_POST["title"];
    $docid = $_POST["docid"];
    $nop = $_POST["nop"];
    $date = $_POST["date"];
    $time = $_POST["time"];

    // Debugging: Print out form values
    // echo "Title: $title, DocID: $docid, NOP: $nop, Date: $date, Time: $time";

    // SQL query
    $sql = "INSERT INTO schedule (docid, title, scheduledate, scheduletime, nop) VALUES ($docid, '$title', '$date', '$time', $nop)";

    // Debugging: Print out SQL query
    // echo "SQL Query: $sql";

    // Execute SQL query
    $result = $database->query($sql);

    if ($result) {
        // Redirect on success
        header("location: schedule.php?action=session-added&title=$title");
    } else {
        // Handle SQL error
        echo "Error: " . $database->error;
    }
}

?>
