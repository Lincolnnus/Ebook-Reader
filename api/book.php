<?php
include_once("connection.php"); 
switch ($_SERVER['REQUEST_METHOD']) 
{
    case 'GET':
    if((isset($_GET["bid"]))&&(isset($_GET["token"])))
    {
    	$bid=$_GET["bid"];
	$token=$_GET["token"];
	if(authentication($bid,$token))
	{
		    $query = sprintf("SELECT * FROM `Book` WHERE bid='%s'",mysql_real_escape_string($bid));
		    $result = mysql_query($query);
		    if (!$result) {
			    $message  = 'Invalid query: ' . mysql_error() . "\n";
			    $message .= 'Whole query: ' . $query;
			    echo $message;
		    }else 
		    {
			$row=mysql_fetch_assoc($result);
			echo json_encode(array('file'=>'http://localhost/viewer/pdf/'.$row["filename"]));
		    }//successfully get upload information
	}
	else
	{
		echo "unauthorized";
	}
    }
    else
    {
	echo "error";
    }
    break;
    case 'POST':
    break;
}
function authentication($uid,$token)
{
   return true;
}
?>
