<?php
require_once __DIR__ . '/../src/bdd/Bdd.php';
require_once __DIR__ . '/../src/model/Mouvement.php';
require_once __DIR__ . '/../src/repository/MouvementRepository.php';
require_once __DIR__ . '/../src/repository/ProduitRepository.php';
require_once __DIR__ . '/../src/repository/MagasinsRepository.php';

// Initialisation des variables de filtre
$filtreProduit = $_GET['produit'] ?? null;
$filtreMagasin = $_GET['magasin'] ?? null;
$filtreType = $_GET['type'] ?? null;
$filtreDateDebut = $_GET['date_debut'] ?? null;
$filtreDateFin = $_GET['date_fin'] ?? null;

// Initialisation des repositories
$mouvementRepository = new \repository\MouvementRepository();

// Récupération du filtre de type depuis l'URL
$typeFiltre = $_GET['type'] ?? 'all';

// Récupération des mouvements via le repository avec filtrage
if ($typeFiltre === 'all') {
    $mouvements = $mouvementRepository->getAllMouvements();
} else {
    $mouvements = $mouvementRepository->findWithFilters(
        null, // produitId
        null, // magasinId
        $typeFiltre, // type
        null, // dateDebut
        null, // dateFin
        1000, // limit (valeur élevée pour éviter la pagination)
        0     // offset
    );
}
$totalMouvements = count($mouvements);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Mouvements de stock - Paristanbul</title>
    <link rel="stylesheet" href="../../src/assets/css/index.css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
    <style>
        .mouvements-container {
            padding: 20px;
        }
        .filtres {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .filtres h3 {
            margin-top: 0;
            color: #333;
            display: flex;
            align-items: center;
        }
        .filtres .form-group {
            margin-bottom: 10px;
            display: inline-block;
            margin-right: 15px;
            vertical-align: top;
        }
        .filtres label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
            color: #555;
        }
        .filtres select, .filtres input {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            min-width: 200px;
            font-size: 14px;
        }
        .btn {
            padding: 8px 15px;
            background-color: #4e73df;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            font-size: 14px;
            transition: background-color 0.2s;
        }
        .btn:hover {
            background-color: #2e59d9;
        }
        .btn i.material-icons {
            font-size: 16px;
            margin-right: 5px;
        }
        .btn-ajouter {
            background-color: #1cc88a;
            margin-bottom: 15px;
        }
        .btn-ajouter:hover {
            background-color: #17a673;
        }
        .btn-danger {
            background-color: #e74a3b;
        }
        .btn-danger:hover {
            background-color: #c0392b;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            background: white;
            border-radius: 5px;
            overflow: hidden;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
        }
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        th {
            background-color: #f2f2f2;
            font-weight: 600;
            color: #333;
            white-space: nowrap;
        }
        tr:hover {
            background-color: #f9f9f9;
        }
        .entree {
            color: #1cc88a;
            font-weight: 600;
        }
        .sortie {
            color: #e74a3b;
            font-weight: 600;
        }
        .pagination {
            margin-top: 20px;
            display: flex;
            justify-content: center;
            gap: 5px;
            flex-wrap: wrap;
        }
        .pagination a, .pagination span {
            padding: 8px 12px;
            border: 1px solid #ddd;
            text-decoration: none;
            color: #4e73df;
            border-radius: 4px;
            min-width: 40px;
            text-align: center;
        }
        .pagination a:hover {
            background-color: #f2f2f2;
        }
        .pagination .active {
            background-color: #4e73df;
            color: white;
            border-color: #4e73df;
        }
        .badge {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.8em;
            font-weight: 500;
            display: inline-block;
            min-width: 70px;
            text-align: center;
        }
        .badge-entree {
            background-color: #d4edda;
            color: #155724;
        }
        .badge-sortie {
            background-color: #f8d7da;
            color: #721c24;
        }
        .no-data {
            text-align: center;
            padding: 30px;
            color: #6c757d;
            font-style: italic;
            background: white;
            border-radius: 5px;
            margin-top: 20px;
        }
        .filters-container {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 15px;
        }
        .filter-group {
            flex: 1;
            min-width: 200px;
        }
        .filter-actions {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }
        .active-filters {
            background-color: #e9ecef;
            padding: 10px 15px;
            border-radius: 4px;
            margin-bottom: 20px;
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        .filter-tag {
            background-color: #e2e3e5;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 0.85em;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        .filter-tag .material-icons {
            font-size: 14px;
            cursor: pointer;
        }
        .table-responsive {
            overflow-x: auto;
            margin-bottom: 20px;
        }
        @media (max-width: 768px) {
            .filters-container {
                flex-direction: column;
                gap: 10px;
            }
            .filter-group {
                width: 100%;
            }
            .filter-actions {
                flex-direction: column;
                width: 100%;
            }
            .filter-actions .btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
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
                    <li><a href="crudProduits/categories.php" class="nav-link dropdown-link">Catégories</a></li>
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
            <li class="nav-item active">
                <a href="mouvements.php" class="nav-link">
                    <span class="material-symbols-rounded">compare_arrows</span>
                    <span class="nav-label">Mouvements</span>
                </a>
            </li>

            <!-- Statistiques -->
            <li class="nav-item">
                <a href="statistiques.php" class="nav-link">
                    <span class="material-symbols-rounded">query_stats</span>
                    <span class="nav-label">Statistiques</span>
                </a>
            </li>

            <!-- Factures -->
            <li class="nav-item">
                <a href="crudFactures/factures.php" class="nav-link">
                    <span class="material-symbols-rounded">receipt_long</span>
                    <span class="nav-label">Factures</span>
                </a>
            </li>

            <!-- Utilisateurs -->
            <li class="nav-item">
                <a href="crudProfils/profil.php" class="nav-link">
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
    <div class="mouvements-container">
        <h1>Mouvements de stock</h1>
        <hr>
        
        <!-- Boutons de filtrage par type de mouvement -->
        <div class="filtre-type-mouvement" style="margin-bottom: 20px;">
            <a href="?type=all" class="btn <?= !isset($_GET['type']) || $_GET['type'] === 'all' ? 'btn-primary' : 'btn-outline-secondary' ?>">
                Tous les mouvements
            </a>
            <a href="?type=entrée" class="btn <?= (isset($_GET['type']) && $_GET['type'] === 'entrée') ? 'btn-primary' : 'btn-outline-secondary' ?>">
                <i class="material-icons" style="vertical-align: middle; margin-right: 5px;">input</i> Entrées
            </a>
            <a href="?type=sortie" class="btn <?= (isset($_GET['type']) && $_GET['type'] === 'sortie') ? 'btn-primary' : 'btn-outline-secondary' ?>">
                <i class="material-icons" style="vertical-align: middle; margin-right: 5px;">output</i> Sorties
            </a>
        </div>
        
        <!-- Tableau des mouvements -->
                    </button>

                </div>
            </form>
        </div>
        
        <!-- Filtres actifs -->
        <?php if ($filtreProduit || $filtreMagasin || $filtreType || $filtreDateDebut || $filtreDateFin): ?>
        <div class="active-filters">
            <strong>Filtres actifs :</strong>
            <?php if ($filtreProduit): 
                $produitLibelle = '';
                foreach ($produits as $p) {
                    if ($p->getId() == $filtreProduit) {
                        $produitLibelle = $p->getLibelle();
                        break;
                    }
                }
                if ($produitLibelle): ?>
                    <span class="filter-tag">
                        Produit: <?= htmlspecialchars($produitLibelle) ?>
                        <a href="?<?= http_build_query(array_merge($_GET, ['produit' => null])) ?>" class="remove-filter">
                            <i class="material-icons" style="font-size: 14px;">close</i>
                        </a>
                    </span>
                <?php endif; ?>
            <?php endif; ?>
            
            <?php if ($filtreMagasin): 
                $magasinNom = '';
                foreach ($magasins as $m) {
                    if ($m->getId() == $filtreMagasin) {
                        $magasinNom = $m->getNom();
                        break;
                    }
                }
                if ($magasinNom): ?>
                    <span class="filter-tag">
                        Magasin: <?= htmlspecialchars($magasinNom) ?>
                        <a href="?<?= http_build_query(array_merge($_GET, ['magasin' => null])) ?>" class="remove-filter">
                            <i class="material-icons" style="font-size: 14px;">close</i>
                        </a>
                    </span>
                <?php endif; ?>
            <?php endif; ?>
            
            <?php if ($filtreType): ?>
                <span class="filter-tag">
                    Type: <?= $typesMouvement[$filtreType] ?? $filtreType ?>
                    <a href="?<?= http_build_query(array_merge($_GET, ['type' => null])) ?>" class="remove-filter">
                        <i class="material-icons" style="font-size: 14px;">close</i>
                    </a>
                </span>
            <?php endif; ?>
            
            <?php if ($filtreDateDebut): ?>
                <span class="filter-tag">
                    À partir du: <?= (new DateTime($filtreDateDebut))->format('d/m/Y') ?>
                    <a href="?<?= http_build_query(array_merge($_GET, ['date_debut' => null])) ?>" class="remove-filter">
                        <i class="material-icons" style="font-size: 14px;">close</i>
                    </a>
                </span>
            <?php endif; ?>
            
            <?php if ($filtreDateFin): ?>
                <span class="filter-tag">
                    Jusqu'au: <?= (new DateTime($filtreDateFin))->format('d/m/Y') ?>
                    <a href="?<?= http_build_query(array_merge($_GET, ['date_fin' => null])) ?>" class="remove-filter">
                        <i class="material-icons" style="font-size: 14px;">close</i>
                    </a>
                </span>
            <?php endif; ?>
        </div>
        <?php endif; ?>
        
        <!-- Tableau des mouvements -->
        <div class="table-responsive">
            <?php if (empty($mouvements)): ?>
                <div class="no-data">
                    <i class="material-icons" style="font-size: 48px; color: #dee2e6; margin-bottom: 15px;">inventory_2</i>
                    <p>Aucun mouvement trouvé avec les critères sélectionnés</p>
                </div>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Référence</th>
                            <th>Produit</th>
                            <th>Type</th>
                            <th>Quantité</th>
                            <th>Source</th>
                            <th>Magasin</th>
                            <th>Commentaire</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($mouvements as $mouvement): ?>
                            <tr>
                                <td><?= (new DateTime($mouvement->getDateMouvement()))->format('d/m/Y H:i') ?></td>
                                <td>#<?= $mouvement->getIdMouvement() ?></td>
                                <td>
                                    <?= htmlspecialchars($mouvement->getData('produit_libelle')) ?>
                                </td>
                                <td>
                                    <span class="badge <?= $mouvement->getType() === 'entrée' ? 'badge-entree' : 'badge-sortie' ?>">
                                        <?= $mouvement->getType() === 'entrée' ? 'Entrée' : 'Sortie' ?>
                                    </span>
                                </td>
                                <td class="<?= $mouvement->getType() ?>">
                                    <?= $mouvement->getType() === 'entrée' ? '+' : '-' ?>
                                    <?= $mouvement->getQuantite() ?>
                                </td>
                                <td><?= ucfirst(str_replace('_', ' ', $mouvement->getSource())) ?></td>
                                <td><?= htmlspecialchars($mouvement->getData('magasin_nom')) ?></td>
                                <td><?= htmlspecialchars($mouvement->getCommentaire() ?? '') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                
                <!-- Affichage du nombre total de mouvements -->
                <div class="mt-3 text-muted">
                    <?= $totalMouvements ?> mouvement(s) au total
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<footer>
    &copy; <?= date('Y') ?> Paristanbul — Gestionnaire de stock
</footer>

<script>
    // Initialisation des sélecteurs de date avec une valeur par défaut si vide
    document.addEventListener('DOMContentLoaded', function() {
        const aujourdHui = new Date().toISOString().split('T')[0];
        const dateDebut = document.getElementById('date_debut');
        const dateFin = document.getElementById('date_fin');
        
        if (dateDebut && !dateDebut.value) {
            // Par défaut, afficher les 30 derniers jours
            const date = new Date();
            date.setDate(date.getDate() - 30);
            dateDebut.value = date.toISOString().split('T')[0];
        }
        
        if (dateFin && !dateFin.value) {
            dateFin.value = aujourdHui;
        }
    });
</script>

<script type="text/javascript" src="../../src/assets/js/index.js"></script>
</body>
</html>
