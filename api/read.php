<?php
include_once("connection.php"); 
switch ($_SERVER['REQUEST_METHOD']) 
{
    case 'GET':
        if((isset($_GET["uid"]))&&(isset($_GET["token"])))
        {
            $uid=$_GET["uid"];
            $token=$_GET["token"];
            if(authentication($uid,$token))
            {
                $query = sprintf("SELECT b.* FROM `Book` b, `Read` r WHERE r.uid='%s' AND r.bid=b.bid",mysql_real_escape_string($uid));
                $result = mysql_query($query);
                if (!$result) {
                    $message  = 'Invalid query: ' . mysql_error() . "\n";
                    $message .= 'Whole query: ' . $query;
                    echo $message;
                }else if(mysql_num_rows($result)<=0){echo "No Reading History";}
                else
                {
                    while($row=mysql_fetch_array($result)) $read[]=$row;
                    echo json_encode($read);
                }//successfully get upload information
            }
            else
            {
                echo "unauthorized";
            }
        }
        else
        {
            echo "error";
        }
    break;
    case 'POST':
        if((isset($_POST["uid"]))&&(isset($_POST["token"]))&&(isset($_POST["bid"]))&&(isset($_POST["pid"])))
        {
            $uid=$_POST["uid"];$bid=$_POST["bid"];$pid=$_POST["pid"];
            $query = sprintf("UPDATE `Read` SET pid='%s' WHERE uid='%s' AND bid='%s'",mysql_real_escape_string($pid),mysql_real_escape_string($uid),mysql_real_escape_string($bid));
            $result = mysql_query($query);
            if (!$result) {
                $message  = 'Invalid query: ' . mysql_error() . "\n";
                $message .= 'Whole query: ' . $query;
                echo $message;
            }else
            {
                echo json_encode('success');
            }//successfully get upload information
        }
    break;
}
function authentication($uid,$token)
{
   return true;
}
?>
