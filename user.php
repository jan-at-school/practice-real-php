<?php
header("Access-Control-Allow-Origin: *");
// array for JSON response
$response = array();

include_once(".\config\dbconnect.php");




function echoMessageWithStatus($status, $message)
{

    $response["status"]  = $status;
    $response["message"] = $message;
    echo json_encode($response);
}



$constants = mysqli_query($mysqli,"SELECT * FROM globals");
$constants = mysqli_fetch_array($constants);


 $going_good = true;

// check for post data
if (isset($_GET["id"])) {
    $id = mysqli_real_escape_string($mysqli, $_GET['id']);
    $user = mysqli_query($mysqli,"SELECT * from users where id=$id");
    $user = mysqli_fetch_assoc($user);





    echo json_encode($user);

} else {
  $going_good = false;
  echoMessageWithStatus(0, "Invalid parameters!");
}


?>
