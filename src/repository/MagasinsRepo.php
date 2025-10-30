<?php
declare(strict_types=1);

namespace repository;

require_once __DIR__ . '/../bdd/Bdd.php';
require_once __DIR__ . '/../model/Magasin.php';

use bdd\Bdd;
use model\Magasin;
use PDO;

final class MagasinsRepo
{
    private const TABLE = 'magasins'; // ← aligné avec le schéma

    private function db(): PDO {
        return (new Bdd())->getBdd();
    }

    // --- Ajouter un magasin ---
    public function ajoutMagasin(Magasin $magasin): Magasin {
        $sql = 'INSERT INTO '.self::TABLE.'
                (ville, adresse, telephone, email, nom, ref_utilisateur)
                VALUES
                (:ville, :adresse, :telephone, :email, :nom, :ref_utilisateur)';
        $st = $this->db()->prepare($sql);
        $st->execute([
            'ville'           => $magasin->getVille(),
            'adresse'         => $magasin->getAdresse(),
            'telephone'       => $magasin->getTelephone(),
            'email'           => $magasin->getEmail(),
            'nom'             => $magasin->getNom(),
            'ref_utilisateur' => $magasin->getRefUtilisateur(),
        ]);
        return $magasin;
    }

    // --- Modifier un magasin ---
    public function modifMagasin(Magasin $magasin): Magasin {
        $sql = 'UPDATE '.self::TABLE.' SET
                    ville = :ville,
                    adresse = :adresse,
                    telephone = :telephone,
                    email = :email,
                    nom = :nom,
                    ref_utilisateur = :ref_utilisateur
                WHERE id_magasin = :id_magasin';
        $st = $this->db()->prepare($sql);
        $st->execute([
            'id_magasin'      => $magasin->getIdMagasin(),
            'ville'           => $magasin->getVille(),
            'adresse'         => $magasin->getAdresse(),
            'telephone'       => $magasin->getTelephone(),
            'email'           => $magasin->getEmail(),
            'nom'             => $magasin->getNom(),
            'ref_utilisateur' => $magasin->getRefUtilisateur(),
        ]);
        return $magasin;
    }

    // --- Supprimer un magasin ---
    public function suppMagasin(int $idMagasin): void {
        $st = $this->db()->prepare('DELETE FROM '.self::TABLE.' WHERE id_magasin = :id');
        $st->execute(['id' => $idMagasin]);
    }

    // --- Liste complète des magasins (objets) ---
    public function listeMagasins(): array {
        $rows = $this->db()->query('SELECT * FROM '.self::TABLE.' ORDER BY id_magasin DESC')
                           ->fetchAll(PDO::FETCH_ASSOC);
        return array_map(fn($r) => new Magasin($r), $rows);
    }

    // --- Derniers magasins ajoutés ---
    public function getDerniersMagasins(int $limit = 5): array {
        $st = $this->db()->prepare('SELECT * FROM '.self::TABLE.' ORDER BY id_magasin DESC LIMIT :lim');
        $st->bindValue(':lim', $limit, PDO::PARAM_INT);
        $st->execute();
        $rows = $st->fetchAll(PDO::FETCH_ASSOC);
        return array_map(fn($r) => new Magasin($r), $rows);
    }

    // --- Nombre total de magasins ---
    public function nbMagasins(): int {
        $res = $this->db()->query('SELECT COUNT(*) AS total FROM '.self::TABLE)
                          ->fetch(PDO::FETCH_ASSOC);
        return (int)$res['total'];
    }

    // --- Récupérer un magasin par ID ---
    public function getMagasinParId(int $idMagasin): ?Magasin {
        $st = $this->db()->prepare('SELECT * FROM '.self::TABLE.' WHERE id_magasin = :id');
        $st->execute(['id' => $idMagasin]);
        $row = $st->fetch(PDO::FETCH_ASSOC);
        return $row ? new Magasin($row) : null;
    }

    // --- Récupérer les magasins d'une ville ---
    public function getMagasinsParVille(string $ville): array {
        $st = $this->db()->prepare('SELECT * FROM '.self::TABLE.' WHERE ville = :ville ORDER BY nom');
        $st->execute(['ville' => $ville]);
        $rows = $st->fetchAll(PDO::FETCH_ASSOC);
        return array_map(fn($r) => new Magasin($r), $rows);
    }

    // --- Rechercher un magasin par nom ---
    public function getMagasinParNom(string $nom): ?Magasin {
        $st = $this->db()->prepare('SELECT * FROM '.self::TABLE.' WHERE nom = :nom');
        $st->execute(['nom' => $nom]);
        $row = $st->fetch(PDO::FETCH_ASSOC);
        return $row ? new Magasin($row) : null;
    }

    /* ----- Helpers pour vues / filtres ----- */
    /** Liste distincte des villes (pour filtres) */
    public function allVilles(): array {
        return $this->db()->query('SELECT DISTINCT ville FROM '.self::TABLE.' WHERE ville IS NOT NULL AND ville <> "" ORDER BY ville')
                          ->fetchAll(PDO::FETCH_COLUMN);
    }

    /** Paires pour <select> (id, nom, ville) */
    public function pairs(): array {
        return $this->db()->query('SELECT id_magasin, nom, ville FROM '.self::TABLE.' ORDER BY nom')
                          ->fetchAll(PDO::FETCH_ASSOC);
    }
}