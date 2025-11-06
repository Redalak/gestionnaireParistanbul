<?php

namespace repository;

require_once __DIR__ . '/../../src/bdd/Bdd.php';
use model\Commande;
use PDO;
use bdd\Bdd;

class CommandeRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = (new Bdd())->getBdd();
    }

    /* ===========================================================
       ===================== MÉTHODES BASIQUES =====================
       =========================================================== */

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

        if (empty($set)) return false;

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
    public function getAllCommandes(): array
    {
        $sql = 'SELECT c.*, m.nom AS nom_magasin, u.nom AS nom_utilisateur, u.prenom AS prenom_utilisateur  
                FROM commande c
                LEFT JOIN magasin m ON c.ref_magasin = m.id_magasin
                LEFT JOIN utilisateur u ON c.ref_utilisateur = u.id_user
                ORDER BY c.date_commande DESC';
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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

    /* ===========================================================
       ===================== MÉTHODES STATISTIQUES =====================
       =========================================================== */

    // Commandes par magasin
    public function getCommandesParMagasin(): array
    {
        $query = "
            SELECT m.nom, COUNT(c.id_commande) AS nb_commandes
            FROM commande c
            INNER JOIN magasin m ON c.ref_magasin = m.id_magasin
            GROUP BY m.nom
        ";
        $stmt = $this->db->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Total des commandes
    public function getTotalCommandes(): int
    {
        return (int)$this->db->query("SELECT COUNT(*) FROM commande")->fetchColumn();
    }

    // État des commandes
    public function getEtatCommandes(): array
    {
        $query = "SELECT etat, COUNT(*) AS nb_commandes FROM commande GROUP BY etat";
        $stmt = $this->db->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Commandes des 30 derniers jours
    public function getCommandesDerniers30Jours(): array
    {
        $query = "
            SELECT DATE(c.date_commande) AS jour, COUNT(*) AS nb_commandes
            FROM commande c
            WHERE c.date_commande >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
            GROUP BY DATE(c.date_commande)
            ORDER BY DATE(c.date_commande)
        ";
        $stmt = $this->db->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Top 10 clients par nombre de commandes
    public function getCommandesParClientTop10(): array
    {
        $query = "
            SELECT u.nom, u.prenom, COUNT(c.id_commande) AS nb_commandes
            FROM commande c
            JOIN utilisateur u ON c.ref_utilisateur = u.id_user
            GROUP BY u.id_user
            ORDER BY nb_commandes DESC
            LIMIT 10
        ";
        $stmt = $this->db->query($query);
        $data = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $data[] = ['client' => $row['prenom'] . ' ' . $row['nom'], 'nb_commandes' => (int)$row['nb_commandes']];
        }
        return $data;
    }

    // Compter commandes en cours
    public function countCommandesEnCours(): int
    {
        $sql = 'SELECT COUNT(*) as total 
                FROM commande 
                WHERE etat IN ("en attente", "préparée", "expédiée")';
        $stmt = $this->db->query($sql);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int) ($result['total'] ?? 0);
    }

    // Dernières commandes par état
    public function getDernieresCommandesParEtat(): array
    {
        $sql = 'SELECT c.*, m.nom AS nom_magasin, u.nom AS nom_utilisateur, u.prenom AS prenom_utilisateur  
                FROM commande c
                LEFT JOIN magasin m ON c.ref_magasin = m.id_magasin
                LEFT JOIN utilisateur u ON c.ref_utilisateur = u.id_user
                WHERE c.etat IN ("en attente", "préparée", "expédiée")
                ORDER BY c.date_commande DESC
                LIMIT 10';
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
