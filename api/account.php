<?php
include_once("connection.php"); 
switch ($_SERVER['REQUEST_METHOD']) 
{
    case 'GET':
	break;
    case 'POST':
	    $uid=$_POST["uid"];
	    $token=$_POST["token"];
	    $query = sprintf("SELECT * FROM `User` WHERE uid='%s' AND token='%s'",mysql_real_escape_string($uid),mysql_real_escape_string($token));
	    $result = mysql_query($query);
	    if (!$result) {
		    $message  = 'Invalid query: ' . mysql_error() . "\n";
		    $message .= 'Whole query: ' . $query;
		    echo $message;
	    }else if(mysql_num_rows($result)<=0){$error = array("code" => "41","msg"=>"No Such User");echo json_encode($error);}
	    else 
	    {
		$row=mysql_fetch_array($result);
		echo json_encode($row);
	    }//successfully get upload information
   	 break;
}
function authentication($uid,$token)
{
   return true;
}
?>
