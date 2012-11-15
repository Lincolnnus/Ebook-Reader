<?php
include_once("connection.php"); 

switch ($_SERVER['REQUEST_METHOD']) 
{
    case 'GET':
	     if((isset($_GET['bid']))&&(isset($_GET['pid']))&&(isset($_GET['aid'])))
            {
                $aid=$_GET['aid'];
                $bid=$_GET['bid'];
                $pid=$_GET['pid'];
                $query = sprintf("SELECT * FROM `Comment` WHERE aid='%s' AND bid='%s' AND pid='%s'", mysql_real_escape_string($aid),mysql_real_escape_string($bid),mysql_real_escape_string($pid));
                $result = mysql_query($query);
                if (!$result) {
                    $message  = 'Invalid query: ' . mysql_error() . "\n";
                    $message .= 'Whole query: ' . $query;
                    die($message);
                }else if(mysql_num_rows($result)<=0){$success_message=array();}
                else
                {
                    while ($row=mysql_fetch_array($result))
                    {$success_message[]=$row;};
                }
            }else {$error_message="Error With Request";}
        
        if (isset($error_message)) echo json_encode(Array('response_type'=>'fail','response_message'=>$error_message));
        if (isset($success_message)) echo json_encode(Array('response_type'=>'succeed','response_message'=>$success_message));
	    break;
    case 'POST':
	    if(isset($_POST['operation']))
	    {
            $operation=$_POST['operation'];
            $text=$_POST['text'];
	    	$uid= $_POST['uid'];
            $token=$_POST['token'];
            $aid=$_POST['aid'];
            $bid=$_POST['bid'];
            $pid=$_POST['pid'];
            $cid=$_POST['cid'];
	    	switch ($operation)
            {
                case 'update':
                    $query = sprintf("UPDATE `Comment` SET text='%s' WHERE uid='%s' AND bid='%s' AND pid='%s' AND aid='%s' AND cid='%s'",
                                     mysql_real_escape_string($text),
                                     mysql_real_escape_string($uid),
                                     mysql_real_escape_string($bid),
                                     mysql_real_escape_string($pid),
                                     mysql_real_escape_string($aid),
                                     mysql_real_escape_string($cid));
                    $result = mysql_query($query);
                    if (!$result) {
                     $error_message="Error Updating Comment";
                    }
                    else
                        $success_message="Success Updating Comment";
                    break;
                case 'delete':
                    $query = sprintf("DELETE FROM `Comment` WHERE uid='%s' AND bid='%s' AND pid='%s' AND aid='%s' AND cid='%s'",
                                     mysql_real_escape_string($uid),
                                     mysql_real_escape_string($bid),
                                     mysql_real_escape_string($pid),
                                     mysql_real_escape_string($aid),
                                     mysql_real_escape_string($cid));
                    $result = mysql_query($query);
                    if (!$result) {
                        $error_message="Error Deleting Comment";
                    }
                    else
                        $success_message="Success Deleting Comment";
                    break;
                case 'create':
                    $query = sprintf("INSERT INTO `Comment`(aid,uid,bid,pid,cid,text) VALUES('%s','%s','%s','%s','%s','%s')",
                                     mysql_real_escape_string($aid),
                                     mysql_real_escape_string($uid),
                                     mysql_real_escape_string($bid),
                                     mysql_real_escape_string($pid),
                                     mysql_real_escape_string($cid),
                                     mysql_real_escape_string($text));
                    $result = mysql_query($query);
                    if (!$result) {
                        $error_message="Error Creating Comment";
                    }
                    else $success_message="Success Creating Comment";
                    break;
            }
	    }
        if (isset($error_message)) echo json_encode(Array('response_type'=>'fail','response_message'=>$error_message));
        if (isset($success_message)) echo json_encode(Array('response_type'=>'succeed','response_message'=>$success_message));
            break;
}
?>
