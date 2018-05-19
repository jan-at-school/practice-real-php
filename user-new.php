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



$constants = mysql_query("SELECT * FROM globals);
$constants = mysql_fetch_array($constants);


 $going_good = true;

// check parameters
if (isset($_GET[""])) {
    $parameter = $_GET[""];
} else {
  $going_good = false;
  echoMessageWithStatus(0, "Invalid parameters!");
}



if($going_good){
    if($included_in_package){
        echoMessageWithStatus(1, "checkout successful!\nRides with current package are $rides_with_current_package now.");
    }
    else{
        echoMessageWithStatus(1, "checkout successful!\nThe ride costed Rs.$fare_in_rs");
    }

}

?>
