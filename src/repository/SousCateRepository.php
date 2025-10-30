<?php
declare(strict_types=1);

namespace repository;

require_once __DIR__ . '/../bdd/Bdd.php';
require_once __DIR__ . '/../model/SousCategorie.php';

use bdd\Bdd;
use model\SousCategorie;
use PDO;

final class SousCateRepo
{
    private const TABLE = 'sous_categorie';

    private function db(): PDO {
        return (new Bdd())->getBdd();
    }

    // --- Ajouter une sous-catégorie ---
    public function ajoutSousCategorie(SousCategorie $sousCategorie): SousCategorie {
        $sql = 'INSERT INTO '.self::TABLE.' (nom, ref_categorie) VALUES (:nom, :ref_categorie)';
        $st  = $this->db()->prepare($sql);
        $st->execute([
            'nom'           => $sousCategorie->getNom(),
            'ref_categorie' => $sousCategorie->getRefCategorie(),
        ]);
        return $sousCategorie;
    }

    // --- Modifier une sous-catégorie ---
    public function modifSousCategorie(SousCategorie $sousCategorie): SousCategorie {
        $sql = 'UPDATE '.self::TABLE.'
                SET nom = :nom, ref_categorie = :ref_categorie
                WHERE id_sous_categorie = :id_sous_categorie';
        $st  = $this->db()->prepare($sql);
        $st->execute([
            'id_sous_categorie' => $sousCategorie->getIdSousCategorie(),
            'nom'               => $sousCategorie->getNom(),
            'ref_categorie'     => $sousCategorie->getRefCategorie(),
        ]);
        return $sousCategorie;
    }

    // --- Supprimer une sous-catégorie ---
    public function suppSousCategorie(int $idSousCategorie): void {
        $st = $this->db()->prepare('DELETE FROM '.self::TABLE.' WHERE id_sous_categorie = :id');
        $st->execute(['id' => $idSousCategorie]);
    }

    // --- Lister toutes les sous-catégories (objets) ---
    public function listeSousCategories(): array {
        $rows = $this->db()->query('SELECT * FROM '.self::TABLE.' ORDER BY nom ASC')
                           ->fetchAll(PDO::FETCH_ASSOC);
        return array_map(fn($r) => new SousCategorie($r), $rows);
    }

    // --- Dernières sous-catégories ajoutées ---
    public function getDernieresSousCategories(int $limit = 5): array {
        $st = $this->db()->prepare('SELECT * FROM '.self::TABLE.' ORDER BY id_sous_categorie DESC LIMIT :lim');
        $st->bindValue(':lim', $limit, PDO::PARAM_INT);
        $st->execute();
        $rows = $st->fetchAll(PDO::FETCH_ASSOC);
        return array_map(fn($r) => new SousCategorie($r), $rows);
    }

    // --- Nombre total de sous-catégories ---
    public function nbSousCategories(): int {
        $res = $this->db()->query('SELECT COUNT(*) AS total FROM '.self::TABLE)
                          ->fetch(PDO::FETCH_ASSOC);
        return (int)$res['total'];
    }

    // --- Récupérer une sous-catégorie par ID ---
    public function getSousCategorieParId(int $idSousCategorie): ?SousCategorie {
        $st = $this->db()->prepare('SELECT * FROM '.self::TABLE.' WHERE id_sous_categorie = :id');
        $st->execute(['id' => $idSousCategorie]);
        $row = $st->fetch(PDO::FETCH_ASSOC);
        return $row ? new SousCategorie($row) : null;
    }

    // --- Récupérer les sous-catégories d'une catégorie ---
    public function getSousCategoriesParCategorie(int $refCategorie): array {
        $st = $this->db()->prepare('SELECT * FROM '.self::TABLE.' WHERE ref_categorie = :c ORDER BY nom ASC');
        $st->execute(['c' => $refCategorie]);
        $rows = $st->fetchAll(PDO::FETCH_ASSOC);
        return array_map(fn($r) => new SousCategorie($r), $rows);
    }

    // --- Récupérer une sous-catégorie par nom ---
    public function getSousCategorieParNom(string $nom): ?SousCategorie {
        $st = $this->db()->prepare('SELECT * FROM '.self::TABLE.' WHERE nom = :nom');
        $st->execute(['nom' => $nom]);
        $row = $st->fetch(PDO::FETCH_ASSOC);
        return $row ? new SousCategorie($row) : null;
    }

    /* ----- Helpers pour vues / selects ----- */
    /** Liste des noms (pour filtres) */
    public function allNames(): array {
        return $this->db()->query('SELECT DISTINCT nom FROM '.self::TABLE.' ORDER BY nom')
                          ->fetchAll(PDO::FETCH_COLUMN);
    }

    /** Paires id/nom (global) */
    public function pairs(): array {
        return $this->db()->query('SELECT id_sous_categorie, nom FROM '.self::TABLE.' ORDER BY nom')
                          ->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Paires id/nom pour une catégorie donnée */
    public function pairsByCategorie(int $refCategorie): array {
        $st = $this->db()->prepare('SELECT id_sous_categorie, nom FROM '.self::TABLE.' WHERE ref_categorie = :c ORDER BY nom');
        $st->execute(['c' => $refCategorie]);
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }
}
