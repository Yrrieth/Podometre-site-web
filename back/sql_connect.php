<?php
    $servername = 'localhost';
    $username = 'root';
    $password = '';
    $dbname = 'steps_database';

    /*try {
    	$bdd = new PDO('mysql:host=localhost;dbname=stepsdatabase;charset=utf8', 'root', '');
    } catch (Exception $e) {
    	die('Connection failed : ') . $e->getMessage();
    }*/
    $connection = mysqli_connect($servername, $username, $password, $dbname);

    if (!$connection) {
        die("Connection failed : " . mysqli_connect_error());
    }
?>