<?php
declare(strict_types=1);

namespace repository;

require_once __DIR__ . '/../bdd/Bdd.php';

use bdd\Bdd;
use PDO;

class ProduitRepository
{
    private const TABLE = 'produits';

    private function db(): PDO {
        return (new Bdd())->getBdd();
    }

    public function create(string $nom, int $refCategorie, int $nbUnites, float $prixUnitaire): int {
        $sql = 'INSERT INTO '.self::TABLE.' (libelle, ref_categorie, nb_unite_pack, prix_unitaire)
                VALUES (:libelle, :ref_categorie, :nb_unite_pack, :prix_unitaire)';
        $st  = $this->db()->prepare($sql);
        $st->execute([
            'libelle'        => $nom,
            'ref_categorie'  => $refCategorie,
            'nb_unite_pack'  => $nbUnites,
            'prix_unitaire'  => $prixUnitaire,
        ]);
        return (int)$this->db()->lastInsertId();
    }

    public function update(int $id, string $nom, int $refCategorie, int $nbUnites, float $prixUnitaire): bool {
        $sql = 'UPDATE '.self::TABLE.'
                SET libelle = :libelle,
                    ref_categorie = :ref_categorie,
                    nb_unite_pack = :nb_unite_pack,
                    prix_unitaire = :prix_unitaire
                WHERE id_produit = :id';
        $st  = $this->db()->prepare($sql);
        return $st->execute([
            'id'             => $id,
            'libelle'        => $nom,
            'ref_categorie'  => $refCategorie,
            'nb_unite_pack'  => $nbUnites,
            'prix_unitaire'  => $prixUnitaire,
        ]);
    }

    public function delete(int $id): bool {
        $st = $this->db()->prepare('DELETE FROM '.self::TABLE.' WHERE id_produit = :id');
        return $st->execute(['id' => $id]);
    }

    public function find(int $id): ?array {
        $sql = 'SELECT p.id_produit AS id,
                       p.libelle    AS nom,
                       c.nom        AS categorie,
                       COALESCE(p.nb_unite_pack,0) AS quantite,
                       p.prix_unitaire
                FROM '.self::TABLE.' p
                LEFT JOIN categorie c ON c.id_categorie = p.ref_categorie
                WHERE p.id_produit = :id';
        $st  = $this->db()->prepare($sql);
        $st->execute(['id' => $id]);
        $row = $st->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function searchList(string $search = '', string $catFilter = '', string $sortField = 'id', string $sortDir = 'DESC'): array {
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
        $sqlWhere = $where ? ('WHERE '.implode(' AND ', $where)) : '';

        $sql = 'SELECT p.id_produit AS id,
                       p.libelle    AS nom,
                       c.nom        AS categorie,
                       COALESCE(p.nb_unite_pack,0) AS quantite,
                       p.prix_unitaire
                FROM '.self::TABLE.' p
                LEFT JOIN categorie c ON c.id_categorie = p.ref_categorie
                '.$sqlWhere.'
                ORDER BY '.$orderBy.' '.$dir.', p.id_produit DESC';

        $st = $this->db()->prepare($sql);
        $st->execute($params);
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }

    public function allCategories(): array {
        return $this->db()->query('SELECT DISTINCT nom FROM categorie ORDER BY nom')
                          ->fetchAll(PDO::FETCH_COLUMN);
    }
}