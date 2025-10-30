<?php
declare(strict_types=1);
session_start();

require_once __DIR__ . '/../src/bdd/Bdd.php';
use bdd\Bdd;

$pdo = (new Bdd())->getBdd();

/* --- ID requis --- */
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    die('❌ ID invalide.');
}

/* --- Récup produit (alias => clés de la vue) --- */
$st = $pdo->prepare("
    SELECT 
        p.id_produit       AS id,
        p.libelle          AS nom,
        p.ref_categorie    AS categorie,
        p.nb_unite_pack    AS quantite,
        p.prix_unitaire    AS prix_unitaire
    FROM produits p
    WHERE p.id_produit = :id
    LIMIT 1
");
$st->execute([':id' => $id]);
$produit = $st->fetch(PDO::FETCH_ASSOC);

if (!$produit) {
    die('❌ Produit introuvable.');
}

/* --- Catégories pour le select (optionnel) --- */
$cats = $pdo->query("SELECT id_categorie, nom FROM categorie ORDER BY nom")->fetchAll(PDO::FETCH_ASSOC);

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
        :root { --primary:#c62828; --secondary:#000; --gray:#f7f7f7; --radius:14px; }
        * { box-sizing: border-box; }
        body { font-family:"Plus Jakarta Sans", Arial, sans-serif; background:var(--gray); margin:0; color:var(--secondary); }
        header{ background:var(--secondary); color:#fff; padding:1.2rem 2rem; display:flex; justify-content:space-between; align-items:center;}
        header h1{ font-size:1.4rem; margin:0; }
        header nav a{ color:#fff; text-decoration:none; margin-left:1.2rem; font-weight:500; transition:opacity .2s; }
        header nav a:hover{ opacity:.8; }
        main{ max-width:700px; margin:3rem auto; background:#fff; padding:2rem; border-radius:var(--radius); box-shadow:0 6px 18px rgba(0,0,0,.06); }
        h2{ text-align:center; margin-bottom:1.5rem; }
        .message{ text-align:center; margin-bottom:1.2rem; padding:.7rem; border-radius:var(--radius); font-weight:500; font-size:.9rem; }
        .success{ background:#e8f5e9; color:#2e7d32; }
        .error{ background:#ffebee; color:#c62828; }
        form{ display:grid; gap:1rem; }
        label{ font-weight:600; font-size:.9rem; }
        input[type="text"], input[type="number"], select{
            width:100%; padding:.7rem; border:1px solid #ccc; border-radius:var(--radius); font-size:1rem;
        }
        button{ background:var(--primary); color:#fff; border:none; padding:.9rem; font-size:1rem; border-radius:var(--radius); cursor:pointer; font-weight:600; transition:background .2s; width:100%; }
        button:hover{ background:#a91f1f; }
        .back{ display:block; text-align:center; margin-top:1.5rem; text-decoration:none; color:var(--secondary); font-weight:600; font-size:.9rem; }
    </style>
</head>
<body>

<header>
    <h1>Modifier un produit</h1>
    <nav>
        <a href="index.php">Accueil</a>
        <a href="produit.php">Produits</a>
        <a href="ajouter.php">Ajouter</a>
        <a href="categories.php">Catégories</a>
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
            <input type="text" name="nom" id="nom" required
                   value="<?= htmlspecialchars($produit['nom'], ENT_QUOTES) ?>">
        </div>

        <div>
            <label for="categorie">Catégorie</label>
            <?php if (!empty($cats)): ?>
                <select name="categorie" id="categorie" required>
                    <option value="">— Sélectionner —</option>
                    <?php foreach ($cats as $c): ?>
                        <option value="<?= (int)$c['id_categorie'] ?>"
                                <?= ((int)$c['id_categorie'] === (int)$produit['categorie']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($c['nom']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            <?php else: ?>
                <input type="number" name="categorie" id="categorie" min="1" required
                       value="<?= (int)$produit['categorie'] ?>">
            <?php endif; ?>
        </div>

        <div>
            <label for="quantite">Quantité (nb unités pack)</label>
            <input type="number" name="quantite" id="quantite" min="0" required
                   value="<?= (int)$produit['quantite'] ?>">
        </div>

        <div>
            <label for="prix_unitaire">Prix unitaire (€)</label>
            <input type="number" name="prix_unitaire" id="prix_unitaire" min="0" step="0.01" required
                   value="<?= number_format((float)$produit['prix_unitaire'], 2, '.', '') ?>">
        </div>

        <button type="submit">Enregistrer les modifications</button>
    </form>

    <a href="produit.php" class="back">← Retour à la liste</a>
</main>

</body>
</html>