<?php
include_once("connection.php"); 
switch ($_SERVER['REQUEST_METHOD']) 
{
    case 'GET':
    if((isset($_GET["uid"]))&&(isset($_GET["token"])))
    {
    	$uid=$_GET["uid"];
	$token=$_GET["token"];
	if(authentication($uid,$token))
	{
		    $query = sprintf("SELECT * FROM `Book` WHERE uid='%s'",mysql_real_escape_string($uid));
		    $result = mysql_query($query);
		    if (!$result) {
			    $message  = 'Invalid query: ' . mysql_error() . "\n";
			    $message .= 'Whole query: ' . $query;
			    echo $message;
		    }else if(mysql_num_rows($result)<=0){echo "No Uploads";}
		    else 
		    {
			while($row=mysql_fetch_array($result)) $upload[]=$row;
			echo json_encode($upload);
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
