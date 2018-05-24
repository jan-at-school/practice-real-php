<?php
  include_once("authenticate.php");

  include_once(".\global\utility.php");
  cors();

  include_once(".\config\dbconnect.php");








  $constants = mysqli_query($mysqli,"SELECT * FROM globals");
  $constants = mysqli_fetch_array($constants);


   $going_good = true;




  if (isset($_GET['title']) && isset($_GET['description']) && isset($_GET['attachments']) && isset($_GET['attachmentsNames']))  {
  	$name = mysqli_real_escape_string($mysqli, $_GET['username']);

  	$email = mysqli_real_escape_string($mysqli, $_GET['email']);
  	$password =	mysqli_real_escape_string($mysqli, $_GET['password']);
  




  }
  else if(file_get_contents("php://input")!=null){
    $postdata = file_get_contents("php://input");
    $request = json_decode($postdata);

    if(isset($request->username) &&  isset($request->email) && isset($request->password)){
      $email =  $request->email;
      $password = $request->password;
      $username = $request->username;
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

      $result = mysqli_query($mysqli, "INSERT INTO users(username,password,email) VALUES('$username','$password','$email')");

      if($result){
        trysigin($email,$password);
      }
      else{
        echoMessageWithStatus(0, "Counldn't sign up!");
      }

  }


?>
