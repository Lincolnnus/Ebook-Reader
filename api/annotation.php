<?php
include_once("connection.php"); 

switch ($_SERVER['REQUEST_METHOD']) 
{
    case 'GET':
	     if((isset($_GET['bid']))&&(isset($_GET['pid'])))
            {
		   if (isset($_GET['uid']))
		   {
			  $uid=$_GET['uid'];
              $bid=$_GET['bid'];
              $pid=$_GET['pid'];
			  if(isset($_GET['friends'])){
				if(($_GET['friends'])=='fb')
				{
				    $query = sprintf("SELECT fbid FROM `User` WHERE uid='%s'", mysql_real_escape_string($uid));
				    $res=mysql_query($query);
				    if($res)
			            {	   
					    $row=mysql_fetch_array($res);$fbid=$row['fbid'];
					    $uid=$_GET['uid'];$bid=$_GET["bid"]; $pid=$_GET["pid"];
					    $query = sprintf("SELECT a.* FROM `User` u,`Annotation` a,`FBfriends` fb WHERE a.bid='%s' AND a.pid='%s' AND fb.fbid1='%s' AND u.fbid=fb.fbid2 AND u.uid=a.uid", mysql_real_escape_string($bid),mysql_real_escape_string($pid),mysql_real_escape_string($fbid));
					    $res= mysql_query($query);
				    }
				}
			  }else{
			    $query = sprintf("SELECT * FROM `Annotation` WHERE uid='%s' AND bid='%s' AND pid='%s'", mysql_real_escape_string($uid),mysql_real_escape_string($bid),mysql_real_escape_string($pid));
			    $res= mysql_query($query);
			 }
		    }else
		    {
			    $bid=$_GET['bid']; $pid=$_GET['pid'];
			    $query = sprintf("SELECT * FROM `Annotation` WHERE bid='%s' AND pid='%s'", mysql_real_escape_string($bid),mysql_real_escape_string($pid));
			    $res= mysql_query($query);
		    }
		    if (mysql_num_rows($res)<=0)
		    echo json_encode(array());
		    else
		    {
			while($row=mysql_fetch_array($res))
			{
				$annotation=$row;
				$annotationList[]=$annotation;
			}
			echo json_encode($annotationList);
		    }
            }
            else if(isset($_GET["bid"]))
	    {
		    $bid=$_GET["bid"];
		    $query = sprintf("SELECT * FROM `Annotation` WHERE bid='%s' ", mysql_real_escape_string($bid));
		    $res= mysql_query($query);
		    if (mysql_num_rows($res)<=0)
		    echo json_encode(array());
		    else
		    {
			while($row=mysql_fetch_array($res))
			{
				$annotation=$row;
				$annotationList[]=$annotation;
			}
			echo json_encode($annotationList);
		    }
	    }
	    else
	    {
		echo "Error With Request";
	    }
	    break;
    case 'POST':
	    if(isset($_POST["operation"]))
	    {
		$operation=$_POST["operation"];
	    	$uid= $_POST['uid'];
	   	$token=$_POST['token'];
	   	$annotation=$_POST['annot'];
	    	switch ($operation)
		{
			case 'update':
			$query = sprintf("UPDATE `Annotation` SET text='%s',access='%s' WHERE uid='%s' AND bid='%s' AND pid='%s' AND aid='%s'",
			        mysql_real_escape_string($annotation["text"]),
				mysql_real_escape_string($annotation["access"]),
				mysql_real_escape_string($annotation["uid"]),
				mysql_real_escape_string($annotation["bid"]),
				mysql_real_escape_string($annotation["pid"]),
				mysql_real_escape_string($annotation["aid"]));
			$result = mysql_query($query);
			if (!$result) {
			    $message  = 'Invalid query: ' . mysql_error() . "\n";
			    $message .= 'Whole query: ' . $query;
			    die($message);
			}
			else echo json_encode("update success");
			break;
			case 'delete':
			$query = sprintf("UPDATE `Annotation` SET status=1 WHERE uid='%s' AND bid='%s' AND pid='%s' AND aid='%s'",
			        mysql_real_escape_string($uid),
				mysql_real_escape_string($annotation["bid"]),
				mysql_real_escape_string($annotation["pid"]),
				mysql_real_escape_string($annotation["aid"]));
			$result = mysql_query($query);
			if (!$result) {
			    $message  = 'Invalid query: ' . mysql_error() . "\n";
			    $message .= 'Whole query: ' . $query;
			    die($message);
			}
			else echo json_encode("delete success");
			break;
			case 'create':
			$query = sprintf("INSERT INTO `Annotation`(aid,uid,bid,pid,startx,starty,width,height,type,text,points,access,color) VALUES('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s')",
                mysql_real_escape_string($annotation["aid"]),
                mysql_real_escape_string($uid),
				mysql_real_escape_string($annotation["bid"]),
				mysql_real_escape_string($annotation["pid"]),
				mysql_real_escape_string($annotation["startx"]),
				mysql_real_escape_string($annotation["starty"]),
				mysql_real_escape_string($annotation["width"]),
				mysql_real_escape_string($annotation["height"]),
				mysql_real_escape_string($annotation["type"]),
				mysql_real_escape_string($annotation["text"]),
				mysql_real_escape_string($annotation["points"]),
				mysql_real_escape_string($annotation["access"]),
				mysql_real_escape_string($annotation["color"]));
			$result = mysql_query($query);
			if (!$result) {
			    $message  = 'Invalid query: ' . mysql_error() . "\n";
			    $message .= 'Whole query: ' . $query;
			    die($message);
			}
                        $lastid= mysql_insert_id();
			echo json_encode($lastid);
			break;
		}
	    }
            break;
}
?>
