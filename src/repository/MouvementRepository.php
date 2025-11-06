<?php

namespace repository;

use bdd\Bdd;
use model\Mouvement;
use PDO;

class MouvementRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = (new Bdd())->getBdd();
    }
    
    /**
     * Récupère tous les mouvements de la base de données
     * @return array Tableau d'objets Mouvement
     */
    public function getAllMouvements(): array
    {
        $sql = 'SELECT m.*, p.libelle as produit_libelle, mag.nom as magasin_nom 
                FROM mouvement m
                LEFT JOIN produit p ON m.ref_produit = p.id_produit
                LEFT JOIN magasin mag ON m.ref_magasin = mag.id_magasin
                ORDER BY m.date_mouvement DESC';
                
        try {
            $stmt = $this->db->query($sql);
            if ($stmt === false) {
                $error = $this->db->errorInfo();
                throw new \PDOException("Erreur SQL: " . ($error[2] ?? 'Inconnue'));
            }
        
            $results = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $mouvementData = [
                    'idMouvement' => $row['id_mouvement'],
                    'refProduit' => $row['ref_produit'],
                    'refMagasin' => $row['ref_magasin'],
                    'type' => $row['type'],
                    'quantite' => $row['quantite'],
                    'source' => $row['source'],
                    'dateMouvement' => $row['date_mouvement'],
                    'commentaire' => $row['commentaire']
                ];
                
                $mouvement = new Mouvement($mouvementData);
                $mouvement->setData('produit_libelle', $row['produit_libelle'] ?? 'Inconnu');
                $mouvement->setData('magasin_nom', $row['magasin_nom'] ?? 'Inconnu');
                $results[] = $mouvement;
            }
            
            return $results;
        } catch (\PDOException $e) {
            // Afficher l'erreur pour le débogage
            die("Erreur lors de la récupération des mouvements: " . $e->getMessage() . "<br>Requête: " . $sql);
        }
    }

    public function create(Mouvement $mouvement): bool
    {
        $sql = 'INSERT INTO mouvement (ref_produit, ref_magasin, type, quantite, source, date_mouvement, commentaire) 
                VALUES (:ref_produit, :ref_magasin, :type, :quantite, :source, :date_mouvement, :commentaire)';
        
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute([
            'ref_produit' => $mouvement->getRefProduit(),
            'ref_magasin' => $mouvement->getRefMagasin(),
            'type' => $mouvement->getType(),
            'quantite' => $mouvement->getQuantite(),
            'source' => $mouvement->getSource(),
            'date_mouvement' => $mouvement->getDateMouvement(),
            'commentaire' => $mouvement->getCommentaire()
        ]);
    }

    public function findWithFilters(
        ?int $produitId = null,
        ?int $magasinId = null,
        ?string $type = null,
        ?string $dateDebut = null,
        ?string $dateFin = null,
        int $limit = 20,
        int $offset = 0
    ): array {
        $sql = 'SELECT m.*, p.libelle as produit_libelle, mag.nom as magasin_nom 
                FROM mouvement m
                LEFT JOIN produit p ON m.ref_produit = p.id_produit
                LEFT JOIN magasin mag ON m.ref_magasin = mag.id_magasin
                WHERE 1=1';
        
        $params = [];
        
        if ($produitId !== null) {
            $sql .= ' AND m.ref_produit = :produit_id';
            $params['produit_id'] = $produitId;
        }
        
        if ($magasinId !== null) {
            $sql .= ' AND m.ref_magasin = :magasin_id';
            $params['magasin_id'] = $magasinId;
        }
        
        if ($type !== null) {
            $sql .= ' AND m.type = :type';
            $params['type'] = $type;
        }
        
        if ($dateDebut !== null) {
            $sql .= ' AND m.date_mouvement >= :date_debut';
            $params['date_debut'] = $dateDebut . ' 00:00:00';
        }
        
        if ($dateFin !== null) {
            $sql .= ' AND m.date_mouvement <= :date_fin';
            $params['date_fin'] = $dateFin . ' 23:59:59';
        }
        
        $sql .= ' ORDER BY m.date_mouvement DESC';
        $sql .= ' LIMIT :limit OFFSET :offset';
        
        $stmt = $this->db->prepare($sql);
        
        // Liaison des paramètres
        foreach ($params as $key => $value) {
            $stmt->bindValue(':' . $key, $value);
        }
        
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        
        $stmt->execute();
        
        $results = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // Créer un tableau avec les noms de champs corrects pour le constructeur
            $mouvementData = [
                'idMouvement' => $row['id_mouvement'],
                'refProduit' => $row['ref_produit'],
                'refMagasin' => $row['ref_magasin'],
                'type' => $row['type'],
                'quantite' => $row['quantite'],
                'source' => $row['source'],
                'dateMouvement' => $row['date_mouvement'],
                'commentaire' => $row['commentaire']
            ];
            
            $mouvement = new Mouvement($mouvementData);
            // Ajout des données supplémentaires
            $mouvement->setData('produit_libelle', $row['produit_libelle'] ?? 'Inconnu');
            $mouvement->setData('magasin_nom', $row['magasin_nom'] ?? 'Inconnu');
            $results[] = $mouvement;
        }
        
        return $results;
    }

    public function countWithFilters(
        ?int $produitId = null,
        ?int $magasinId = null,
        ?string $type = null,
        ?string $dateDebut = null,
        ?string $dateFin = null
    ): int {
        $sql = 'SELECT COUNT(*) as total FROM mouvement m WHERE 1=1';
        $params = [];
        
        if ($produitId !== null) {
            $sql .= ' AND m.ref_produit = :produit_id';
            $params['produit_id'] = $produitId;
        }
        
        if ($magasinId !== null) {
            $sql .= ' AND m.ref_magasin = :magasin_id';
            $params['magasin_id'] = $magasinId;
        }
        
        if ($type !== null) {
            $sql .= ' AND m.type = :type';
            $params['type'] = $type;
        }
        
        if ($dateDebut !== null) {
            $sql .= ' AND m.date_mouvement >= :date_debut';
            $params['date_debut'] = $dateDebut . ' 00:00:00';
        }
        
        if ($dateFin !== null) {
            $sql .= ' AND m.date_mouvement <= :date_fin';
            $params['date_fin'] = $dateFin . ' 23:59:59';
        }
        
        $stmt = $this->db->prepare($sql);
        
        foreach ($params as $key => $value) {
            $stmt->bindValue(':' . $key, $value);
        }
        
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return (int)($result['total'] ?? 0);
    }
}
