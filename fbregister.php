<?php
define('FACEBOOK_APP_ID', '239586922749187');
define('FACEBOOK_SECRET', 'b6bb72a0659c26406587548e50385a38');

function parse_signed_request($signed_request, $secret) {
  list($encoded_sig, $payload) = explode('.', $signed_request, 2); 

  // decode the data
  $sig = base64_url_decode($encoded_sig);
  $data = json_decode(base64_url_decode($payload), true);

  if (strtoupper($data['algorithm']) !== 'HMAC-SHA256') {
    error_log('Unknown algorithm. Expected HMAC-SHA256');
    return null;
  }

  // check sig
  $expected_sig = hash_hmac('sha256', $payload, $secret, $raw = true);
  if ($sig !== $expected_sig) {
    error_log('Bad Signed JSON signature!');
    return null;
  }

  return $data;
}

function base64_url_decode($input) {
    return base64_decode(strtr($input, '-_', '+/'));
}

if ($_REQUEST) {
  $response = parse_signed_request($_REQUEST['signed_request'], 
                                   FACEBOOK_SECRET);
  print_r($response);
  $user=$response["user_id"];
  $locale=$response["user"]["locale"];
  $country=$response["user"]["country"];
  $user_profile=$response["registration"];
  $password=uniqid(mt_rand(), true);
  $token=uniqid(mt_rand().mt_rand(),true);
  include_once("api/connection.php"); 
  $query= sprintf("SELECT * FROM `User` WHERE email='%s'",mysql_real_escape_string($user_profile["email"]));
	    $result = mysql_query($query);
	    if (!$result) {
		    $message  = 'Invalid query: ' . mysql_error() . "\n";
		    $message .= 'Whole query: ' . $query;
		    echo $message;
	    }else if(mysql_num_rows($result)<=0){//if the facebook user hasn't registered yet
  		    $query = sprintf("SELECT * FROM `User` WHERE fbid='%s'",mysql_real_escape_string($user));
		    $result = mysql_query($query);
		    if (!$result) {
			    $message  = 'Invalid query: ' . mysql_error() . "\n";
			    $message .= 'Whole query: ' . $query;
			    echo $message;
	    	    }else if(mysql_num_rows($result)<=0){
			    $password=uniqid(mt_rand(), true);
			    $token=uniqid(mt_rand().mt_rand(),true);
			    $query = sprintf("INSERT INTO `User`(email,password,fbid,name,avatar_url,gender,locale,city,birthday,website,token) VALUES('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s')",mysql_real_escape_string($user_profile["email"]),mysql_real_escape_string($password),mysql_real_escape_string($user),mysql_real_escape_string($user_profile["name"]),mysql_real_escape_string("https://graph.facebook.com/".$user."/picture"),mysql_real_escape_string($user_profile["gender"]),mysql_real_escape_string($locale),mysql_real_escape_string($user_profile["location"]["name"]),mysql_real_escape_string($user_profile["birthday"]),mysql_real_escape_string("https://www.facebook.com/".$user),$token);
			    $result = mysql_query($query);
			    if (!$result) {
				    $message  = 'Invalid query: ' . mysql_error() . "\n";
				    $message .= 'Whole query: ' . $query;
				    echo $message;
			    }else {
				$query=sprintf("SELECT * FROM `User` WHERE email='%s'",mysql_real_escape_string($user_profile["email"]));
			        $result=mysql_query($query);
				if ($result)//Get User Information
				{	$my_profile=mysql_fetch_array($result);
					$friends= $facebook->api('/me/friends');
					$friends=$friends["data"];
					for($i=0;$i<count($friends);$i++)
					{
						$query=sprintf("INSERT INTO `FBfriends` (fbid1,fbid2) VALUES('%s','%s')",mysql_real_escape_string($user),mysql_real_escape_string($friends[$i]["id"]));
						$result = mysql_query($query);
					}
				}
			   }
		    }
		    else{  
			$my_profile=mysql_fetch_array($result);//If the facebook user has already registered.
		    }
	    }
	    else 
	    {
   		    $query = sprintf("SELECT * FROM `User` WHERE fbid='%s'",mysql_real_escape_string($user));
		    $result = mysql_query($query);
		    if (!$result) {
			    $message  = 'Invalid query: ' . mysql_error() . "\n";
			    $message .= 'Whole query: ' . $query;
			    echo $message;
	    	    }else if(mysql_num_rows($result)<=0){
	 		    $query = sprintf("UPDATE `User` SET fbid='%s' WHERE email='%s'",mysql_real_escape_string($user),mysql_real_escape_string($user_profile["email"]));
			    $result = mysql_query($query);
			    if (!$result) {
				    $message  = 'Invalid query: ' . mysql_error() . "\n";
				    $message .= 'Whole query: ' . $query;
				    echo $message;
			    }else {
			     $query=sprintf("SELECT * FROM `User` WHERE email='%s'",mysql_real_escape_string($user_profile["email"]));
			     $result=mysql_query($query);
			     if ($result){$my_profile=mysql_fetch_array($result);}//If the facebook user has already registered.
			     	$friends= $facebook->api('/me/friends');
			        $friends=$friends["data"];
				for($i=0;$i<count($friends);$i++)
				{
					$query=sprintf("INSERT INTO `FBfriends` (fbid1,fbid2) VALUES('%s','%s')",mysql_real_escape_string($user),mysql_real_escape_string($friends[$i]["id"]));
					$result = mysql_query($query);
				}
				
			    }
		    }else{
		     $query=sprintf("SELECT * FROM `User` WHERE email='%s'",mysql_real_escape_string($user_profile["email"]));
		     $result=mysql_query($query);
		     if ($result){$my_profile=mysql_fetch_array($result);}//If the facebook user has already registered.
		    }
	    }
	  if ($my_profile)
	  {
		setcookie("uid", $my_profile["uid"]);	
		setcookie("name",$my_profile["name"]);
		setcookie("token",$my_profile["token"]);
		setcookie("thumbnail",$my_profile["avatar_url"]);
		header("Location:".SERVER_URL."/account.html");
	  }
	  else { header("Location:".SERVER_URL."public.html?error=error with fb login");}
} else {
  //echo 'Please Register';
}
?>
