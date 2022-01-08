<?php
    require_once("../PHP/connexion.php");
    $cnx = new connexion();
    $db = $cnx ->getCnx();
    $data = json_decode(file_get_contents('../files/data.geojson'), true);
    for ($i = 0; $i < count($data['features']); $i++) {
        $et_data = $data['features'][$i];
        if (isset($et_data['properties']["identifiant_idref"])){
            $IdEtablissement = $et_data['properties']["identifiant_idref"];
            $reg_id = $et_data['properties']["reg_id"];
            $insert = $db->prepare('INSERT INTO localisation VALUES(:IdEtablissement, :reg_id)');
            $insert->bindParam('IdEtablissement',$IdEtablissement);
            $insert->bindParam('reg_id',$reg_id);
            $insert->execute();
            echo $IdEtablissement.' '.$reg_id.'<br>';
        }
    }
