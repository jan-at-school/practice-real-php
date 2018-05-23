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




$constants = mysqli_query($mysqli,"SELECT * FROM globals");
$constants = mysqli_fetch_array($constants);


 $going_good = true;






if (isset($_GET['name']) && isset($_GET['email']) && isset($_GET['password']))  {
	$username = mysqli_real_escape_string($mysqli, $_GET['name']);

	$email = mysqli_real_escape_string($mysqli, $_GET['email']);
	$password =	mysqli_real_escape_string($mysqli, $_GET['password']);



  $result = mysqli_query($mysqli, "INSERT INTO users(username,password,email) VALUES('$name','$password','$email')");




  if($result){
    choMessageWithStatus(1, "successful");
    }

  }
  else {
     $going_good = false;
     echoMessageWithStatus(0, "Invalid parameters!");
   }
  





?>
