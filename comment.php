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


// check for post data
if (isset($_GET["id"])) {
    $id = mysqli_real_escape_string($mysqli, $_GET['id']);
    $commentResult = mysqli_query($mysqli,"SELECT * from comments where id = $id");
    $comment = mysqli_fetch_assoc($commentResult);
    if($comment['dpImgUrl']==null || $comment['dpImgUrl'] =="" || $comment['dpImgUrl'] =="user_placeholder.jpg"){
      $comment['dpImgUrl']='user_placeholder.jpg';
    }
    else{
      $comment['dpImgUrl']= 'profile-images/'.$comment['dpImgUrl'];
    }
    echo json_encode($comment,JSON_UNESCAPED_SLASHES);
    $servedRequest =true;
}





if (isset($_GET["assignmentId"])) {
    $assignmentId = mysqli_real_escape_string($mysqli, $_GET['assignmentId']);
    $commentsResult = mysqli_query($mysqli,"SELECT * from comments where assignmentId = $assignmentId");
    $comments =array();
    while($comment = mysqli_fetch_assoc($commentsResult)){

      if($comment['dpImgUrl']==null || $comment['dpImgUrl'] =="" || $comment['dpImgUrl'] =="user_placeholder.jpg"){
        $comment['dpImgUrl']='user_placeholder.jpg';
      }
      else{
        $comment['dpImgUrl']= 'profile-images/'.$comment['dpImgUrl'];
      }
      // $assignment=prepare_assignment($assignment);
      array_push($comments,$comment);
    }

    echo json_encode($comments,JSON_UNESCAPED_SLASHES);
    $servedRequest = true;
}



if(!$servedRequest){
  $postdata = file_get_contents("php://input");
  $request = json_decode($postdata);

  if(isset($request->text) && isset($request->assignmentId)){
    $userId =  $request->userId;
    $text = $request->text;
    $assignmentId = $request->assignmentId;


    $user = mysqli_query($mysqli,"SELECT * from users where id=$userId");
    $user = mysqli_fetch_assoc($user);

    $username = $user['username'];
    $dpImgUrl = $user['dpImgUrl'];


    $result = mysqli_query($mysqli, "INSERT INTO comments(userId,assignmentId,username,text,dpImgUrl) VALUES('$userId','$assignmentId','$username','$text','$dpImgUrl')");

    if($result){
      echoMessageWithStatus(1, "Done!");
    }
    else{
      echoMessageWithStatus(0, "Couldn't post comment!");
    }



  }
  else{
    $going_good=false;
    echoMessageWithStatus(0, "Invalid parameters!");
  }

}

if(!$servedRequest){

  $going_good=false;
  echoMessageWithStatus(0, "Invalid parameters!");

}





?>
