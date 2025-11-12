<?php

namespace model;

class Mouvement
{
    private $idMouvement;
    private $refProduit;
    private $refMagasin;
    private $type;
    private $quantite;
    private $source;
    private $dateMouvement;
    private $commentaire;
    private $extraData = [];

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

    // Getters
    public function getIdMouvement()
    {
        return $this->idMouvement;
    }

    public function getRefProduit()
    {
        return $this->refProduit;
    }

    public function getRefMagasin()
    {
        return $this->refMagasin;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getQuantite()
    {
        return $this->quantite;
    }

    public function getSource()
    {
        return $this->source;
    }

    public function getDateMouvement()
    {
        return $this->dateMouvement;
    }

    public function getCommentaire()
    {
        return $this->commentaire;
    }

    // Setters
    public function setIdMouvement($idMouvement): void
    {
        $this->idMouvement = $idMouvement;
    }

    public function setRefProduit($refProduit): void
    {
        $this->refProduit = $refProduit;
    }

    public function setRefMagasin($refMagasin): void
    {
        $this->refMagasin = $refMagasin;
    }

    public function setType($type): void
    {
        $this->type = $type;
    }

    public function setQuantite($quantite): void
    {
        $this->quantite = $quantite;
    }

    public function setSource($source): void
    {
        $this->source = $source;
    }

    public function setDateMouvement($dateMouvement): void
    {
        $this->dateMouvement = $dateMouvement;
    }

    public function setCommentaire($commentaire): void
    {
        $this->commentaire = $commentaire;
    }

    /**
     * Méthode pour définir des données supplémentaires non mappées directement aux propriétés
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function setData(string $key, $value): void
    {
        $this->extraData[$key] = $value;
    }
    
    /**
     * Récupère une donnée supplémentaire par sa clé
     * @param string $key La clé de la donnée à récupérer
     * @return mixed|null La valeur de la donnée ou null si elle n'existe pas
     */
    public function getData(string $key)
    {
        return $this->extraData[$key] ?? null;
    }
}
