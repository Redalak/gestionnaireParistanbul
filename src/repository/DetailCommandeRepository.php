<?php

namespace repository;

require_once __DIR__ . '/../bdd/Bdd.php';
require_once __DIR__ . '/../model/User.php';

use bdd\Bdd;
use model\User;
use PDO;

class DetailCommandeRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = (new Bdd())->getBdd();
    }

    // Total par magasin
    public function getCoutsParMagasin(): array
    {
        $sql = "
            SELECT
                m.id_magasin,
                m.nom,
                SUM(dc.quantite * dc.prix_unitaire) AS total_cout,
                COUNT(DISTINCT c.id_commande) AS nb_commandes
            FROM magasin m
            INNER JOIN commande c ON c.ref_magasin = m.id_magasin
            INNER JOIN commande_detail dc ON dc.ref_commande = c.id_commande
            GROUP BY m.id_magasin
            ORDER BY total_cout DESC
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Détails des commandes d’un magasin
    public function getDetailsByMagasin(int $id_magasin): array
    {
        $sql = "
            SELECT
                c.id_commande,
                c.date_commande,
                p.libelle,
                p.marque,
                cd.quantite,
                cd.prix_unitaire,
                (cd.quantite * cd.prix_unitaire) AS total_ligne
            FROM commande c
            INNER JOIN commande_detail cd ON cd.ref_commande = c.id_commande
            INNER JOIN produit p ON p.id_produit = cd.ref_produit
            WHERE c.ref_magasin = :id_magasin
            ORDER BY c.date_commande DESC, p.libelle ASC
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id_magasin', $id_magasin, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getDetailCommande(int $id): array
    {
        $sql = "
        SELECT 
            p.libelle, 
            p.marque, 
            cd.quantite, 
            cd.prix_unitaire,
            (cd.quantite * cd.prix_unitaire) AS total_ligne
        FROM commande_detail cd
        LEFT JOIN produit p ON cd.ref_produit = p.id_produit
        WHERE cd.ref_commande = ?
    ";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log('Erreur SQL dans getDetailCommande : ' . $e->getMessage());
            return [];
        }
    }

}
