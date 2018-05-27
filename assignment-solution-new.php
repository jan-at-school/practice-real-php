<?php
  include_once("authenticate.php");

  include_once(".\global\utility.php");
  cors();

  include_once(".\config\dbconnect.php");


$tags=null;
$externalAttachment = "";





  $constants = mysqli_query($mysqli,"SELECT * FROM globals");
  $constants = mysqli_fetch_array($constants);


   $going_good = true;




  if (isset($_GET['text']) &&isset($_GET['assignmentId']))  {
  	$text = mysqli_real_escape_string($mysqli, $_GET['text']);
	  $assignmentId = mysqli_real_escape_string($mysqli, $_GET['assignmentId']);
    if(isset($_GET['externalAttachment'])){
    	$externalAttachment =	mysqli_real_escape_string($mysqli, $_GET['externalAttachment']);
    }





  }
  else if(file_get_contents("php://input")!=null){
    $postdata = file_get_contents("php://input");
    $request = json_decode($postdata);

    if(isset($request->text) && isset($request->assignmentId)){

      $text =  mysqli_real_escape_string($mysqli, $request->text);
      $assignmentId =  mysqli_real_escape_string($mysqli, $request->assignmentId);

      if(isset($request->externalAttachment)){
         $externalAttachment =  mysqli_real_escape_string($mysqli, $request->externalAttachment);
         $tags_array= true;
      }

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



      $result = mysqli_query($mysqli, "INSERT INTO assignmentSolutions(text,userId,assignmentId,username,dpImgUrl,externalAttachment,time) VALUES('$text',$userId,$assignmentId,'$username','$dpImgUrl','$externalAttachment','$current_time')");

      //todo... chekc i ftags are present

      if($result){
        $assginmentSolId = mysqli_query($mysqli,"SELECT LAST_INSERT_ID() as 'assginmentSolId';");
        $assginmentSolId = mysqli_fetch_assoc($assginmentSolId);
        $assginmentSolId = $assginmentSolId['assginmentSolId'];


        echo file_get_contents("http://localhost/pr/assignment-solution.php?id=$assginmentSolId");


      }
      else{
        echoMessageWithStatus(0, "Counldn't save the solution");
      }

  }


?>
