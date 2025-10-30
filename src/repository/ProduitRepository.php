<?php
declare(strict_types=1);

require_once __DIR__ . '/../Bdd.php';

class ProduitRepository {
    private PDO $bdd;

    public function __construct() {
        $this->bdd = Bdd::connect();
    }

    // Récupère toutes les catégories distinctes pour alimenter le <select>
    public function allCategories(): array {
        $sql = "SELECT DISTINCT categorie FROM produits ORDER BY categorie ASC";
        $st = $this->bdd->query($sql);
        return $st->fetchAll(PDO::FETCH_COLUMN); // renvoie ["Boisson","Pâtisserie",...]
    }

    // Liste filtrée / triée
    public function searchList(
        ?string $query,
        ?string $categorie,
        string $sortField,
        string $sortDir
    ): array {
        // Sécurité du ORDER BY
        $allowedSort = [
            'nom' => 'nom',
            'quantite' => 'quantite',
            'prix_unitaire' => 'prix_unitaire',
            'date_ajout' => 'date_ajout'
        ];
        $allowedDir = ['ASC','DESC'];

        $orderBy = $allowedSort[$sortField] ?? 'nom';
        $dir = in_array(strtoupper($sortDir), $allowedDir, true) ? strtoupper($sortDir) : 'ASC';

        $where = [];
        $params = [];

        if ($query) {
            $where[] = '(nom LIKE ? OR categorie LIKE ?)';
            $like = '%' . $query . '%';
            $params[] = $like;
            $params[] = $like;
        }

        if ($categorie) {
            $where[] = 'categorie = ?';
            $params[] = $categorie;
        }

        $sql = "SELECT * FROM produits";
        if ($where) {
            $sql .= " WHERE " . implode(' AND ', $where);
        }
        $sql .= " ORDER BY $orderBy $dir";

        $st = $this->bdd->prepare($sql);
        $st->execute($params);
        return $st->fetchAll();
    }

    public function find(int $id): ?array {
        $st = $this->bdd->prepare("SELECT * FROM produits WHERE id = ?");
        $st->execute([$id]);
        $res = $st->fetch();
        return $res ?: null;
    }

    public function create(string $nom, string $categorie, int $quantite, float $prix): void {
        $st = $this->bdd->prepare("
            INSERT INTO produits (nom, categorie, quantite, prix_unitaire)
            VALUES (?, ?, ?, ?)
        ");
        $st->execute([$nom, $categorie, $quantite, $prix]);
    }

    public function update(int $id, string $nom, string $categorie, int $quantite, float $prix): void {
        $st = $this->bdd->prepare("
            UPDATE produits
            SET nom = ?, categorie = ?, quantite = ?, prix_unitaire = ?
            WHERE id = ?
        ");
        $st->execute([$nom, $categorie, $quantite, $prix, $id]);
    }

    public function delete(int $id): void {
        $st = $this->bdd->prepare("DELETE FROM produits WHERE id = ?");
        $st->execute([$id]);
    }
}