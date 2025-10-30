<?php

namespace model;

class Fournisseur
{
    private $idFournisseur ;

    private $cp ;
    private $adresse ;
    private $email ;
    private $siteWeb ;
    private $mdp ;
    private $numTelephone ;
    private $numMobile ;
    private $entreprise ;
    private $devise ;

    public function __construct(array $donnees)
    {
        $this->hydrate($donnees);
    }

    private function hydrate(array $donnees): void
    {
        foreach ($donnees as $key => $value) {

            $method = 'set'.ucfirst($key);
            if (method_exists($this, $method)) {

                $this->$method($value);
            }
        }
    }

    /**
     * @return mixed
     */
    public function getIdFournisseur()
    {
        return $this->idFournisseur;
    }

    /**
     * @param mixed $idFournisseur
     */
    public function setIdFournisseur($idFournisseur): void
    {
        $this->idFournisseur = $idFournisseur;
    }


    /**
     * @return mixed
     */
    public function getCp()
    {
        return $this->cp;
    }

    /**
     * @param mixed $cp
     */
    public function setCp($cp): void
    {
        $this->cp = $cp;
    }

    /**
     * @return mixed
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * @param mixed $adresse
     */
    public function setAdresse($adresse): void
    {
        $this->adresse = $adresse;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getSiteWeb()
    {
        return $this->siteWeb;
    }

    /**
     * @param mixed $siteWeb
     */
    public function setSiteWeb($siteWeb): void
    {
        $this->siteWeb = $siteWeb;
    }

    /**
     * @return mixed
     */
    public function getMdp()
    {
        return $this->mdp;
    }

    /**
     * @param mixed $mdp
     */
    public function setMdp($mdp): void
    {
        $this->mdp = $mdp;
    }

    /**
     * @return mixed
     */
    public function getNumTelephone()
    {
        return $this->numTelephone;
    }

    /**
     * @param mixed $numTelephone
     */
    public function setNumTelephone($numTelephone): void
    {
        $this->numTelephone = $numTelephone;
    }

    /**
     * @return mixed
     */
    public function getNumMobile()
    {
        return $this->numMobile;
    }

    /**
     * @param mixed $numMobile
     */
    public function setNumMobile($numMobile): void
    {
        $this->numMobile = $numMobile;
    }

    /**
     * @return mixed
     */
    public function getEntreprise()
    {
        return $this->entreprise;
    }

    /**
     * @param mixed $entreprise
     */
    public function setEntreprise($entreprise): void
    {
        $this->entreprise = $entreprise;
    }

    /**
     * @return mixed
     */
    public function getDevise()
    {
        return $this->devise;
    }

    /**
     * @param mixed $devise
     */
    public function setDevise($devise): void
    {
        $this->devise = $devise;
    }
}