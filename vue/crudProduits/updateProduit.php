<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../../src/repository/ProduitRepository.php';
require_once __DIR__ . '/../../src/repository/CategoriesRepository.php';
require_once __DIR__ . '/../../src/model/Produit.php';
require_once __DIR__ . '/../../src/model/Categorie.php';
use repository\ProduitRepository;
use repository\CategoriesRepository ;

use model\Produit;
$id = (int)$_GET['id'] ;
$repoProduit = new ProduitRepository();
$repoCategorie = new CategoriesRepository();
$categories = $repoCategorie ->listeCategories();
$produit = $repoProduit->getProduitParId($id);

$valueDate = ($produit && $produit->getDateAjout())
        ? date('Y-m-d', strtotime($produit->getDateAjout()))
        : date('Y-m-d');
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
    <link rel="stylesheet" href="../../src/assets/css/updateProduit.css" />
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
<main class="main-content">
    <div>
        <!-- Bandeau titre -->
        <div class="offre-header d-flex justify-content-between align-items-center p-3 mb-4"
              >
            <h2 class="fw-bold mb-0">Modifier un produit</h2>
            <button type="button" class="btn"
                    onclick="window.location.href='../index.php'">
                <i class="bi bi-arrow-left-circle"></i> Retour
            </button>
        </div>

        <form method="post" action="../../src/traitement/traitementUpdateProduit.php" class="form-container" style="background:white; padding:20px; border-radius:8px; box-shadow:0 0 10px rgba(0,0,0,0.1);">
            <input type="hidden" name="update_produit" value="1">
            <input type="hidden" name="id" value="<?= htmlspecialchars($produit->getIdProduit()) ?>">

            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label for="libelle">Libelle :</label>
                        <input type="text" id="libelle" name="libelle" class="form-control"
                               value="<?= htmlspecialchars($produit->getLibelle()) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="marque">Marque :</label>
                        <input type="text" id="marque" name="marque" class="form-control"
                               value="<?= htmlspecialchars($produit->getMarque()) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="quantite">Quantité :</label>
                        <input type="number" id="quantite" name="quantite" class="form-control"
                               value="<?= htmlspecialchars($produit->getQuantiteCentrale()) ?>" required>
                    </div>
                </div>

                <div class="col">
                    <div class="form-group">
                        <label for="prix_unitaire">Prix unitaire :</label>
                        <input type="number" step="0.01" id="prix_unitaire" name="prix_unitaire" class="form-control"
                               value="<?= htmlspecialchars($produit->getPrixUnitaire()) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="seuil">Seuil alerte :</label>
                        <input type="number" id="seuil" name="seuil" class="form-control" min="1"
                               value="<?= htmlspecialchars($produit->getSeuilAlerte()) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="categorie">Categorie :</label>
                        <select id="categorie" name="categorie" class="form-control" required>
                            <?php foreach ($categories as $categorie) : ?>
                                <option name="ref_categorie" value="<?= $categorie->getIdCategorie() == $produit->getRefCategorie() ? 'selected' : '' ?>">

                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="dateAjout">Date ajout :</label>
                        <input type="date" id="dateAjout" name="date_ajout"
                               value="<?= htmlspecialchars($valueDate) ?>" required>
                    </div>
                </div>
            </div> <!-- fin row -->

            <!-- Boutons côte à côte -->
            <div class="form-group d-flex justify-content-end gap-2 mt-3">
                <button class="btn btn-outline-primary" type="submit">Enregistrer les modifications</button>
                <button  class="btn btn-outline-info" type="reset">Annuler les modifications</button>
            </div>
    </div>
    </form>
</main>
</body>
</html>
<script type="text/javascript" src="../../src/assets/js/index.js"> </script>

