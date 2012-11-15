<?php
include_once("connection.php"); 
switch ($_SERVER['REQUEST_METHOD']) 
{
    case 'GET':
        $uid=$_GET['uid'];
	    $query = sprintf("SELECT * FROM `User` WHERE uid='%s'",mysql_real_escape_string($uid));
	    $result = mysql_query($query);
	    if (!$result) {
		    $message  = 'Invalid query: ' . mysql_error() . "\n";
		    $message .= 'Whole query: ' . $query;
		    echo $message;
	    }else if(mysql_num_rows($result)<=0){echo "No User";}
	    else 
	    {
		while($row=mysql_fetch_array($result)) $upload[]=$row;
		echo json_encode($upload);
	    }//successfully get upload information
    case 'POST':
    break;
}
?>
