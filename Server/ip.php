<?php

header("Content-type: text/plain"); //data shall be proccessed as plain text


if(empty($_SERVER['REMOTE_ADDR'])) {  //checking if the ip address is passed or not
//if not just stop
} else {
    $ip=$_SERVER['REMOTE_ADDR'];//since the ip is not null we assign it to a varible

    if(empty($_GET["user"])) {//checking if the username is passed from the client or not
        //if the username isn't passed we only print the ip for now
        echo htmlspecialchars("Your IP Is: $ip");

    } else {
        //user is passed from the client
        $user=$_GET["user"];//assigning username to a variable
        echo htmlspecialchars("$user Your IP Is: $ip"); //ensuring not to render any passed html content
        
        //TODO: save the variables to database
        
    }
}


?>