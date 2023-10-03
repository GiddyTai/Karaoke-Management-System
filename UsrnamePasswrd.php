<?php  
$username = "z1936409"; 

$password = "1998Jul20"; 

$server = "courses"; 

$db = "z1936409"; 

try { // if something goes wrong, an exception is thrown 

$dsn = "mysql:host=$server;dbname=$db"; 

$pdo = new PDO($dsn, $username, $password); 

$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION); 

} 

catch(PDOexception $e) { // handle that exception 

echo "Connection to database failed: " . $e->getMessage(); 

} 
?> 