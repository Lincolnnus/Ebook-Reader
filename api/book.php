<?php
    include_once("connection.php");
    switch ($_SERVER['REQUEST_METHOD'])
    {
        case 'GET':
            $bid=$_GET["bid"];
            $query = sprintf("SELECT * FROM `Book` WHERE bid='%s'",mysql_real_escape_string($bid));
            $result = mysql_query($query);
            if (!$result) {
                $message  = 'Invalid query: ' . mysql_error() . "\n";
                $message .= 'Whole query: ' . $query;
                echo $message;
            }else
            {
                $row=mysql_fetch_assoc($result);
                echo json_encode(array('file'=>DATASERVER_URL.'/pdf/'.$row["file_name"],'cover'=>DATASERVER_URL.'/pdfimage/'.$row['cover_url'],'title'=>$row['title'],'author'=>$row['author']));
            }//successfully get upload information
            break;
    }
?>
