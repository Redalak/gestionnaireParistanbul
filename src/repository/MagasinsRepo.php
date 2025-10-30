<?php

namespace repository;

require_once __DIR__ . '/../bdd/Bdd.php';
require_once __DIR__ . '/../model/Magasin.php';

use bdd\Bdd;
use model\Magasins;

class MagasinsRepo
{
    // --- Ajouter un magasin ---
    public function ajoutMagasin(Magasin $magasin) {
        $bdd = new Bdd();
        $database = $bdd->getBdd();

        $req = $database->prepare('
            INSERT INTO magasin (ville, adresse, telephone, email, nom, ref_utilisateur)
            VALUES (:ville, :adresse, :telephone, :email, :nom, :ref_utilisateur)
        ');

        $req->execute([
            'ville'           => $magasin->getVille(),
            'adresse'         => $magasin->getAdresse(),
            'telephone'       => $magasin->getTelephone(),
            'email'           => $magasin->getEmail(),
            'nom'             => $magasin->getNom(),
            'ref_utilisateur' => $magasin->getRefUtilisateur()
        ]);

        return $magasin;
    }

    // --- Modifier un magasin ---
    public function modifMagasin(Magasin $magasin) {
        $bdd = new Bdd();
        $database = $bdd->getBdd();

        $req = $database->prepare('
            UPDATE magasin SET
                ville = :ville,
                adresse = :adresse,
                telephone = :telephone,
                email = :email,
                nom = :nom,
                ref_utilisateur = :ref_utilisateur
            WHERE id_magasin = :id_magasin
        ');

        $req->execute([
            'id_magasin'      => $magasin->getIdMagasin(),
            'ville'           => $magasin->getVille(),
            'adresse'         => $magasin->getAdresse(),
            'telephone'       => $magasin->getTelephone(),
            'email'           => $magasin->getEmail(),
            'nom'             => $magasin->getNom(),
            'ref_utilisateur' => $magasin->getRefUtilisateur()
        ]);

        return $magasin;
    }

    // --- Supprimer un magasin ---
    public function suppMagasin(int $idMagasin) {
        $bdd = new Bdd();
        $database = $bdd->getBdd();
        $req = $database->prepare('DELETE FROM magasin WHERE id_magasin = :id_magasin');
        $req->execute(['id_magasin' => $idMagasin]);
    }

    // --- Liste complète des magasins ---
    public function listeMagasins() {
        $bdd = new Bdd();
        $database = $bdd->getBdd();
        $req = $database->query('SELECT * FROM magasin ORDER BY id_magasin DESC');
        $rows = $req->fetchAll(\PDO::FETCH_ASSOC);

        $magasins = [];
        foreach ($rows as $row) {
            $magasins[] = new Magasin($row);
        }
        return $magasins;
    }

    // --- Derniers magasins ajoutés ---
    public function getDerniersMagasins(int $limit = 5) {
        $bdd = new Bdd();
        $database = $bdd->getBdd();
        $stmt = $database->prepare('SELECT * FROM magasin ORDER BY id_magasin DESC LIMIT :limit');
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $magasins = [];
        foreach ($rows as $row) {
            $magasins[] = new Magasin($row);
        }
        return $magasins;
    }

    // --- Nombre total de magasins ---
    public function nbMagasins(): int {
        $bdd = new Bdd();
        $database = $bdd->getBdd();
        $req = $database->query('SELECT COUNT(*) as total FROM magasin');
        $result = $req->fetch(\PDO::FETCH_ASSOC);
        return (int)$result['total'];
    }

    // --- Récupérer un magasin par ID ---
    public function getMagasinParId(int $idMagasin): ?Magasin {
        $bdd = new Bdd();
        $database = $bdd->getBdd();
        $req = $database->prepare('SELECT * FROM magasin WHERE id_magasin = :id_magasin');
        $req->execute(['id_magasin' => $idMagasin]);
        $row = $req->fetch(\PDO::FETCH_ASSOC);

        if ($row) {
            return new Magasin($row);
        }
        return null;
    }

    // --- Bonus : Récupérer les magasins d'une ville ---
    public function getMagasinsParVille(string $ville) {
        $bdd = new Bdd();
        $database = $bdd->getBdd();
        $req = $database->prepare('SELECT * FROM magasin WHERE ville = :ville');
        $req->execute(['ville' => $ville]);
        $rows = $req->fetchAll(\PDO::FETCH_ASSOC);

        $magasins = [];
        foreach ($rows as $row) {
            $magasins[] = new Magasin($row);
        }
        return $magasins;
    }

    // --- Bonus : Rechercher un magasin par nom (utile pour pages de contact, etc.) ---
    public function getMagasinParNom(string $nom): ?Magasin {
        $bdd = new Bdd();
        $database = $bdd->getBdd();
        $req = $database->prepare('SELECT * FROM magasin WHERE nom = :nom');
        $req->execute(['nom' => $nom]);
        $row = $req->fetch(\PDO::FETCH_ASSOC);

        if ($row) {
            return new Magasin($row);
        }
        return null;
    }
}
