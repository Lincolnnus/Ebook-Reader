<?php
/**
 * Copyright 2011 Facebook, Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may
 * not use this file except in compliance with the License. You may obtain
 * a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations
 * under the License.
 */

require 'src/facebook.php';

// Create our Application instance (replace this with your appId and secret).
$facebook = new Facebook(array(
  'appId'  => '239586922749187',
  'secret' => 'b6bb72a0659c26406587548e50385a38',
  'scope'=>'email,read_friendlists'
));

// Get User ID
$user = $facebook->getUser();
// We may or may not have this data based on whether the user is logged in.
//
// If we have a $user id here, it means we know the user is logged into
// Facebook, but we don't know if the access token is valid. An access
// token is invalid if the user logged out of Facebook.

if ($user) {
  try {
    // Proceed knowing you have a logged in user who's authenticated.
    $user_profile = $facebook->api('/me');
    include_once("api/connection.php"); 
    $query = sprintf("SELECT * FROM `User` WHERE fbid='%s'",mysql_real_escape_string($user));
	    $result = mysql_query($query);
	    if (!$result) {
		    $message  = 'Invalid query: ' . mysql_error() . "\n";
		    $message .= 'Whole query: ' . $query;
		    echo $message;
	    }else if(mysql_num_rows($result)<=0){//if the facebook user hasn't registered yet
		    $query= sprintf("SELECT * FROM `User` WHERE email='%s'",mysql_real_escape_string($user_profile["email"]));
		    $result = mysql_query($query);
		    if (!$result) {
			    $message  = 'Invalid query: ' . mysql_error() . "\n";
			    $message .= 'Whole query: ' . $query;
			    echo $message;
	    	    }else if(mysql_num_rows($result)<=0){
			    $password=uniqid(mt_rand(), true);
			    $token=uniqid(mt_rand().mt_rand(),true);
			    $query = sprintf("INSERT INTO `User`(email,password,fbid,firstname,lastname,name,avatar_url,gender,timezone,locale,city,birthday,website,token) VALUES('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s')",mysql_real_escape_string($user_profile["email"]),mysql_real_escape_string($password),mysql_real_escape_string($user),mysql_real_escape_string($user_profile["first_name"]),mysql_real_escape_string($user_profile["last_name"]),mysql_real_escape_string($user_profile["name"]),mysql_real_escape_string("https://graph.facebook.com/".$user."/picture"),mysql_real_escape_string($user_profile["gender"]),mysql_real_escape_string($user_profile["timezone"]),mysql_real_escape_string($user_profile["locale"]),mysql_real_escape_string($user_profile["location"]["name"]),mysql_real_escape_string($user_profile["birthday"]),mysql_real_escape_string("https://www.facebook.com/".$user),mysql_real_escape_string($token));
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
			    $query = sprintf("UPDATE `User` SET fbid='%s' WHERE email='%s'",mysql_real_escape_string($user),mysql_real_escape_string($user_profile["email"]));
			    $result = mysql_query($query);
			    if (!$result) {
				    $message  = 'Invalid query: ' . mysql_error() . "\n";
				    $message .= 'Whole query: ' . $query;
				    echo $message;
			    }else {
				$query=sprintf("SELECT * FROM `User` WHERE email='%s'",mysql_real_escape_string($user_profile["email"]));
				$result=mysql_query($query);
				if ($result){
						$my_profile=mysql_fetch_array($result);
						$friends= $facebook->api('/me/friends');
						$friends=$friends["data"];
						for($i=0;$i<count($friends);$i++)
						{
							$query=sprintf("INSERT INTO `FBfriends` (fbid1,fbid2) VALUES('%s','%s')",mysql_real_escape_string($user),mysql_real_escape_string($friends[$i]["id"]));
							$result = mysql_query($query);
						}
					}//If the facebook user has already registered.
			    }
			
		    }
	    }
	    else 
	    {
		 $my_profile=mysql_fetch_array($result);//If the facebook user has already registered.
	    }
  } catch (FacebookApiException $e) {
    error_log($e);
    $user = null;
  }
}

// Login or logout url will be needed depending on current user state.
if ($user) {
  $logoutUrl = $facebook->getLogoutUrl();
    if(isset($_POST["remember"])&&($_POST["remember"]==1))
    {$expire=360000;}else{$expire=3600;}
    $path="/viewer";
    $domain="";
  if ($my_profile)
  {
      setcookie('uid', $my_profile["uid"],time()+$expire,$path, $domain,0,0);
      setcookie('name',$my_profile["name"],time()+$expire, $path, $domain, 0,0);
      setcookie('token',$my_profile["token"],time()+$expire, $path, $domain, 0,0);
      setcookie('thumbnail',$my_profile["avatar_url"],time()+$expire, $path, $domain,0, 0);
      if (isset($_COOKIE['redirect']))
          header("Location:".$_COOKIE['redirect']);
      else header("Location:".SERVER_URL."/index.html");
	
  }
  else { 
      if (isset($_COOKIE['redirect']))
          header("Location:".$_COOKIE['redirect']);
      else {header("Location:".SERVER_URL."public.html?error=error with fb login");
      }
} else {
  $loginUrl = $facebook->getLoginUrl();
  header('Location: '.$loginUrl);
}

// This call will always work since we are fetching public data.
$naitik = $facebook->api('/naitik');

?>
