<?php
declare(strict_types=1);

namespace repository;

require_once __DIR__ . '/../bdd/Bdd.php';
require_once __DIR__ . '/../model/User.php';

use bdd\Bdd;
use model\User;
use PDO;

class UserRepository
{
    private PDO $db;

    public function __construct(?PDO $pdo = null)
    {
        $this->db = $pdo ?? (new Bdd())->getBdd();
    }

    /**
     * Retourne la liste des utilisateurs (objets User)
     */
    public function getAllUsers(): array
    {
        $sql = 'SELECT * FROM utilisateur ORDER BY id_user DESC';
        $stmt = $this->db->query($sql);
        $results = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $results[] = $row;
        }
    return $results ;
    }

    /**
     * Retourne uniquement les utilisateurs en attente (role = 'pending')
     */
    public function getPendingUsers(): array
    {
        // Pending = comptes sans affectation magasin (ref_magasin IS NULL)
        $sql = "SELECT * FROM utilisateur WHERE ref_magasin IS NULL ORDER BY id_user DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Trouve un utilisateur par son ID
     */
    public function getUserById(int $idUser): ?User
    {
        $sql = 'SELECT * FROM utilisateur WHERE id_user = :id_user';
        $st  = $this->db->prepare($sql);
        $st->execute(['id_user' => $idUser]);
        $row = $st->fetch(PDO::FETCH_ASSOC);
        return $row ? new User($row) : null;
    }

    /**
     * Inscription / création d'utilisateur
     */
    public function inscription(User $user): bool
    {
        // Insert compatible with current schema: no genre/poste; ref_magasin nullable
        $sql = 'INSERT INTO utilisateur (nom, prenom, email, mdp, role, ref_magasin)
                VALUES (:nom, :prenom, :email, :mdp, :role, :ref_magasin)';
        $st  = $this->db->prepare($sql);
        return $st->execute([
            'nom'          => $user->getNom(),
            'prenom'       => $user->getPrenom(),
            'email'        => $user->getEmail(),
            'mdp'          => $user->getMdp(),
            'role'         => $user->getRole(),
            'ref_magasin'  => $user->getRefMagasin(), // null for pending
        ]);
    }

    /**
     * Récupère un utilisateur par email
     */
    public function getUserByEmail(string $email): ?User
    {
        $sql = 'SELECT * FROM utilisateur WHERE email = :email';
        $st  = $this->db->prepare($sql);
        $st->execute(['email' => $email]);
        $row = $st->fetch(PDO::FETCH_ASSOC);
        return $row ? new User($row) : null;
    }

    /**
     * Update partiel à partir d'un tableau associatif de champs autorisés
     */
    public function update(int $id_user, array $data): bool
    {
        $allowed = ['nom','prenom','email','mdp','role','ref_magasin'];
        $set = [];
        $params = ['id_user' => $id_user];

        foreach ($allowed as $k) {
            if (array_key_exists($k, $data)) {
                $set[] = "$k = :$k";
                $params[$k] = $data[$k];
            }
        }

        if (!$set) return false;

        $sql = 'UPDATE utilisateur SET ' . implode(', ', $set) . ' WHERE id_user = :id_user';
        $st  = $this->db->prepare($sql);
        return $st->execute($params);
    }

    /**
     * Suppression par ID
     */
    public function delete(int $id_user): bool
    {
        $sql = 'DELETE FROM utilisateur WHERE id_user = :id_user';
        $st  = $this->db->prepare($sql);
        return $st->execute(['id_user' => $id_user]);
    }
}