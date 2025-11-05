<?php
require_once __DIR__ . '/../../src/repository/CommandeRepository.php';
require_once __DIR__ . '/../../src/repository/MagasinsRepository.php';
require_once __DIR__ . '/../../src/repository/UserRepository.php';
require_once __DIR__ . '/../../src/model/Commande.php';

use repository\CommandeRepository;
use repository\MagasinsRepository;
use repository\UserRepository;
use model\Commande;

$repoCommande = new CommandeRepository();
$repoMagasin = new MagasinsRepository();
$repoUser = new UserRepository();

$magasins = $repoMagasin->getAllMagasins();
$utilisateurs = $repoUser->getAllUsers();


?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- DataTables CSS + JS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Paristanbul • Tableau de bord</title>

    <link rel="stylesheet" href="../../src/assets/css/index.css" />
    <link rel="stylesheet" href="../../src/assets/css/createCommande.css" />
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
                    <li><a href="../crudProduits/listeProduits.php" class="nav-link dropdown-link">Liste des produits</a></li>
                    <li><a href="../crudProduits/createProduit.php" class="nav-link dropdown-link">Ajouter un produit</a></li>
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
                    <li><a href="listeCommandes.php" class="nav-link dropdown-link">Historique</a></li>
                    <li><a href="createCommande.php" class="nav-link dropdown-link">Nouvelle commande</a></li>
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
<main class="main-content">
    <div class="container mt-5" style="max-width: 900px;">
        <h2 class="fw-bold mb-4 text-center text-primary">Nouvelle commande</h2>


        <form method="post" class="p-5 bg-white rounded shadow-lg border border-light-subtle" style="font-size: 1.1rem;"
        action="../../src/traitement/traitementCreateCommande.php">

            <!-- Sélection du magasin -->
            <div class="mb-4">
                <label for="ref_magasin" class="form-label fw-semibold">Magasin :</label>
                <select name="ref_magasin" class="form-select form-select-lg" required>
                    <?php foreach ($magasins as $mag): ?>
                        <option value="<?= htmlspecialchars($mag->getIdMagasin() ?? '') ?>">
                            <?= htmlspecialchars($mag->getNom() ?? '') ?>
                        </option>

                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Sélection de l’utilisateur -->
            <div class="mb-4">
                <label for="ref_utilisateur" class="form-label fw-semibold">Utilisateur :</label>
                <select name="ref_utilisateur" id="ref_utilisateur" class="form-select form-select-lg" required>
                    <option value="">-- Sélectionnez un utilisateur --</option>
                    <?php foreach ($utilisateurs as $user): ?>
                        <option value="<?= htmlspecialchars($user['id_user']) ?>">
                            <?= htmlspecialchars($user['nom']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Date de commande -->
            <div class="mb-4">
                <label for="date_commande" class="form-label fw-semibold">Date de commande :</label>
                <input type="datetime-local" id="date_commande" name="date_commande"
                       value="<?= date('Y-m-d\TH:i') ?>" class="form-control form-control-lg" required>
            </div>

            <!-- État -->
            <div class="mb-4">
                <label for="etat" class="form-label fw-semibold">État :</label>
                <select name="etat" id="etat" class="form-select form-select-lg" required>
                    <option value="en attente">En attente</option>
                    <option value="préparée">Préparée</option>
                    <option value="expédiée">Expédiée</option>
                    <option value="livrée">Livrée</option>
                    <option value="annulée">Annulée</option>
                </select>
            </div>

            <!-- Commentaire -->
            <div class="mb-4">
                <label for="commentaire" class="form-label fw-semibold">Commentaire :</label>
                <textarea name="commentaire" id="commentaire" class="form-control form-control-lg"
                          rows="4" placeholder="Ajoutez un commentaire..."></textarea>
            </div>

            <!-- Boutons -->
            <div class="d-flex justify-content-between align-items-center mt-4">
                <button type="button" class="btn btn-outline-secondary btn-lg"
                        onclick="window.location.href='listeCommandes.php'">
                    <i class="bi bi-arrow-left-circle"></i> Retour
                </button>
                <button type="submit" class="btn btn-primary btn-lg px-4">
                    <i class="bi bi-check-circle"></i> Ajouter la commande
                </button>
            </div>
        </form>
    </div>
</main>

</body>
</html>
<script type="text/javascript" src="../../src/assets/js/index.js"> </script>
