<?php
    include_once("connection.php");
    switch ($_SERVER['REQUEST_METHOD'])
    {
        case 'GET':
            $uid=$_GET["uid"];
            $query = sprintf("SELECT u2.uid,u2.thumbnail,u2.uname FROM `User` u1, `User` u2,`Friend` f WHERE u1.uid=f.fid1 AND u1.uid='%s' AND u2.uid=f.fid2",mysql_real_escape_string($uid));
            $result = mysql_query($query);
            if (!$result) {
                $message  = 'Invalid query: ' . mysql_error() . "\n";
                $message .= 'Whole query: ' . $query;
                echo $message;
            }else
            {
                while ($row=mysql_fetch_assoc($result))
                {
                    $follower[]=$row;
                }
                
            }
            $query = sprintf("SELECT u2.uid,u2.thumbnail,u2.uname FROM `User` u1, `User` u2, `FBfriend` fb WHERE u1.fbid=fb.fbid1 AND fb.fbid2= u2.fbid AND u1.uid='%s'",mysql_real_escape_string($uid));
            $result = mysql_query($query);
            if (!$result) {
                $message  = 'Invalid query: ' . mysql_error() . "\n";
                $message .= 'Whole query: ' . $query;
                echo $message;
            }else
            {
                while ($row=mysql_fetch_assoc($result))
                    $follower[]=$row;
            }
            if(isset($follower)) {$follower=array_map('unserialize', array_unique(array_map('serialize', $follower)));}
            else {$follower=array();}
            $query = sprintf("SELECT u2.uid,u2.thumbnail,u2.uname FROM `User` u1, `User` u2,`Friend` f WHERE u1.uid=f.fid2 AND u1.uid='%s' AND u2.uid=f.fid1",mysql_real_escape_string($uid));
            $result = mysql_query($query);
            if (!$result) {
                $message  = 'Invalid query: ' . mysql_error() . "\n";
                $message .= 'Whole query: ' . $query;
                echo $message;
            }else
            {
                while ($row=mysql_fetch_assoc($result))
                {
                    $following[]=$row;
                }
                
            }
            $query = sprintf("SELECT u2.uid,u2.thumbnail,u2.uname FROM `User` u1, `User` u2, `FBfriend` fb WHERE u1.fbid=fb.fbid2 AND fb.fbid1= u2.fbid AND u1.uid='%s'",mysql_real_escape_string($uid));
            $result = mysql_query($query);
            if (!$result) {
                $message  = 'Invalid query: ' . mysql_error() . "\n";
                $message .= 'Whole query: ' . $query;
                echo $message;
            }else
            {
                while ($row=mysql_fetch_assoc($result))
                    $following[]=$row;
            }
            if(isset($following)) {$following=array_map('unserialize', array_unique(array_map('serialize', $following)));}
            else {$following=array();}
            echo json_encode(array('follower'=>$follower,'following'=>$following));
            break;
        case 'POST':
            if((isset($_POST['fid1']))&&(isset($_POST['fid2'])))
            {
                $fid1=$_POST['fid1'];
                $fid2=$_POST['fid2'];
                $query = sprintf("INSERT INTO `Friend` (fid1,fid2) VALUES('%s','%s')",mysql_real_escape_string($fid1),mysql_real_escape_string($fid2));
                $result = mysql_query($query);
                if (!$result) {
                    $error_message="Error Creating Friends";
                }else
                {
                    $success_message="Success Creating Friends";
                    
                }
            }
            
            if (isset($error_message)) echo json_encode(Array('response_type'=>'fail','response_message'=>$error_message));
            if (isset($success_message)) echo json_encode(Array('response_type'=>'succeed','response_message'=>$success_message));
            break;
    }
?>
