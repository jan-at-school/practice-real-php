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
    $assignment = mysqli_fetch_assoc($assignment);

    $attatchments = $assignment['attachmentJsonData'];
    // $attatchments = '[ { "url":"sdlkafjadsl", "type":"type" }, { "url":"sdlkafjadsl", "type":"type" }, { "url":"sdlkafjadsl", "type":"type" } ]';
    $attatchments = json_decode($attatchments);

    $assignment['attachmentJsonData'] = "";
    $assignment = (object) $assignment;
    $assignment-> attachments = $attatchments;



    echo json_encode($assignment);

}





if (isset($_GET["uploadedBy"])) {
    $uploadedBy = mysqli_real_escape_string($mysqli, $_GET['uploadedBy']);
    $assignment = mysqli_query($mysqli,"SELECT * from assignmentswhere uploadedBy=$uploadedBy");
    $assignments =array();

    while($assignment = mysqli_fetch_assoc($assignment)){
      a
    }

    echo json_encode($user);

}


?>
