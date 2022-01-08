<?php
class connexion
{
    /**
     * @return mixed
    **/
    public function getCnx()
    {
        return new PDO('mysql:host=sqletud.u-pem.fr;dbname=direzdubois_db','direzdubois','77r2d277', array(
        PDO::ATTR_PERSISTENT => true
        ));
    }
}

