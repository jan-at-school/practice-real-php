<?php


  include_once(".\global\utility.php");


  if(isset($_POST['authToken'])){
    $authToken = my_decrypt($_POST['authToken'], $key);
    echo $authToken . "<br>";

  }





  if(isset($_GET['authToken'])){
    try{


      if(my_decrypt($_GET['authToken'], $key)){
        $authToken = my_decrypt($_GET['authToken'], $key);

        if($authToken =json_encode($authToken)){

        }
        else{
          echoMessageWithStatus(0, "Invalid token!");
          die;

        }
      }
      else{

          echoMessageWithStatus(0, "Invalid token!");
          die;
      }


    }
    catch(Exception $e){
      echo $e->getMessage();
    }

  }
  else{
    echoMessageWithStatus(0, "No token!");

    die;
  }


?>
