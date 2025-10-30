<?php

namespace repository;

require_once __DIR__ . '/../bdd/Bdd.php';
require_once __DIR__ . '/../model/Categorie.php';

use bdd\Bdd;
use model\Categorie;

class CategoriesRepo
{
    // --- Ajouter une catégorie ---
    public function ajoutCategorie(Categorie $categorie) {
        $bdd = new Bdd();
        $database = $bdd->getBdd();

        $req = $database->prepare('INSERT INTO categorie (nom) VALUES (:nom)');
        $req->execute([
            'nom' => $categorie->getNom()
        ]);

        return $categorie;
    }

    // --- Modifier une catégorie ---
    public function modifCategorie(Categorie $categorie) {
        $bdd = new Bdd();
        $database = $bdd->getBdd();

        $req = $database->prepare('UPDATE categorie SET nom = :nom WHERE id_categorie = :id_categorie');
        $req->execute([
            'id_categorie' => $categorie->getIdCategorie(),
            'nom'          => $categorie->getNom()
        ]);

        return $categorie;
    }

    // --- Supprimer une catégorie ---
    public function suppCategorie(int $idCategorie) {
        $bdd = new Bdd();
        $database = $bdd->getBdd();

        $req = $database->prepare('DELETE FROM categorie WHERE id_categorie = :id_categorie');
        $req->execute(['id_categorie' => $idCategorie]);
    }

    // --- Liste complète des catégories ---
    public function listeCategories() {
        $bdd = new Bdd();
        $database = $bdd->getBdd();

        $req = $database->query('SELECT * FROM categorie ORDER BY nom ASC');
        $rows = $req->fetchAll(\PDO::FETCH_ASSOC);

        $categories = [];
        foreach ($rows as $row) {
            $categories[] = new Categorie($row);
        }
        return $categories;
    }

    // --- Dernières catégories ajoutées ---
    public function getDernieresCategories(int $limit = 5) {
        $bdd = new Bdd();
        $database = $bdd->getBdd();

        $stmt = $database->prepare('SELECT * FROM categorie ORDER BY id_categorie DESC LIMIT :limit');
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $categories = [];
        foreach ($rows as $row) {
            $categories[] = new Categorie($row);
        }
        return $categories;
    }

    // --- Nombre total de catégories ---
    public function nbCategories(): int {
        $bdd = new Bdd();
        $database = $bdd->getBdd();

        $req = $database->query('SELECT COUNT(*) as total FROM categorie');
        $result = $req->fetch(\PDO::FETCH_ASSOC);
        return (int)$result['total'];
    }

    // --- Récupérer une catégorie par ID ---
    public function getCategorieParId(int $idCategorie): ?Categorie {
        $bdd = new Bdd();
        $database = $bdd->getBdd();

        $req = $database->prepare('SELECT * FROM categorie WHERE id_categorie = :id_categorie');
        $req->execute(['id_categorie' => $idCategorie]);
        $row = $req->fetch(\PDO::FETCH_ASSOC);

        if ($row) {
            return new Categorie($row);
        }
        return null;
    }

    // --- Récupérer une catégorie par nom ---
    public function getCategorieParNom(string $nom): ?Categorie {
        $bdd = new Bdd();
        $database = $bdd->getBdd();

        $req = $database->prepare('SELECT * FROM categorie WHERE nom = :nom');
        $req->execute(['nom' => $nom]);
        $row = $req->fetch(\PDO::FETCH_ASSOC);

        if ($row) {
            return new Categorie($row);
        }
        return null;
    }
}
