<?php
session_start();
require_once __DIR__ . '/src/Bdd.php';
$bdd = Bdd::connect();

$produits = $bdd->query("SELECT * FROM produits ORDER BY date_ajout DESC LIMIT 5")->fetchAll();

// Données temporaires (plus tard remplacées par ta BDD)
$totalProduits = 245;
$produitsEnRupture = 7;
$livraisonsEnCours = 3;
$sortiesRecente = 5;
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Paristanbul — Gestionnaire de Stock</title>

    <style>
        :root {
            --rouge: #c62828;
            --noir: #111;
            --gris: #f4f4f4;
            --blanc: #fff;
        }

        * {
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
        }

        body {
            margin: 0;
            background: var(--gris);
            color: var(--noir);
        }

        /* HEADER */
        header {
            background: var(--noir);
            color: var(--blanc);
            padding: 16px 32px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        header h1 {
            margin: 0;
            font-size: 1.5rem;
            letter-spacing: .05em;
        }

        nav a {
            color: var(--blanc);
            text-decoration: none;
            margin-left: 20px;
            font-weight: 500;
            transition: opacity .2s;
        }

        nav a:hover { opacity: 0.8; }

        /* MAIN */
        main {
            padding: 40px 5%;
        }

        .dashboard {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 24px;
        }

        .card {
            background: var(--blanc);
            border-radius: 16px;
            box-shadow: 0 4px 10px rgba(0,0,0,.05);
            padding: 24px;
            transition: transform .2s;
        }

        .card:hover {
            transform: translateY(-4px);
        }

        .card h3 {
            margin: 0 0 8px;
            font-size: 1rem;
            text-transform: uppercase;
            letter-spacing: .05em;
            color: var(--rouge);
        }

        .card .number {
            font-size: 2.5rem;
            font-weight: 700;
        }

        /* TABLEAU */
        .table-zone {
            margin-top: 40px;
            background: var(--blanc);
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 4px 10px rgba(0,0,0,.05);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.95rem;
        }

        th, td {
            text-align: left;
            padding: 12px 10px;
            border-bottom: 1px solid #eee;
        }

        th {
            text-transform: uppercase;
            font-size: .8rem;
            letter-spacing: .04em;
            color: #666;
        }

        tr:hover {
            background: #fafafa;
        }

        .btn {
            display: inline-block;
            padding: 10px 18px;
            background: var(--rouge);
            color: var(--blanc);
            border-radius: 8px;
            text-decoration: none;
            font-size: .9rem;
            font-weight: 600;
            transition: background .2s;
        }

        .btn:hover {
            background: #a11f1f;
        }

        footer {
            text-align: center;
            padding: 24px;
            font-size: .85rem;
            color: #777;
            margin-top: 60px;
        }
    </style>
</head>
<body>

<header>
    <h1>Paristanbul — Gestion des Stocks</h1>
    <nav>
        <a href="#">Accueil</a>
        <a href="#">Produits</a>
        <a href="#">Entrées</a>
        <a href="#">Sorties</a>
        <a href="#">Fournisseurs</a>
    </nav>
</header>

<main>
    <h2>Tableau de bord</h2>
    <p>Bienvenue dans le gestionnaire de stock Paristanbul. Voici l’état général du stock :</p>

    <section class="dashboard">
        <div class="card">
            <h3>Total Produits</h3>
            <div class="number"><?= $totalProduits ?></div>
        </div>

        <div class="card">
            <h3>Produits en rupture</h3>
            <div class="number"><?= $produitsEnRupture ?></div>
        </div>

        <div class="card">
            <h3>Livraisons en cours</h3>
            <div class="number"><?= $livraisonsEnCours ?></div>
        </div>

        <div class="card">
            <h3>Sorties récentes</h3>
            <div class="number"><?= $sortiesRecente ?></div>
        </div>
    </section>

    <section class="table-zone">
        <h3>Derniers mouvements</h3>
        <table>
            <thead>
            <tr>
                <th>Date</th>
                <th>Produit</th>
                <th>Type</th>
                <th>Quantité</th>
                <th>Statut</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($produits as $p): ?>
                <tr>
                    <td><?= date('d/m/Y', strtotime($p['date_ajout'])) ?></td>
                    <td><?= htmlspecialchars($p['nom']) ?></td>
                    <td><?= htmlspecialchars($p['categorie']) ?></td>
                    <td><?= (int)$p['quantite'] ?></td>
                    <td><?= number_format($p['prix_unitaire'], 2, ',', ' ') ?> €</td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <br>
        <a href="#" class="btn">Voir tous les mouvements</a>
    </section>
</main>

<footer>
    &copy; <?= date('Y') ?> Paristanbul — Gestionnaire de Stock
</footer>

</body>
</html>