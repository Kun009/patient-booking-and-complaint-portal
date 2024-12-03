<?php
session_start();
include("../connection.php");

// Ensure a patient session is active
if (isset($_SESSION["user"]) && $_SESSION["usertype"] == 'p') {
    $useremail = $_SESSION["user"];
    $userrow = $database->query("SELECT * FROM patient WHERE pemail='$useremail'");
    
    // Check if a patient with the given email exists
    if ($userrow->num_rows > 0) {
        $userfetch = $userrow->fetch_assoc();
        $userid = $userfetch["pid"];
        $username = $userfetch["pname"];

        // Update the SQL query to fetch reports for the logged-in patient
        $sqlmain = "SELECT pname, pid, diagnosis, prescription FROM reports WHERE pid = '$userid' ORDER BY rid DESC";
    } else {
        // Redirect to the login page if the patient doesn't exist
        header("location: ../login.php");
        exit();
    }
} else {
    // Redirect to the login page if there is no patient session or if the user type is not 'p'
    header("location: ../login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/animations.css">  
    <link rel="stylesheet" href="../css/main.css">  
    <link rel="stylesheet" href="../css/admin.css">
        
    <title>Doctors</title>
    <style>
        .popup{
            animation: transitionIn-Y-bottom 0.5s;
        }
        .sub-table{
            animation: transitionIn-Y-bottom 0.5s;
        }
</style>
</head>
<body>
  
    <div class="container">
        <div class="menu">
            <table class="menu-container" border="0">
                <tr>
                    <td style="padding:10px" colspan="2">
                        <table border="0" class="profile-container">
                            <tr>
                                <td width="30%" style="padding-left:20px" >
                                    <img src="../img/user.png" alt="" width="100%" style="border-radius:50%">
                                </td>
                                <td style="padding:0px;margin:0px;">
                                    <p class="profile-title"><?php echo substr($username,0,13)  ?>..</p>
                                    <p class="profile-subtitle"><?php echo substr($useremail,0,22)  ?></p>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                <a href="../logout.php" ><input type="button" value="Log out" class="logout-btn btn-primary-soft btn"></a>
                                </td>
                            </tr>
                    </table>
                    </td>
                
                </tr>
                <tr class="menu-row" >
                    <td class="menu-btn menu-icon-home " >
                        <a href="index.php" class="non-style-link-menu "><div><p class="menu-text">Home</p></a></div></a>
                    </td>
                </tr>
                <tr class="menu-row">
                    <td class="menu-btn menu-icon-doctor ">
                        <a href="doctors.php" class="non-style-link-menu "><div><p class="menu-text">All Doctors</p></a></div>
                    </td>
                </tr>
                
                <tr class="menu-row" >
                    <td class="menu-btn menu-icon-session">
                        <a href="schedule.php" class="non-style-link-menu"><div><p class="menu-text">Scheduled Sessions</p></div></a>
                    </td>
                </tr>
                <tr class="menu-row" >
                    <td class="menu-btn menu-icon-appoinment">
                        <a href="appointment.php" class="non-style-link-menu"><div><p class="menu-text">My Bookings</p></a></div>
                    </td>
                </tr>
                <tr class="menu-row" >
                    <td class="menu-btn menu-icon-doctor menu-active menu-icon-doctor-active">
                        <a href="report.php" class="non-style-link-menu non-style-link-menu-active"><div><p class="menu-text">Medical Report</p></a></div>
                    </td>
                </tr>
                <tr class="menu-row" >
                    <td class="menu-btn menu-icon-settings">
                        <a href="settings.php" class="non-style-link-menu"><div><p class="menu-text">Settings</p></a></div>
                    </td>
                </tr>
                
            </table>
        </div>
        <div class="dash-body">
            <table border="0" width="100%" style=" border-spacing: 0;margin:0;padding:0;margin-top:25px; ">
                <tr >
                    <td width="13%">
                        <a href="doctors.php" ><button  class="login-btn btn-primary-soft btn btn-icon-back"  style="padding-top:11px;padding-bottom:11px;margin-left:20px;width:125px"><font class="tn-in-text">Back</font></button></a>
                    </td>
                    <td>
                        
                        <form action="" method="post" class="header-search">

                            <input type="search" name="search" class="input-text header-searchbar" placeholder="Search Report or Diagnosis" list="doctors">&nbsp;&nbsp;
                            
                            <?php
                                echo '<datalist id="reports">';
                                $list11 = $database->query("select  pname, pid from  reports;");

                                for ($y=0;$y<$list11->num_rows;$y++){
                                    $row00=$list11->fetch_assoc();
                                    $d=$row00["pname"];
                                    $c=$row00["pid"];
                                    echo "<option value='$d'><br/>";
                                    echo "<option value='$c'><br/>";
                                };

                            echo ' </datalist>';
?>
                            
                       
                            <input type="Submit" value="Search" class="login-btn btn-primary btn" style="padding-left: 25px;padding-right: 25px;padding-top: 10px;padding-bottom: 10px;">
                        
                        </form>
                        
                    </td>
                    <td width="15%">
                        <p style="font-size: 14px;color: rgb(119, 119, 119);padding: 0;margin: 0;text-align: right;">
                            Today's Date
                        </p>
                        <p class="heading-sub12" style="padding: 0;margin: 0;">
                            <?php 
                        date_default_timezone_set('Asia/Kolkata');

                        $date = date('Y-m-d');
                        echo $date;
                        ?>
                        </p>
                    </td>
                    <td width="10%">
                        <button  class="btn-label"  style="display: flex;justify-content: center;align-items: center;"><img src="../img/calendar.svg" width="100%"></button>
                    </td>


                </tr>
               
                
                <tr>
                    <td colspan="4" style="padding-top:10px;">
                        <p class="heading-main12" style="margin-left: 45px;font-size:18px;color:rgb(49, 49, 49)">All Reports(<?php echo $list11->num_rows; ?>)</p>
                    </td>
                    
                </tr>
                <?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the search keyword
    $keyword = $_POST["search"];

    // Check if the keyword is numeric (indicating a patient ID)
    if (is_numeric($keyword)) {
        // If numeric, search by patient ID
        $sqlmain = "SELECT pname, pid, diagnosis, prescription FROM reports WHERE pid = '$keyword' ORDER BY rid DESC";
    } else {
        // If not numeric, search by patient name
        $sqlmain = "SELECT pname, pid, diagnosis, prescription FROM reports WHERE pname LIKE '%$keyword%' ORDER BY rid DESC";
    }
} else {
    // Default SQL query to fetch all reports
    $sqlmain = "SELECT pname, pid, diagnosis, prescription FROM reports WHERE pid = '$userid' ORDER BY rid DESC";
}
?>

                  
                <tr>
                   <td colspan="4">
                       <center>
                       <div class="abc scroll">
                    <table width="93%" class="sub-table scrolldown" border="0">
                        <thead>
                            <tr>
                                <th class="table-headin">Patient Name</th>
                                <th class="table-headin">Patient ID</th>
                                <th class="table-headin">Diagnosis</th>
                                <th class="table-headin">Prescription</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $result = $database->query($sqlmain);

                            if ($result->num_rows == 0) {
                                // Display a message if no reports are found for the logged-in patient
                                echo '<tr>
                                    <td colspan="4">
                                        <br><br><br><br>
                                        <center>
                                            <img src="../img/notfound.svg" width="25%">
                                            <br>
                                            <p class="heading-main12" style="margin-left: 45px; font-size: 20px; color: rgb(49, 49, 49)">No medical reports available for this patient.</p>
                                        </center>
                                        <br><br><br><br>
                                    </td>
                                </tr>';
                            } else {
                                // Display the medical reports for the logged-in patient
                                while ($row = $result->fetch_assoc()) {
                                    echo '<tr>
                                    
                                    <td>' . (isset($row['pname']) ? substr($row['pname'], 0, 30) : '') . '</td>
                                    <td>' . (isset($row['pid']) ? substr($row['pid'], 0, 100) : '') . '</td>
                                    <td>' . (isset($row['diagnosis']) ? substr($row['diagnosis'], 0, 100) : '') . '</td>
                                    <td>' . (isset($row['prescription']) ? substr($row['prescription'], 0, 120) : '') . '</td>
                                
                                    </tr>';
                                
                                    
                                    
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </center>
        </td>
    </tr>
                       
                        
                        
            </table>
        </div>
    </div>
   
</div>

</body>
</html>