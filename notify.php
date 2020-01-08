<?php

header("Access-Control-Allow-Origin:","*");
header("Access-Control-Allow-Credentials: true");

ob_start();
error_reporting(0); //E_ALL ^ E_NOTICE ^ E_DEPRECATED
ini_set('display_errors', 0);

static $link;
$link = mysqli_connect("localhost", "funmunch_mena", "Q1w2@mena") or print(mysqli_error()."error\n");
mysqli_select_db($link,"funmunch_mena_notifications") or die(mysqli_error()."\n"); 


if($_SERVER["REQUEST_METHOD"] == "GET")
{
    //echo "get method";
    $Msisdn = mysqli_real_escape_string($link,trim(isset($_GET['Msisdn']))) ? mysqli_real_escape_string($link,trim($_GET['Msisdn'])) :'';
    $ServiceName = mysqli_real_escape_string($link,trim(isset($_GET['ServiceName']))) ? mysqli_real_escape_string($link,trim($_GET['ServiceName'])) :'';
    $TransactionId = mysqli_real_escape_string($link,trim(isset($_GET['TransactionId']))) ? mysqli_real_escape_string($link,trim($_GET['TransactionId'])) :'';
    $Category = mysqli_real_escape_string($link,trim(isset($_GET['Category']))) ? mysqli_real_escape_string($link,trim($_GET['Category'])) :'';
    $Status = mysqli_real_escape_string($link,trim(isset($_GET['Status']))) ? mysqli_real_escape_string($link,trim($_GET['Status'])) :'';
    $Price = mysqli_real_escape_string($link,trim(isset($_GET['Price']))) ? mysqli_real_escape_string($link,trim($_GET['Price'])) :'';
    $SubCycle = mysqli_real_escape_string($link,trim(isset($_GET['SubCycle']))) ? mysqli_real_escape_string($link,trim($_GET['SubCycle'])) :'';
    $RefId  = mysqli_real_escape_string($link,trim(isset($_GET['RefId']))) ? mysqli_real_escape_string($link,trim($_GET['RefId'])) :'';
    
    if(empty($Msisdn) || $Msisdn == null || empty($ServiceName) || $ServiceName == null || empty($TransactionId) || $TransactionId == null || empty($Category) || $Category == null || empty($Status) ||
    $Status == null || empty($Price) || $Price == null || empty($SubCycle) || $SubCycle == null || empty($RefId) || $RefId == null)
    {
        $response['status']=400;
        //$response['status']="Missing Params";
    }
    elseif(!is_numeric($Msisdn))
    {
        $response['status']=405;
        //$response['status']="Msisdn Should Be Numeric";
    }
    elseif(!is_numeric($TransactionId))
    {
        $response['status']=400;
        //$response['status']="TransactionId Should Be Numeric";
    }
    elseif(!is_numeric($Category))
    {
        $response['status']=400;
        //$response['status']="Category Should Be Numeric";
    }
    elseif(!is_numeric($Status))
    {
        $response['status']=400;
        //$response['status']="Status Should Be Numeric";
    }
    elseif(!is_numeric($SubCycle))
    {
        $response['status']=400;
        //$response['status']="SubCycle Should Be Numeric";
    }
    elseif(!ctype_alnum($RefId))
    {
        $response['status']=400;
        //$response['status']="RefId Should Be Alpha Numeric";
    }
    else
    {
        $insert_query = "insert into mena_notification_url (`msisdn`,`service_name`,`transaction_id`,`category`,`status`,`price`,`sub_cycle`,`refId`,`insertion_time`) 
    	values('$Msisdn','$ServiceName','$TransactionId','$Category','$Status','$Price','$SubCycle','$RefId',NOW()) ON DUPLICATE KEY UPDATE updation_time=NOW()";
    	
        $insert_query_rs = mysqli_query($link,$insert_query);
        if($insert_query_rs)
        {
                $response['status']=200;
        }
    	else
    	{
    	        header("HTTP/1.0 404 Not Found");
    	        die; 
    	}
    }
    
			
}
else
{
	header("HTTP/1.0 404 Not Found");
	die;
}
echo json_encode($response,JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

?>