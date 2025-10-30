<?php

namespace repository;
use model\Commande;
use model\Facture;
use model\Magasin;
use PDO ;
use bdd\Bdd;

class CommandeRepository
{
    private PDO $db;

    public function __construct(?PDO $pdo = null)
    {
        $this->db = $pdo ?? (new Bdd())->getBdd();
    }
    public function ajoutCommande(Commande $commande): bool
    {
        $sql = 'INSERT INTO commande (adresse_facturation,ref_fournisseur,ref_produit,ref_magasin,
                 ref_utilisateur , date_commande , date_arrivee ,quantite , total_ht , tva , total_ttc ,
                      remise , date_reglement , quantite_totale , mode_reglement)
                VALUES (:adresse_facturation, :ref_fournisseur, :ref_produit, :ref_magasin,:ref_utilisateur,
                        :date_commande ,:date_arrivee ,:quantite,:total_ht ,:tva ,:total_ttc , :remise,
                        :date_reglement , :quantite_totale , :mode_reglement )';
        $stmt  = $this->db->prepare($sql);
        return $stmt->execute([
            'adresse_facturation'    => $commande->getAdresseFacturation(),
            'ref_fournisseur' => $commande->getRefFournisseur(),
            'ref_produit'    => $commande->getRefProduit(),
            'ref_magasin'   => $commande->getRefMagasin(),
            'ref_utilisateur' => $commande->getRefUtilisateur(),
            'date_commande' => $commande->getDateCommande(),
            'date_arrivee' => $commande->getDateArrivee(),
            'quantite'     =>$commande->getQuantite(),
            'total_ht' => $commande->getTotalHT(),
            'tva'     => $commande->getTva(),
            'total_ttc' =>$commande->getTotalTTC(),
            'remise' => $commande->getRemise(),
            'date_reglement' => $commande->getDateReglement(),
            'quantite_totale' => $commande->getQuantiteTotale(),
            'mode_reglement' => $commande->getModeReglement() ,
        ]);
    }
    public function updateCommande(int $id_commande, array $data): bool {
        $allowed = ['adresse_facturation','ref_fournisseur','ref_produit','ref_magasin',
                 'ref_utilisateur' ,'date_commande' , 'date_arrivee' ,'quantite' , 'total_ht' , 'tva' , 'total_ttc' ,
                      'remise' , 'date_reglement' , 'quantite_totale' , 'mode_reglement'];
        $set = [];
        $params = ['id_commande' => $id_commande];

        foreach ($allowed as $k) {
            if (array_key_exists($k, $data)) {
                $set[] = "$k = :$k";
                $params[$k] = $data[$k];
            }
        }

        if (!$set) return false;

        $sql = 'UPDATE commandes SET ' . implode(', ', $set) . ' WHERE id_commande = :id_commande';
        $st  = $this->db->prepare($sql);
        return $st->execute($params);
    }
    public function deleteCommande(int $id_commande): bool
    {
        $sql = 'DELETE FROM commandes WHERE id_commande = :id_commande';
        $st  = $this->db->prepare($sql);
        return $st->execute(['id_commande' => $id_commande]);
    }
    public function getCommandeById(int $idCommande): ?Commande
    {
        $sql = 'SELECT * FROM commande WHERE id_commande = :id_commande';
        $stmt  = $this->db->prepare($sql);
        $stmt ->execute(['id_commande' => $idCommande]);
        $row = $stmt ->fetch(PDO::FETCH_ASSOC);
        return $row ? new Commande($row) : null;
    }
    public function getAllCommandes(): array {
        $sql = 'SELECT * FROM commandes';
        $stmt = $this->db->query($sql);
        $results = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $results[] = new Commande($row);
        }
        return $results;
    }
    public function getCommandesByUser(int $idUser): array {
        $sql = 'SELECT * FROM commandes WHERE ref_utilisateur = :idUser';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['user_id' => $idUser]);
        $results = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $results[] = new Commande($row);
        }
        return $results;
    }
    public function getCommandesByMagasin(int $idMagasin): array {
        $sql = 'SELECT * FROM commandes WHERE ref_magasin = :id_magasin';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id_magasin' => $idMagasin]);
        $results = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $results[] = new Commande($row);
        }
        return $results;
    }
    public function countCommandes(): int {
        $sql = 'SELECT COUNT(*) FROM commandes';
        return (int) $this->db->query($sql)->fetchColumn();
    }
    public function findCommandesByDate(string $startDate, string $endDate): array {
        $sql = 'SELECT * FROM commandes WHERE date_commande BETWEEN :start AND :end';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['start' => $startDate, 'end' => $endDate]);
        $results = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $results[] = new Commande($row);
        }
        return $results;
    }







}