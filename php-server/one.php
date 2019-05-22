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
// Script start
//$rustart = getrusage();
$time_start = microtime_float();								

// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

//https://docstore.mik.ua/orelly/webprog/php/ch07_05.htm
//To expire a document three hours from the time the page was generated, 
//use time( ) and gmstrftime( ) to generate the expiration date string: 
//$now = time( );
//$then = gmstrftime("%a, %d %b %Y %H:%M:%S GMT", $now + 60*60*3);
//header("Expires: $then");													
//redirect
// header("Location:index.php");

// products array
$arr=array();
$arr["data"]=array();
//This means that if you are going to save the
//$_SERVER['HTTP_X_FORWARDED_FOR'], make sure you also save the
//$_SERVER['REMOTE_ADDR'] value.
//max value 45 symbols
if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $arr["http_client_ip"]=$_SERVER['HTTP_CLIENT_IP'];
}
if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $arr["http_x_forwarded"] = $_SERVER['HTTP_X_FORWARDED_FOR'];
}
if (!empty($_SERVER['REMOTE_ADDR'])) {
    $arr["remote_addr"] = $_SERVER['REMOTE_ADDR'];
}
// returns Saturday, January 30 10 02:06:34.123456
//$arr["date_started"]=date('l, F d y h:i:s.u');

// returns Saturday, January 30 10 02:06:34.123
$arr["date_started"]=millitime('l, F d y h:i:s.u');

$arr["request_headers"] = apache_request_headers();
$arr["method"] = $_SERVER['REQUEST_METHOD'];

$query_params="";
//$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"
$url = $_SERVER['REQUEST_URI'];
$query_str = parse_url($url, PHP_URL_QUERY);
parse_str($query_str, $query_params);
//print_r($query_params);
if (!empty($query_params)){
    $arr["params"] = $query_params;
}

$num = 1;
while ($num<4){
    $myname = "igork";
    $arr_item=array(
        "name" => $myname.(string)$num,
        "number" => $num,
    );
    array_push($arr["data"], $arr_item);
    $num = $num + 1;
}

sleep(5);

//
//time_nanosleep() 	Delays code execution for a number of seconds and nanoseconds
//time_nanosleep(3,500000000) 3,5sec
//
//time_sleep_until() 	Delays code execution until a specified time
//// wake up ten seconds from now
//time_sleep_until(time()+10);
//

//shows 
//$arr["date_completed"]=date('l, F d y h:i:s.u');
$arr["date_completed"]=millitime('l, F d y h:i:s.u');



//if(!$_SERVER['SERVER_ADDR']){ $_SERVER['SERVER_ADDR'] = $_SERVER['LOCAL_ADDR']; }
$arr["server_addr"] = $_SERVER['SERVER_ADDR'];
$arr["remote_ip"] = getUserIP();
$arr["server_ipecho"] = file_get_contents("http://ipecho.net/plain");

//Script end
//1
//$ru = getrusage();
//$arr["computations_ms"]=rutime($ru, $rustart, "utime");
//$arr["system_calls_ms"]=rutime($ru, $rustart, "stime");

//2
$diffr = microtime_float() - $time_start;
$diffr = $diffr*1000.0;
$arr["elapsed_ms"] = round($diffr,0);

$body=json_encode($arr);

$id = log_ip_r($query_params,apache_request_headers(),$body);

$arr["id"] = $id;
$body=json_encode($arr);

echo $body;

function millitime($format){
    //Just trim off the last two characters:
    return substr(date($format), 0, -3);
}

function microtime_float()
{
    //list($usec, $sec) = explode(" ", microtime());
    //return ((float)$usec + (float)$sec);
	return microtime(true);
}

function rutime($ru, $rus, $index) {
    return ($ru["ru_$index.tv_sec"]*1000 + intval($ru["ru_$index.tv_usec"]/1000))
     -  ($rus["ru_$index.tv_sec"]*1000 + intval($rus["ru_$index.tv_usec"]/1000));
}
// Method: POST, PUT, GET etc
// Data: array("param" => "value") ==> index.php?param=value
function CallAPI($method, $url, $data = false)
{
    $curl = curl_init();
    
    switch ($method)
    {
        case "POST":
            curl_setopt($curl, CURLOPT_POST, 1);
            
            if ($data)
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
        case "PUT":
            curl_setopt($curl, CURLOPT_PUT, 1);
            break;
        default:
            if ($data)
                $url = sprintf("%s?%s", $url, http_build_query($data));
    }
    
    // Optional Authentication:
    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($curl, CURLOPT_USERPWD, "username:password");
    
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    
    $result = curl_exec($curl);
    
    curl_close($curl);
    
    return $result;
}

function getUserIP()
{
    // Get real visitor IP behind CloudFlare network
    if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
        $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
        $_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
    }
    $client  = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote  = $_SERVER['REMOTE_ADDR'];
    
    if(filter_var($client, FILTER_VALIDATE_IP))
    {
        $ip = $client;
    }
    elseif(filter_var($forward, FILTER_VALIDATE_IP))
    {
        $ip = $forward;
    }
    else
    {
        $ip = $remote;
    }
    
    return $ip;
}
?>