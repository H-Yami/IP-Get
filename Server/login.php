<?php
require "settings.php"; //getting the database credintials
if(empty($_POST['user'])) {  //checking if the username is passed or not
    //if not just stop
    die("UserName Is Empty");
    } else {
        $user=$_POST['user'];//since the user is not null we assign it to a varible
        if(empty($_POST['password'])) {//checking if the password is passed
            //if the password isn't passed we stop
            die("Password is Empty");
        } else {
            //password is passed
            $password = $_POST['password']; //assigning password to a variable
            $hashed_password = password_hash($password, PASSWORD_DEFAULT); //hashing the password
            $con = new PDO('mysql:dbname='.$dbName.';host='.$serverName.';charset=utf8mb4', $dbUser, $dbPassword);//connecting to the database
            $con->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            if($con === false){
                die("ERROR: Could not connect to the database. " . mysqli_connect_error());
            }
            $getUsers = $con->prepare("SELECT `id` FROM `login` WHERE 1");//checking if there are any users
            $getUsers->execute();
            $count = $getUsers->rowCount();//returns number of columns which should translate to users
            $getUsers=null;
            if($count===0){//if there are no users
                //we create a user from the form input
                $registerStat = $con->prepare('INSERT INTO '.$loginTable. ' (id, user, password) VALUES (:id, :user, :password)');//preparing sql statement to lower chances of sql injection
                $registerStat->bindValue(':id', NULL); //your database should have id as not null , unique , and auto increment, so it should take care of assigning to correct id
                $registerStat->bindValue(':user', $user);//binding values to the prepared statement
                $registerStat->bindValue(':password', $hashed_password);//storing the hashed password
                $registerStat->execute(); //executing the prepared sql statement
                $registerStat=null; //destroying the object
            }
            //checking the login credintials
            if($loginStat = $con->prepare('SELECT id, password FROM ' .$loginTable. ' WHERE user = :user')){//getting the hased password and id for the selected user
            $loginStat->bindparam(':user', $user);//binding the username to the prepared statement
            $loginStat->execute(); //executing the prepared sql statement
            //$loginStat->store_result();
            $userId='';
            $userPassword='';
            if ($loginStat->rowCount() > 0) {
                //user exists
                $userAuth=$loginStat->fetch(PDO::FETCH_ASSOC); //fetching the resulting row , returns an array indexed by column name.
                $userId=$userAuth['id']; //user id and hashed password fetched from the database.
                $userPassword=$userAuth['password'];
                if(password_verify($password,$userPassword)){ //if the password in the form matches the returned password from the db.
                    session_start();//starting a session , user is logged in
                    session_regenerate_id(); //generating a session id , using regenerate id to help prevent session hijacking attacks
                    $_SESSION['loggedin'] = TRUE;
                    $_SESSION['name'] = $user;
                    $_SESSION['id'] = $userId;
                    header('Location: clients.php');
                    die();
                }else{
                 //incorrect password
                 echo("username or password is incorrect") ;
                }
            }else{
                //incorrect username
                echo("username or password is incorrect");
            }
            //last line of user and password exsist
        }
    }
}
?>
