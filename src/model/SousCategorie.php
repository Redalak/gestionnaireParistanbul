<?php

namespace model;

class SousCategorie
{
    private $idSousCategorie ;
    private $nom ;
    private $refCategorie ;
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
    public function getIdSousCategorie()
    {
        return $this->idSousCategorie;
    }

    /**
     * @param mixed $idSousCategorie
     */
    public function setIdSousCategorie($idSousCategorie): void
    {
        $this->idSousCategorie = $idSousCategorie;
    }

    /**
     * @return mixed
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * @param mixed $nom
     */
    public function setNom($nom): void
    {
        $this->nom = $nom;
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
}