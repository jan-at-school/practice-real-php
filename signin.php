<?php
include_once(".\global\utility.php");
cors();
// array for JSON response
$response = array();

include_once(".\config\dbconnect.php");

include_once(".\global\utility.php");


$current_time_in_millis = round(microtime(true) * 1000);




$constants = mysqli_query($mysqli,"SELECT * FROM globals");
$constants = mysqli_fetch_array($constants);


 $going_good = true;







// check for post data
 if (isset($_GET["email"]) && isset($_GET["password"])) {
    $email = mysqli_real_escape_string($mysqli, $_GET['email']);
    $password = mysqli_real_escape_string($mysqli, $_GET['password']);



} else if(file_get_contents("php://input")!=null){
  $postdata = file_get_contents("php://input");
  $request = json_decode($postdata);

  if(isset($request->email) && isset($request->password)){
    $email =  $request->email;
    $password = $request->password;



  }
  else{
    $going_good=false;
  }

  // var_dump($request);
}
 else {
  $going_good = false;
  echoMessageWithStatus(0, "Invalid parameters!");
}



if($going_good){

      $user = mysqli_query($mysqli,"SELECT * from users where email='$email' && password='$password'");


      if(mysqli_num_rows($user)==0){
        $going_good = false;
        echoMessageWithStatus(0, "Invalid email or password!");
        die;
      }

      $user = mysqli_fetch_assoc($user);
      $userId = $user["id"];



      $token = array();
      $token["userId"] = $userId;
      $token["scope"] = "user-private";
      $token["timeCreated"] = $current_time_in_millis;


      //our data to be encoded
      $token = json_encode($token);

      //our data being encrypted. This encrypted data will probably be going into a cookie
      $token_encrypted = my_encrypt($token, $key);

      // //now we turn our encrypted data back to plain text
      // $password_decrypted = my_decrypt($password_encrypted, $key);
      // echo $password_decrypted . "<br>";

      $response["status"]  = 1;
      $response["message"] = "Sign in successful";
      $response["authToken"] = "$token_encrypted";
      echo json_encode($response);
}



?>
