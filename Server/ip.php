<?php

header("Content-type: text/plain"); //data shall be proccessed as plain text

require "settings.php"; //importing database credentials





//$settings = readfile("settings");
//echo ($settings);
if(empty($_SERVER['REMOTE_ADDR'])) {  //checking if the ip address is passed or not
//if not just stop
} else {
    $ip=$_SERVER['REMOTE_ADDR'];//since the ip is not null we assign it to a varible

    if(empty($_GET["user"])) {//checking if the username is passed from the client or not
        //if the username isn't passed we only print the ip for now
        echo htmlspecialchars("Your IP Is :$ip");//ensuring not to render any passed html content even though it shouldn't be possible

    } else {
        //user is passed from the client
        $user=$_GET["user"];//assigning username to a variable

        
        $con = new PDO('mysql:dbname='.$dbName.';host='.$serverName.';charset=utf8mb4', $dbUser, $dbPassword);//connecting to the database

        $con->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        if($con === false){

            die("ERROR: Could not connect to the database. " . mysqli_connect_error());
        
        }
        $prepStat = $con->prepare('INSERT INTO '.$tableName. ' (ip, user) VALUES (:ip, :user)');//preparing sql statement to lower chances of sql injection
        $prepStat->bindValue(':ip', $ip);//binding values to the prepared statement
        $prepStat->bindValue(':user', $user);
        $prepStat->execute(); //executing the prepared sql statement
        echo ("Success"); 
    }
}


?>