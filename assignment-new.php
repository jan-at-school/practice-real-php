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
   $tags_array= false;




  if (isset($_GET['title']) && isset($_GET['description']) )  {
  	$title = mysqli_real_escape_string($mysqli, $_GET['title']);

  	$description = mysqli_real_escape_string($mysqli, $_GET['description']);
    if(isset($_GET['tags'])){

    	$tags =	mysqli_real_escape_string($mysqli, $_GET['tags']);
    }

    if(isset($_GET['externalAttachment'])){

    	$externalAttachment =	mysqli_real_escape_string($mysqli, $_GET['externalAttachment']);
    }





  }
  else if(file_get_contents("php://input")!=null){
    $postdata = file_get_contents("php://input");
    $request = json_decode($postdata);

    if(isset($request->title) &&  isset($request->description)){

      $title =  mysqli_real_escape_string($mysqli, $request->title);
      $description =  mysqli_real_escape_string($mysqli, $request->description);
      if(isset($request->tags)){
         $tags = $request->tags;
         $tags_array= true;
      }
      if(isset($request->externalAttachment)){
        $externalAttachment =  mysqli_real_escape_string($mysqli, $request->externalAttachment);
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
      $descriptionUTF8 = utf8_encode($description);




      $user = mysqli_query($mysqli,"SELECT * from users where id=$userId");
      $user = mysqli_fetch_assoc($user);
      $username = $user['username'];
      $dpImgUrl = $user['dpImgUrl'];


      if($tags_array){
        $tags = implode(",",$tags);
        $tags =  mysqli_real_escape_string($mysqli, $tags);


      }

      $sql = "INSERT INTO assignments(title,tags,description,uploadedBy,username,dpImgUrl,time)
                                          VALUES('$title','$tags','$descriptionUTF8','$userId','$username','$dpImgUrl','$current_time')";


      $result = mysqli_query($mysqli, $sql);

      //todo... chekc i ftags are present

      if($result){
        $assginmentId = mysqli_query($mysqli,"SELECT LAST_INSERT_ID() as 'assignmentId';");
        $assignmentId = mysqli_fetch_assoc($assginmentId);
        $assignmentId = $assignmentId['assignmentId'];

        if($tags!=null){
          // if(!$tags_array){
            $tags = explode(",",$tags);
          // }
          $sql = "INSERT into tags (tag,assignmentId) VALUES";
          foreach ($tags as $tag) {
            $sql .= "('$tag',$assignmentId),";
          }
          $sql=rtrim($sql,", ");
          $result  = mysqli_query($mysqli,$sql);
          if($result){
            echo file_get_contents("http://localhost/pr/assignment.php?id=$assignmentId");
          }
          else{
            echoMessageWithStatus(0, "Failed to save!");

          }
        }
        else{
          echo file_get_contents("http://localhost/pr/assignment.php?id=$assignmentId");
        }


      }
      else{
        echoMessageWithStatus(0, "Counldn't upload!");
      }

  }


?>
