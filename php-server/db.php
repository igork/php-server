<?php
//https://www.binpress.com/using-php-with-mysql/
function db_connect(){
    
    // Define connection as a static variable, to avoid connecting more than once
    static $connection;
    
    // Try and connect to the database, if a connection has not been established yet
    if(!isset($connection)) {
        $config = parse_ini_file('../../secure/config.ini');
        // Try and connect to the database
        $connection = mysqli_connect($config['host'],$config['username'],$config['password'],$config['dbname']);
    }
    
    // If connection was not successful, handle the error
    if($connection === false) {
        // Handle error - notify administrator, log to a file, show an error screen, etc.
        return mysqli_connect_error();
    }
    return $connection;
}


function db_error() {
    $connection = db_connect();
    return mysqli_error($connection);
}

//return boolean
function db_query($query) {
    // Connect to the database
    $connection = db_connect();
    
    // Query the database
    $result = mysqli_query($connection,$query);
    
    return $result;
}


function db_select($query) {
    $rows = array();
    $result = db_query($query);
    
    // If query failed, return `false`
    if($result === false) {
        return false;
    }
    
    // If query was successful, retrieve all the rows into an array
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    return $rows;
}

function db_insert($query) {
    //$rows = array();
    // Connect to the database
    $connection = db_connect();
    
    // Query the database
    $result = mysqli_query($connection,$query);
    
    // If query failed, return `false`
    if($result === false) {
        return false;
    }
    $last_id = mysqli_insert_id($connection);
    // If query was successful, retrieve all the rows into an array
    //while ($row = mysqli_fetch_assoc($result)) {
    //    $rows[] = $row;
    //}
    return $last_id;
}
?>