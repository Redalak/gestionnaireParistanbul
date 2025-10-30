<?php

namespace repository;

require_once __DIR__ . '/../bdd/Bdd.php';
require_once __DIR__ . '/../model/SousCategorie.php';

use bdd\Bdd;
use model\SousCategorie;

class SousCateRepository
{
    // --- Ajouter une sous-catégorie ---
    public function ajoutSousCategorie(SousCategorie $sousCategorie) {
        $bdd = new Bdd();
        $database = $bdd->getBdd();

        $req = $database->prepare('
            INSERT INTO sous_categorie (nom, ref_categorie)
            VALUES (:nom, :ref_categorie)
        ');
        $req->execute([
            'nom'           => $sousCategorie->getNom(),
            'ref_categorie' => $sousCategorie->getRefCategorie()
        ]);

        return $sousCategorie;
    }

    // --- Modifier une sous-catégorie ---
    public function modifSousCategorie(SousCategorie $sousCategorie) {
        $bdd = new Bdd();
        $database = $bdd->getBdd();

        $req = $database->prepare('
            UPDATE sous_categorie 
            SET nom = :nom, ref_categorie = :ref_categorie
            WHERE id_sous_categorie = :id_sous_categorie
        ');
        $req->execute([
            'id_sous_categorie' => $sousCategorie->getIdSousCategorie(),
            'nom'               => $sousCategorie->getNom(),
            'ref_categorie'     => $sousCategorie->getRefCategorie()
        ]);

        return $sousCategorie;
    }

    // --- Supprimer une sous-catégorie ---
    public function suppSousCategorie(int $idSousCategorie) {
        $bdd = new Bdd();
        $database = $bdd->getBdd();

        $req = $database->prepare('
            DELETE FROM sous_categorie 
            WHERE id_sous_categorie = :id_sous_categorie
        ');
        $req->execute(['id_sous_categorie' => $idSousCategorie]);
    }

    // --- Lister toutes les sous-catégories ---
    public function listeSousCategories() {
        $bdd = new Bdd();
        $database = $bdd->getBdd();

        $req = $database->query('
            SELECT * FROM sous_categorie ORDER BY nom ASC
        ');
        $rows = $req->fetchAll(\PDO::FETCH_ASSOC);

        $sousCategories = [];
        foreach ($rows as $row) {
            $sousCategories[] = new SousCategorie($row);
        }
        return $sousCategories;
    }

    // --- Récupérer les dernières sous-catégories ajoutées ---
    public function getDernieresSousCategories(int $limit = 5) {
        $bdd = new Bdd();
        $database = $bdd->getBdd();

        $stmt = $database->prepare('
            SELECT * FROM sous_categorie 
            ORDER BY id_sous_categorie DESC 
            LIMIT :limit
        ');
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $sousCategories = [];
        foreach ($rows as $row) {
            $sousCategories[] = new SousCategorie($row);
        }
        return $sousCategories;
    }

    // --- Nombre total de sous-catégories ---
    public function nbSousCategories(): int {
        $bdd = new Bdd();
        $database = $bdd->getBdd();

        $req = $database->query('
            SELECT COUNT(*) as total FROM sous_categorie
        ');
        $result = $req->fetch(\PDO::FETCH_ASSOC);
        return (int)$result['total'];
    }

    // --- Récupérer une sous-catégorie par ID ---
    public function getSousCategorieParId(int $idSousCategorie): ?SousCategorie {
        $bdd = new Bdd();
        $database = $bdd->getBdd();

        $req = $database->prepare('
            SELECT * FROM sous_categorie 
            WHERE id_sous_categorie = :id_sous_categorie
        ');
        $req->execute(['id_sous_categorie' => $idSousCategorie]);
        $row = $req->fetch(\PDO::FETCH_ASSOC);

        if ($row) {
            return new SousCategorie($row);
        }
        return null;
    }

    // --- Récupérer les sous-catégories d'une catégorie ---
    public function getSousCategoriesParCategorie(int $refCategorie) {
        $bdd = new Bdd();
        $database = $bdd->getBdd();

        $req = $database->prepare('
            SELECT * FROM sous_categorie 
            WHERE ref_categorie = :ref_categorie 
            ORDER BY nom ASC
        ');
        $req->execute(['ref_categorie' => $refCategorie]);
        $rows = $req->fetchAll(\PDO::FETCH_ASSOC);

        $sousCategories = [];
        foreach ($rows as $row) {
            $sousCategories[] = new SousCategorie($row);
        }
        return $sousCategories;
    }

    // --- Récupérer une sous-catégorie par nom ---
    public function getSousCategorieParNom(string $nom): ?SousCategorie {
        $bdd = new Bdd();
        $database = $bdd->getBdd();

        $req = $database->prepare('
            SELECT * FROM sous_categorie WHERE nom = :nom
        ');
        $req->execute(['nom' => $nom]);
        $row = $req->fetch(\PDO::FETCH_ASSOC);

        if ($row) {
            return new SousCategorie($row);
        }
        return null;
    }
}
