<?php
require("constants.php");
$file=$_POST["urlfile"];
$extension = end(explode(".", $file));
if ($extension!="pdf")
{
  $msg="invalid pdf file";
  header( 'Location: '.errorurl.'?error='.$msg) ;
}
else
{
	$newfile=md5(uniqid()).".pdf";
	$newfilename=pdfDirectory.$newfile;
	$thumb=thumbDirectory.$newfile.".jpg";
	$err_msg = ''; 
	$out = fopen($newfilename,"wb");  
	if ($out == FALSE)
	{ 
	     $msg="File not opened<br>"; 
 	     header( 'Location: '.errorurl.'?error='.$msg) ;
	     exit; 
	} 
	$ch = curl_init(); 

	curl_setopt($ch, CURLOPT_FILE, $out); 
	curl_setopt($ch, CURLOPT_HEADER, 0); 
	curl_setopt($ch, CURLOPT_URL, $file); 

	curl_exec($ch); 
	if(curl_error ( $ch)!="") $msg=curl_error ( $ch); 
	curl_close($ch); 
	fclose($out); 
	if(isset($msg)) 
	{	header( 'Location: '.errorurl.'?error='.$msg);}
	else 
	{
	   	exec("convert \"{$newfilename}[0]\" -colorspace RGB -geometry 200 $thumb");
		header('Location: '.saveurl.'?file='.$newfile);
	}
}
?>
