<?php 

//setting connection variables 
$username = "";
$dsn = "mysql:host=; dbname=";
$password = "";

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
