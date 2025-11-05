<?php

namespace model;

class Categorie
{
    private $idCategorie ;
    private $nom ;

    public function __construct(array $donnees)
    {
        $this->hydrate($donnees);
    }

    private function hydrate(array $donnees): void
    {
        foreach ($donnees as $key => $value) {
            // Transforme id_categorie -> IdCategorie pour correspondre aux setters
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
    public function getIdCategorie()
    {
        return $this->idCategorie;
    }

    /**
     * @param mixed $idCategorie
     */
    public function setIdCategorie($idCategorie): void
    {
        $this->idCategorie = $idCategorie;
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
}