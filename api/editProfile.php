<?php
include_once("connection.php"); 
switch ($_SERVER['REQUEST_METHOD']) 
{
    case 'GET':
	break;
    case 'POST':
	    $uid=$_POST["uid"];
	    $token=$_POST["token"];
	    $field=$_POST["field"];
	    $value=$_POST["value"];
	    $query = sprintf("UPDATE `User` SET ".$field."='%s' WHERE uid='%s' AND token='%s'",mysql_real_escape_string($value),mysql_real_escape_string($uid),mysql_real_escape_string($token));
	    $result = mysql_query($query);
	    if (!$result) {
		    $message  = 'Invalid query: ' . mysql_error() . "\n";
		    $message .= 'Whole query: ' . $query;
		    echo $message;
	    }else
	    {
		echo json_encode($result);//Successfully Updated.
	    }
   	 break;
}
function authentication($uid,$token)
{
   return true;
}
?>
