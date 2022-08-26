<?php
$password = "1234";
$typedPassword = "123";
$md5Password = md5($password);
echo $md5Password;
echo "<br>";
$hashedPassword = password_hash($password, PASSWORD_BCRYPT);
echo $hashedPassword;
echo "<br>";
$bool = password_verify($typedPassword, $hashedPassword);
var_dump($bool);

function connect($host, $dbname, $login, $password){
    return new PDO("mysql:host=".$host.";dbname=".$dbname, $login, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
}

try{
    $database = connect("localhost", "hallucine", "root", "");
}catch(Exception $error){
    echo "Erreur de connexion à la BDD.<br>";
    die("ERROR: ".$error->getMessage());
}

$sql = "SELECT * FROM `users`;";
$request = $database->prepare($sql);
$request->execute();
$rows = $request->fetchAll(PDO::FETCH_ASSOC);
$request->closeCursor();

foreach ($rows as $key => $value) {
    $database = connect("localhost", "hallucine", "root", "");
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    $sql = "UPDATE `users` SET `password` = '$hashedPassword'";
    $request = $database->prepare($sql);
    $request->execute();
    $request->closeCursor();
}
?>