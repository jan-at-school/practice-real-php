<?php
include_once(".\global\utility.php");
cors();
// array for JSON response
$response = array();

include_once(".\config\dbconnect.php");




$constants = mysqli_query($mysqli, "SELECT * FROM globals");
$constants = mysqli_fetch_assoc($constants);


 $going_good = true;


$servedRequest =false;

function prepare_assignment($assignment){
  $attatchments = $assignment['attachmentJsonData'];
  // $attatchments = '[ { "url":"sdlkafjadsl", "type":"type" }, { "url":"sdlkafjadsl", "type":"type" }, { "url":"sdlkafjadsl", "type":"type" } ]';
  $attatchments = json_decode($attatchments,JSON_UNESCAPED_SLASHES);
  $assignment['attachmentJsonData'] = "";
  $assignment = (object) $assignment;
  $assignment-> attachments = $attatchments;
  return $assignment;
}


// check for post data
if (isset($_GET["id"])) {
    $id = mysqli_real_escape_string($mysqli, $_GET['id']);
    $assignment = mysqli_query($mysqli,"SELECT * from assignments where id=$id");
    $assignment = mysqli_fetch_assoc($assignment);

    $assignment=prepare_assignment($assignment);


    echo json_encode($assignment,JSON_UNESCAPED_SLASHES);
    $servedRequest =true;
}





if (isset($_GET["uploadedBy"])) {
    $uploadedBy = mysqli_real_escape_string($mysqli, $_GET['uploadedBy']);
    $assignmentsResult = mysqli_query($mysqli,"SELECT * from assignments where uploadedBy=$uploadedBy");
    $assignments =array();
    while($assignment = mysqli_fetch_assoc($assignmentsResult)){

      $assignment=prepare_assignment($assignment);
      array_push($assignments,$assignment);
    }

    echo json_encode($assignments,JSON_UNESCAPED_SLASHES);
    $servedRequest =true;
}




//return assignments.....
if (!$servedRequest) {
  $assignmentsResult = mysqli_query($mysqli,"SELECT * from assignments");
  $assignments =array();
  while($assignment = mysqli_fetch_assoc($assignmentsResult)){

    $assignment=prepare_assignment($assignment);
    array_push($assignments,$assignment);
  }

  echo json_encode($assignments,JSON_UNESCAPED_SLASHES);
}



?>
