<?php
//
// http://localhost/api/logging.php
//

include "db.php";

function log_ip($param,$headers){
	
	$user_ip = getUserIP();

	$p = json_encode($param);
	
	$h = json_encode($headers);
	
	$sql = "INSERT INTO log (ip,param,headers) VALUES ('".($user_ip)."','".$p."','".$h."')".PHP_EOL;
	$rows = db_select($sql);
	if($rows === false) {
	    $error = db_error();
	    print($error);
	} else {
	    print_r($rows);
	}
	
}

function log_ip_r($param,$headers,$response){
    
    $user_ip = getUserIP();
    
    $p = json_encode($param);
    
    $h = json_encode($headers);
    
    $r = json_encode($response);
    
    $sql = "INSERT INTO log (ip,param,headers,response) VALUES ('".($user_ip)."','".$p."','".$h."','".$r."')".PHP_EOL;
    $rows = db_insert($sql);
    
    if($rows == 0) {
        $error = db_error();
        print($error);
    } else {
       return $rows;
    }
}

?>