<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function connectDB()
{
    $host = 'db'; // Docker service name
    $dbname = 'e_commerce'; // Matches docker-compose.yml and SQL file
    $username = 'ecommerceuser'; // Matches docker-compose.yml
    $password = 'ecommercepass'; // Matches docker-compose.yml

    try {
        $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch (PDOException $e) {
        die("Échec de la connexion : " . $e->getMessage());
    }
}

function closeDB($conn){ 
    $conn=null;
}


