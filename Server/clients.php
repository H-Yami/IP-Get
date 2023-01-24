<?php
session_start();
require "settings.php";
if (isset($_SESSION['id'])) {// checking if we have a session
   if($_SESSION['loggedin']){//if the user is logged in
    $con = new PDO('mysql:dbname='.$dbName.';host='.$serverName.';charset=utf8mb4', $dbUser, $dbPassword);//connecting to the database
    $con->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    if($con === false){
        die("ERROR: Could not connect to the database. " . mysqli_connect_error());
    }
    if($clients = $con->prepare('select ip, user , max( time ) from '.$ipTable.' group by ip , user ')){//getting the latest ip for each user
        $clients->execute(); //executing the prepared sql statement
        while($row=$clients->fetch(PDO::FETCH_ASSOC)){//going through each resulting row
        echo($row['user'].' | '.$row['ip']);
        }
    }

}else{//if the user isn't logged in, redirect to login
    header('Location: index.html');
	die();
}






}else{//if there is no session, redirect to login
    header('Location: index.html');
	die();
}


?>
