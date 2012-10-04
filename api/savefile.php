<?php
include_once("connection.php"); 
switch ($_SERVER['REQUEST_METHOD']) 
{
    case 'GET':
	  break;
    case 'POST':
	  if(!isset($_POST["tac"])) header( 'Location: '.errorurl.'?error=Please Agree Terms and Conditions');
	  else if((isset($_POST["filename"]))&&(isset($_POST["filetitle"]))&&(isset($_POST["author"]))&&(isset($_POST["description"]))&&(isset($_POST["language"]))&&(isset($_POST["access"])))
	  {
		$filename=$_POST["filename"];
		$filetitle=$_POST["filetitle"];
		$author=$_POST["author"];
		$description=$_POST["description"];
		$language=$_POST["language"];
		$access=$_POST["access"];
		$thumbnail=$_POST["thumbnail"];
	        $uid=1;
		// Formulate Query
		// This is the best way to perform an SQL query
		// For more examples, see mysql_real_escape_string()
		$query = sprintf("INSERT INTO `Book`(title,author,story,language,filename,access,uploader,thumbnail) VALUES('%s','%s','%s','%s','%s','%s','%s','%s')",
			mysql_real_escape_string($filetitle),
			mysql_real_escape_string($author),
			mysql_real_escape_string($description),
			mysql_real_escape_string($language),
			mysql_real_escape_string($filename),
			mysql_real_escape_string($access),
			mysql_real_escape_string($uid),
			mysql_real_escape_string($thumbnail)
		);
		// Perform Query
		$result = mysql_query($query);
		// Check result
		if (!$result) {
		    header( 'Location: '.errorurl.'?error='.mysql_error());
		}
		else{
			$bid= mysql_insert_id();
			header( 'Location: '.successurl.'?bid='.$bid);
		}
	   }
	   else
	   {echo header( 'Location: '.errorurl.'?error=Please Fill In The Form Correctly');}
	   break;
}
?>
