<?php

include 'db.php';

//test2();

test();

test3();

function test(){
    $config = parse_ini_file("../../secure/config.ini");
    
    if (isset($config)){
        print_r($config['username']);
        print_r($config['password']);
        print_r($config['dbname']);
    } else {
        echo "cannot find";
    }
    
}

function test2(){
    define('BIRD', 'Dodo bird');
    
    // Parse without sections
    $ini_array = parse_ini_file("../../secure/sample8.ini");
    print_r($ini_array);
    
    echo "   ".PHP_EOL;
    
    // Parse with sections
    $ini_array = parse_ini_file("sample.ini", true);
    print_r($ini_array);
}

function test3(){
    $rows = db_select("SELECT * FROM `log` WHERE id>5");
    //$rows = db_select("SELECT `name`,`email` FROM `users` WHERE id=5");
    if($rows === false) {
        $error = db_error();
        print($error);
    } else {
        print_r($rows);
    }
    
}

?>