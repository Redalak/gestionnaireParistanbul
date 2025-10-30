<?php

namespace model;

class Commande
{
    private $idCommande ;
    private $adresseFacturation ;
    private $refFournisseur ;
    private $refProduit ;
    private $refMagasin ;
    private $refUtilisateur ;
    private $dateCommande ;
    private $dateArrivee ;
    private $quantite ;
    private $totalHT ;
    private $tva ;
    private $totalTTC ;
    private $remise ;
    private $dateReglement ;
    private $quantiteTotale;
    private $modeReglement ;
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
    public function getIdCommande()
    {
        return $this->idCommande;
    }

    /**
     * @param mixed $idCommande
     */
    public function setIdCommande($idCommande): void
    {
        $this->idCommande = $idCommande;
    }

    /**
     * @return mixed
     */
    public function getAdresseFacturation()
    {
        return $this->adresseFacturation;
    }

    /**
     * @param mixed $adresseFacturation
     */
    public function setAdresseFacturation($adresseFacturation): void
    {
        $this->adresseFacturation = $adresseFacturation;
    }

    /**
     * @return mixed
     */
    public function getRefFournisseur()
    {
        return $this->refFournisseur;
    }

    /**
     * @param mixed $refFournisseur
     */
    public function setRefFournisseur($refFournisseur): void
    {
        $this->refFournisseur = $refFournisseur;
    }

    /**
     * @return mixed
     */
    public function getRefProduit()
    {
        return $this->refProduit;
    }

    /**
     * @param mixed $refProduit
     */
    public function setRefProduit($refProduit): void
    {
        $this->refProduit = $refProduit;
    }

    /**
     * @return mixed
     */
    public function getRefMagasin()
    {
        return $this->refMagasin;
    }

    /**
     * @param mixed $refMagasin
     */
    public function setRefMagasin($refMagasin): void
    {
        $this->refMagasin = $refMagasin;
    }

    /**
     * @return mixed
     */
    public function getRefUtilisateur()
    {
        return $this->refUtilisateur;
    }

    /**
     * @param mixed $refUtilisateur
     */
    public function setRefUtilisateur($refUtilisateur): void
    {
        $this->refUtilisateur = $refUtilisateur;
    }

    /**
     * @return mixed
     */
    public function getDateCommande()
    {
        return $this->dateCommande;
    }

    /**
     * @param mixed $dateCommande
     */
    public function setDateCommande($dateCommande): void
    {
        $this->dateCommande = $dateCommande;
    }

    /**
     * @return mixed
     */
    public function getDateArrivee()
    {
        return $this->dateArrivee;
    }

    /**
     * @param mixed $dateArrivee
     */
    public function setDateArrivee($dateArrivee): void
    {
        $this->dateArrivee = $dateArrivee;
    }

    /**
     * @return mixed
     */
    public function getQuantite()
    {
        return $this->quantite;
    }

    /**
     * @param mixed $quantite
     */
    public function setQuantite($quantite): void
    {
        $this->quantite = $quantite;
    }

    /**
     * @return mixed
     */
    public function getTotalHT()
    {
        return $this->totalHT;
    }

    /**
     * @param mixed $totalHT
     */
    public function setTotalHT($totalHT): void
    {
        $this->totalHT = $totalHT;
    }

    /**
     * @return mixed
     */
    public function getTva()
    {
        return $this->tva;
    }

    /**
     * @param mixed $tva
     */
    public function setTva($tva): void
    {
        $this->tva = $tva;
    }

    /**
     * @return mixed
     */
    public function getTotalTTC()
    {
        return $this->totalTTC;
    }

    /**
     * @param mixed $totalTTC
     */
    public function setTotalTTC($totalTTC): void
    {
        $this->totalTTC = $totalTTC;
    }

    /**
     * @return mixed
     */
    public function getRemise()
    {
        return $this->remise;
    }

    /**
     * @param mixed $remise
     */
    public function setRemise($remise): void
    {
        $this->remise = $remise;
    }

    /**
     * @return mixed
     */
    public function getDateReglement()
    {
        return $this->dateReglement;
    }

    /**
     * @param mixed $dateReglement
     */
    public function setDateReglement($dateReglement): void
    {
        $this->dateReglement = $dateReglement;
    }

    /**
     * @return mixed
     */
    public function getQuantiteTotale()
    {
        return $this->quantiteTotale;
    }

    /**
     * @param mixed $quantiteTotale
     */
    public function setQuantiteTotale($quantiteTotale): void
    {
        $this->quantiteTotale = $quantiteTotale;
    }

    /**
     * @return mixed
     */
    public function getModeReglement()
    {
        return $this->modeReglement;
    }

    /**
     * @param mixed $modeReglement
     */
    public function setModeReglement($modeReglement): void
    {
        $this->modeReglement = $modeReglement;
    }

}