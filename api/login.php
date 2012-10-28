<?php
include_once("connection.php"); 
switch ($_SERVER['REQUEST_METHOD']) 
{
    case 'GET':
	break;
    case 'POST':
	    if(isset($_POST["redirect"]))
	    {
		$redirect=$_POST["redirect"];
	    }
	    else $redirect="public.html";
	    $email=$_POST["email"];
	    $password=$_POST["password"];
	    $query = sprintf("SELECT * FROM `User` WHERE email='%s' AND password='%s'",mysql_real_escape_string($email),mysql_real_escape_string($password));
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
