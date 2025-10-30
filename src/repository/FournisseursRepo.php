<?php
declare(strict_types=1);

namespace repository;

require_once __DIR__ . '/../bdd/Bdd.php';
require_once __DIR__ . '/../model/Fournisseur.php';

use bdd\Bdd;
use model\Fournisseur;
use PDO;

final class FournisseursRepo
{
    private const TABLE = 'fournisseur';

    private function db(): PDO {
        return (new Bdd())->getBdd();
    }

    // --- Ajouter un fournisseur ---
    public function ajoutFournisseur(Fournisseur $fournisseur): Fournisseur {
        $sql = 'INSERT INTO '.self::TABLE.'
                (nom, prenom, cp, adresse, email, site_web, mdp, num_telephone, num_mobile, entreprise, devise)
                VALUES
                (:nom, :prenom, :cp, :adresse, :email, :site_web, :mdp, :num_telephone, :num_mobile, :entreprise, :devise)';
        $req = $this->db()->prepare($sql);
        $req->execute([
            'nom'            => $fournisseur->getNom(),
            'prenom'         => $fournisseur->getPrenom(),
            'cp'             => $fournisseur->getCp(),
            'adresse'        => $fournisseur->getAdresse(), // fix typo
            'email'          => $fournisseur->getEmail(),
            'site_web'       => $fournisseur->getSiteWeb(),
            'mdp'            => $fournisseur->getMdp(),
            'num_telephone'  => $fournisseur->getNumTelephone(),
            'num_mobile'     => $fournisseur->getNumMobile(),
            'entreprise'     => $fournisseur->getEntreprise(),
            'devise'         => $fournisseur->getDevise(),
        ]);
        return $fournisseur;
    }

    // --- Modifier un fournisseur ---
    public function modifFournisseur(Fournisseur $fournisseur): Fournisseur {
        $sql = 'UPDATE '.self::TABLE.' SET
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
                WHERE id_fournisseur = :id_fournisseur';
        $req = $this->db()->prepare($sql);
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
            'devise'         => $fournisseur->getDevise(),
        ]);
        return $fournisseur;
    }

    // --- Supprimer un fournisseur ---
    public function suppFournisseur(int $idFournisseur): void {
        $req = $this->db()->prepare('DELETE FROM '.self::TABLE.' WHERE id_fournisseur = :id');
        $req->execute(['id' => $idFournisseur]);
    }

    // --- Liste complète des fournisseurs ---
    public function listeFournisseurs(): array {
        $rows = $this->db()->query('SELECT * FROM '.self::TABLE.' ORDER BY id_fournisseur DESC')
                           ->fetchAll(PDO::FETCH_ASSOC);
        return array_map(fn($r) => new Fournisseur($r), $rows);
    }

    // --- Derniers fournisseurs ajoutés ---
    public function getDerniersFournisseurs(int $limit = 5): array {
        $stmt = $this->db()->prepare('SELECT * FROM '.self::TABLE.' ORDER BY id_fournisseur DESC LIMIT :lim');
        $stmt->bindValue(':lim', $limit, PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(fn($r) => new Fournisseur($r), $rows);
    }

    // --- Compter le nombre total de fournisseurs ---
    public function nbFournisseurs(): int {
        $res = $this->db()->query('SELECT COUNT(*) AS total FROM '.self::TABLE)
                          ->fetch(PDO::FETCH_ASSOC);
        return (int)$res['total'];
    }

    // --- Récupérer un fournisseur par ID ---
    public function getFournisseurParId(int $idFournisseur): ?Fournisseur {
        $req = $this->db()->prepare('SELECT * FROM '.self::TABLE.' WHERE id_fournisseur = :id');
        $req->execute(['id' => $idFournisseur]);
        $row = $req->fetch(PDO::FETCH_ASSOC);
        return $row ? new Fournisseur($row) : null;
    }

    // --- Rechercher par email ---
    public function getFournisseurParEmail(string $email): ?Fournisseur {
        $req = $this->db()->prepare('SELECT * FROM '.self::TABLE.' WHERE email = :email');
        $req->execute(['email' => $email]);
        $row = $req->fetch(PDO::FETCH_ASSOC);
        return $row ? new Fournisseur($row) : null;
    }

    // --- Helpers pour filtres/selects ---
    /** Liste des entreprises distinctes (pour filtres) */
    public function allEntreprises(): array {
        return $this->db()->query('SELECT DISTINCT entreprise FROM '.self::TABLE.' WHERE entreprise IS NOT NULL AND entreprise <> "" ORDER BY entreprise')
                          ->fetchAll(PDO::FETCH_COLUMN);
    }

    /** Paires pour <select> (id, nom, prenom, entreprise) */
    public function pairs(): array {
        return $this->db()->query('SELECT id_fournisseur, nom, prenom, entreprise FROM '.self::TABLE.' ORDER BY nom, prenom')
                          ->fetchAll(PDO::FETCH_ASSOC);
    }
}