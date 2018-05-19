<?php
// array for JSON response
$response = array();

include_once(".\config\dbconnect.php");




function echoMessageWithStatus($status, $message)
{

    $response["status"]  = $status;
    $response["message"] = $message;
    echo json_encode($response);
}



$constants = mysqli_query("SELECT * FROM globals);
$constants = mysql_fetch_array($constants);


 $going_good = true;

// check for post data
if (isset($_GET["id"])) {
    $id = mysqli_real_escape_string($mysqli, $_GET['id']);
    $assignment = mysqli_query("SELECT * from assignments,attachments where assignments.id=$id && assignments.attachmentId = attachments.id");
    $user = mysqli_fetch_assoc($user);
    $user
    echo json_encode($user);

} else {
  $going_good = false;
  echoMessageWithStatus(0, "Invalid parameters!");
}


?>
