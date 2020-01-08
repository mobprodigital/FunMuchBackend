<?php
//phpinfo(); die;
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");

header('Content-Type: application/json');
ob_start();
error_reporting(E_ALL); //E_ALL ^ E_NOTICE ^ E_DEPRECATED
ini_set('display_errors', 0);

$response = array();


if($_SERVER["REQUEST_METHOD"] == "GET")
{ 
    $token_response =  array();
   
    if(isset($_GET['uu_id']))
    {
           $uu_id = $_GET['uu_id']; 
        
    }
    else {  
           $token_response['status'] = false;
           http_response_code(400);
           $token_response['message'] = 'Please provide uu_id';
           
           echo json_encode($token_response,JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
           die;
        }
        
    $user_name     = strtoupper('MorisMedia');
    $service_name  = strtoupper('FunMunch');
    $uu_id         = strtoupper($_GET['uu_id']);
    $password      = strtoupper(md5('8Wfs(dL6'));
    //$uu_id         = strtoupper('H8H3YRRU2');
    $token_response['status'] = true;
    $token_response['message'] = 'User Token';
    //$token_response['token'] = $user_name.$service_name.$uu_id.$password;
    $token_response['token'] = strtoupper(md5($user_name.$service_name.$uu_id.$password));
    

}
else
{
	header("HTTP/1.0 404 Not Found");
	die;
}
//echo '<pre>'; print_r($response);
echo json_encode($token_response,JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);





?>