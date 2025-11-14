<?php
/* factures.php */
require_once __DIR__ . '/../../src/auth/Auth.php';
\auth\Auth::startSession();
\auth\Auth::requireAnyRole(['admin','gestionnaire']);

require_once __DIR__ . '/../../src/bdd/Bdd.php';
use bdd\Bdd ;
require_once __DIR__ . '/../../src/repository/FactureRepository.php';
use repository\FactureRepository;

$factureRepository = new FactureRepository() ;
$factures = $factureRepository -> getAllFactures();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Factures • Paristanbul</title>

    <link rel="stylesheet" href="../../src/assets/css/index.css">
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="stylesheet"
          href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" />
</head>

<body>

<!-- Bouton menu mobile -->
<button class="sidebar-menu-button">
    <span class="material-symbols-rounded">menu</span>
</button>

<!-- BARRE LATÉRALE -->

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
                        <li><a href="../crudProduits/listeProduits.php" class="nav-link dropdown-link">Liste des produits</a></li>
                        <li><a href="../crudProduits/createProduit.php" class="nav-link dropdown-link">Ajouter un produit</a></li>
                        <li><a href="../crudProduits/categories.php" class="nav-link dropdown-link">Catégories</a></li>
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
                    <a href="../mouvements.php" class="nav-link">
                        <span class="material-symbols-rounded">compare_arrows</span>
                        <span class="nav-label">Mouvements</span>
                    </a>
                </li>

                <!-- Statistiques -->
                <li class="nav-item">
                    <a href="../statistiques.php" class="nav-link">
                        <span class="material-symbols-rounded">query_stats</span>
                        <span class="nav-label">Statistiques</span>
                    </a>
                </li>
            <!-- Logistique -->
            <li class="nav-item">
                <a href="../agenda.php" class="nav-link">
                    <span class="material-symbols-rounded">query_stats</span>
                    <span class="nav-label">Agenda</span>
                </a>
            </li>

                <!-- Factures -->
                <li class="nav-item">
                    <a href="../crudFactures/factures.php" class="nav-link">
                        <span class="material-symbols-rounded">receipt_long</span>
                        <span class="nav-label">Factures</span>
                    </a>
                </li>

                <!-- Utilisateurs -->
                <li class="nav-item">
                    <a href="../crudProfils/profil.php" class="nav-link">
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


<!-- CONTENU PRINCIPAL -->
<main class="main-content">

    <div class="page-header">
        <h1>Gestion des factures</h1>
        <p>Suivi des factures clients et fournisseurs liées aux commandes.</p>
    </div>

    <section class="card p-4 mt-6">
        <table id="table-factures" class="table table-striped" style="width:100%">
            <thead>
            <tr>
                <th>ID</th>
                <th>Commande</th>
                <th>Magasin</th>
                <th>Montant (€)</th>
                <th>Date émission</th>
                <th>État paiement</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($factures as $f): ?>
                <tr data-id="<?= $f['id_facture'] ?>">
                    <td><?= $f['id_facture'] ?></td>
                    <td>#<?= $f['ref_commande'] ?> (<?= htmlspecialchars($f['etat']) ?>)</td>
                    <td><?= htmlspecialchars($f['magasin_nom'] ?? '—') ?></td>
                    <td><?= number_format($f['montant'], 2, ',', ' ') ?> €</td>
                    <td><?= date('d/m/Y', strtotime($f['date_emission'])) ?></td>
                    <td>
                        <button class="btn-pay btn <?= $f['paye'] ? 'btn-success' : 'btn-warning' ?>">
                            <?= $f['paye'] ? 'Payée' : 'En attente' ?>
                        </button>
                    </td>
                    <td>
                        <a href="detailFacture.php?id=<?= $f['id_facture'] ?>" class="btn btn-info">Détail</a>
                        <button class="btn btn-danger btn-delete">Supprimer</button>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </section>

    <footer>&copy; <?= date('Y') ?> Paristanbul — Gestionnaire de stock</footer>
</main>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script src="../../src/assets/js/index.js"></script>

<script>
    $(document).ready(function() {
        $('#table-factures').DataTable({
            language: { url: '//cdn.datatables.net/plug-ins/1.13.4/
}
</script>