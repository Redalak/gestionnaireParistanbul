<?php

namespace model;

class Produit
{
    private $idProduit ;
    private $libelle ;
    private $marque ;
    private $quantiteCentrale ;
    private $prixUnitaire;
    private $seuilAlerte ;

    private $refCategorie ;
    private $dateAjout ;

    public function __construct(array $donnees)
    {
        $this->hydrate($donnees);
    }

    private function hydrate(array $donnees): void
    {
        foreach ($donnees as $key => $value) {
            // Transforme id_produit → idProduit
            $key = str_replace('_', '', ucwords($key, '_'));
            $method = 'set' . $key;
            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }
    }


    /**
     * @return mixed
     */
    public function getIdProduit()
    {
        return $this->idProduit;
    }

    /**
     * @param mixed $idProduit
     */
    public function setIdProduit($idProduit): void
    {
        $this->idProduit = $idProduit;
    }

    /**
     * @return mixed
     */
    public function getLibelle()
    {
        return $this->libelle;
    }

    /**
     * @param mixed $libelle
     */
    public function setLibelle($libelle): void
    {
        $this->libelle = $libelle;
    }

    /**
     * @return mixed
     */
    public function getMarque()
    {
        return $this->marque;
    }

    /**
     * @param mixed $marque
     */
    public function setMarque($marque): void
    {
        $this->marque = $marque;
    }

    /**
     * @return mixed
     */
    public function getOrigine()
    {
        return $this->origine;
    }

    /**
     * @param mixed $origine
     */
    public function setOrigine($origine): void
    {
        $this->origine = $origine;
    }



    /**
     * @param mixed $refSousCategorie
     */
    public function setRefSousCategorie($refSousCategorie): void
    {
        $this->refSousCategorie = $refSousCategorie;
    }

    /**
     * @return mixed
     */
    public function getRefCategorie()
    {
        return $this->refCategorie;
    }

    /**
     * @param mixed $refCategorie
     */
    public function setRefCategorie($refCategorie): void
    {
        $this->refCategorie = $refCategorie;
    }

    /**
     * @return mixed
     */
    public function getReferenceProduit()
    {
        return $this->referenceProduit;
    }

    /**
     * @param mixed $referenceProduit
     */
    public function setReferenceProduit($referenceProduit): void
    {
        $this->referenceProduit = $referenceProduit;
    }


    public function getPrixUnitaire()
    {
        return $this->prixUnitaire;
    }

    /**
     * @param mixed $prixUnitaire
     */
    public function setPrixUnitaire($prixUnitaire): void
    {
        $this->prixUnitaire = $prixUnitaire;
    }

    /**
     * @return mixed
     */
    public function getQuantiteCentrale()
    {
        return $this->quantiteCentrale;
    }

    /**
     * @param mixed $quantiteCentrale
     */
    public function setQuantiteCentrale($quantiteCentrale): void
    {
        $this->quantiteCentrale = $quantiteCentrale;
    }

    /**
     * @return mixed
     */
    public function getSeuilAlerte()
    {
        return $this->seuilAlerte;
    }

    /**
     * @param mixed $seuilAlerte
     */
    public function setSeuilAlerte($seuilAlerte): void
    {
        $this->seuilAlerte = $seuilAlerte;
    }

    /**
     * @return mixed
     */
    public function getDateAjout()
    {
        return $this->dateAjout;
    }

    /**
     * @param mixed $dateAjout
     */
    public function setDateAjout($dateAjout): void
    {
        $this->dateAjout = $dateAjout;
    }

}