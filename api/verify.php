<?php
include_once("connection.php");
switch ($_SERVER['REQUEST_METHOD']) 
{
    case 'GET':
        $token=$_GET['token'];
	    $query = sprintf("SELECT uid,email FROM `User` WHERE token='%s'",mysql_real_escape_string($token));
	    $result = mysql_query($query);
	    if (!$result) { $error_message="System Error";
	    }else if(mysql_num_rows($result)<=0){$error_message="Invalid Verification Code";}
	    else 
	    {
            $my_profile=mysql_fetch_array($result);
            $query = sprintf("Update `User` SET verified=1 WHERE token='%s'",mysql_real_escape_string($token));
            $result = mysql_query($query);
            $success_message=$my_profile['email']." successfully verified "."<a href=\"".DATASERVER_URL."\"> Login</a>";
	    }//successfully get upload information
   	 break;
}
    if(isset($error_message)) echo $error_message;
    else if(isset($success_message)) echo $success_message;
?>
