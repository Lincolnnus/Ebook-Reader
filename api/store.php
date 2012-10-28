<?php
include_once("connection.php"); 
switch ($_SERVER['REQUEST_METHOD']) 
{
    case 'GET':
	    $query = sprintf("SELECT * FROM `Book` WHERE access='public'");
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
    case 'POST':
    break;
}
function authentication($uid,$token)
{
   return true;
}
?>
