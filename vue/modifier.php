<?php
session_start();

require_once __DIR__ . '/src/repository/ProduitRepo.php';

$id = (int)($_GET['id'] ?? 0);
$repo = new ProduitRepo();
$produit = $repo->find($id);

if (!$produit) {
    die("❌ Produit introuvable.");
}

$success = isset($_GET['success']);
$error   = isset($_GET['error']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier le produit — Paristanbul</title>
    <style>
        :root {
            --primary: #c62828;
            --secondary: #000;
            --gray: #f7f7f7;
            --radius: 14px;
        }

        body {
            font-family: "Plus Jakarta Sans", Arial, sans-serif;
            background: var(--gray);
            margin: 0;
            padding: 0;
        }

        header {
            background: var(--secondary);
            color: white;
            padding: 1.2rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        header h1 { font-size: 1.4rem; margin:0; }

        header nav a {
            color: white;
            text-decoration: none;
            margin-left: 1.2rem;
            font-weight: 500;
            transition: opacity .2s;
        }
        header nav a:hover { opacity: 0.8; }

        main {
            max-width: 700px;
            margin: 3rem auto;
            background: white;
            padding: 2rem;
            border-radius: var(--radius);
            box-shadow: 0 6px 18px rgba(0,0,0,0.06);
        }

        h2 {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .message {
            text-align: center;
            margin-bottom: 1.5rem;
            padding: 0.7rem;
            border-radius: var(--radius);
            font-weight: 500;
            font-size: .9rem;
        }
        .success { background: #e8f5e9; color: #2e7d32; }
        .error { background: #ffebee; color: #c62828; }

        form {
            display: grid;
            gap: 1rem;
        }

        label {
            font-weight: 600;
            font-size: .9rem;
        }

        input[type="text"],
        input[type="number"] {
            width: 100%;
            padding: 0.7rem;
            border: 1px solid #ccc;
            border-radius: var(--radius);
            font-size: 1rem;
        }

        button {
            background: var(--primary);
            color: white;
            border: none;
            padding: 0.9rem;
            font-size: 1rem;
            border-radius: var(--radius);
            cursor: pointer;
            font-weight: 600;
            transition: background .2s;
            width: 100%;
        }

        button:hover {
            background: #a91f1f;
        }

        .back {
            display: block;
            text-align: center;
            margin-top: 1.5rem;
            text-decoration: none;
            color: var(--secondary);
            font-weight: 600;
            font-size: .9rem;
        }
    </style>
</head>
<body>

<header>
    <h1>Modifier un produit</h1>
    <nav>
        <a href="index.php">Accueil</a>
        <a href="produits.php">Produits</a>
        <a href="ajouter.php">Ajouter</a>
    </nav>
</header>

<main>
    <h2>Éditer le produit #<?= (int)$produit['id'] ?></h2>

    <?php if ($success): ?>
        <div class="message success">✅ Produit mis à jour avec succès.</div>
    <?php elseif ($error): ?>
        <div class="message error">⚠️ Erreur lors de la mise à jour.</div>
    <?php endif; ?>

    <form method="post" action="../src/traitement/modifierProduit.php">
        <input type="hidden" name="id" value="<?= (int)$produit['id'] ?>">

        <div>
            <label for="nom">Nom du produit</label>
            <input type="text" name="nom" id="nom" required value="<?= htmlspecialchars($produit['nom']) ?>">
        </div>

        <div>
            <label for="categorie">Catégorie</label>
            <input type="text" name="categorie" id="categorie" required value="<?= htmlspecialchars($produit['categorie']) ?>">
        </div>

        <div>
            <label for="quantite">Quantité</label>
            <input type="number" name="quantite" id="quantite" min="0" required value="<?= (int)$produit['quantite'] ?>">
        </div>

        <div>
            <label for="prix_unitaire">Prix unitaire (€)</label>
            <input type="number" name="prix_unitaire" id="prix_unitaire" min="0" step="0.01" required value="<?= number_format((float)$produit['prix_unitaire'], 2, '.', '') ?>">
        </div>

        <button type="submit">Enregistrer les modifications</button>
    </form>

    <!-- ⬇⬇⬇ c'est CE lien dont on parle -->
    <a href="produit.php" class="back">← Retour à la liste</a></main>

</body>
</html>