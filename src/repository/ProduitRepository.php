<?php
declare(strict_types=1);

namespace repository;

require_once __DIR__ . '/../bdd/Bdd.php';
// garde le model si tu en as besoin pour tes autres écrans
require_once __DIR__ . '/../model/Produit.php';

use bdd\Bdd;
use model\Produit;
use PDO;

final class ProduitRepository
{
    private const TABLE = 'produit';    // aligné avec le schéma existant (singulier)

    private function db(): PDO {
        return (new Bdd())->getBdd();
    }

    /* =========================
       ========== VUE ==========
       ========================= */

    /**
     * Liste paginable/triable/filtrable pour listeProduits.php
     * Retourne des tableaux associatifs avec clés attendues par la vue:
     *  id, nom, categorie, quantite, prix_unitaire
     */
    public function searchList(
        string $search = '',
        string $catFilter = '',
        string $sortField = 'id',
        string $sortDir = 'DESC'
    ): array {
        $pdo = $this->db();

        // Whitelist colonnes de tri
        $allowed = [
            'id'            => 'p.id_produit',
            'nom'           => 'p.libelle',
            'quantite'      => 'p.nb_unite_pack',
            'prix_unitaire' => 'p.prix_unitaire',
        ];
        $orderBy = $allowed[$sortField] ?? $allowed['id'];
        $dir     = (strtoupper($sortDir) === 'ASC') ? 'ASC' : 'DESC';

        $where  = [];
        $params = [];

        if ($search !== '') {
            $where[] = '(p.libelle LIKE :q OR c.nom LIKE :q)';
            $params[':q'] = '%'.$search.'%';
        }
        if ($catFilter !== '') {
            $where[] = 'c.nom = :cat';
            $params[':cat'] = $catFilter;
        }
        $sqlWhere = $where ? 'WHERE '.implode(' AND ', $where) : '';

        $sql = "
            SELECT
                p.id_produit                 AS id,
                p.libelle                    AS nom,
                c.nom                        AS categorie,
                COALESCE(p.nb_unite_pack,0)  AS quantite,
                p.prix_unitaire
            FROM ".self::TABLE." p
            LEFT JOIN categorie c ON c.id_categorie = p.ref_categorie
            $sqlWhere
            ORDER BY $orderBy $dir, p.id_produit DESC
        ";

        $st = $pdo->prepare($sql);
        $st->execute($params);
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Liste des noms de catégories pour le <select> filtre */
    public function allCategories(): array {
        $pdo = $this->db();
        return $pdo->query("SELECT DISTINCT nom FROM categorie ORDER BY nom")
            ->fetchAll(PDO::FETCH_COLUMN);
    }

    /**
     * Trouver un produit pour updateProduit.php
     * Retourne un tableau normalisé: id, nom, categorie, quantite, prix_unitaire
     */
    public function find(int $id): ?array {
        $pdo = $this->db();
        $st = $pdo->prepare("
            SELECT
                p.id_produit                 AS id,
                p.libelle                    AS nom,
                COALESCE(c.nom, '')          AS categorie,
                COALESCE(p.nb_unite_pack,0)  AS quantite,
                p.prix_unitaire              AS prix_unitaire
            FROM ".self::TABLE." p
            LEFT JOIN categorie c ON c.id_categorie = p.ref_categorie
            WHERE p.id_produit = :id
        ");
        $st->execute([':id' => $id]);
        $row = $st->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    /** Créer un produit minimal depuis le formulaire d'ajout */
    public function create(
        string $nom,
        int $categorieId,
        int $quantiteCentrale,
        float $prix,
        int $seuilAlerte,
        string $dateAjout // format 'Y-m-d'
    ): int {
        $pdo = $this->db();
        $sql = "INSERT INTO ".self::TABLE." (libelle, ref_categorie, quantite_centrale, prix_unitaire, seuil_alerte, date_ajout)
                VALUES (:nom, :cat, :qte, :prix, :seuil, :date_ajout)";
        $st = $pdo->prepare($sql);
        $st->execute([
            ':nom'        => $nom,
            ':cat'        => $categorieId,
            ':qte'        => $quantiteCentrale,
            ':prix'       => $prix,
            ':seuil'      => $seuilAlerte,
            ':date_ajout' => $dateAjout,
        ]);
        return (int)$pdo->lastInsertId();
    }

    /** Mettre à jour un produit depuis updateProduit.php */
    public function update(int $id, string $nom, int $categorieId, int $quantite, float $prix): bool {
        $pdo = $this->db();
        $sql = "UPDATE ".self::TABLE." SET
                    libelle = :nom,
                    ref_categorie = :cat,
                    nb_unite_pack = :qte,
                    prix_unitaire = :prix
                WHERE id_produit = :id";
        $st = $pdo->prepare($sql);
        return $st->execute([
            ':id'   => $id,
            ':nom'  => $nom,
            ':cat'  => $categorieId,
            ':qte'  => $quantite,
            ':prix' => $prix,
        ]);
    }

    /** Supprimer un produit par ID (alias pratique) */
    public function delete(int $id): bool {
        $pdo = $this->db();
        $st = $pdo->prepare('DELETE FROM '.self::TABLE.' WHERE id_produit = :id');
        return $st->execute([':id' => $id]);
    }

    /* ===================================
       ========== CRUD existants ==========
       =================================== */

    // Ajouter (table corrigée)
    public function ajoutProduit(Produit $produit): Produit {
        $db = $this->db();
        $req = $db->prepare('
            INSERT INTO '.self::TABLE.' (
                libelle, marque, origine, ref_sous_categorie, ref_categorie,
                reference_produit, code_barre, unite_mesure, unite_ou_pack,
                nb_unite_pack, bio, halal, vegan, prix_unitaire
            ) VALUES (
                :libelle, :marque, :origine, :ref_sous_categorie, :ref_categorie,
                :reference_produit, :code_barre, :unite_mesure, :unite_ou_pack,
                :nb_unite_pack, :bio, :halal, :vegan, :prix_unitaire
            )
        ');
        $req->execute([
            'libelle'            => $produit->getLibelle(),
            'marque'             => $produit->getMarque(),
            'origine'            => $produit->getOrigine(),
            'ref_sous_categorie' => $produit->getRefSousCategorie(),
            'ref_categorie'      => $produit->getRefCategorie(),
            'reference_produit'  => $produit->getReferenceProduit(),
            'code_barre'         => $produit->getCodeBarre(),
            'unite_mesure'       => $produit->getUniteMesure(),
            'unite_ou_pack'      => $produit->getUniteOuPack(),
            'nb_unite_pack'      => $produit->getNbUnitePack(),
            'bio'                => $produit->getBio(),
            'halal'              => $produit->getHalal(),
            'vegan'              => $produit->getVegan(),
            'prix_unitaire'      => $produit->getPrixUnitaire(),
        ]);
        return $produit;
    }

    // Modifier
    public function modifProduit(Produit $produit): Produit {
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

        $stmt->execute([
            ':libelle' => $produit->getLibelle(),
            ':marque' => $produit->getMarque(),
            ':quantite_centrale' => $produit->getQuantiteCentrale(),
            ':prix_unitaire' => $produit->getPrixUnitaire(),
            ':seuil_alerte' => $produit->getSeuilAlerte(),
            ':ref_categorie' => $produit->getRefCategorie(),
            ':date_ajout' => $produit->getDateAjout(),
            ':id_produit' => $produit->getIdProduit()
        ]);

        return $produit;
    }


    // Supprimer
    public function suppProduit(int $idProduit): void {
        $db = $this->db();
        $req = $db->prepare('DELETE FROM '.self::TABLE.' WHERE id_produit = :id_produit');
        $req->execute(['id_produit' => $idProduit]);
    }

    // Lister tous (retourne des objets Produit comme avant)
    public function listeProduits(): array {
        $db = $this->db();
        $stmt = $db->prepare('SELECT * FROM produit ORDER BY id_produit DESC');
        $stmt->execute();
        return array_map(fn($row) => new Produit($row), $stmt->fetchAll(PDO::FETCH_ASSOC));
    }


    // Derniers produits
    public function getDerniersProduits(int $limit = 5): array {
        $db = $this->db();
        $st = $db->prepare('SELECT * FROM '.self::TABLE.' ORDER BY id_produit DESC LIMIT :lim');
        $st->bindValue(':lim', $limit, PDO::PARAM_INT);
        $st->execute();
        $rows = $st->fetchAll(PDO::FETCH_ASSOC);
        return array_map(fn($r) => new Produit($r), $rows);
    }

    // Compter
    public function nbProduits(): int {
        $db = $this->db();
        $req = $db->query('SELECT COUNT(*) AS total FROM '.self::TABLE);
        return (int)$req->fetch(PDO::FETCH_ASSOC)['total'];
    }

    // Récup produit par ID (objet)
    public function getProduitParId(int $idProduit): ?Produit {
        $db = $this->db();
        $st = $db->prepare('SELECT * FROM produit WHERE id_produit = :id');
        $st->execute([':id' => $idProduit]);
        $row = $st->fetch(PDO::FETCH_ASSOC);
        return $row ? new Produit($row) : null;
    }

    // Recherche par libellé (objets)
    public function getProduitsParLibelle(string $libelle): array {
        $db = $this->db();
        $st = $db->prepare('SELECT * FROM  WHERE libelle LIKE :q');
        $st->execute([':q' => '%'.$libelle.'%']);
        $rows = $st->fetchAll(PDO::FETCH_ASSOC);
        return array_map(fn($r) => new Produit($r), $rows);
    }

    // Filtre par catégorie (objets)
    public function getProduitsParCategorie(int $refCategorie): array {
        $db = $this->db();
        $st = $db->prepare('SELECT * FROM  WHERE ref_categorie = :c');
        $st->execute([':c' => $refCategorie]);
        $rows = $st->fetchAll(PDO::FETCH_ASSOC);
        return array_map(fn($r) => new Produit($r), $rows);
    }
}