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
    public function getAllUser(): array
    {
        $sql  = 'SELECT * FROM utilisateur ORDER BY id_user DESC';
        $rows = $this->db->prepare($sql)->fetchAll(PDO::FETCH_ASSOC);
        return array_map(fn($r) => new User($r), $rows);
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
        $sql = 'INSERT INTO utilisateur (nom, prenom, email, mdp, role, genre, poste)
                VALUES (:nom, :prenom, :email, :mdp, :role, :genre, :poste)';
        $st  = $this->db->prepare($sql);
        return $st->execute([
            'nom'    => $user->getNom(),
            'prenom' => $user->getPrenom(),
            'email'  => $user->getEmail(),
            'mdp'    => $user->getMdp(),
            'role'   => $user->getRole(),
            'genre'  => $user->getGenre(),
            'poste'  => $user->getPoste(),
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
        $allowed = ['nom','prenom','email','mdp','role','genre','poste'];
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