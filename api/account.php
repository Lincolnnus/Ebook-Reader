<?php
switch ($_SERVER['REQUEST_METHOD']) 
{
    case 'POST':
        include_once("connection.php");        
	    $uid=$_POST["uid"];
        $token=$_POST["token"];
        $query = sprintf("SELECT * FROM `User` WHERE uid='%s' AND token='%s'",mysql_real_escape_string($uid),mysql_real_escape_string($token));
        $result = mysql_query($query);
        if (!$result) {
            $error_message="System Error Find User"; //$error  = 'Invalid query: ' . mysql_error() . "\n";
        }else if(mysql_num_rows($result)<=0){$error_message="Invalid Email and Password Combination";}
        else
        {
            $success_message=mysql_fetch_array($result);
        }
   	 break;
}
    if (isset($error_message)) echo json_encode(Array('response_type'=>'fail','response_message'=>$error_message));
    if (isset($success_message)) echo json_encode(Array('response_type'=>'succeed','response_message'=>$success_message));
?>
