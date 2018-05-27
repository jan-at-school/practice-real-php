<?php
// header("Access-Control-Allow-Origin: *");
// header("Access-Control-Allow-Methods : GET,POST,PUT,DELETE,OPTIONS");
// header("Access-Control-Allow-Headers : Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
// array for JSON response
$response = array();

include_once(".\config\dbconnect.php");

include_once(".\global\utility.php");

cors();





$constants = mysqli_query($mysqli,"SELECT * FROM globals");
$constants = mysqli_fetch_array($constants);


 $going_good = true;




// check for post data
if (isset($_GET["count"])) {
    $count = mysqli_real_escape_string($mysqli, $_GET['count']);

    $count = mysqli_query($mysqli,"SELECT COUNT(*) as 'count' from $count;");
    $count = mysqli_fetch_assoc($count);
    $count = $count['count'];

    echo $count;

} else {
  $going_good = false;
  echoMessageWithStatus(0, "Invalid parameters!");
}


?>
