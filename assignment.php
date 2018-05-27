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

  if($assignment['dpImgUrl']==null || $assignment['dpImgUrl'] =="" || $assignment['dpImgUrl'] =="user_placeholder.jpg"){
    $assignment['dpImgUrl']='user_placeholder.jpg';
  }
  else{
    $assignment['dpImgUrl']= 'profile-images/'.$assignment['dpImgUrl'];
  }
  $assignment = (object) $assignment;
  $assignment-> attachments = $attatchments;




  return $assignment;
}


// check for post data
if (isset($_GET["id"])) {
    $id = mysqli_real_escape_string($mysqli, $_GET['id']);
    $assignmentsResult = mysqli_query($mysqli,"SELECT * from assignments where id = $id");
    $assignment = mysqli_fetch_assoc($assignmentsResult);

    $assignment=prepare_assignment($assignment);


    echo json_encode($assignment,JSON_UNESCAPED_SLASHES);
    $servedRequest =true;
}





if (isset($_GET["uploadedBy"])) {
    if(isset($_GET["offset"])){
      $offset = mysqli_real_escape_string($mysqli, $_GET['offset']);
    }
    else{
      $offset = 0;
    }
    if(isset($_GET["limit"])){
      $limit = mysqli_real_escape_string($mysqli, $_GET['limit']);
    }
    else{
      $limit = 20;
    }
    $uploadedBy = mysqli_real_escape_string($mysqli, $_GET['uploadedBy']);

    $assignmentsResult = mysqli_query($mysqli,"SELECT *, SUBSTRING(description, 1,300) as 'description'  from assignments where uploadedBy=$uploadedBy order by time DESC limit $offset,$limit");
    $assignments =array();
    while($assignment = mysqli_fetch_assoc($assignmentsResult)){

      $assignment=prepare_assignment($assignment);
      array_push($assignments,$assignment);
    }

    echo json_encode($assignments,JSON_UNESCAPED_SLASHES);
    $servedRequest =true;
}


if (isset($_GET["tag"])) {
    if(isset($_GET["offset"])){
      $offset = mysqli_real_escape_string($mysqli, $_GET['offset']);
    }
    else{
      $offset = 0;
    }
    if(isset($_GET["limit"])){
      $limit = mysqli_real_escape_string($mysqli, $_GET['limit']);
    }
    else{
      $limit = 20;
    }
    $tag = mysqli_real_escape_string($mysqli, $_GET['tag']);
    $assignmentsResult = mysqli_query($mysqli,"SELECT assignments.*, SUBSTRING(description, 1,300) as 'description'  from assignments,tags where tags.tag= '$tag' && assignments.id=tags.assignmentId order by time DESC limit $offset,$limit");
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
    $assignmentsResult = mysqli_query($mysqli,"SELECT *,  SUBSTRING(description, 1,300) as 'description'  from assignments order by time DESC limit 20");
  $assignments =array();
  while($assignment = mysqli_fetch_assoc($assignmentsResult)){

    $assignment=prepare_assignment($assignment);
    array_push($assignments,$assignment);
  }

  echo json_encode($assignments,JSON_UNESCAPED_SLASHES);
}



?>
