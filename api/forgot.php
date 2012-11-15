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
    if(($_POST['email'])&&(validate_email($_POST['email'])))
    {
        $email=$_POST['email'];
        include_once("connection.php");
        $query = sprintf("SELECT * FROM `User` WHERE email='%s'",mysql_real_escape_string($email));
        $result = mysql_query($query);
        if (!$result) {
            $error_message="Error Getting User";
        }else if(mysql_num_rows($result)<=0){$error_message="No Such User";}
        else
        {
            $my_profile=mysql_fetch_array($result);
            $uname=$my_profile['uname'];
            $password=$my_profile['password'];
            ini_set('include_path', PEAR_PATH);
            require_once "Mail.php";
            $subject = "Your Password For Friend Learn";
            $from = "Friend Learn<noreply@friendlearn.com>";
            $to = $email;
            $message = "Dear ".$uname.",\n\nWe noticed that you request to get back your passport today on Friend Learn. Below is the information\n\nYour Email Address is: ".$email."\nYour Password is:".$password."\n\nBest Regards,\nFriend Learn Team\n";
            $host = "ssl://smtp.gmail.com";
            $port = "465";
            $username = "lincolnnus@gmail.com";
            $password = "33455432";
            $headers = array ('From' => $from,
                              'To' => $to,
                              'Subject' => $subject);
            $smtp = Mail::factory('smtp',
                                  array ('host' => $host,
                                         'port' => $port,
                                         'auth' => true,
                                         'username' => $username,
                                         'password' => $password));
            $mail = $smtp->send($to, $headers, $message);
            if (PEAR::isError($mail)) {
                echo  $mail->getMessage();
                $error_message="Error Sending Verification Email";
            } else {
                $success_message="Password Sent, Please Check Your Email";
            }
        }
    }
    else {$error_message="Invalid Email";}
    if (isset($error_message)) echo json_encode(Array('response_type'=>'fail','response_message'=>$error_message));
    if (isset($success_message)) echo json_encode(Array('response_type'=>'succeed','response_message'=>$success_message));
?>