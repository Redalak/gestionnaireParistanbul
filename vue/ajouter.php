<?php
session_start();
require_once __DIR__ . '/../src/Bdd.php';
$bdd = Bdd::connect();
$success = isset($_GET['success']);
$error   = isset($_GET['error']);

$message = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom       = trim($_POST['nom'] ?? '');
    $categorie = trim($_POST['categorie'] ?? '');
    $quantite  = (int)($_POST['quantite'] ?? 0);
    $prix      = (float)($_POST['prix_unitaire'] ?? 0);

    if ($nom && $categorie && $quantite >= 0 && $prix >= 0) {
        $stmt = $bdd->prepare("INSERT INTO produits (nom, categorie, quantite, prix_unitaire) VALUES (?, ?, ?, ?)");
        $stmt->execute([$nom, $categorie, $quantite, $prix]);
        $message = "✅ Produit ajouté avec succès !";
    } else {
        $message = "⚠️ Merci de remplir correctement tous les champs.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un produit — Paristanbul</title>
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

        header h1 {
            font-size: 1.4rem;
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

        form {
            display: grid;
            gap: 1.2rem;
        }

        label {
            font-weight: 600;
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
        }

        button:hover {
            background: #a91f1f;
        }

        .message {
            text-align: center;
            margin-bottom: 1.5rem;
            padding: 0.7rem;
            border-radius: var(--radius);
            font-weight: 500;
        }

        .success {
            background: #e8f5e9;
            color: #2e7d32;
        }

        .error {
            background: #ffebee;
            color: #c62828;
        }

        .back {
            display: block;
            text-align: center;
            margin-top: 1.5rem;
            text-decoration: none;
            color: var(--secondary);
            font-weight: 600;
        }
    </style>
</head>
<body>

<header>
    <h1>Ajouter un produit</h1>
    <nav>
        <a href="index.php">Accueil</a>
        <a href="ajouter.php">Ajout</a>
        <a href="produit.php">Produits</a>
    </nav>
</header>

<main>
    <h2>Nouvel article en stock</h2>

    <?php if ($message): ?>
        <div class="message <?= str_starts_with($message, '✅') ? 'success' : 'error' ?>">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>
    <?php if ($success): ?>
        <div class="message success">✅ Produit ajouté avec succès.</div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="message error">⚠️ Merci de remplir correctement tous les champs.</div>
    <?php endif; ?>
    <form method="post" action="../src/traitement/ajouterProduit.php">
        <div>
            <label for="nom">Nom du produit</label>
            <input type="text" name="nom" id="nom" required>
        </div>

        <div>
            <label for="categorie">Catégorie</label>
            <input type="text" name="categorie" id="categorie" required>
        </div>

        <div>
            <label for="quantite">Quantité</label>
            <input type="number" name="quantite" id="quantite" min="0" required>
        </div>

        <div>
            <label for="prix_unitaire">Prix unitaire (€)</label>
            <input type="number" name="prix_unitaire" id="prix_unitaire" min="0" step="0.01" required>
        </div>

        <button type="submit">Ajouter le produit</button>
    </form>

    <a href="index.php" class="back">← Retour à l’accueil</a>
</main>

</body>
</html>