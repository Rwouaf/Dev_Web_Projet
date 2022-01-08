<?php
require_once("connexion.php");
require_once("../class/these.php");
//require_once("../class/Visits_Model_Visit.php");

$cnx = new connexion();
$db = $cnx ->getCnx();
$articles = $db->query('SELECT id_these FROM `these` LIMIT 10');
$articles = $db->prepare('SELECT id_these FROM `these`');
if(isset($_GET['q']) AND !empty($_GET['q'])) {
    $keyWord = "%".$_GET['q']."%";
    $articles = $db->prepare('SELECT id_these FROM these WHERE titre LIKE :keyWord ORDER BY auteur DESC');
    $articles->bindParam('keyWord',$keyWord,PDO::PARAM_STR,500);
    $articles->execute();
    if($articles->rowCount() == 0) {
        $articles = $db->prepare('SELECT id_these FROM these WHERE auteur LIKE :keyWord ORDER BY auteur DESC');
        $articles->bindParam('keyWord',$keyWord,PDO::PARAM_STR,500);
        $articles->execute();
    }
}
$tab = array();
while($resultat = $articles->fetch()) {
    $id = $resultat['id_these'];
    $reflection = new ReflectionClass("these");
    $these = $reflection->newInstanceWithoutConstructor();
    $these->setIdThese($id);
    /* @var $these these */
    $these->load();
    $tab[] = $these;
}

echo "<pre>".json_encode($tab,JSON_PRETTY_PRINT)."</pre>";


