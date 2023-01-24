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
    //beaware what follows is a not so pretty echoing of html
    //if you have a better idea PLEASE tell me
    echo("<html>
    <head>
    <style>
    body {
        background-color: #131516;
        position: relative;
      }
    td {
  color: lightgrey; border: 2px solid grey; border-radius: 10px; padding: 6px;
    }
    th{
  color: white; border: 2px solid white; border-radius: 10px; padding: 6px;
    }
    </style>
    </head>
    <body>
    <table style='margin-left: auto; margin-right: auto;'>
  <tr>
  <center><th>User</th></center>
  <center><th>IP</th></center>
  </tr>
  ");
    if($clients = $con->prepare('select ip, user , max( time ) from '.$ipTable.' group by ip , user ')){//getting the latest ip for each user
        $clients->execute(); //executing the prepared sql statement
        while($row=$clients->fetch(PDO::FETCH_ASSOC)){//going through each resulting row
        echo("<tr>");
        echo("<td><center>".htmlspecialchars($row['user'])."</center></td>");
        echo("<td><center>".htmlspecialchars($row['ip'])."</center></td>");
        echo("</tr>");
        }
    }
    echo("</table> </body></html>");
}else{//if the user isn't logged in, redirect to login
    header('Location: index.html');
	die();
}

}else{//if there is no session, redirect to login
    header('Location: index.html');
	die();
}


?>
