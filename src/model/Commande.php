<?php

namespace model;

class Commande
{

    private ?int $idCommande;
    private ?int $refMagasin;
    private ?int $refUtilisateur;
    private ?string $dateCommande;
    private ?string $etat;
    private ?string $commentaire;

    // Champs ajoutés pour JOIN
    private ?string $nom_magasin;
    private ?string $ville;

    public function __construct(array $donnees = [])
    {
        $this->hydrate($donnees);
    }

    private function hydrate(array $donnees): void
    {
        foreach ($donnees as $key => $value) {
            // transforme magasin_nom → setMagasinNom
            $method = 'set' . str_replace('_', '', ucfirst($key));

            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }
    }

    public function getIdCommande(): ?int
    {
        return $this->idCommande;
    }

    public function setIdCommande(int $idCommande): void
    {
        $this->idCommande = $idCommande;
    }

    public function getRefMagasin(): ?int
    {
        return $this->refMagasin;
    }

    public function setRefMagasin(?int $refMagasin): void
    {
        $this->refMagasin = $refMagasin;
    }

    public function getRefUtilisateur(): ?int
    {
        return $this->refUtilisateur;
    }

    public function setRefUtilisateur(?int $refUtilisateur): void
    {
        $this->refUtilisateur = $refUtilisateur;
    }

    public function getDateCommande(): ?string
    {
        return $this->dateCommande;
    }

    public function setDateCommande(?string $dateCommande): void
    {
        $this->dateCommande = $dateCommande;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(?string $etat): void
    {
        $this->etat = $etat;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(?string $commentaire): void
    {
        $this->commentaire = $commentaire;
    }

    // GETTERS / SETTERS POUR MAGASIN + VILLE

    public function getNomMagasin(): ?string
    {
        return $this->nom_magasin;
    }

    public function setNomMagasin(?string $nom_magasin): void
    {
        $this->nom_magasin = $nom_magasin;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(?string $ville): void
    {
        $this->ville = $ville;
    }

}
