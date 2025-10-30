<?php
session_start();

require_once __DIR__ . '/../src/Bdd.php';
require_once __DIR__ . '/../src/repository/ProduitRepo.php';

$repo = new ProduitRepo();

/* Récupération des filtres GET */
$search     = isset($_GET['q']) ? trim($_GET['q']) : '';
$catFilter  = isset($_GET['cat']) ? trim($_GET['cat']) : '';
$sortField  = isset($_GET['sort']) ? $_GET['sort'] : 'nom';
$sortDir    = isset($_GET['dir']) ? $_GET['dir'] : 'ASC';

$liste      = $repo->searchList($search, $catFilter, $sortField, $sortDir);
$categories = $repo->allCategories();

$deleted = isset($_GET['deleted']) ? (int)$_GET['deleted'] : null;

/* Pour construire les liens de tri sans perdre la recherche */
function triLink(string $col, string $label, string $currentSort, string $currentDir, string $search, string $cat) {
    // toggle ASC <-> DESC si on reclique sur la même colonne
    $dir = 'ASC';
    if ($col === $currentSort && strtoupper($currentDir) === 'ASC') {
        $dir = 'DESC';
    }

    $params = http_build_query([
        'q'   => $search,
        'cat' => $cat,
        'sort'=> $col,
        'dir' => $dir,
    ]);

    // petite flèche visuelle
    $arrow = '';
    if ($col === $currentSort) {
        $arrow = strtoupper($currentDir) === 'ASC' ? '▲' : '▼';
    }

    return '<a href="produits.php?' . htmlspecialchars($params) . '" style="color:#666;text-decoration:none;">'
        . htmlspecialchars($label) . ' ' . $arrow . '</a>';
}

/**
 * Donne une classe CSS de niveau stock en fonction de la quantité
 */
function stockClass(int $q): string {
    if ($q <= 3) {
        return 'low-critical'; // rouge
    } elseif ($q <= 10) {
        return 'low-warning'; // orange
    }
    return '';
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produits — Paristanbul Stock</title>

    <style>
        :root {
            --primary: #c62828;
            --secondary: #000;
            --bg-page: #f5f5f5;
            --white: #fff;
            --radius: 14px;

            --warn-bg: #fff4db;
            --warn-text: #9c6500;

            --crit-bg: #fdecea;
            --crit-text: #c62828;
        }

        * {
            box-sizing: border-box;
            font-family: "Plus Jakarta Sans", Arial, sans-serif;
        }

        body {
            margin: 0;
            background: var(--bg-page);
            color: var(--secondary);
        }

        header {
            background: var(--secondary);
            color: white;
            padding: 1.2rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        header h1 {
            font-size: 1.4rem;
            margin: 0;
            letter-spacing: .03em;
        }

        header nav a {
            color: white;
            text-decoration: none;
            margin-left: 1.2rem;
            font-weight: 500;
            transition: opacity .2s;
        }
        header nav a:hover { opacity: 0.8; }

        main {
            max-width: 1100px;
            margin: 2rem auto 4rem;
            background: var(--white);
            padding: 2rem;
            border-radius: var(--radius);
            box-shadow: 0 10px 30px rgba(0,0,0,.08);
        }

        h2 {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            flex-wrap: wrap;
            margin: 0 0 1.5rem;
            font-size: 1.4rem;
        }

        .top-actions {
            display: flex;
            flex-wrap: wrap;
            gap: .8rem;
        }

        .btn {
            display: inline-block;
            background: var(--primary);
            color: #fff;
            padding: .7rem 1rem;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            font-size: .9rem;
            transition: background .2s;
        }
        .btn:hover {
            background: #a91f1f;
        }

        .msg {
            border-radius: 10px;
            padding: .8rem 1rem;
            font-size: .9rem;
            font-weight: 500;
            margin-bottom: 1rem;
        }
        .msg.ok {
            background: #e8f5e9;
            color: #2e7d32;
        }
        .msg.err {
            background: #ffebee;
            color: #c62828;
        }

        /* barre filtres */
        .filters {
            display: grid;
            grid-template-columns: 1fr 200px auto;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        @media(max-width: 800px){
            .filters {
                grid-template-columns: 1fr;
            }
        }

        .filters input[type="text"],
        .filters select {
            width: 100%;
            border: 1px solid #ccc;
            border-radius: 10px;
            padding: .6rem .7rem;
            font-size: .9rem;
        }

        .filters button {
            background: var(--secondary);
            color: #fff;
            border: none;
            font-size: .9rem;
            font-weight: 600;
            border-radius: 10px;
            padding: .7rem 1rem;
            cursor: pointer;
        }

        .filters button:hover {
            opacity: .9;
        }

        .table-wrapper {
            overflow-x: auto;
            border-radius: var(--radius);
            border: 1px solid #eee;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 760px;
            background: var(--white);
        }

        thead {
            background: #fafafa;
        }

        th {
            text-align: left;
            padding: .8rem .9rem;
            font-size: .75rem;
            font-weight: 600;
            text-transform: uppercase;
            color: #666;
            letter-spacing: .04em;
            border-bottom: 1px solid #eee;
        }

        td {
            padding: .8rem .9rem;
            font-size: .9rem;
            border-bottom: 1px solid #eee;
            vertical-align: top;
        }

        tr:hover td {
            background: #fcfcfc;
        }

        /* lignes d'alerte stock */
        tr.low-warning td {
            background: var(--warn-bg);
            color: var(--warn-text);
        }
        tr.low-critical td {
            background: var(--crit-bg);
            color: var(--crit-text);
            font-weight: 600;
        }

        .qty {
            font-weight: 600;
        }

        .price {
            font-weight: 600;
            white-space: nowrap;
        }

        .actions {
            display: flex;
            flex-wrap: wrap;
            gap: .5rem;
        }

        .mini-btn {
            display: inline-block;
            font-size: .75rem;
            text-decoration: none;
            border-radius: 8px;
            padding: .4rem .6rem;
            line-height: 1.2;
            font-weight: 600;
        }

        .mini-btn.edit {
            background: #111;
            color: #fff;
        }

        .mini-btn.delete {
            background: var(--primary);
            color: #fff;
        }

        footer {
            text-align: center;
            font-size: .85rem;
            color: #777;
            margin-top: 2rem;
        }
    </style>

</head>
<body>

<header>
    <h1>Paristanbul — Produits en stock</h1>
    <nav>
        <a href="index.php">Accueil</a>
        <a href="produits.php">Produits</a>
        <a href="ajouter.php">Ajouter</a>
    </nav>
</header>

<main>

    <h2>
        <span>Inventaire complet</span>
        <span class="top-actions">
            <a class="btn" href="ajouter.php">+ Nouveau produit</a>
        </span>
    </h2>

    <?php if ($deleted === 1): ?>
        <div class="msg ok">Produit supprimé.</div>
    <?php elseif ($deleted === 0): ?>
        <div class="msg err">Suppression impossible.</div>
    <?php endif; ?>

    <!-- Filtres -->
    <form class="filters" method="get" action="produits.php">
        <input
            type="text"
            name="q"
            placeholder="Rechercher (nom ou catégorie)…"
            value="<?= htmlspecialchars($search) ?>"
        />

        <select name="cat">
            <option value="">Toutes les catégories</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= htmlspecialchars($cat) ?>"
                    <?= $cat === $catFilter ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cat) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button type="submit">Filtrer</button>
    </form>

    <div class="table-wrapper">
        <table>
            <thead>
            <tr>
                <th>ID</th>
                <th><?= triLink('nom', 'Produit', $sortField, $sortDir, $search, $catFilter) ?></th>
                <th>Catégorie</th>
                <th><?= triLink('quantite', 'Quantité', $sortField, $sortDir, $search, $catFilter) ?></th>
                <th><?= triLink('prix_unitaire', 'Prix (€)', $sortField, $sortDir, $search, $catFilter) ?></th>
                <th><?= triLink('date_ajout', 'Ajouté le', $sortField, $sortDir, $search, $catFilter) ?></th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>

            <?php if (empty($liste)): ?>
                <tr>
                    <td colspan="7" style="text-align:center; padding:2rem; color:#888;">
                        Aucun résultat avec ces filtres.
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($liste as $p): ?>
                    <?php
                    $qte = (int)$p['quantite'];
                    $rowClass = stockClass($qte);
                    ?>
                    <tr class="<?= $rowClass ?>">
                        <td>#<?= (int)$p['id'] ?></td>
                        <td><?= htmlspecialchars($p['nom']) ?></td>
                        <td><?= htmlspecialchars($p['categorie']) ?></td>
                        <td class="qty"><?= $qte ?></td>
                        <td class="price">
                            <?= number_format((float)$p['prix_unitaire'], 2, ',', ' ') ?> €
                        </td>
                        <td>
                            <?= date('d/m/Y H:i', strtotime($p['date_ajout'])) ?>
                        </td>
                        <td>
                            <div class="actions">
                                <a class="mini-btn edit"
                                   href="modifier.php?id=<?= (int)$p['id'] ?>">
                                    Modifier
                                </a>

                                <a class="mini-btn delete"
                                   href="../src/traitement/supprimerProduit.php?id=<?= (int)$p['id'] ?>"
                                   onclick="return confirm('Supprimer ce produit ?');">
                                    Supprimer
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>

            </tbody>
        </table>
    </div>

    <footer>
        &copy; <?= date('Y') ?> Paristanbul — Gestionnaire de stock
    </footer>

</main>

</body>
</html>