<?php

namespace model;

class Produit
{
    private $idProduit ;
    private $libelle ;
    private $marque ;
    private $origine ;
    private $refSousCategorie ;
    private $refCategorie ;
    private $referenceProduit ;
    private $codeBarre ;
    private $uniteMesure ;
    private $uniteOuPack;
    private $nbUnitePack ;
    private $bio ;
    private $halal ;
    private $vegan ;
    private $prixUnitaire ;

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
     * @return mixed
     */
    public function getRefSousCategorie()
    {
        return $this->refSousCategorie;
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

    /**
     * @return mixed
     */
    public function getCodeBarre()
    {
        return $this->codeBarre;
    }

    /**
     * @param mixed $codeBarre
     */
    public function setCodeBarre($codeBarre): void
    {
        $this->codeBarre = $codeBarre;
    }

    /**
     * @return mixed
     */
    public function getUniteMesure()
    {
        return $this->uniteMesure;
    }

    /**
     * @param mixed $uniteMesure
     */
    public function setUniteMesure($uniteMesure): void
    {
        $this->uniteMesure = $uniteMesure;
    }

    /**
     * @return mixed
     */
    public function getUniteOuPack()
    {
        return $this->uniteOuPack;
    }

    /**
     * @param mixed $uniteOuPack
     */
    public function setUniteOuPack($uniteOuPack): void
    {
        $this->uniteOuPack = $uniteOuPack;
    }

    /**
     * @return mixed
     */
    public function getNbUnitePack()
    {
        return $this->nbUnitePack;
    }

    /**
     * @param mixed $nbUnitePack
     */
    public function setNbUnitePack($nbUnitePack): void
    {
        $this->nbUnitePack = $nbUnitePack;
    }

    /**
     * @return mixed
     */
    public function getBio()
    {
        return $this->bio;
    }

    /**
     * @param mixed $bio
     */
    public function setBio($bio): void
    {
        $this->bio = $bio;
    }

    /**
     * @return mixed
     */
    public function getHalal()
    {
        return $this->halal;
    }

    /**
     * @param mixed $halal
     */
    public function setHalal($halal): void
    {
        $this->halal = $halal;
    }

    /**
     * @return mixed
     */
    public function getVegan()
    {
        return $this->vegan;
    }

    /**
     * @param mixed $vegan
     */
    public function setVegan($vegan): void
    {
        $this->vegan = $vegan;
    }

    /**
     * @return mixed
     */
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

}