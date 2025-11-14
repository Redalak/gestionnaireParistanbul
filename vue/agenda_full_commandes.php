<?php

use repository\CommandeRepository;
require_once __DIR__ . '/../src/repository/CommandeRepository.php';

$repoCommande = new CommandeRepository();
$commandesEnCours = $repoCommande->getCommandeEnCours();

// Regroupement par date
$events = [];
foreach ($commandesEnCours as $cmd) {
    $date = substr($cmd->getDateCommande(), 0, 10);
    $events[$date][] = $cmd;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Planning complet</title>

    <link rel="stylesheet" href="../src/assets/css/index.css">
    <link rel="stylesheet" href="../src/assets/css/agenda.css">
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
    <script type="text/javascript" src="../src/assets/js/index.js"></script>
</head>

<body>
<!-- Bouton menu mobile -->
<button class="sidebar-menu-button">
    <span class="material-symbols-rounded">menu</span>
</button>

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

            <li class="nav-item">
                <a href="index.php" class="nav-link">
                    <span class="material-symbols-rounded">dashboard</span>
                    Dashboard
                </a>
            </li>

            <!-- PRODUITS -->
            <li class="nav-item dropdown-container">
                <a href="#" class="nav-link dropdown-toggle">
                    <span class="material-symbols-rounded">inventory_2</span>
                    Produits
                    <span class="dropdown-icon material-symbols-rounded">keyboard_arrow_down</span>
                </a>
                <ul class="dropdown-menu">
                    <li><a href="crudProduits/listeProduits.php" class="nav-link dropdown-link">Liste des produits</a></li>
                    <li><a href="crudProduits/createProduit.php" class="nav-link dropdown-link">Ajouter un produit</a></li>
                    <li><a href="../vue/crudProduits/categories.php" class="nav-link dropdown-link">Catégories</a></li>
                </ul>
            </li>

            <!-- COMMANDES -->
            <li class="nav-item dropdown-container">
                <a href="#" class="nav-link dropdown-toggle">
                    <span class="material-symbols-rounded">shopping_cart</span>
                    Commandes
                    <span class="dropdown-icon material-symbols-rounded">keyboard_arrow_down</span>
                </a>
                <ul class="dropdown-menu">
                    <li><a href="crudCommandes/listeCommandes.php" class="nav-link dropdown-link">Historique</a></li>
                    <li><a href="crudCommandes/createCommande.php" class="nav-link dropdown-link">Nouvelle commande</a></li>
                </ul>
            </li>

            <li class="nav-item">
                <a href="../vue/mouvements.php" class="nav-link">
                    <span class="material-symbols-rounded">compare_arrows</span>
                    Mouvements
                </a>
            </li>

            <li class="nav-item">
                <a href="../vue/statistiques.php" class="nav-link">
                    <span class="material-symbols-rounded">query_stats</span>
                    Statistiques
                </a>
            </li>

            <li class="nav-item">
                <a href="agenda.php" class="nav-link">
                    <span class="material-symbols-rounded">today</span>
                    Agenda
                </a>
            </li>

            <li class="nav-item">
                <a href="../vue/crudFactures/factures.php" class="nav-link">
                    <span class="material-symbols-rounded">receipt_long</span>
                    Factures
                </a>
            </li>

            <li class="nav-item">
                <a href="../vue/crudProfils/profil.php" class="nav-link">
                    <span class="material-symbols-rounded">group</span>
                    Utilisateurs
                </a>
            </li>
        </ul>

        <ul class="nav-list secondary-nav">
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <span class="material-symbols-rounded">help</span>
                    Support
                </a>
            </li>
            <li class="nav-item">
                <a href="../logout.php" class="nav-link">
                    <span class="material-symbols-rounded">logout</span>
                    Déconnexion
                </a>
            </li>
        </ul>
    </nav>
</aside>

<main class="main-content">
    <div class="container mt-4">
        <h2 class="text-primary mb-4">📅 Planning complet des commandes</h2>
        <a href="agenda.php" class="btn btn-secondary mb-3">
            ⬅ Retour à l'agenda
        </a>

        <div class="timeline mt-3">
            <?php foreach ($events as $date => $cmdList): ?>
                <div class="timeline-date">
                    <h4><?= htmlspecialchars($date) ?></h4>
                </div>

                <?php foreach ($cmdList as $cmd): ?>
                    <div class="timeline-item shadow-sm">
                        <div class="timeline-item-content">
                            <h5>
                                Commande #<?= htmlspecialchars($cmd->getIdCommande()) ?>
                                <span class="badge bg-info"><?= htmlspecialchars($cmd->getEtat()) ?></span>
                            </h5>

                            <p>
                                <strong>Magasin :</strong> <?= htmlspecialchars($cmd->getNomMagasin() ?? 'Inconnu') ?><br>
                                <strong>Ville :</strong> <?= htmlspecialchars($cmd->getVille() ?? 'N/A') ?><br>
                                <strong>Date :</strong> <?= htmlspecialchars($cmd->getDateCommande()) ?>
                            </p>

                            <a href="../detailCommande.php?id=<?= htmlspecialchars($cmd->getIdCommande()) ?>"
                               class="btn btn-primary btn-sm">
                                Voir détails
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </div>

    </div>
</main>
</body>
</html>
