<?php
declare(strict_types=1);

namespace repository;

require_once __DIR__ . '/../bdd/Bdd.php';
require_once __DIR__ . '/../model/Produit.php';

use bdd\Bdd;
use model\Produit;
use PDO;

final class ProduitRepository
{
    private PDO $db;
    private const TABLE = 'produit';

    // ✅ constructeur corrigé pour recevoir un PDO ou créer un nouveau
    public function __construct(?PDO $pdo = null)
    {
        $this->db = $pdo ?? (new Bdd())->getBdd();
    }

    /* =========================
       ========== VUE ==========
       ========================= */

    /**
     * Liste paginable/triable/filtrable pour listeProduits.php
     * Retourne des tableaux associatifs avec clés attendues par la vue:
     *  id, nom, categorie, quantite, prix_unitaire
     */

    /** Liste des noms de catégories pour le <select> filtre */
    public function allCategories(): array {
        return $stmt = $this->db->prepare("SELECT DISTINCT nom FROM categorie ORDER BY nom")
            ->fetchAll(PDO::FETCH_COLUMN);
    }

    /**
     * Trouver un produit pour updateProduit.php
     * Retourne un tableau normalisé: id, nom, categorie, quantite, prix_unitaire
     */
    public function findProduit(int $id): ?array {
        $stmt = $this->db->prepare("
            SELECT
                p.id_produit                 AS id,
                p.libelle                    AS nom,
                COALESCE(c.nom, '')          AS categorie,
                COALESCE(p.quantite_centrale,0)  AS quantite,
                p.prix_unitaire              AS prix_unitaire
            FROM produit p
            LEFT JOIN categorie c ON c.id_categorie = p.ref_categorie
            WHERE p.id_produit = :id
        ");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    /** Créer un produit */
    public function create(
        string $nom,
        int $categorieId,
        int $quantiteCentrale,
        float $prix,
        int $seuilAlerte,
        string $dateAjout // format 'Y-m-d'
    ): int {
        $sql = "INSERT INTO ".self::TABLE." (libelle, ref_categorie, quantite_centrale, prix_unitaire, seuil_alerte, date_ajout)
                VALUES (:nom, :cat, :qte, :prix, :seuil, :date_ajout)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':nom'        => $nom,
            ':cat'        => $categorieId,
            ':qte'        => $quantiteCentrale,
            ':prix'       => $prix,
            ':seuil'      => $seuilAlerte,
            ':date_ajout' => $dateAjout,
        ]);
        return (int)$stmt->lastInsertId();
    }

    /** Mettre à jour un produit depuis updateProduit.php */

    /** Supprimer un produit par ID (alias pratique) */
    public function delete(int $id): bool {
        $stmt = $this->db->prepare('DELETE FROM produit WHERE id_produit = :id');
        return $stmt->execute([':id' => $id]);
    }
    /* ===================================
       ========== CRUD existants ==========
       =================================== */
    // Ajouter (table corrigée)
    public function ajoutProduit(Produit $produit): Produit {
        $db = $this->db();
        $req = $db->prepare('
            INSERT INTO produit(
                libelle,marque,quantite_centrale,prix_unitaire,seuil_alerte,ref_categorie,date_ajout
            ) VALUES (
                :libelle,:marque,:quantite_centrale,:prix_unitaire,:seuil_alerte,:ref_categorie,:date_ajout
            )
        ');
        $req->execute([
            'libelle'            => $produit->getLibelle(),
            'marque'             => $produit->getMarque(),
            'quantite_centrale'  => $produit->getQuantiteCentrale(),
            'prix_unitaire'      => $produit->getPrixUnitaire(),
            'seuil_alerte'       => $produit->getSeuilAlerte(),
            'ref_categorie'      => $produit->getRefCategorie(),
            'date_ajout'         => $produit->getDateAjout(),
        ]);
        return $produit;
    }
    // Modifier
    public function updateProduit(Produit $produit): bool
    {
        $db = $this->db();

        $sql = "UPDATE produit SET
                libelle = :libelle,
                marque = :marque,
                quantite_centrale = :quantite_centrale,
                prix_unitaire = :prix_unitaire,
                seuil_alerte = :seuil_alerte,
                ref_categorie = :ref_categorie,
                date_ajout = :date_ajout
            WHERE id_produit = :id_produit";

        $stmt = $db->prepare($sql);

        return $stmt->execute([
            ':libelle'           => $produit->getLibelle(),
            ':marque'            => $produit->getMarque(),
            ':quantite_centrale' => $produit->getQuantiteCentrale(),
            ':prix_unitaire'     => $produit->getPrixUnitaire(),
            ':seuil_alerte'      => $produit->getSeuilAlerte(),
            ':ref_categorie'     => $produit->getRefCategorie(),
            ':date_ajout'        => $produit->getDateAjout(),
            ':id_produit'        => $produit->getIdProduit()
        ]);
    }



    // Supprimer
    public function suppProduit(int $idProduit): void {
        $req = $this->db->prepare('DELETE FROM produit WHERE id_produit = :id_produit');
        $req->execute(['id_produit' => $idProduit]);
    }

    // Lister tous (retourne des objets Produit comme avant)
    public function listeProduits(): array {
        $stmt = $this->db->prepare('SELECT * FROM produit ORDER BY id_produit DESC');
        $stmt->execute();
        return array_map(fn($row) => new Produit($row), $stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    /**
     * Récupère les derniers produits ajoutés
     *
     * @param int $limit Nombre maximum de produits à retourner (par défaut 5)
     * @return array Tableau de tableaux associatifs représentant les produits
     */
    public function getDerniersProduits(int $limit = 5): array {
        $stmt = $this->db->prepare('SELECT p.*, c.nom as nom_categorie 
                           FROM '.self::TABLE.' p 
                           LEFT JOIN categorie c ON p.ref_categorie = c.id_categorie 
                           ORDER BY p.id_produit DESC 
                           LIMIT :lim');
        $stmt->bindValue(':lim', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Compter le nombre total de produits uniques
    public function nbProduits(): int {
        $stmt = $this->db->query('SELECT COUNT(DISTINCT id_produit) as total FROM '.self::TABLE);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)($result['total'] ?? 0);
    }

    // Compter le nombre de produits en alerte de stock
    public function nbProduitsEnAlerte(): int {

        $stmt = $this->db->query('SELECT COUNT(*) as total FROM '.self::TABLE.' WHERE quantite_centrale <= seuil_alerte');
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)($result['total'] ?? 0);
    }

    // Récupérer les produits en dessous du seuil d'alerte
    public function getProduitsSousSeuil(): array {
        try {
            $sql = 'SELECT p.*, c.nom as nom_categorie 
                    FROM '.self::TABLE.' p 
                    LEFT JOIN categorie c ON p.ref_categorie = c.id_categorie 
                    WHERE p.quantite_centrale <= p.seuil_alerte
                    AND p.quantite_centrale IS NOT NULL
                    ORDER BY p.quantite_centrale ASC';

            $stmt = $this->db->query($sql);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Log pour le débogage
            error_log('Produits sous seuil: ' . print_r($result, true));

            return $result;
        } catch (\PDOException $e) {
            error_log('Erreur dans getProduitsSousSeuil: ' . $e->getMessage());
            return [];
        }
    }

    // Récup produit par ID (objet)
    public function getProduitParId(int $idProduit): ?Produit {

        $stmt = $this->db->prepare('SELECT * FROM produit WHERE id_produit = :id');
        $stmt->execute([':id' => $idProduit]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? new Produit($row) : null;
    }

    // Recherche par libellé (objets)
    public function getProduitsParLibelle(string $libelle): array {

        $stmt = $this->db->query('SELECT * FROM produit WHERE libelle LIKE :q');
        $stmt->execute([':q' => '%'.$libelle.'%']);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(fn($r) => new Produit($r), $rows);
    }

    // Filtre par catégorie (objets)
    public function getProduitsParCategorie(int $refCategorie): array {
        $stmt = $this->db->prepare('SELECT * FROM produit WHERE ref_categorie = :c');
        $stmt->execute([':c' => $refCategorie]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(fn($r) => new Produit($r), $rows);
    }

    /**
     * Récupère le stock total par catégorie
     * @return array Tableau associatif [nom_catégorie => quantité_totale]
     */
    public function getStockParCategorie(): array {
        $sql = 'SELECT 
                    c.nom as categorie,
                    COUNT(p.id_produit) as nb_produits,
                    SUM(p.quantite_centrale) as quantite_totale
                FROM categorie c
                LEFT JOIN produit p ON c.id_categorie = p.ref_categorie
                GROUP BY c.id_categorie, c.nom
                ORDER BY c.nom';

        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /** ========================= Méthodes statistiques ========================= */

    public function getTotalProduits(): int {
        return (int)$this->db->query("SELECT COUNT(*) FROM produit")->fetchColumn();
    }


    public function getTopProduitsVendus(): array {
        $stmt = $this->db->query("
            SELECT p.libelle, SUM(cd.quantite) AS total_vendu
            FROM commande_detail cd
            JOIN produit p ON cd.ref_produit = p.id_produit
            GROUP BY p.id_produit
            ORDER BY total_vendu DESC
            LIMIT 10
        ");
        $data = [];
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $data[] = ['produit' => $row['libelle'], 'quantite' => (int)$row['total_vendu']];
        }
        return $data;
    }

    public function getValeurTotaleCommandes(): float {
        $val = $this->db->query("SELECT SUM(quantite * prix_unitaire) AS valeur_totale FROM commande_detail")->fetchColumn();
        return (float)$val ;
    }

    public function getStocksParCategorie(): array {
        $stmt = $this->db->query('
            SELECT c.nom as categorie, COUNT(p.id_produit) as nb_produits, SUM(p.quantite_centrale) as quantite_totale
            FROM categorie c
            LEFT JOIN produit p ON c.id_categorie = p.ref_categorie
            GROUP BY c.id_categorie, c.nom
            ORDER BY c.nom
        ');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getProduitsParCategorieStats(): array {
        $sql = "
        SELECT 
            c.nom AS categorie,
            COUNT(p.id_produit) AS nb_produits,
            SUM(p.quantite_centrale) AS quantite_totale
        FROM categorie c
        LEFT JOIN produit p ON c.id_categorie = p.ref_categorie
        GROUP BY c.id_categorie, c.nom
        ORDER BY c.nom
    ";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
