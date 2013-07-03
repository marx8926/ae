<?php
$host = "localhost"; 
$user = "postgres"; 
$pass = "260489"; 
$db = "AE_DB_T"; 

$name = $_GET["name"];
$uploadName = date("Y-m-d-H-i-s").$name;
$url = "uploads/".$uploadName;

$con = pg_connect("host=$host dbname=$db user=$user password=$pass")
    or die ("Could not connect to server\n"); 

$query = "INSERT INTO archivo (direccion,nombre) values('".$uploadName."','".$url."')"; 
$rs = pg_query($con, $query) or die("Cannot execute query: $query\n");
pg_close($con); 
?>
