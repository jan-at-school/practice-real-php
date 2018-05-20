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



$constants = mysqli_query($mysqli, "SELECT * FROM globals");
$constants = mysqli_fetch_assoc($constants);


 $going_good = true;

if (!isset($_GET['id']) && !isset($_GET['uploadedBy'])) {
  echoMessageWithStatus(0, "Invalid parameters!");
  die;
}







// check for post data
if (isset($_GET["id"])) {
    $id = mysqli_real_escape_string($mysqli, $_GET['id']);
    $assignment = mysqli_query($mysqli,"SELECT * from assignments where id=$id");
    var_dump($assignment);
    $assignment = mysqli_fetch_assoc($assignment);
    //print_r($assignment);


    $attatchments = $assignment['attachmentJsonData'];
    $attatchments = '[ { "url":"sdlkafjadsl", "type":"type" }, { "url":"sdlkafjadsl", "type":"type" }, { "url":"sdlkafjadsl", "type":"type" } ]';
    echo $attatchments;
    $attatchments = json_decode($attatchments);

    $assignment['attachmentJsonData'] = "";
    $assignment = (object) $assignment;
    $assignment-> attachments = array( $attatchments);




    echo json_encode($assignment);

}





if (isset($_GET["uploadedBy"])) {
    $uploadedBy = mysqli_real_escape_string($mysqli, $_GET['uploadedBy']);
    $assignment = mysqli_query($mysqli,"SELECT * from assignmentswhere uploadedBy=$uploadedBy");
    $assignment = mysqli_fetch_assoc($assignment);
    echo json_encode($user);

}


?>
