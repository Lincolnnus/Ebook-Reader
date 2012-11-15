<?php session_start();

/** Validate captcha */
if (!empty($_REQUEST['captcha'])) {
    if (empty($_SESSION['captcha']) || trim(strtolower($_REQUEST['captcha'])) != $_SESSION['captcha']) {
        $error_message="Invalid Captcha";
    } else {
        $password1=$_REQUEST['password1'];
        $password2=$_REQUEST['password2'];
        if(($password1=="")||($password1!=$password2))
        {
            $error_message="Passwords Don't Match";
        }
        else
        {
            $email=$_REQUEST['email'];
            if(validate_email($email))
            {
                $uname=$_REQUEST['uname'];
                $token=md5($email).md5($password1);
                include_once("connection.php");
                $query = sprintf("INSERT INTO `User`(email,password,uname,token) VALUES ('%s','%s','%s','%s')",mysql_real_escape_string($email),mysql_real_escape_string($password1),mysql_real_escape_string($uname),mysql_real_escape_string($token));
                $result = mysql_query($query);
                if (!$result) {
                    $error_message="Error Creating User";
                }
                else {
                    ini_set('include_path', PEAR_PATH);
                    require_once "Mail.php";
                    $subject = "Thank You for Registering Friend Learn";
                    $from = "Friend Learn<noreply@friendlearn.com>";
                    $to = $email;
                    $message = "Dear ".$uname.",\n\nThank your for registering Friend Learn.\n\nYour Email Address is: ".$email."\n Your Password is:".$password1."\nPlease verify your email address by clicking the following link \n ".SERVER_URL."/api/verify.php?token=".$token." \n\nHappy Learning:-)\nBest Regards,\nFriend Learn Team\n";
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
                       $success_message="Register Success,Check your Email to Verify";
                    }
                }
            }
            else {$error_message="Invalid Email";}
        }
    }
    unset($_SESSION['captcha']);
}else{$error_message="No Captcha";}
    if (isset($error_message)) echo json_encode(Array('response_type'=>'fail','response_message'=>$error_message));
    if (isset($success_message)) echo json_encode(Array('response_type'=>'succeed','response_message'=>$success_message));
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
?>
