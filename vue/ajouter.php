<?php
declare(strict_types=1);
session_start();

require_once __DIR__ . '/../src/bdd/bddp.php'; // <= adapte si ton fichier s'appelle autrement
$bdd = bdd(); // ou Bdd::connect()

$success = isset($_GET['success']);
$error   = isset($_GET['error']);
$message = null;

/* Charger les catégories pour le <select> */
$categories = $bdd->query("SELECT id_categorie, nom FROM categorie ORDER BY nom")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom        = trim($_POST['nom'] ?? '');
    $catId      = (int)($_POST['categorie'] ?? 0);      // doit être l'ID de categorie
    $nbUnites   = (int)($_POST['quantite'] ?? 0);       // mappé sur nb_unite_pack
    $prix       = (float)($_POST['prix_unitaire'] ?? 0);

    if ($nom !== '' && $catId > 0 && $nbUnites >= 0 && $prix >= 0) {
        $stmt = $bdd->prepare("
            INSERT INTO produits (libelle, ref_categorie, nb_unite_pack, prix_unitaire)
            VALUES (:libelle, :ref_categorie, :nb_unite_pack, :prix_unitaire)
        ");
        $stmt->execute([
                ':libelle'        => $nom,
                ':ref_categorie'  => $catId,
                ':nb_unite_pack'  => $nbUnites,
                ':prix_unitaire'  => $prix,
        ]);
        // Affiche un message ou redirige si tu préfères :
        $message = "✅ Produit ajouté avec succès !";
        // header('Location: ajouter.php?success=1'); exit;
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
        :root { --primary:#c62828; --secondary:#000; --gray:#f7f7f7; --radius:14px; }
        body { font-family: "Plus Jakarta Sans", Arial, sans-serif; background:var(--gray); margin:0; }
        header{ background:var(--secondary); color:#fff; padding:1.2rem 2rem; display:flex; justify-content:space-between; align-items:center;}
        header h1{ font-size:1.4rem; }
        header nav a{ color:#fff; text-decoration:none; margin-left:1.2rem; font-weight:500; transition:opacity .2s; }
        header nav a:hover{ opacity:.8; }
        main{ max-width:700px; margin:3rem auto; background:#fff; padding:2rem; border-radius:var(--radius); box-shadow:0 6px 18px rgba(0,0,0,.06); }
        h2{ text-align:center; margin-bottom:1.5rem; }
        form{ display:grid; gap:1.2rem; }
        label{ font-weight:600; }
        input[type="text"], input[type="number"], select{
            width:100%; padding:.7rem; border:1px solid #ccc; border-radius:var(--radius); font-size:1rem;
        }
        button{ background:var(--primary); color:#fff; border:none; padding:.9rem; font-size:1rem; border-radius:var(--radius); cursor:pointer; font-weight:600; transition:background .2s; }
        button:hover{ background:#a91f1f; }
        .message{ text-align:center; margin-bottom:1.5rem; padding:.7rem; border-radius:var(--radius); font-weight:500; }
        .success{ background:#e8f5e9; color:#2e7d32; }
        .error{ background:#ffebee; color:#c62828; }
        .back{ display:block; text-align:center; margin-top:1.5rem; text-decoration:none; color:var(--secondary); font-weight:600; }
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

    <!-- Self-post : action vide -->
    <form method="post" action="">
        <div>
            <label for="nom">Nom du produit</label>
            <input type="text" name="nom" id="nom" required>
        </div>

        <div>
            <label for="categorie">Catégorie</label>
            <select name="categorie" id="categorie" required>
                <option value="">— Sélectionner —</option>
                <?php foreach ($categories as $c): ?>
                    <option value="<?= (int)$c['id_categorie'] ?>">
                        <?= htmlspecialchars($c['nom']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div>
            <label for="quantite">Nb d’unités (pack)</label>
            <input type="number" name="quantite" id="quantite" min="0" value="1" required>
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