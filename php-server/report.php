<?php

include 'log.php';

//http://localhost/api/report.php
//http://localhost/php-server/record.php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

echo report(10);


function report($num)
{
/*
	$mysql_host     = "127.0.0.1";
	$mysql_username = "root";
	$mysql_password = "";
	$mysql_database = "test";
	
	$link = mysqli_connect($mysql_host, $mysql_username, $mysql_password, $mysql_database);

	if (!$link) {
		echo "Error: Unable to connect to MySQL." . PHP_EOL;
		echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
		echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
		exit;
	}
*/
	$sql = "SELECT * FROM log ORDER BY created desc LIMIT ".($num).PHP_EOL;
	
	$rows = db_query($sql);
	
	if($rows === false) {
	    $error = db_error();
	    print($error);
	} else {
	    
		  if (mysqli_num_rows($rows)>0) {

    			$arr=array();
    			$arr["data"]=array();
    			$num = 1;
    			
    			while ($row = mysqli_fetch_assoc($rows)){
    				$arr_item=array(
    					"number" => $num,
    					"id" => $row["id"],
    					"ip" => $row["ip"],
    					"time" => $row["created"],
    				);
    				array_push($arr["data"], $arr_item);
    				$num = $num + 1;
    				
    				$body=json_encode($arr);
    			}
			
		  } 
		  mysqli_free_result($rows);
		  return $body;
    }
}

?>