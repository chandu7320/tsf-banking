<?php
$dbhost = "localhost";
$dbname = "tsf-netbanking";
$dbusername = "root";
$dbpassword = "";
$charset = "utf8mb4";

$dns = "mysql: host=$dbhost;dbname=$dbname;charset=$charset";


try {
    $pdo = new PDO($dns, $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE,  PDO::ERRMODE_EXCEPTION);
} catch (PDOException $th) {
    echo $th->getMessage();
}
