<?php

include 'log.php';

//http://localhost/sandbox/record
//http://localhost/php-server/record.php?id=6


header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

//$param=array("id" => 11);
$param=parameters();

if (array_key_exists("id",$param)){
	$num =  $param["id"];
} else {
	$num = 1;
}

echo record($num);

function record($num)
{
	$sql = "SELECT * FROM log WHERE id=".($num).PHP_EOL;
	
	//$connection = db_connect();
	//$rows = mysqli_query($connection,$sql);
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
	            //$myname = "igork";
	            $arr_item=array(
	                //	"number" => $num,
	                "id" => $row["id"],
	                "ip" => $row["ip"],
	                "time" => $row["created"],
	                //"response" => $row["body"]
	            );
	            array_push($arr["data"], $arr_item);
	            $num = $num + 1;
	        }
	        $body=json_encode($arr);
	        
	        return $body;
	        
	    }
	    mysqli_free_result($rows);
	    
	}
}
function parameters(){
	$url = $_SERVER['REQUEST_URI'];
	$query_str = parse_url($url, PHP_URL_QUERY);
	parse_str($query_str, $query_params);
	//print_r($query_params);
	if (!empty($query_params)){
		return $query_params;
	} 
	return array();	
}

?>