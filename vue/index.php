<?php
declare(strict_types=1);
session_start();

require_once __DIR__ . '/../src/bdd/Bdd.php';
use bdd\Bdd;

$pdo = (new Bdd())->getBdd();

/* Derniers produits (avec nom de catégorie) */
$sql = "
  SELECT
    p.id_produit,
    p.libelle            AS nom,
    c.nom                AS categorie,
    p.nb_unite_pack      AS quantite,
    p.prix_unitaire
  FROM produits p
  LEFT JOIN categorie c ON c.id_categorie = p.ref_categorie
  ORDER BY p.id_produit DESC
  LIMIT 12
";
$produits = $pdo->query($sql)->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionnaire de stock — Paristanbul</title>

    <style>
        /* Style global inspiré du site Paristanbul */
        :root {
            --primary: #c62828;
            --secondary: #000;
            --bg: #fff;
            --gray: #f5f5f5;
            --radius: 14px;
        }

        * { box-sizing: border-box; font-family: "Plus Jakarta Sans", Arial, sans-serif; }
        body { margin: 0; background: var(--bg); color: var(--secondary); }

        header {
            background: var(--secondary);
            color: white;
            padding: 1.2rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        header h1 { font-size: 1.4rem; letter-spacing: 0.5px; }
        header nav a { color: white; text-decoration: none; margin-left: 1.2rem; font-weight: 500; transition: opacity .2s; }
        header nav a:hover { opacity: 0.8; }

        main { padding: 2rem; max-width: 1100px; margin: auto; }
        h2 { font-size: 1.6rem; margin-bottom: 1rem; }

        .produits { display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 1.5rem; }

        .card {
            background: var(--gray);
            border-radius: var(--radius);
            padding: 1rem;
            box-shadow: 0 4px 10px rgba(0,0,0,0.06);
            transition: transform 0.2s ease;
        }
        .card:hover { transform: translateY(-3px); }

        .card img {
            width: 100%;
            height: 160px;
            object-fit: cover;
            border-radius: var(--radius);
        }
        .card h3 { font-size: 1.1rem; margin: 0.8rem 0 0.3rem; }
        .card p { margin: 0.2rem 0; font-size: 0.95rem; }

        .btn {
            display: inline-block;
            margin-top: 2rem;
            background: var(--primary);
            color: white;
            padding: 0.8rem 1.6rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: background .2s;
        }
        .btn:hover { background: #a91f1f; }

        footer { text-align: center; padding: 1rem; background: var(--secondary); color: #fff; margin-top: 2rem; }
    </style>
</head>

<body>
<header>
    <h1>Paristanbul — Gestion des stocks</h1>
    <nav>
        <a href="index.php">Accueil</a>
        <a href="produit.php">Produits</a>
        <a href="ajouter.php">Ajout</a>
        <a href="mouvement.php">mouvement</a>
        <a href="categories.php">Catégories</a>
        <a href="#">Statistiques</a>
    </nav>
</header>

<main>
    <h2>Derniers produits ajoutés</h2>

    <?php if (empty($produits)): ?>
        <p>Aucun produit pour le moment.</p>
    <?php else: ?>
        <div class="produits">
            <?php foreach ($produits as $p): ?>
                <div class="card">
                    <img src="https://images.unsplash.com/photo-1511690743698-d9d85f2fbf38?q=80&w=800&auto=format&fit=crop" alt="Produit">
                    <h3><?= htmlspecialchars($p['nom']) ?></h3>
                    <p>Catégorie : <?= htmlspecialchars($p['categorie'] ?? '—') ?></p>
                    <p>Quantité : <?= (int)($p['quantite'] ?? 0) ?></p>
                    <p>Prix unitaire : <?= number_format((float)$p['prix_unitaire'], 2, ',', ' ') ?> €</p>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <a href="produit.php" class="btn">Voir tout le stock</a>
</main>

<footer>
    &copy; <?= date('Y') ?> Paristanbul — Gestionnaire de stock
</footer>
</body>
</html>