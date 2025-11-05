<?php
/* listeCommandes.php */
require_once __DIR__ . '/../../src/repository/CommandeRepository.php';
require_once __DIR__ . '/../../src/model/Commande.php';
require_once __DIR__ . '/../../src/repository/MagasinsRepository.php';
require_once __DIR__ . '/../../src/model/Magasin.php';
require_once __DIR__ . '/../../src/repository/UserRepository.php';
require_once __DIR__ . '/../../src/model/User.php';

use repository\CommandeRepository;
use model\Produit;

$repoCommandes = new \repository\CommandeRepository();
$magasinRepo = new \repository\MagasinsRepository() ;
$userRepo = new \repository\UserRepository();

$commande = $repoCommandes ->getCommandeById($_GET['id']);
$magasins = $magasinRepo ->getAllMagasins();
$users = $userRepo -> getAllUsers() ;


?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">


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
    <link rel="stylesheet" href="../../src/assets/css/updateCommande.css" />
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
        <a href="index.php" class="header-logo">
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
<!-- CONTENU PRINCIPAL -->
<main class="main-content">
    <div>
        <!-- Bandeau titre -->
        <div class="offre-header d-flex justify-content-between align-items-center p-3 mb-4">
            <h2 class="fw-bold mb-0">Modifier une commande</h2>
            <button type="button" class="btn" onclick="window.location.href='listeCommandes.php'">
                <i class="bi bi-arrow-left-circle"></i> Retour
            </button>
        </div>

        <form method="post" class="form-container" style="background:white; padding:20px; border-radius:8px; box-shadow:0 0 10px rgba(0,0,0,0.1);">
            <!-- ID de la commande caché -->
            <input type="hidden" name="id_commande" value="<?= htmlspecialchars($commande->getIdCommande()) ?>">

            <div class="row">
                <div class="col">
                    <!-- Sélection du magasin -->
                    <div class="form-group mb-3">
                        <label for="ref_magasin">Magasin :</label>
                        <select name="ref_magasin" id="ref_magasin" class="form-control" required>
                            <?php foreach ($magasins as $mag): ?>
                                <option value="<?= htmlspecialchars($mag->getIdMagasin()) ?>"
                                    <?= $mag->getIdMagasin() === $commande->getRefMagasin() ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($mag->getNom()) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Sélection de l’utilisateur -->
                    <div class="form-group mb-3">
                        <label for="ref_utilisateur">Utilisateur :</label>
                        <select name="ref_utilisateur" id="ref_utilisateur" class="form-control" required>
                            <?php foreach ($users as $user): ?>
                                <option value="<?= htmlspecialchars($user['id_user']) ?>"
                                    <?= $user['id_user'] === $commande->getRefUtilisateur() ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($user['nom']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Date de commande -->
                    <div class="form-group mb-3">
                        <label for="date_commande">Date de commande :</label>
                        <input type="datetime-local" id="date_commande" name="date_commande"
                               value="<?= date('Y-m-d\TH:i', strtotime($commande->getDateCommande())) ?>"
                               class="form-control" required>
                    </div>
                </div>

                <div class="col">
                    <!-- État -->
                    <div class="form-group mb-3">
                        <label for="etat">État :</label>
                        <select name="etat" id="etat" class="form-control" required>
                            <?php
                            $etats = ['en attente', 'préparée', 'expédiée', 'livrée', 'annulée'];
                            foreach ($etats as $etatOption): ?>
                                <option value="<?= $etatOption ?>"
                                    <?= $etatOption === $commande->getEtat() ? 'selected' : '' ?>>
                                    <?= ucfirst($etatOption) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Commentaire -->
                    <div class="form-group mb-3">
                        <label for="commentaire">Commentaire :</label>
                        <textarea name="commentaire" id="commentaire" class="form-control" rows="4"
                                  placeholder="Ajouter un commentaire..."><?= htmlspecialchars($commande->getCommentaire()) ?></textarea>
                    </div>
                </div>
            </div> <!-- fin row -->

            <!-- Boutons -->
            <div class="form-group d-flex justify-content-end gap-2 mt-3">
                <button class="btn btn-outline-primary" type="submit">Enregistrer les modifications</button>
                <button class="btn btn-outline-info" type="reset">Annuler les modifications</button>
            </div>
        </form>
    </div>
</main>

</body>
</html>
<script type="text/javascript" src="../../src/assets/js/index.js"> </script>