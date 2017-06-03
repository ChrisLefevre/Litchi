<?php
try{
    $pdo = new PDO('sqlite:'.dirname(__FILE__).'/database.sqlite');
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // ERRMODE_WARNING | ERRMODE_EXCEPTION | ERRMODE_SILENT
} catch(Exception $e) {
    echo "Impossible d'accéder à la base de données SQLite : ".$e->getMessage();
    die();
}
?>

<?php
$pdo->query("CREATE TABLE IF NOT EXISTS posts ( 
    id            INTEGER         PRIMARY KEY AUTOINCREMENT,
    titre         VARCHAR( 250 ),
    created       DATETIME
);");
?>

<?php
$stmt = $pdo->prepare("INSERT INTO posts (titre, created) VALUES (:titre, :created)");
$result = $stmt->execute(array(
    'titre'         => "Lorem ipsum",
    'created'       => date("Y-m-d H:i:s")
));
?>