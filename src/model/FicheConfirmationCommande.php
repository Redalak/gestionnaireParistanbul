<?php

namespace model;

class FicheConfirmationCommande
{
private $idFiche ;
private $refCommande ;
private $dateConfirmation ;
private $commentaire ;
private $confirmePar ;
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
    public function getIdFiche()
    {
        return $this->idFiche;
    }

    /**
     * @param mixed $idFiche
     */
    public function setIdFiche($idFiche): void
    {
        $this->idFiche = $idFiche;
    }

    /**
     * @return mixed
     */
    public function getRefCommande()
    {
        return $this->refCommande;
    }

    /**
     * @param mixed $refCommande
     */
    public function setRefCommande($refCommande): void
    {
        $this->refCommande = $refCommande;
    }

    /**
     * @return mixed
     */
    public function getDateConfirmation()
    {
        return $this->dateConfirmation;
    }

    /**
     * @param mixed $dateConfirmation
     */
    public function setDateConfirmation($dateConfirmation): void
    {
        $this->dateConfirmation = $dateConfirmation;
    }

    /**
     * @return mixed
     */
    public function getCommentaire()
    {
        return $this->commentaire;
    }

    /**
     * @param mixed $commentaire
     */
    public function setCommentaire($commentaire): void
    {
        $this->commentaire = $commentaire;
    }

    /**
     * @return mixed
     */
    public function getConfirmePar()
    {
        return $this->confirmePar;
    }

    /**
     * @param mixed $confirmePar
     */
    public function setConfirmePar($confirmePar): void
    {
        $this->confirmePar = $confirmePar;
    }

}