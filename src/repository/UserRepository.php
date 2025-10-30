<?php

namespace repository;

use Bdd;
use model\User;

class UserRepository
{
    private Bdd $db ;
    public function __construct(?\Bdd $pdo = null)
    {

            $this -> db = (new Bdd())-> connect;

    }
public function getAllUser(){
        $sql = "SELECT * from utilisateur";
        $stmt = $this -> bdd -> prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(Bdd::FETCH_ASSOC);
    return $result ?: null;

}
public function getUserById(User $idUser){
    $sql = "SELECT * FROM utilisateur WHERE id_user = :id_user";
    $stmt = $this->db->prepare($sql);
    $stmt->execute(['id_user' => $idUser]);
    $result = $stmt->fetch(Bdd::FETCH_ASSOC);
    return $result ?: null;
}
public function inscription(User $user){
    $sql = "INSERT INTO utilisateur (nom, prenom, email, mdp, role, genre,poste)
                VALUES (:nom, :prenom, :email, :mdp, :role, :genre,:poste)";
    $stmt = $this->db->prepare($sql);
    return $stmt->execute([
        'nom'            => $user->getNom(),
        'prenom'         => $user->getPrenom(),
        'email'          => $user->getEmail(),
        'mdp'            => $user->getMdp(),
        'role'           => $user->getRole(),
        'genre'          => $user -> getGenre() ,
        'poste'          => $user -> getPoste()
    ]);

}
    public function getUserByEmail(string $email): ?array
    {
        $sql = "SELECT * FROM utilisateur WHERE email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['email' => $email]);
        $result = $stmt->fetch(Bdd::FETCH_ASSOC);
        return $result ?: null;
    }
    public function update(int $id_user, array $data): bool
    {
        $allowed = [
            'nom','prenom','email','mdp','role','genre',
            'poste'
        ];
        $set = [];
        $params = ['id_user' => $id_user];
        foreach ($allowed as $k) {
            if (array_key_exists($k, $data)) {
                $set[] = "$k = :$k";
                $params[$k] = $data[$k];
            }
        }
        if (!$set) return false;
        $sql = "UPDATE utilisateur SET ".implode(', ', $set)." WHERE id_user = :id_user";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }
    public function delete(int $id_user): bool
    {
        $sql = "DELETE FROM utilisateur WHERE id_user = :id_user";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id_user' => $id_user]);
    }

}