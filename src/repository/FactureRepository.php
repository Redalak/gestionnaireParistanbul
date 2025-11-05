<?php

namespace repository;

use bdd\Bdd;
use model\Facture;
use PDO;

class FactureRepository
{
    private PDO $db;

    public function __construct(?PDO $pdo = null)
    {
        $this->db = $pdo ?? (new Bdd())->getBdd();
    }
    public function ajoutFacture(Facture $facture): bool
    {
        $sql = 'INSERT INTO facture (refUser,refCommande,datePaiement,paye)
                VALUES (:refUser, :refCommande, :datePaiement, :paye)';
        $stmt  = $this->db->prepare($sql);
        return $stmt->execute([
            'refUser'    => $facture->getRefUser(),
            'refCommande' => $facture->getRefCommande(),
            'datePaiement'    => $facture->getDatePaiement(),
            'paye'   => $facture->getPaye(),
        ]);
    }
    public function updateFacture(int $id_facture, array $data): bool
    {
        $allowed = ['refUser','refCommande','datePaiement','paye'];
        $set = [];
        $params = ['id_facture' => $id_facture];

        foreach ($allowed as $k) {
            if (array_key_exists($k, $data)) {
                $set[] = "$k = :$k";
                $params[$k] = $data[$k];
            }
        }

        if (!$set) return false;

        $sql = 'UPDATE facture SET ' . implode(', ', $set) . ' WHERE id_facture = :id_facture';
        $stmt  = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    public function getAllFactures() : array
    {
        $sql = 'SELECT * FROM facture ORDER BY id_facture DESC';
        $rows = $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        return array_map(fn($r) => new Facture($r), $rows);
    }
    public function getFactureById(int $idFacture): ?Facture
    {
        $sql = 'SELECT * FROM facture WHERE id_facture = :id_facture';
        $stmt  = $this->db->prepare($sql);
        $stmt->execute(['id_facture' => $idFacture]);
        $ligne = $stmt->fetch(PDO::FETCH_ASSOC);
        return $ligne ? new Facture($ligne) : null;
    }
    public function deleteFacture(int $id_facture): bool
    {
        $sql = 'DELETE FROM facture WHERE id_facture = :id_facture';
        $stmt  = $this->db->prepare($sql);
        return $stmt->execute(['id_facture' => $id_facture]);
    }

    /**
     * Récupère toutes les factures non payées (où paye = 0)
     *
     * @return array Tableau de tableaux associatifs représentant les factures impayées
     */
    public function getFacturesImpayees(): array
    {
        $sql = 'SELECT f.*, c.*, u.nom as nom_utilisateur, u.prenom as prenom_utilisateur
                FROM facture f
                LEFT JOIN commande c ON f.ref_commande = c.id_commande
                LEFT JOIN utilisateur u ON c.ref_utilisateur = u.id_user
                WHERE f.paye = 0
                ORDER BY f.date_emission ASC';
                
        try {
            $stmt = $this->db->query($sql);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            error_log('Erreur SQL dans getFacturesImpayees: ' . $e->getMessage());
            return [];
        }
    }
}