<?php

namespace repository;

use bdd\Bdd;
use model\Commande;
use model\Facture;
use model\FicheConfirmationCommande;

class FicheConfirmationCommandeRepository
{
    private PDO $db;

    public function __construct(?PDO $pdo = null)
    {
        $this->db = $pdo ?? (new Bdd())->getBdd();
    }
    public function ajoutFiche(FicheConfirmationCommande $fiche): bool
    {
        $sql = 'INSERT INTO ficheconfirmationcommande (refCommande ,dateConfirmation,commentaire ,confirmePar)
                VALUES (:refCommande , :dateConfirmation, :commentaire, :confirmePar)';
        $stmt  = $this->db->prepare($sql);
        return $stmt->execute([
            'ref_commande'    => $fiche->getRefCommande(),
            'date_confirmation' => $fiche->getDateConfirmation(),
            'commentaiire'    => $fiche->getCommentaire(),
            'confirme_par'   => $fiche->getConfirmePar(),
        ]);
    }
    public function getAllFiches() : array
    {
        $sql = 'SELECT * FROM ficheconfirmatiponcommande ORDER BY id_fiche DESC';
        $rows = $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        return array_map(fn($r) => new Facture($r), $rows);
    }
    public function getFicheById(int $idFiche): ?FicheConfirmationCommande
    {
        $sql = 'SELECT * FROM ficheconfirmationcommande WHERE id_fiche = :id_fiche';
        $stmt  = $this->db->prepare($sql);
        $stmt->execute(['id_fiche' => $idFiche]);
        $ligne = $stmt->fetch(PDO::FETCH_ASSOC);
        return $ligne ? new FicheConfirmationCommande($ligne) : null;
    }
    public function updateFiche(int $id_fiche, array $data): bool
    {
        $allowed = ['ref_commande','date_confirmation','commentaire','confirme_par'];
        $set = [];
        $params = ['id_fiche' => $id_fiche];

        foreach ($allowed as $k) {
            if (array_key_exists($k, $data)) {
                $set[] = "$k = :$k";
                $params[$k] = $data[$k];
            }
        }

        if (!$set) return false;

        $sql = 'UPDATE ficheconfirmationcommande SET ' . implode(', ', $set) . ' WHERE id_fiche = :id_fichee';
        $stmt  = $this->db->prepare($sql);
        return $stmt->execute($params);
    }
    public function deleteFiche(int $id_fiche): bool
    {
        $sql = 'DELETE FROM ficheconfirmationcommande WHERE id_fiche = :id_fiche';
        $stmt  = $this->db->prepare($sql);
        return $stmt->execute(['id_facture' => $id_fiche]);
    }
}