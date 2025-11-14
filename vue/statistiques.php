<?php


/* statistiques.php */
require_once __DIR__ . '/../src/auth/Auth.php';
\auth\Auth::startSession();
\auth\Auth::requireAnyRole(['admin','gestionnaire']);


require_once __DIR__ . '/../src/bdd/Bdd.php';
require_once __DIR__ . '/../src/repository/CommandeRepository.php';
require_once __DIR__ . '/../src/repository/ProduitRepository.php';

use bdd\Bdd;
use repository\CommandeRepository;
use repository\ProduitRepository;

require_once __DIR__ . '/../src/repository/CommandeRepository.php';
require_once __DIR__ . '/../src/repository/ProduitRepository.php';

$pdo = (new Bdd())->getBdd();

$commandeRepo = new CommandeRepository();
$produitRepo = new ProduitRepository();

// ========================== COMMANDES ==========================

// Commandes par magasin
$commandeParMagasin = $commandeRepo->getCommandesParMagasin();
$commandeParMagasinJson = json_encode(array_map(fn($c) => [
        'magasin' => $c['nom_magasin'] ?? $c['magasin'] ?? 'Inconnu',
        'nb_commandes' => (int)($c['nb_commandes'] ?? 0)
], $commandeParMagasin));

// Commandes du mois (dernier 30 jours)
$commandesMois = $commandeRepo->getCommandesDerniers30Jours();
$commandesMoisJson = json_encode(array_map(fn($c) => [
        'date' => $c['date'] ?? $c['jour'] ?? 'Inconnu',
        'nb_commandes' => (int)($c['nb_commandes'] ?? 0)
], $commandesMois));

// Commandes par client Top 10
$commandeParClient = $commandeRepo->getCommandesParClientTop10();
$commandeParClientJson = json_encode(array_map(fn($c) => [
        'client' => $c['nom_client'] ?? $c['client'] ?? 'Inconnu',
        'nb_commandes' => (int)($c['nb_commandes'] ?? 0)
], $commandeParClient));

// État des commandes
$etatCommandes = $commandeRepo->getEtatCommandes();
$etatCommandesJson = json_encode(array_map(fn($e) => [
        'etat' => $e['etat'] ?? 'Inconnu',
        'nb_commandes' => (int)($e['nb_commandes'] ?? 0)
], $etatCommandes));

// Total commandes
$totalCommandes = $commandeRepo->getTotalCommandes();

// ========================== PRODUITS ==========================

// Total produits
$totalProduits = $produitRepo->getTotalProduits();

// Valeur totale commandes
$valeurTotaleCommandes = $produitRepo->getValeurTotaleCommandes();


// Top produits vendus
$topProduits = $produitRepo->getTopProduitsVendus();
$topProduitsJson = json_encode(array_map(fn($p) => [
        'produit' => $p['produit'] ?? $p['libelle'] ?? 'Inconnu',
        'quantite' => (int)($p['quantite'] ?? $p['total_vendu'] ?? 0)
], $topProduits));

// Produits par catégorie
$produitsParCategorie = $produitRepo->getProduitsParCategorieStats();
$produitsParCategorieJson = json_encode(array_map(fn($p) => [
        'nom_categorie' => $p['nom_categorie'] ?? $p['categorie'] ?? 'Inconnu',
        'quantite_totale' => (int)($p['quantite_totale'] ?? 0)
], $produitsParCategorie));
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Paristanbul • Statistiques</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../src/assets/css/index.css" />
    <link rel="stylesheet" href="../src/assets/css/statistiques.css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>
<body>

<!-- BARRE LATÉRALE -->
<aside class="sidebar">
    <header class="sidebar-header">
        <a href="index.php" class="header-logo">
            <img src="../src/assets/img/logo.png" style="width:180px;" alt="Paristanbul" />
        </a>
        <button class="sidebar-toggler">
            <span class="material-symbols-rounded">chevron_left</span>
        </button>
    </header>

    <nav class="sidebar-nav">
        <ul class="nav-list primary-nav">

            <!-- Tableau de bord -->
            <li class="nav-item">
                <a href="index.php" class="nav-link">
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
                    <li><a href="crudProduits/listeProduits.php" class="nav-link dropdown-link">Liste des produits</a></li>
                    <li><a href="crudProduits/createProduit.php" class="nav-link dropdown-link">Ajouter un produit</a></li>
                    <li><a href="../vue/crudProduits/categories.php" class="nav-link dropdown-link">Catégories</a></li>
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
                    <li><a href="crudCommandes/listeCommandes.php" class="nav-link dropdown-link">Historique</a></li>
                    <li><a href="crudCommandes/createCommande.php" class="nav-link dropdown-link">Nouvelle commande</a></li>
                </ul>
            </li>

            <!-- Mouvements -->
            <li class="nav-item">
                <a href="../vue/mouvements.php" class="nav-link">
                    <span class="material-symbols-rounded">compare_arrows</span>
                    <span class="nav-label">Mouvements</span>
                </a>
            </li>

            <!-- Statistiques -->
            <li class="nav-item">
                <a href="../vue/statistiques.php" class="nav-link">
                    <span class="material-symbols-rounded">query_stats</span>
                    <span class="nav-label">Statistiques</span>
                </a>
            </li>
            <!-- Logistique -->
            <li class="nav-item">
                <a href="agenda.php" class="nav-link">
                    <span class="material-symbols-rounded">query_stats</span>
                    <span class="nav-label">Agenda</span>
                </a>
            </li>

            <!-- Factures -->
            <li class="nav-item">
                <a href="../vue/crudFactures/factures.php" class="nav-link">
                    <span class="material-symbols-rounded">receipt_long</span>
                    <span class="nav-label">Factures</span>
                </a>
            </li>

            <!-- Utilisateurs -->
            <li class="nav-item">
                <a href="../vue/crudProfils/profil.php" class="nav-link">
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
</aside>


<main class="main-content">
    <h1>Dashboard Statistiques</h1>

    <div class="stats-cards">
        <div class="stats-card">
            <h3>Total des commandes</h3>
            <p id="totalCommandes"><?= $totalCommandes ?></p>
        </div>
        <div class="stats-card">
            <h3>Total des produits</h3>
            <p id="totalProduits"><?= $totalProduits ?></p>
        </div>
        <div class="stats-card">
            <h3>Valeur totale commandes</h3>
            <p id="valeurTotale"><?= number_format($valeurTotaleCommandes, 2, ',', ' ') ?> €</p>
        </div>
    </div>

    <div class="chart-container">
        <h2>Commandes par magasin</h2>
        <canvas id="commandeChart"></canvas>
    </div>

    <div class="chart-container">
        <h2>Commandes du mois</h2>
        <canvas id="commandesMoisChart"></canvas>
    </div>

    <div class="grid-two">
        <div class="grid-item chart-container">
            <h2>Commandes par client (Top 10)</h2>
            <canvas id="commandeClientChart"></canvas>
        </div>
        <div class="grid-item chart-container">
            <h2>Top produits vendus (Top 10)</h2>
            <canvas id="topProduitsChart"></canvas>
        </div>
    </div>

    <div class="grid-two">
        <div class="grid-item chart-container">
            <h2>État des commandes</h2>
            <canvas id="etatChart"></canvas>
        </div>
        <div class="grid-item chart-container">
            <h2>Répartition des produits par catégorie</h2>
            <canvas id="categorieChart"></canvas>
        </div>
    </div>
</main>

<script>
    window.statsData = {
        commandeParMagasin: <?= $commandeParMagasinJson ?>,
        commandesMois: <?= $commandesMoisJson ?>,
        commandeParClient: <?= $commandeParClientJson ?>,
        topProduits: <?= $topProduitsJson ?>,
        etatCommandes: <?= $etatCommandesJson ?>,
        produitsParCategorie: <?= $produitsParCategorieJson ?>
    };
</script>

<script src="../src/assets/js/chart.js"></script>
<script src="../src/assets/js/stats.js"></script>
<script type="text/javascript" src="../src/assets/js/index.js"> </script>
</body>
</html>
