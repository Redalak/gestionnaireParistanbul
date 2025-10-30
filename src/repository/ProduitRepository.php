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
    private const TABLE = 'produits';   // <-- IMPORTANT: au pluriel

    private function db(): PDO {
        return (new Bdd())->getBdd();
    }

    /* =========================
       ========== VUE ==========
       ========================= */

    /**
     * Liste paginable/triable/filtrable pour produit.php
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
     * Trouver un produit au format tableau pour modifier.php
     * (clés adaptées à la vue)
     */
    public function find(int $id): ?array {
        $pdo = $this->db();
        $st = $pdo->prepare("
            SELECT
                p.id_produit,
                p.libelle,
                p.ref_categorie,
                p.nb_unite_pack,
                p.prix_unitaire,
                c.nom AS categorie_nom
            FROM ".self::TABLE." p
            LEFT JOIN categorie c ON c.id_categorie = p.ref_categorie
            WHERE p.id_produit = :id
        ");
        $st->execute([':id' => $id]);
        $row = $st->fetch(PDO::FETCH_ASSOC);
        if (!$row) return null;

        // mapping simple si ta vue attend d’autres noms
        return [
            'id_produit'   => (int)$row['id_produit'],
            'libelle'      => $row['libelle'],
            'ref_categorie'=> $row['ref_categorie'],
            'categorie'    => $row['categorie_nom'],
            'nb_unite_pack'=> (int)($row['nb_unite_pack'] ?? 0),
            'prix_unitaire'=> (float)$row['prix_unitaire'],
        ];
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
        $req = $db->prepare('
            UPDATE '.self::TABLE.' SET
                libelle = :libelle,
                marque = :marque,
                origine = :origine,
                ref_sous_categorie = :ref_sous_categorie,
                ref_categorie = :ref_categorie,
                reference_produit = :reference_produit,
                code_barre = :code_barre,
                unite_mesure = :unite_mesure,
                unite_ou_pack = :unite_ou_pack,
                nb_unite_pack = :nb_unite_pack,
                bio = :bio,
                halal = :halal,
                vegan = :vegan,
                prix_unitaire = :prix_unitaire
            WHERE id_produit = :id_produit
        ');
        $req->execute([
            'id_produit'         => $produit->getIdProduit(),
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

    // Supprimer
    public function suppProduit(int $idProduit): void {
        $db = $this->db();
        $req = $db->prepare('DELETE FROM '.self::TABLE.' WHERE id_produit = :id_produit');
        $req->execute(['id_produit' => $idProduit]);
    }

    // Lister tous (retourne des objets Produit comme avant)
    public function listeProduits(): array {
        $db = $this->db();
        $req = $db->query('SELECT * FROM '.self::TABLE.' ORDER BY id_produit DESC');
        $rows = $req->fetchAll(PDO::FETCH_ASSOC);
        return array_map(fn($r) => new Produit($r), $rows);
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
        $st = $db->prepare('SELECT * FROM '.self::TABLE.' WHERE id_produit = :id');
        $st->execute([':id' => $idProduit]);
        $row = $st->fetch(PDO::FETCH_ASSOC);
        return $row ? new Produit($row) : null;
    }

    // Recherche par libellé (objets)
    public function getProduitsParLibelle(string $libelle): array {
        $db = $this->db();
        $st = $db->prepare('SELECT * FROM '.self::TABLE.' WHERE libelle LIKE :q');
        $st->execute([':q' => '%'.$libelle.'%']);
        $rows = $st->fetchAll(PDO::FETCH_ASSOC);
        return array_map(fn($r) => new Produit($r), $rows);
    }

    // Filtre par catégorie (objets)
    public function getProduitsParCategorie(int $refCategorie): array {
        $db = $this->db();
        $st = $db->prepare('SELECT * FROM '.self::TABLE.' WHERE ref_categorie = :c');
        $st->execute([':c' => $refCategorie]);
        $rows = $st->fetchAll(PDO::FETCH_ASSOC);
        return array_map(fn($r) => new Produit($r), $rows);
    }
}