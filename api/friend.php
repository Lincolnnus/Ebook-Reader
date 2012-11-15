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
                    $friend[]=$row;
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
                    $friend[]=$row;
            }
            if(isset($friend)) echo json_encode(array_unique($friend));
            else echo json_encode(array());
            break;
    }
?>
