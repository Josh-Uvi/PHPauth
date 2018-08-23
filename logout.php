<?php 
include_once("res/session.php");
include_once("res/functions.php");

session_destroy();
redirectTo('index');


?>