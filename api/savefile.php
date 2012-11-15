<?php
include_once("connection.php"); 
switch ($_SERVER['REQUEST_METHOD']) 
{
    case 'GET':
	  break;
    case 'POST':
	  if(!isset($_POST["tac"])) header( 'Location: '.errorurl.'?error=Please Agree Terms and Conditions');
	  else if((isset($_POST["filename"]))&&(isset($_POST["cover"]))&&(isset($_POST["title"]))&&(isset($_POST["author"]))&&(isset($_POST["description"]))&&(isset($_POST["language"]))&&(isset($_POST["accessibility"])))
	  {
		$file=$_POST["filename"];
		$title=$_POST["title"];
		$author=$_POST["author"];
		$description=$_POST["description"];
		$language=$_POST["language"];
		$accessibility=$_POST["accessibility"];
		$cover=$_POST["cover"];
        $uid=$_POST["uid"];
          
		// Formulate Query
		// This is the best way to perform an SQL query
		// For more examples, see mysql_real_escape_string()
		$query = sprintf("INSERT INTO `Book`(title,author,description,language,file_name,accessibility,uid,cover_url) VALUES('%s','%s','%s','%s','%s','%s','%s','%s')",
			mysql_real_escape_string($title),
			mysql_real_escape_string($author),
			mysql_real_escape_string($description),
			mysql_real_escape_string($language),
			mysql_real_escape_string($file),
			mysql_real_escape_string($accessibility),
			mysql_real_escape_string($uid),
			mysql_real_escape_string($cover)
		);
		// Perform Query
		$result = mysql_query($query);
		// Check result
		if (!$result) {
		    header( 'Location: '.errorurl.'?error='.mysql_error());
		}
		else{
			$bid= mysql_insert_id();
            setcookie("bid",$bid,time()+360000, SERVER_PATH, "",0,0);
			header( 'Location: '.successurl);
		}
	   }
	   else
	   {echo header( 'Location: '.errorurl.'?error=Please Fill In The Form Correctly');}
	   break;
}
?>
