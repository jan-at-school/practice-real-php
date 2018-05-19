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

// check for post data
if (isset($_GET["email"]) && isset($_GET["password"])) {
    $email = mysqli_real_escape_string($mysqli, $_GET['email']);
    $password = mysqli_real_escape_string($mysqli, $_GET['password']);


    $user = mysqli_query($mysqli,"SELECT * from users where email='$email' && password='$password'");

    if(mysqli_num_rows($user)==0){
      $going_good = false;
      echoMessageWithStatus(0, "Invalid email or password!");
      die;
    }

    $user = mysqli_fetch_assoc($user);


    echo json_encode($user);

} else {
  $going_good = false;
  echoMessageWithStatus(0, "Invalid parameters!");
}


?>
