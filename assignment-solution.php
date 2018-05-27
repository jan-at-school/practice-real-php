<?php
// array for JSON response
$response = array();


include_once(".\config\dbconnect.php");

include_once(".\global\utility.php");
cors();
$servedRequest =false;


$constants = mysqli_query($mysqli, "SELECT * FROM globals");
$constants = mysqli_fetch_array($constants);


 $going_good = true;

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

// check for post data
if (isset($_GET["id"])) {
    $id = mysqli_real_escape_string($mysqli, $_GET['id']);
    $assignmentsResult = mysqli_query($mysqli,"SELECT * from assignmentSolutions where id = $id");
    $assignment = mysqli_fetch_assoc($assignmentsResult);


    echo json_encode($assignment,JSON_UNESCAPED_SLASHES);
    $servedRequest =true;
}

if (isset($_GET["userId"])) {

    $userId = mysqli_real_escape_string($mysqli, $_GET['userId']);

    $sql = "SELECT *, SUBSTRING(text, 1,300) as 'text'  from assignmentSolutions where userId=$userId order by time DESC limit $offset,$limit";

    $assignmentsResult = mysqli_query($mysqli,$sql);
    $assignments =array();
    while($assignment = mysqli_fetch_assoc($assignmentsResult)){

      array_push($assignments,$assignment);
    }

    echo json_encode($assignments,JSON_UNESCAPED_SLASHES);
    $servedRequest =true;
}

if (isset($_GET["assignmentId"])) {

    $assignmentId = mysqli_real_escape_string($mysqli, $_GET['assignmentId']);
    $sql = "SELECT *, SUBSTRING(text, 1,300) as 'text'  from assignmentSolutions where assignmentId=$assignmentId order by time DESC limit $offset,$limit";
    $assignmentsResult = mysqli_query($mysqli,$sql);
    $assignments =array();
    while($assignment = mysqli_fetch_assoc($assignmentsResult)){

      array_push($assignments,$assignment);
    }

    echo json_encode($assignments,JSON_UNESCAPED_SLASHES);
    $servedRequest =true;
}



if (!$servedRequest) {
    echoMessageWithStatus(0, "Invalid parameters!");
}

?>
