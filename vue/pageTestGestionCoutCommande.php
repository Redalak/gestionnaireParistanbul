<?php
require_once __DIR__ . '/../src/repository/DetailCommandeRepository.php';
use repository\DetailCommandeRepository;

$repo = new DetailCommandeRepository();
$stats = $repo->getCoutsParMagasin();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    {{ ... }}
    <title>Paristanbul • Tableau de bord</title>

    <link rel="stylesheet" href="../src/assets/css/index.css" />
    <style>
        .dashboard-section {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }

        .commandes-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        .commandes-table th,
        .commandes-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
        }

        .commandes-table th {
            background-color: #f8f9fa;
            font-weight: 600;
        }

        .etat-badge {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.8em;
            font-weight: 500;
            white-space: nowrap;
        }

        .etat-en attente {
            background-color: #fff3cd;
            color: #856404;
        }

        .etat-preparée, .etat-preparee {
            background-color: #cce5ff;
            color: #004085;
        }

        .etat-expédiée, .etat-expediee {
            background-color: #d4edda;
            color: #155724;
        }
    </style>
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

<!-- CONTENU PRINCIPAL -->
<main class="main-content">
    <section class="dashboard">
<h1>Analyse des coûts par magasin</h1>

<table class="styled-table">
    <thead>
    <tr>
        <th>Magasin</th>
        <th>Nombre de commandes</th>
        <th>Total (€)</th>
        <th>Détails</th>
    </tr>
    <?php foreach($stats as $ligne) :  ?>
    <td> <?= $ligne['nom'] ?></td>
    <td> <?= $ligne['nb_commandes'] ?></td>
    <td> <?= $ligne['total_cout'] ?></td>
    <td> <button class="voir-detail" data-id="<?= $ligne['id'] ?>" data-nom="<?= $ligne['nom'] ?>">Voir detail</button></td>
    <?php endforeach; ?>
    </thead>
    <tbody>

    </tbody>
</table>

<!-- Modal caché -->
<div id="detailModal" class="modal" style="display:none;">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2 id="modal-title">Détails du magasin</h2>
        <div id="modal-body">Chargement...</div>
    </div>
</div>
</body>
</html>
<script type="text/javascript" src="../src/assets/js/index.js"> </script>
<style>
    .modal {
        position: fixed;
        top: 0; left: 0; width: 100%; height: 100%;
        background-color: rgba(0,0,0,0.5);
        display: flex; justify-content: center; align-items: center;
        z-index: 1000;
    }
    .modal-content {
        background: white; padding: 20px; border-radius: 8px; width: 70%;
        max-height: 80vh; overflow-y: auto; position: relative;
    }
    .close {
        position: absolute; right: 15px; top: 10px; cursor: pointer; font-size: 22px;
    }
</style>

<script>
    document.querySelectorAll('.voir-detail').forEach(btn => {
        btn.addEventListener('click', async () => {
            const id = btn.dataset.id;
            const nom = btn.dataset.nom;
            const modal = document.getElementById('detailModal');
            const body = document.getElementById('modal-body');
            const title = document.getElementById('modal-title');

            title.textContent = "Détails des commandes pour " + nom;
            body.innerHTML = "Chargement...";

            try {
                const response = await fetch(`traitementDetailCout.php?id=${id}`);
                const html = await response.text();
                body.innerHTML = html;
            } catch (err) {
                body.innerHTML = "<p>Erreur lors du chargement des détails.</p>";
            }

            modal.style.display = 'flex';
        });
    });

    // Fermeture du modal
    document.querySelector('.close').addEventListener('click', () => {
        document.getElementById('detailModal').style.display = 'none';
    });

    // Fermer si on clique à l’extérieur
    window.onclick = function(event) {
        const modal = document.getElementById('detailModal');
        if (event.target === modal) modal.style.display = "none";
    }
</script>
