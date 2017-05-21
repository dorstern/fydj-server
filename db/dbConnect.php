<?php
    global $pdo;
    
    $user = 'root';
    $pass = 'root';
    $db = 'fydj';
    $host = 'localhost';
    $port = '8889';

    try {
         $pdo = new PDO('mysql:host='.$host.';dbname='.$db.';port='.$port, $user, $pass);
    } catch (PDOException $e) {
        echo 'Connection failed: ' . $e->getMessage();
    }

?>