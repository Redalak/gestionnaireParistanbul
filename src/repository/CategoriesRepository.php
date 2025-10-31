<?php
declare(strict_types=1);

namespace repository;

require_once __DIR__ . '/../bdd/Bdd.php';
require_once __DIR__ . '/../model/Categorie.php';

use bdd\Bdd;
use model\Categorie;
use PDO;

final class CategoriesRepository
{
    private const TABLE = 'categorie';

    private function db(): PDO {
        return (new Bdd())->getBdd();
    }

    // --- Ajouter une catégorie ---
    public function ajoutCategorie(Categorie $categorie): Categorie {
        $req = $this->db()->prepare('INSERT INTO '.self::TABLE.' (nom) VALUES (:nom)');
        $req->execute(['nom' => $categorie->getNom()]);
        return $categorie;
    }

    // --- Modifier une catégorie ---
    public function modifCategorie(Categorie $categorie): Categorie {
        $req = $this->db()->prepare('UPDATE '.self::TABLE.' SET nom = :nom WHERE id_categorie = :id_categorie');
        $req->execute([
            'id_categorie' => $categorie->getIdCategorie(),
            'nom'          => $categorie->getNom(),
        ]);
        return $categorie;
    }

    // --- Supprimer une catégorie ---
    public function suppCategorie(int $idCategorie): void {
        $req = $this->db()->prepare('DELETE FROM categorie WHERE id_categorie = :id_categorie');
        $req->execute(['id_categorie' => $idCategorie]);
    }

    // --- Liste complète des catégories (objets) ---
    public function listeCategories(): array {
        $rows = $this->db()->query('SELECT * FROM categorie ORDER BY nom ASC')->fetchAll(PDO::FETCH_ASSOC);
        return array_map(fn($r) => new Categorie($r), $rows);
    }

    // --- Dernières catégories ajoutées ---
    public function getDernieresCategories(int $limit = 5): array {
        $stmt = $this->db()->prepare('SELECT * FROM  ORDER BY id_categorie DESC LIMIT :limit');
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(fn($r) => new Categorie($r), $rows);
    }

    // --- Nombre total de catégories ---
    public function nbCategories(): int {
        $res = $this->db()->query('SELECT COUNT(*) AS total FROM '.self::TABLE)->fetch(PDO::FETCH_ASSOC);
        return (int)$res['total'];
    }

    // --- Récupérer une catégorie par ID ---
    public function getCategorieParId(int $idCategorie): ?Categorie {
        $req = $this->db()->prepare('SELECT * FROM '.self::TABLE.' WHERE id_categorie = :id_categorie');
        $req->execute(['id_categorie' => $idCategorie]);
        $row = $req->fetch(PDO::FETCH_ASSOC);
        return $row ? new Categorie($row) : null;
    }

    // --- Récupérer une catégorie par nom ---
    public function getCategorieParNom(string $nom): ?Categorie {
        $req = $this->db()->prepare('SELECT * FROM '.self::TABLE.' WHERE nom = :nom');
        $req->execute(['nom' => $nom]);
        $row = $req->fetch(PDO::FETCH_ASSOC);
        return $row ? new Categorie($row) : null;
    }

    // --- Helpers pour les vues ---
    /** Liste des noms (pour filtres) */
    public function allNames(): array {
        return $this->db()->query('SELECT DISTINCT nom FROM '.self::TABLE.' ORDER BY nom')
                          ->fetchAll(PDO::FETCH_COLUMN);
    }

    /** Paires id/nom (pour <select>) */
    public function pairs(): array {
        return $this->db()->query('SELECT id_categorie, nom FROM '.self::TABLE.' ORDER BY nom')
                          ->fetchAll(PDO::FETCH_ASSOC);
    }
}
