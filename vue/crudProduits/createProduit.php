<?php
declare(strict_types=1);

require_once __DIR__ . '/../../src/auth/Auth.php';
\auth\Auth::startSession();
\auth\Auth::requireAnyRole(['admin','gestionnaire']);

require_once __DIR__ . '/../../src/repository/ProduitRepository.php';
require_once __DIR__ . '/../../src/repository/CategoriesRepository.php';

use repository\ProduitRepository;
use repository\CategoriesRepository;

$errors = [];
$old = [
    'nom' => '',
    'categorie' => '',
    'quantite_centrale' => '',
    'prix' => '',
    'seuil_alerte' => '',
    'date_ajout' => date('Y-m-d')
];

// Charger les catégories pour le <select>
$catRepo = new CategoriesRepository();
$categories = $catRepo->pairs(); // [ [id_categorie, nom], ... ]

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom'] ?? '');
    $categorie = (int)($_POST['categorie'] ?? 0);
    $quantiteCentrale = (int)($_POST['quantite_centrale'] ?? 0);
    $prix = (float)str_replace(',', '.', (string)($_POST['prix'] ?? '0'));
    $seuilAlerte = (int)($_POST['seuil_alerte'] ?? 0);
    $dateAjout = (string)($_POST['date_ajout'] ?? date('Y-m-d'));

    $old = [
        'nom' => $nom,
        'categorie' => (string)$categorie,
        'quantite_centrale' => (string)$quantiteCentrale,
        'prix' => (string)($_POST['prix'] ?? ''),
        'seuil_alerte' => (string)$seuilAlerte,
        'date_ajout' => $dateAjout,
    ];

    if ($nom === '') {
        $errors['nom'] = 'Le nom est requis.';
    }
    if ($categorie <= 0) {
        $errors['categorie'] = 'La catégorie est requise.';
    }
    if ($quantiteCentrale < 0) {
        $errors['quantite'] = 'La quantité doit être positive.';
    }
    if ($prix < 0) {
        $errors['prix'] = 'Le prix doit être positif.';
    }
    if ($seuilAlerte < 0) {
        $errors['seuil_alerte'] = 'Le seuil doit être positif.';
    }
    // Validation simple de la date (YYYY-MM-DD)
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateAjout)) {
        $errors['date_ajout'] = 'La date doit être au format YYYY-MM-DD.';
    }

    if (!$errors) {
        $repo = new ProduitRepository();
        $id = $repo->create($nom, $categorie, $quantiteCentrale, $prix, $seuilAlerte, $dateAjout);
        header('Location: listeProduits.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Paristanbul • Tableau de bord</title>

    <link rel="stylesheet" href="../../src/assets/css/index.css" />
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
</head>

<body>

<!-- Bouton menu mobile -->
<button class="sidebar-menu-button">
    <span class="material-symbols-rounded">menu</span>
</button>

<!-- BARRE LATÉRALE -->
<aside class="sidebar">
    <header class="sidebar-header">
        <a href="../index.php" class="header-logo">
            <img src="../../src/assets/img/logo.png" style="width:180px;" alt="Paristanbul" />
        </a>
        <button class="sidebar-toggler">
            <span class="material-symbols-rounded">chevron_left</span>
        </button>
    </header>

    <nav class="sidebar-nav">
        <ul class="nav-list primary-nav">

            <!-- Tableau de bord -->
            <li class="nav-item">
                <a href="../index.php" class="nav-link">
                    <span class="material-symbols-rounded">dashboard</span>
                    <span class="nav-label">Dashboard</span>
                </a>
            </li>

            <!-- Produits -->
            <li class="nav-item dropdown-container">
                <a href="#" class="nav-link dropdown-toggle">
                    <span class="material-symbols-rounded">inventory_2</span>
                    <span class="nav-label">Produits</span>
                    <span class="dropdown-icon material-symbols-rounded">keyboard_arrow_down</span>
                </a>
                <ul class="dropdown-menu">
                    <li><a href="listeProduits.php" class="nav-link dropdown-link">Liste des produits</a></li>
                    <li><a href="createProduit.php" class="nav-link dropdown-link">Ajouter un produit</a></li>
                    <li><a href="../../vue/crudProduits/categories.php" class="nav-link dropdown-link">Catégories</a></li>
                </ul>
            </li>

            <!-- Commandes -->
            <li class="nav-item dropdown-container">
                <a href="#" class="nav-link dropdown-toggle">
                    <span class="material-symbols-rounded">shopping_cart</span>
                    <span class="nav-label">Commandes</span>
                    <span class="dropdown-icon material-symbols-rounded">keyboard_arrow_down</span>
                </a>
                <ul class="dropdown-menu">
                    <li><a href="../crudCommandes/listeCommandes.php" class="nav-link dropdown-link">Historique</a></li>
                    <li><a href="../crudCommandes/createCommande.php" class="nav-link dropdown-link">Nouvelle commande</a></li>
                </ul>
            </li>

            <!-- Mouvements -->
            <li class="nav-item">
                <a href="../../vue/mouvements.php" class="nav-link">
                    <span class="material-symbols-rounded">compare_arrows</span>
                    <span class="nav-label">Mouvements</span>
                </a>
            </li>

            <!-- Statistiques -->
            <li class="nav-item">
                <a href="../../vue/statistiques.php" class="nav-link">
                    <span class="material-symbols-rounded">query_stats</span>
                    <span class="nav-label">Statistiques</span>
                </a>
            </li>

            <!-- Factures -->
            <li class="nav-item">
                <a href="../../vue/crudFactures/factures.php" class="nav-link">
                    <span class="material-symbols-rounded">receipt_long</span>
                    <span class="nav-label">Factures</span>
                </a>
            </li>

            <!-- Utilisateurs -->
            <li class="nav-item">
                <a href="../../vue/crudProfils/profil.php" class="nav-link">
                    <span class="material-symbols-rounded">group</span>
                    <span class="nav-label">Utilisateurs</span>
                </a>
            </li>
        </ul>

        <ul class="nav-list secondary-nav">
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <span class="material-symbols-rounded">help</span>
                    <span class="nav-label">Support</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="../logout.php" class="nav-link">
                    <span class="material-symbols-rounded">logout</span>
                    <span class="nav-label">Déconnexion</span>
                </a>
            </li>
        </ul>
    </nav>
</aside>

<!-- CONTENU PRINCIPAL -->
<main class="main-content" style="padding-right:16px;">
    <style>
        .page-header { display:flex; justify-content:space-between; align-items:center; padding:16px 0; border-bottom:1px solid #e5e7eb; margin-bottom:12px; }
        .page-header h1 { margin:0; font-size:22px; }
        .btn-primary { background:#2563eb; color:#fff; border:none; border-radius:8px; padding:10px 14px; }
        .btn-primary:hover { background:#1d4ed8; }
        .btn-ghost { border:1px solid #d1d5db; background:#fff; color:#111827; border-radius:8px; padding:8px 12px; }
        .card { background:#fff; border:1px solid #e5e7eb; border-radius:12px; box-shadow:0 1px 2px rgba(0,0,0,0.04); }
        .form-grid { display:grid; gap:16px; }
        .form-row-2 { display:grid; grid-template-columns:1fr 1fr; gap:16px; }
        .form-group label { display:block; margin-bottom:6px; font-weight:600; }
        .form-group input, .form-group select { width:100%; padding:10px; border:1px solid #d1d5db; border-radius:8px; }
        .alert-error { background:#ffe6e6; border:1px solid #ff9999; color:#990000; padding:12px; border-radius:8px; }
    </style>

    <div class="page-header">
        <h1>Ajouter un produit</h1>
        <a href="listeProduits.php" class="btn-ghost">← Retour à la liste</a>
    </div>

    <div class="card" style="padding:20px; max-width:900px; margin:auto;">

        <?php if ($errors): ?>
            <div class="alert-error" style="margin-bottom:16px;">
                <strong>Veuillez corriger les erreurs suivantes :</strong>
                <ul style="margin:8px 0 0 18px;">
                    <?php foreach ($errors as $msg): ?>
                        <li><?= htmlspecialchars($msg) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="post" action="" class="form-grid">
            <div class="form-group">
                <label for="nom">Nom du produit</label>
                <input type="text" id="nom" name="nom" required placeholder="Ex: Gel douche"
                       value="<?= htmlspecialchars($old['nom']) ?>">
            </div>

            <div class="form-group">
                <label for="categorie">Catégorie</label>
                <select id="categorie" name="categorie" required>
                    <option value="">— Sélectionner —</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= (int)$cat['id_categorie'] ?>" <?= ($old['categorie'] == (string)$cat['id_categorie']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['nom']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-row-2">
                <div class="form-group">
                    <label for="quantite_centrale">Quantité centrale</label>
                    <input type="number" id="quantite_centrale" name="quantite_centrale" min="0" step="1" placeholder="0"
                           value="<?= htmlspecialchars($old['quantite_centrale']) ?>">
                </div>
                <div class="form-group">
                    <label for="prix">Prix unitaire (€)</label>
                    <input type="number" id="prix" name="prix" min="0" step="0.01" placeholder="0.00"
                           value="<?= htmlspecialchars($old['prix']) ?>">
                </div>
            </div>

            <div class="form-row-2">
                <div class="form-group">
                    <label for="seuil_alerte">Seuil d'alerte</label>
                    <input type="number" id="seuil_alerte" name="seuil_alerte" min="0" step="1" placeholder="0"
                           value="<?= htmlspecialchars($old['seuil_alerte']) ?>">
                </div>
                <div class="form-group">
                    <label for="date_ajout">Date d'ajout</label>
                    <input type="date" id="date_ajout" name="date_ajout"
                           value="<?= htmlspecialchars($old['date_ajout']) ?>">
                </div>
            </div>

            <div style="display:flex; gap:12px;">
                <button type="submit" class="btn-primary">Enregistrer</button>
                <a href="listeProduits.php" class="btn-ghost">Annuler</a>
            </div>
        </form>
    </div>
    </main>

</body>
</html>
<script type="text/javascript" src="../../src/assets/js/index.js"> </script>