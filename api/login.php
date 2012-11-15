<?php
    function validate_email($email) {
        // First, we check that there's one @ symbol,
        // and that the lengths are right.
        if (!ereg("^[^@]{1,64}@[^@]{1,255}$", $email)) {
            // Email invalid because wrong number of characters
            // in one section or wrong number of @ symbols.
            return false;
        }
        // Split it into sections to make life easier
        $email_array = explode("@", $email);
        $local_array = explode(".", $email_array[0]);
        for ($i = 0; $i < sizeof($local_array); $i++) {
            if
                (!ereg("^(([A-Za-z0-9!#$%&'*+/=?^_`{|}~-][A-Za-z0-9!#$%&
                       ↪'*+/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$",
                       $local_array[$i])) {
                    return false;
                }
        }
        // Check if domain is IP. If not,
        // it should be valid domain name
        if (!ereg("^\[?[0-9\.]+\]?$", $email_array[1])) {
            $domain_array = explode(".", $email_array[1]);
            if (sizeof($domain_array) < 2) {
                return false; // Not enough parts to domain
            }
            for ($i = 0; $i < sizeof($domain_array); $i++) {
                if
                    (!ereg("^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|
                           ↪([A-Za-z0-9]+))$",
                     $domain_array[$i])) {
                        return false;
                    }
            }
        }
        return true;
    }
    
switch ($_SERVER['REQUEST_METHOD']) 
{
    case 'POST':
	    $email=$_POST["email"];
        if(validate_email($email))
        {
            include_once("connection.php");
            $password=$_POST["password"];
            $query = sprintf("SELECT * FROM `User` WHERE email='%s' AND password='%s'",mysql_real_escape_string($email),mysql_real_escape_string($password));
            $result = mysql_query($query);
            if (!$result) {
                $error_message="System Error Find User"; //$error  = 'Invalid query: ' . mysql_error() . "\n";
            }else if(mysql_num_rows($result)<=0){$error_message="Invalid Email and Password Combination";}
            else
            {
                $my_profile=mysql_fetch_array($result);
                if($my_profile['verified'])
                {
                    $token=md5(time());
                    $query = sprintf("Update `User` SET token='%s' WHERE email='%s'",mysql_real_escape_string($token),mysql_real_escape_string($email));
                    $result = mysql_query($query);
                    if (!$result) {
                        $error_message="Error Update User";
                    }
                    else{
                        if(isset($_POST['remember'])&&($_POST['remember']))
                        {$expire=360000;}else{$expire=3600;}
                        $path=SERVER_PATH;
                        $domain="";
                        setcookie('uid', $my_profile["uid"],time()+$expire,$path, $domain,0,0);
                        setcookie('uname',$my_profile["uname"],time()+$expire, $path, $domain, 0,0);
                        setcookie('token', $token,time()+$expire, $path, $domain, 0,0);
                        setcookie('thumbnail',$my_profile["thumbnail"],time()+$expire, $path, $domain,0, 0);
                        $success_message="Valid User";
                    }
                }
                else {$error_message="Please Verify Your Email";}
            }
        }
        else {$error_message="Invalid Email Address";}
   	 break;
}
    if (isset($error_message)) echo json_encode(Array('response_type'=>'fail','response_message'=>$error_message));
    if (isset($success_message)) echo json_encode(Array('response_type'=>'succeed','response_message'=>$success_message));
?>
