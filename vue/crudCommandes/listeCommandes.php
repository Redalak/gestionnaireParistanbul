<?php
/* listeCommandes.php */
require_once __DIR__ . '/../../src/repository/CommandeRepository.php';
require_once __DIR__ . '/../../src/model/Commande.php';
require_once __DIR__ . '/../../src/repository/MagasinsRepository.php';
require_once __DIR__ . '/../../src/model/Magasin.php';

use repository\CommandeRepository;
use model\Produit;

$repoCommandes = new \repository\CommandeRepository();
$commandes = $repoCommandes ->getAllCommandes() ;


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
    <div class="table-responsive">
        <table id="liste-commandes">
            <thead>
            <tr>
                <th>ID</th>
                <th>Magasin</th>
                <th>Utilisateur</th>
                <th>Date_commande</th>
                <th>Etat</th>
                <th>Commentaire</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($commandes as $commande): ?>
                <tr>
                    <td><?= $commande['id_commande']?></td>
                    <td><?= $commande['nom_magasin']?></td>
                    <td><?= $commande['nom_utilisateur']?></td>
                    <td><?= $commande['date_commande']?></td>
                    <td><?= $commande['etat'] ?></td>
                    <td><?= $commande['commentaire'] ?></td>

                    <td class="actions">
                        <a href="updateCommande.php?id=<?= $commande['id_commande']?>" class="btn btn-sm btn-primary" title="Modifier">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <a href="deleteCommande.php?id=<?= $commande['id_commande'] ?>"
                           class="btn btn-sm btn-danger"
                           title="Supprimer"
                           onclick="return confirm('Voulez-vous vraiment supprimer cet événement ?')">
                            <i class="bi bi-trash"></i>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>


    <footer>&copy; <?= date('Y') ?> Paristanbul — Gestionnaire de stock</footer>
</main>
</body>
</html>
<script type="text/javascript" src="../../src/assets/js/index.js"> </script>
<!-- Datatable JS id="offre-table" -->
<script>
    $(document).ready(function () {
        $('#liste-commandes').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json"
            },
            "pageLength": 10,  // nombre de lignes par page
            "ordering": true,  // tri des colonnes activé
            "searching": true, // barre de recherche activée
            "responsive": true // design responsive
        });
    });
</script>
