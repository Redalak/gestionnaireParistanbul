<?php

namespace repository;

require_once __DIR__ . '/../bdd/Bdd.php';
require_once __DIR__ . '/../model/Fournisseur.php';

use bdd\Bdd;
use model\Fournisseur;

class FournisseursRepository
{
    // --- Ajouter un fournisseur ---
    public function ajoutFournisseur(Fournisseur $fournisseur) {
        $bdd = new Bdd();
        $database = $bdd->getBdd();

        $req = $database->prepare('
            INSERT INTO fournisseur 
                (nom, prenom, cp, adresse, email, site_web, mdp, num_telephone, num_mobile, entreprise, devise)
            VALUES
                (:nom, :prenom, :cp, :adresse, :email, :site_web, :mdp, :num_telephone, :num_mobile, :entreprise, :devise)
        ');

        $req->execute([
            'nom'            => $fournisseur->getNom(),
            'prenom'         => $fournisseur->getPrenom(),
            'cp'             => $fournisseur->getCp(),
            'adresse'        => $fournisseur->getAdresse(),
            'email'          => $fournisseur->getEmail(),
            'site_web'       => $fournisseur->getSiteWeb(),
            'mdp'            => $fournisseur->getMdp(),
            'num_telephone'  => $fournisseur->getNumTelephone(),
            'num_mobile'     => $fournisseur->getNumMobile(),
            'entreprise'     => $fournisseur->getEntreprise(),
            'devise'         => $fournisseur->getDevise()
        ]);

        return $fournisseur;
    }

    // --- Modifier un fournisseur ---
    public function modifFournisseur(Fournisseur $fournisseur) {
        $bdd = new Bdd();
        $database = $bdd->getBdd();

        $req = $database->prepare('
            UPDATE fournisseur SET
                nom = :nom,
                prenom = :prenom,
                cp = :cp,
                adresse = :adresse,
                email = :email,
                site_web = :site_web,
                mdp = :mdp,
                num_telephone = :num_telephone,
                num_mobile = :num_mobile,
                entreprise = :entreprise,
                devise = :devise
            WHERE id_fournisseur = :id_fournisseur
        ');

        $req->execute([
            'id_fournisseur' => $fournisseur->getIdFournisseur(),
            'nom'            => $fournisseur->getNom(),
            'prenom'         => $fournisseur->getPrenom(),
            'cp'             => $fournisseur->getCp(),
            'adresse'        => $fournisseur->getAdresse(),
            'email'          => $fournisseur->getEmail(),
            'site_web'       => $fournisseur->getSiteWeb(),
            'mdp'            => $fournisseur->getMdp(),
            'num_telephone'  => $fournisseur->getNumTelephone(),
            'num_mobile'     => $fournisseur->getNumMobile(),
            'entreprise'     => $fournisseur->getEntreprise(),
            'devise'         => $fournisseur->getDevise()
        ]);

        return $fournisseur;
    }

    // --- Supprimer un fournisseur ---
    public function suppFournisseur(int $idFournisseur) {
        $bdd = new Bdd();
        $database = $bdd->getBdd();
        $req = $database->prepare('DELETE FROM fournisseur WHERE id_fournisseur = :id_fournisseur');
        $req->execute(['id_fournisseur' => $idFournisseur]);
    }

    // --- Liste complète des fournisseurs ---
    public function listeFournisseurs() {
        $bdd = new Bdd();
        $database = $bdd->getBdd();
        $req = $database->query('SELECT * FROM fournisseur ORDER BY id_fournisseur DESC');
        $rows = $req->fetchAll(\PDO::FETCH_ASSOC);

        $fournisseurs = [];
        foreach ($rows as $row) {
            $fournisseurs[] = new Fournisseur($row);
        }
        return $fournisseurs;
    }

    // --- Derniers fournisseurs ajoutés ---
    public function getDerniersFournisseurs(int $limit = 5) {
        $bdd = new Bdd();
        $database = $bdd->getBdd();
        $stmt = $database->prepare('SELECT * FROM fournisseur ORDER BY id_fournisseur DESC LIMIT :limit');
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $fournisseurs = [];
        foreach ($rows as $row) {
            $fournisseurs[] = new Fournisseur($row);
        }
        return $fournisseurs;
    }

    // --- Compter le nombre total de fournisseurs ---
    public function nbFournisseurs(): int {
        $bdd = new Bdd();
        $database = $bdd->getBdd();
        $req = $database->query('SELECT COUNT(*) as total FROM fournisseur');
        $result = $req->fetch(\PDO::FETCH_ASSOC);
        return (int)$result['total'];
    }

    // --- Récupérer un fournisseur par ID ---
    public function getFournisseurParId(int $idFournisseur): ?Fournisseur {
        $bdd = new Bdd();
        $database = $bdd->getBdd();
        $req = $database->prepare('SELECT * FROM fournisseur WHERE id_fournisseur = :id_fournisseur');
        $req->execute(['id_fournisseur' => $idFournisseur]);
        $row = $req->fetch(\PDO::FETCH_ASSOC);

        if ($row) {
            return new Fournisseur($row);
        }
        return null;
    }

    // --- Suggestion : rechercher un fournisseur par email (utile pour connexion ou vérification) ---
    public function getFournisseurParEmail(string $email): ?Fournisseur {
        $bdd = new Bdd();
        $database = $bdd->getBdd();
        $req = $database->prepare('SELECT * FROM fournisseur WHERE email = :email');
        $req->execute(['email' => $email]);
        $row = $req->fetch(\PDO::FETCH_ASSOC);

        if ($row) {
            return new Fournisseur($row);
        }
        return null;
    }
}
