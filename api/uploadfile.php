<?php
require("constants.php");
$allowedExts = array("pdf");
$extension = end(explode(".", $_FILES["file"]["name"]));
if (($_FILES["file"]["size"] < 400000)&& in_array($extension, $allowedExts))
{
    if ($_FILES["file"]["error"] > 0)
    {
    	$msg= "Return Code: " . $_FILES["file"]["error"] . "<br />";
	header( 'Location: '.errorurl.'?error='.$msg) ;
    }
    else
    {
	$newfile=md5(uniqid()).".pdf";
	$newfilename=pdfDirectory.$newfile;
	$thumb=thumbDirectory.$newfile.".jpg";
	move_uploaded_file($_FILES["file"]["tmp_name"],
	$newfilename);
	exec("convert \"{$newfilename}[0]\" -colorspace RGB -geometry 200 $thumb");
 	header( 'Location: '.saveurl.'?file='.$newfile) ;
    }
}
else
{
  $msg="Invalid file";
  header( 'Location: '.errorurl.'?error='.$msg) ;
}
