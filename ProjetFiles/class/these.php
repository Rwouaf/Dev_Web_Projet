<?php
require_once("../PHP/connexion.php");
class these implements JsonSerializable
{
    private $auteur;
    private $id_auteur;
    private $titre;
    private $these_directeur;
    private $id_directeur;
    private $etab_soutenance;
    private $id_etab;
    private $dicipline;
    private $date_inscription;
    private $date_soutenance;
    private $lang_these;
    private $id_these;
    private $onLigne;
    private $publication;
    private $miseAjour;

    public function jsonSerialize() {
        return (object) get_object_vars($this);
    }

    /**
     * @param $auteur
     * @param $id_auteur
     * @param $titre
     * @param $these_directeur
     * @param $id_directeur
     * @param $etab_soutenance
     * @param $id_etab
     * @param $dicipline
     * @param $date_inscription
     * @param $date_soutenance
     * @param $lang_these
     * @param $id_these
     * @param $onLigne
     * @param $publication
     * @param $miseAjour
     */
    public function __construct($auteur, $id_auteur, $titre, $these_directeur, $id_directeur, $etab_soutenance, $id_etab, $dicipline, $date_inscription, $date_soutenance, $lang_these, $id_these, $onLigne, $publication, $miseAjour)
    {
        $this->auteur = $auteur;
        $this->id_auteur = $id_auteur;
        $this->titre = $titre;
        $this->these_directeur = $these_directeur;
        $this->id_directeur = $id_directeur;
        $this->etab_soutenance = $etab_soutenance;
        $this->id_etab = $id_etab;
        $this->dicipline = $dicipline;
        $this->date_inscription = $date_inscription;
        $this->date_soutenance = $date_soutenance;
        $this->lang_these = $lang_these;
        $this->id_these = $id_these;
        $this->onLigne = $onLigne;
        $this->publication = $publication;
        $this->miseAjour = $miseAjour;
    }

    public function load() {
        $cnx = new connexion();
        $db = $cnx ->getCnx();
        $id = $this->getIdThese();

        $requete = $db->prepare('SELECT * FROM these where id_these = :id LIMIT 1');
        $requete->bindParam('id',$this->id_these,PDO::PARAM_STR,20);
        $requete->execute();

        $these = $requete->fetch();
        $this->setAuteur($these['auteur']);
        $this->setIdAuteur($these['id_auteur']);
        $this->setTitre($these['titre']);
        $this->setTheseDirecteur($these['these_directeur']);
        $this->setIdDirecteur($these['id_directeur']);
        $this->setEtabSoutenance($these['etab_soutenance']);
        $this->setIdEtab($these['id_etab']);
        $this->setDicipline($these['dicipline']);
        $this->setDateInscription($these['date_inscription']);
        $this->setDateSoutenance($these['date_soutenance']);
        $this->setLangThese($these['lang_these']);
        $this->setOnLigne($these['onLigne']);
        $this->setPublication($these['publication']);
        $this->setMiseAjour($these['miseAjour']);

    }


    /**
     * @return mixed
     */
    public function getAuteur()
    {
        return $this->auteur;
    }

    /**
     * @param mixed $auteur
     */
    public function setAuteur($auteur)
    {
        $this->auteur = $auteur;
    }

    /**
     * @return mixed
     */
    public function getIdAuteur()
    {
        return $this->id_auteur;
    }

    /**
     * @param mixed $id_auteur
     */
    public function setIdAuteur($id_auteur)
    {
        $this->id_auteur = $id_auteur;
    }

    /**
     * @return mixed
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * @param mixed $titre
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;
    }

    /**
     * @return mixed
     */
    public function getTheseDirecteur()
    {
        return $this->these_directeur;
    }

    /**
     * @param mixed $these_directeur
     */
    public function setTheseDirecteur($these_directeur)
    {
        $this->these_directeur = $these_directeur;
    }

    /**
     * @return mixed
     */
    public function getIdDirecteur()
    {
        return $this->id_directeur;
    }

    /**
     * @param mixed $id_directeur
     */
    public function setIdDirecteur($id_directeur)
    {
        $this->id_directeur = $id_directeur;
    }

    /**
     * @return mixed
     */
    public function getEtabSoutenance()
    {
        return $this->etab_soutenance;
    }

    /**
     * @param mixed $etab_soutenance
     */
    public function setEtabSoutenance($etab_soutenance)
    {
        $this->etab_soutenance = $etab_soutenance;
    }

    /**
     * @return mixed
     */
    public function getIdEtab()
    {
        return $this->id_etab;
    }

    /**
     * @param mixed $id_etab
     */
    public function setIdEtab($id_etab)
    {
        $this->id_etab = $id_etab;
    }

    /**
     * @return mixed
     */
    public function getDicipline()
    {
        return $this->dicipline;
    }

    /**
     * @param mixed $dicipline
     */
    public function setDicipline($dicipline)
    {
        $this->dicipline = $dicipline;
    }

    /**
     * @return mixed
     */
    public function getDateInscription()
    {
        return $this->date_inscription;
    }

    /**
     * @param mixed $date_inscription
     */
    public function setDateInscription($date_inscription)
    {
        $this->date_inscription = $date_inscription;
    }

    /**
     * @return mixed
     */
    public function getDateSoutenance()
    {
        return $this->date_soutenance;
    }

    /**
     * @param mixed $date_soutenance
     */
    public function setDateSoutenance($date_soutenance)
    {
        $this->date_soutenance = $date_soutenance;
    }

    /**
     * @return mixed
     */
    public function getLangThese()
    {
        return $this->lang_these;
    }

    /**
     * @param mixed $lang_these
     */
    public function setLangThese($lang_these)
    {
        $this->lang_these = $lang_these;
    }

    /**
     * @return mixed
     */
    public function getIdThese()
    {
        return $this->id_these;
    }

    /**
     * @param mixed $id_these
     */
    public function setIdThese($id_these)
    {
        $this->id_these = $id_these;
    }

    /**
     * @return mixed
     */
    public function getOnLigne()
    {
        if ($this->onLigne == "oui"){
            return 1;
        }
        else{
            return 0;
        }
    }

    /**
     * @param mixed $onLigne
     */
    public function setOnLigne($onLigne)
    {
        $this->onLigne = $onLigne;
    }

    /**
     * @return mixed
     */
    public function getPublication()
    {
        return $this->publication;
    }

    /**
     * @param mixed $publication
     */
    public function setPublication($publication)
    {
        $this->publication = $publication;
    }

    /**
     * @return null
     */
    public function getMiseAjour()
    {
        return $this->miseAjour;
    }

    /**
     * @param null $miseAjour
     */
    public function setMiseAjour($miseAjour)
    {
        $this->miseAjour = $miseAjour;
    }

    public function affichage(){
        echo $this->getAuteur()." ".$this->getIdAuteur()." ".$this->getTitre()." ".$this->getTheseDirecteur()." ".$this->getIdDirecteur()." ".$this->getEtabSoutenance()." ".$this->getIdEtab()." ".$this->getDicipline()." ".$this->getStatut()." ".$this->getDateInscription()." ".$this->getDateSoutenance()." ".$this->getLangThese()." ".$this->getIdThese()." ".$this->getOnLigne()." ".$this->getPublication()." ".$this->getMiseAjour()."<br>"."<br>";
    }


    public function save(){
        $cnx = new connexion();
        $db = $cnx ->getCnx();
        $requete = $db->prepare("INSERT INTO these VALUES (:auteur,:id_auteur,:titre,
                                   :these_directeur,:id_directeur,:etab_soutenance, :id_etab,:dicipline ,
                                   :date_incrisption,:date_soutenance,:lang_these,:id_these,:onLigne,:publication,
                                   :miseAjour)");
        $requete->bindParam('auteur',$this->auteur);
        $requete->bindParam('id_auteur',$this->id_auteur);
        $requete->bindParam('titre',$this->titre);
        $requete->bindParam('these_directeur',$this->these_directeur);
        $requete->bindParam('id_directeur',$this->id_directeur);
        $requete->bindParam('etab_soutenance',$this->etab_soutenance);
        $requete->bindParam('id_etab',$this->id_etab);
        $requete->bindParam('dicipline',$this->dicipline);
        $requete->bindParam('date_incrisption',$this->date_inscription);
        $requete->bindParam('date_soutenance',$this->date_soutenance);
        $requete->bindParam('lang_these',$this->lang_these);
        $requete->bindParam('id_these',$this->id_these);
        $requete->bindParam('onLigne',$this->onLigne);
        $requete->bindParam('publication',$this->publication);
        $requete->bindParam('miseAjour',$this->miseAjour);

        $requete->execute();
    }

}