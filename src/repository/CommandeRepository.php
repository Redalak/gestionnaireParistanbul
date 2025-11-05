<?php

namespace repository;
require_once __DIR__ . '/../../src/bdd/Bdd.php';
use model\Commande;
use PDO;
use bdd\Bdd;
class CommandeRepository
{
    private PDO $db;

    public function __construct(?PDO $pdo = null)
    {
        $this->db = $pdo ?? (new Bdd())->getBdd();
    }

    // Ajouter une commande
    public function ajoutCommande(Commande $commande): bool
    {
        // Vérifier que le magasin existe
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM magasin WHERE id_magasin = ?");
        $stmt->execute([(int) $commande->getRefMagasin()]);
        if ($stmt->fetchColumn() == 0) {
            die("Le magasin sélectionné n'existe pas.");
        }

        // Vérifier que l'utilisateur existe
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM utilisateur WHERE id_user = ?");
        $stmt->execute([(int) $commande->getRefUtilisateur()]);
        if ($stmt->fetchColumn() == 0) {
            die("L'utilisateur sélectionné n'existe pas.");
        }

        // Insertion
        $sql = "INSERT INTO commande (ref_magasin, ref_utilisateur, date_commande, etat, commentaire)
            VALUES (:ref_magasin, :ref_utilisateur, :date_commande, :etat, :commentaire)";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            'ref_magasin' => (int) $commande->getRefMagasin(),
            'ref_utilisateur' => (int) $commande->getRefUtilisateur(),
            'date_commande' => $commande->getDateCommande(),
            'etat' => $commande->getEtat(),
            'commentaire' => $commande->getCommentaire()
        ]);
    }


    // Modifier une commande
    public function updateCommande(Commande $commande): bool
    {
        // Liste des champs autorisés à être mis à jour
        $allowed = ['ref_magasin', 'ref_utilisateur', 'date_commande', 'etat', 'commentaire'];

        $set = [];
        $params = ['id_commande' => $commande->getIdCommande()];

        foreach ($allowed as $field) {
            $getter = 'get' . str_replace('_', '', ucwords($field, '_'));
            if (method_exists($commande, $getter)) {
                $value = $commande->$getter();
                if ($value !== null) {
                    $set[] = "$field = :$field";
                    $params[$field] = $value;
                }
            }
        }

        // Si aucun champ à mettre à jour, on retourne false
        if (empty($set)) {
            return false;
        }

        $sql = 'UPDATE commande SET ' . implode(', ', $set) . ' WHERE id_commande = :id_commande';
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    // Supprimer une commande
    public function deleteCommande(int $id_commande): bool
    {
        $sql = 'DELETE FROM commande WHERE id_commande = :id_commande';
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id_commande' => $id_commande]);
    }

    // Récupérer une commande par ID
    public function getCommandeById(int $id_commande): ?Commande
    {
        $sql = 'SELECT * FROM commande WHERE id_commande = :id_commande';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id_commande' => $id_commande]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? new Commande($row) : null;
    }

    // Récupérer toutes les commandes
    public function getAllCommandes(): array {
        $sql = 'SELECT c.*, m.nom AS nom_magasin, u.nom AS nom_utilisateur, u.prenom AS prenom_utilisateur  
            FROM commande c
            LEFT JOIN magasin m ON c.ref_magasin = m.id_magasin
            LEFT JOIN utilisateur u ON c.ref_utilisateur = u.id_user
            ORDER BY c.date_commande DESC';
        $stmt = $this->db->query($sql);
        $results = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $results[] = $row;
        }
        return $results;
    }

    // Récupérer commandes par utilisateur
    public function getCommandesByUser(int $idUser): array
    {
        $sql = 'SELECT * FROM commande WHERE ref_utilisateur = :idUser';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['idUser' => $idUser]);
        $results = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $results[] = new Commande($row);
        }
        return $results;
    }

    // Récupérer commandes par magasin
    public function getCommandesByMagasin(int $idMagasin): array
    {
        $sql = 'SELECT * FROM commande WHERE ref_magasin = :idMagasin';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['idMagasin' => $idMagasin]);
        $results = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $results[] = new Commande($row);
        }
        return $results;
    }

    // Compter toutes les commandes
    public function countCommandes(): int
    {
        $sql = 'SELECT COUNT(*) FROM commande';
        return (int) $this->db->query($sql)->fetchColumn();
    }

    // Rechercher commandes par intervalle de dates
    public function findCommandesByDate(string $startDate, string $endDate): array
    {
        $sql = 'SELECT * FROM commande WHERE date_commande BETWEEN :start AND :end';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['start' => $startDate, 'end' => $endDate]);
        $results = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $results[] = new Commande($row);
        }
        return $results;
    }
}
