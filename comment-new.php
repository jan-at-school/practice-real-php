<?php
  include_once("authenticate.php");

  include_once(".\global\utility.php");
  cors();

  include_once(".\config\dbconnect.php");


$tags=null;





  $constants = mysqli_query($mysqli,"SELECT * FROM globals");
  $constants = mysqli_fetch_array($constants);


   $going_good = true;
   $tags_array= false;




  if (isset($_GET['text']) && isset($_GET['assignmentId']) )  {
  	$assignmentId = mysqli_real_escape_string($mysqli, $_GET['assignmentId']);
  	$text = mysqli_real_escape_string($mysqli, $_GET['text']);

  }
  else if(file_get_contents("php://input")!=null){
    $postdata = file_get_contents("php://input");
    $request = json_decode($postdata);

    if(isset($request->text) && isset($request->assignmentId)){
      $text =  $request->text;
      $assignmentId = $request->assignmentId;

    }
    else{
      $going_good=false;
      echoMessageWithStatus(0, "Invalid parameters!");
    }

    // var_dump($request);
  }
   else {
    $going_good = false;
    echoMessageWithStatus(0, "Invalid parameters!");
  }


  if($going_good){
      $userId = $authToken->userId;
      $textUTF8 = utf8_encode($text);




      $user = mysqli_query($mysqli,"SELECT * from users where id=$userId");
      $user = mysqli_fetch_assoc($user);
      $username = $user['username'];
      $dpImgUrl = $user['dpImgUrl'];



      $result = mysqli_query($mysqli, "INSERT INTO comments(assignmentId,text,userId,username,dpImgUrl) VALUES('$assignmentId','$textUTF8','$userId','$username','$dpImgUrl')");

      //todo... chekc i ftags are present

      if($result){
        $commentId = mysqli_query($mysqli,"SELECT LAST_INSERT_ID() as 'commentId';");
        $commentId = mysqli_fetch_assoc($commentId);
        $commentId = $commentId['commentId'];
        echo file_get_contents("http://localhost/pr/comment.php?id=$commentId");


      }
      else{
        echoMessageWithStatus(0, "Counldn't comment!");
      }

  }


?>
