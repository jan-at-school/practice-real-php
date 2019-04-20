<?php

// vendor/cloudinary/cloudinary_php/src/

include_once("authenticate.php");
require 'vendor/cloudinary/cloudinary_php/src/Cloudinary.php';
require 'vendor/cloudinary/cloudinary_php/src/Uploader.php';
require 'vendor/cloudinary/cloudinary_php/src/Api.php';


include_once(".\global\utility.php");
cors();

include_once(".\config\dbconnect.php");


\Cloudinary::config(array(
    "cloud_name" => "",
  "api_key" => "",
  "api_secret" => ""
));


// CLOUDINARY_URL=cloudinary://696273655219257:Wzbcdar3YPB1kY6OFL9HZpDdzI0@janmarwat

$target_dir="profileImages";
$target_file = $target_dir. basename($_FILES["dp"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["dp"]["tmp_name"]);
    if($check !== false) {
        // echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
      echoMessageWithStatus(0, "File is not an image.");
      $uploadOk = 0;
    }
}

// Check file size
if ($_FILES["dp"]["size"] > 500000) {
    echoMessageWithStatus(0, "File size too large.");
    $uploadOk = 0;
}
// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif" ) {
      echoMessageWithStatus(0, "Only png and jpg/jpeg.");
      $uploadOk = 0;
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
} else {

    $userId = $authToken->userId;
    try{
      \Cloudinary\Uploader::upload($_FILES["dp"]["tmp_name"],
      array("folder" => "profile-images/", "public_id" => "dp_$userId", "overwrite" => TRUE, "invalidate" => TRUE));

      $sql = "UPDATE users set dpImgUrl='dp_$userId.$imageFileType' where id=$userId";

      $result = mysqli_query($mysqli, $sql);

      if($result){
        echoMessageWithStatus(1, "Uploaded");
      }
      else{
        echoMessageWithStatus(0, "Couldn't change the picture");
      }


    }
    catch(Exception $e){

      echo $e->getMessage();
    }



    //
    // if (move_uploaded_file($_FILES["dp"]["tmp_name"], $target_file)) {
    //     echo "The file ". basename( $_FILES["dp"]["name"]). " has been uploaded.";
    // } else {
    //     echoMessageWithStatus(0, "There was an error..");
    // }
}

?>
