<?php
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


    $user = mysqli_query($mysqli,"SELECT * from users where email='$email' && password='$password'");


    if(mysqli_num_rows($user)==0){
      $going_good = false;
      echoMessageWithStatus(0, "Invalid email or password!");
      die;
    }

    $user = mysqli_fetch_assoc($user);
    $userId = $user["id"];
    //
    //
    // do{
    //   $authToken = getToken(50);
    //   $userId =1;
    //   $authTokenExists = mysqli_query($mysqli,"SELECT * from authTokens where userId = '$userId'");
    //   if(mysqli_num_rows($authTokenExists)==0){
    //     //insert
    //     $tokenSaved = mysqli_query($mysqli,"UPDATE authTokens SET authToken=$authToken WHERE userId= $userId");
    //   }
    //   else{
    //     //update
    //     $tokenSaved = mysqli_query($mysqli,"INSERT into authTokens(userId,authToken) values ($userId,$authToken)");
    //   }
    // }
    // while ($tokenSaved);

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

} else {
  $going_good = false;
  echoMessageWithStatus(0, "Invalid parameters!");
}


?>