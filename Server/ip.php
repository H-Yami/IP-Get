<?php
header("Content-type: text/plain");

if(empty($_SERVER['REMOTE_ADDR'])) {

} else {
    $ip=$_SERVER['REMOTE_ADDR'];

    if(empty($_GET["user"])) {

        echo htmlspecialchars("Your IP Is: $ip");

    } else {

        $user=$_GET["user"];
        echo htmlspecialchars("$user Your IP Is: $ip");

    }
}


?>