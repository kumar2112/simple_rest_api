<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header("Access-Control-Allow-Origin: http://localhost/simple-rest-api/");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once 'config/database.php';
include_once 'objects/user.php';

// get database connection
$database = new Database();
$db = $database->getConnection();

// instantiate user object
$user = new User($db);

$data = json_decode(file_get_contents("php://input"));

if(empty($data->name)){
    http_response_code(501);
    echo json_encode(array("message" => "Name is required."));
    return;
}
if(empty($data->email)){
    http_response_code(502);
    echo json_encode(array("message" => "Email is required."));
    return;
}
if(empty($data->password)){
    http_response_code(503);
    echo json_encode(array("message" => "Password is required."));
    return;
}
if(!$user->checkUnique('email',$data->email)){
    http_response_code(504);
    echo json_encode(array("message" => "An user already exists with given email."));
    return;
}
//set user property values
$user->name = $data->name;
$user->email = $data->email;
$user->password = $data->password;

if($user->create()){
  http_response_code(200);
  // display message: user was created
  echo json_encode(array("message" => "User was created."));
}else{
    http_response_code(400);

    // display message: unable to create user
    echo json_encode(array("message" => "Unable to create user."));
}
?>
