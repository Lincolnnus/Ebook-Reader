<?php
include_once("connection.php");
switch ($_SERVER['REQUEST_METHOD']) 
{
    case 'GET':
	break;
    case 'POST':
        if(isset($_POST["remember"])&&($_POST["remember"]==1))
        {$expire=360000;}else{$expire=3600;}
        $path="/viewer";
        $domain="";
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
            $my_profile=mysql_fetch_array($result);
            setcookie('uid', $my_profile["uid"],time()+$expire,$path, $domain,0,0);
            setcookie('name',$my_profile["name"],time()+$expire, $path, $domain, 0,0);
            setcookie('token',$my_profile["token"],time()+$expire, $path, $domain, 0,0);
            setcookie('thumbnail',$my_profile["avatar_url"],time()+$expire, $path, $domain,0, 0);
            echo json_encode("success");
	    }//successfully get upload information
   	 break;
}
function authentication($uid,$token)
{
   return true;
}
?>
