<?php

//http://localhost/php-server/one.php

/*
 GET
 http://localhost/api/one.php?ig=kr&kr=ig
 custom_header = custom_value
 
 output:
 {
 	"data": [
 		{
 			"name": "igork1",
			"number": 1
 		},
 		{
 			"name": "igork2",
 			"number": 2
		},
 		{
 			"name": "igork3",
 			"number": 3
 		}
 	],
 	"remote_addr": "::1",
 	"date": "Tuesday, September 18 18 07:30:33",
 	"request_headers": {
 		"custom_header": "custom_value",
 		"cache-control": "no-cache",
 		"Postman-Token": "57dfa68e-b62e-49fe-8aa8-90cc6c0cc5bd",
 		"User-Agent": "PostmanRuntime/7.2.0",
 		"Accept": "*//*",
 		"Host": "localhost",
 		"accept-encoding": "gzip, deflate",
 		"Connection": "keep-alive"
 	},
 	"method": "GET",
 	"params": {
 		"ig": "kr",
 		"kr": "ig"
 	}
 }
 
 */

include 'log.php';
include 'lib.php';

	//body
	$arr = response();
	$body=json_encode($arr);

	//params
	$url = $_SERVER['REQUEST_URI'];
	$query_str = parse_url($url, PHP_URL_QUERY);
	parse_str($query_str, $query_params);
	if (!empty($query_params)){
		$arr["params"] = $query_params;
	}

	$id = log_ip_r($query_params,apache_request_headers(),$body);

	$arr["id"] = $id;
	$body=json_encode($arr);

	echo $body;



?>