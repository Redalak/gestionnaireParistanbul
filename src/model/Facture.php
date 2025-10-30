<?php

namespace model;

class Facture
{
    private $idFacture ;
    private $refUser ;
    private $refCommande ;
    private $datePaiement ;
    private $paye ;
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
    public function getIdFacture()
    {
        return $this->idFacture;
    }

    /**
     * @param mixed $idFacture
     */
    public function setIdFacture($idFacture): void
    {
        $this->idFacture = $idFacture;
    }

    /**
     * @return mixed
     */
    public function getRefUser()
    {
        return $this->refUser;
    }

    /**
     * @param mixed $refUser
     */
    public function setRefUser($refUser): void
    {
        $this->refUser = $refUser;
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
    public function getDatePaiement()
    {
        return $this->datePaiement;
    }

    /**
     * @param mixed $datePaiement
     */
    public function setDatePaiement($datePaiement): void
    {
        $this->datePaiement = $datePaiement;
    }

    /**
     * @return mixed
     */
    public function getPaye()
    {
        return $this->paye;
    }

    /**
     * @param mixed $paye
     */
    public function setPaye($paye): void
    {
        $this->paye = $paye;
    }

}