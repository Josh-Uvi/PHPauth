<?php 

//setting connection variables 
$username = "root";
$dsn = "mysql:host=localhost; dbname=auth";
$password = "Mypass07";

//creating connection to the database
try{
    //creating a PDO instance
    $db = new PDO($dsn, $username, $password);

    //setting PDO error message
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //dsiplay success message
    // echo "Connected successfully to database";

} catch(PDOException $ex) {
    //display error message
    echo "Sorry connection failed, try again".$ex->getMessage();
}

?>