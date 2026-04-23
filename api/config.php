<?php
$host = 'localhost';
$dbname = 'L3_geom';
$dbuser = 'postgres';
$dbpass = 'kd776475461';

try{
    $conn = new PDO("pgsql:dbname=$dbname;host=$host", $dbuser, $dbpass);
}
catch (PDOException $e) { echo "Erreur : " . $e->getMessage();}
?>