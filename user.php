<?php
// header("Access-Control-Allow-Origin: *");
// header("Access-Control-Allow-Methods : GET,POST,PUT,DELETE,OPTIONS");
// header("Access-Control-Allow-Headers : Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
// array for JSON response
$response = array();

include_once(".\config\dbconnect.php");

include_once(".\global\utility.php");

cors();





$constants = mysqli_query($mysqli,"SELECT * FROM globals");
$constants = mysqli_fetch_array($constants);


 $going_good = true;




// check for post data
if (isset($_GET["id"])) {
    $id = mysqli_real_escape_string($mysqli, $_GET['id']);
    $user = mysqli_query($mysqli,"SELECT * from users where id=$id");
    $user = mysqli_fetch_assoc($user);

    $user['about'] = utf8_encode($user['about']);



    echo json_encode($user);

} else {
  $postdata = file_get_contents("php://input");
  $request = json_decode($postdata);
  var_dump($request);
  $going_good = false;
  echoMessageWithStatus(0, "Invalid parameters!");
}


?>
