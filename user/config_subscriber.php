<?php
//session_start();
ob_start();
$servername = "localhost"; // change if required
$username = "root";
$password = "Bang2earth!";
$dbname = "textimz_subscriber_database";

try {
    $db = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // set the PDO error mode to exception
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //echo "Connected successfully"; 
    }
catch(PDOException $e)
    {
    echo "Database Connection failed because : " . $e->getMessage();
    }
	
	
	
?>
