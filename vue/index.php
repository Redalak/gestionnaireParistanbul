<?php
/* index.php — tableau de bord principal */
require_once __DIR__ . '/../src/bdd/Bdd.php';
require_once __DIR__ . '/../src/model/Commande.php';
require_once __DIR__ . '/../src/repository/CommandeRepository.php';
require_once __DIR__ . '/../src/repository/FactureRepository.php';
require_once __DIR__ . '/../src/repository/ProduitRepository.php';
require_once __DIR__ . '/../src/model/Produit.php';

$commandeRepository = new \repository\CommandeRepository();
$factureRepository = new \repository\FactureRepository();
$produitRepository = new \repository\ProduitRepository();

$dernieresCommandes = $commandeRepository->getDernieresCommandesParEtat();
$facturesImpayees = $factureRepository->getFacturesImpayees();
$nombreFacturesImpayees = count($facturesImpayees);
$derniersProduits = $produitRepository->getDerniersProduits(12);
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
    </nav>
</aside>

<!-- CONTENU PRINCIPAL -->
<main class="main-content">
    <section class="dashboard">
        <h1>Tableau de bord</h1>
        <hr>
        <div style="padding:40px">
            <h2>Résumé du stock</h2>
            <ul class="summary-list">
                <li class="summary-item">
                    <span class="material-symbols-rounded">inventory</span>
                    <div>
                        <div class="summary-value">Total produits en stock</div>
                        <div class="summary-label">En attente de mise à jour</div>
                    </div>
                </li>
                <li class="summary-item">
                    <span class="material-symbols-rounded">shopping_cart</span>
                    <div>
                        <div class="summary-value"><?= $commandeRepository->countCommandesEnCours() ?> commandes</div>
                        <div class="summary-label">En attente, préparées ou expédiées</div>
                    </div>
                </li>
                <li class="summary-item">
                    <span class="material-symbols-rounded">receipt_long</span>
                    <div>
                        <div class="summary-value"><?= $nombreFacturesImpayees ?> factures</div>
                        <div class="summary-label">En attente de paiement</div>
                    </div>
                </li>
                <li class="summary-item">
                    <span class="material-symbols-rounded">warning</span>
                    <div>
                        <div class="summary-value">Produits en alerte</div>
                        <div class="summary-label">Niveaux de stock bas</div>
                    </div>
                </li>
            </ul>
            <style>
                .summary-list {
                    display: grid;
                    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                    gap: 1rem;
                    padding: 0;
                    margin: 0;
                    list-style: none;
                }
                .summary-item {
                    background: white;
                    border-radius: 8px;
                    padding: 1.25rem;
                    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
                    display: flex;
                    align-items: center;
                    gap: 1rem;
                    transition: transform 0.2s;
                }
                .summary-item:hover {
                    transform: translateY(-2px);
                    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
                }
                .summary-item .material-symbols-rounded {
                    font-size: 2rem;
                    color: #4a6cf7;
                    background: #eef2ff;
                    padding: 0.75rem;
                    border-radius: 50%;
                }
                .summary-value {
                    font-weight: 600;
                    font-size: 1.1rem;
                    color: #1f2937;
                }
                .summary-label {
                    font-size: 0.875rem;
                    color: #6b7280;
                    margin-top: 0.25rem;
                }
            </style>
        </div>

        <div class="dashboard-section">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                <h2>Commandes récentes</h2>
                <div class="commande-counter" style="background-color: #f8f9fa; padding: 8px 15px; border-radius: 20px; font-weight: 500; display: flex; align-items: center; gap: 8px;">
                    <span class="material-symbols-rounded" style="font-size: 20px;">shopping_cart</span>
                    <span><?= $commandeRepository->countCommandesEnCours() ?> commandes en cours</span>
                </div>
            </div>
            <div class="commandes-list">
                <?php if (!empty($dernieresCommandes)): ?>
                    <table class="commandes-table">
                        <thead>
                            <tr>
                                <th>N° Commande</th>
                                <th>Date</th>
                                <th>Magasin</th>
                                <th>Utilisateur</th>
                                <th>État</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($dernieresCommandes as $commande): ?>
                                <tr>
                                    <td>#<?= htmlspecialchars($commande['id_commande']) ?></td>
                                    <td><?= (new DateTime($commande['date_commande']))->format('d/m/Y H:i') ?></td>
                                    <td><?= htmlspecialchars($commande['nom_magasin'] ?? 'N/A') ?></td>
                                    <td>
                                        <?= htmlspecialchars(
                                            (!empty($commande['prenom_utilisateur']) || !empty($commande['nom_utilisateur'])) 
                                                ? trim($commande['prenom_utilisateur'] . ' ' . $commande['nom_utilisateur'])
                                                : 'N/A'
                                        ) ?>
                                    </td>
                                    <td>
                                        <span class="etat-badge etat-<?= strtolower(str_replace('é', 'e', $commande['etat'])) ?>">
                                            <?= htmlspecialchars($commande['etat']) ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>Aucune commande récente.</p>
                <?php endif; ?>
            </div>
        </div>

        <div style="padding:40px">
            <h2>Derniers produits ajoutés</h2>
            <p>Liste des 10 à 12 derniers produits + bouton “Voir tout le stock”.</p>
        </div>

        <div style="padding:40px">
            <h2>Statistiques simples</h2>
            <ul>
                <li>Graphique “Stock par catégorie”</li>
                <li>Graphique “Produits les plus vendus”</li>
                <li>Chiffre d’affaires mensuel / par magasin</li>
            </ul>
        </div>

        <div style="padding:40px">
            <h2>Alertes</h2>
            <ul>
                <li>Produits sous le seuil</li>
                <li>Commandes non livrées depuis longtemps</li>
            </ul>
        </div>

        <!-- Section Factures impayées -->
        <div class="dashboard-section">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                <h2>Factures impayées</h2>
                <div class="facture-counter" style="background-color: #f8f9fa; padding: 8px 15px; border-radius: 20px; font-weight: 500; display: flex; align-items: center; gap: 8px;">
                    <span class="material-symbols-rounded" style="font-size: 20px;">receipt_long</span>
                    <span><?= $nombreFacturesImpayees ?> factures impayées</span>
                </div>
            </div>
            
            <div class="factures-list">
                <?php if (!empty($facturesImpayees)): ?>
                    <table class="commandes-table">
                        <thead>
                            <tr>
                                <th>N° Facture</th>
                                <th>Date d'émission</th>
                                <th>N° Commande</th>
                                <th>Montant</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($facturesImpayees as $facture): ?>
                                <tr>
                                    <td>#<?= htmlspecialchars($facture['id_facture']) ?></td>
                                    <td><?= (new DateTime($facture['date_emission']))->format('d/m/Y') ?></td>
                                    <td>#<?= htmlspecialchars($facture['ref_commande'] ?? 'N/A') ?></td>
                                    <td><?= number_format($facture['montant'], 2, ',', ' ') ?> €</td>
                                    <td>
                                        <span class="etat-badge etat-impayee">
                                            <?= $facture['paye'] ? 'Payée' : 'Impayée' ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>Aucune facture impayée.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Section Derniers produits -->
        <div class="dashboard-section">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                <h2>Derniers produits ajoutés</h2>
                <div class="produit-counter" style="background-color: #f8f9fa; padding: 8px 15px; border-radius: 20px; font-weight: 500; display: flex; align-items: center; gap: 8px;">
                    <span class="material-symbols-rounded" style="font-size: 20px;">inventory_2</span>
                    <span><?= count($derniersProduits) ?> produits</span>
                </div>
            </div>
            
            <div class="produits-list">
                <?php if (!empty($derniersProduits)): ?>
                    <table class="commandes-table">
                        <thead>
                            <tr>
                                <th>Référence</th>
                                <th>Produit</th>
                                <th>Marque</th>
                                <th>Catégorie</th>
                                <th>Quantité</th>
                                <th>Prix unitaire</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($derniersProduits as $produit): 
                                $classeAlerte = ($produit['quantite_centrale'] <= $produit['seuil_alerte']) ? 'style="background-color: #fff3e0;"' : '';
                            ?>
                                <tr <?= $classeAlerte ?>>
                                    <td>#<?= htmlspecialchars($produit['id_produit']) ?></td>
                                    <td><?= htmlspecialchars($produit['libelle']) ?></td>
                                    <td><?= htmlspecialchars($produit['marque'] ?? 'N/A') ?></td>
                                    <td><?= htmlspecialchars($produit['nom_categorie'] ?? 'Non catégorisé') ?></td>
                                    <td>
                                        <span class="quantite-cell <?= $produit['quantite_centrale'] <= $produit['seuil_alerte'] ? 'quantite-alerte' : '' ?>">
                                            <?= htmlspecialchars($produit['quantite_centrale']) ?>
                                            <?php if ($produit['quantite_centrale'] <= $produit['seuil_alerte']): ?>
                                                <span class="material-symbols-rounded" style="color: #f44336; font-size: 16px; vertical-align: middle; margin-left: 4px;">warning</span>
                                            <?php endif; ?>
                                        </span>
                                    </td>
                                    <td><?= number_format($produit['prix_unitaire'], 2, ',', ' ') ?> €</td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>Aucun produit trouvé.</p>
                <?php endif; ?>
            </div>
        </div>
    </section>
</main>

<style>
    .produits-list {
        margin-top: 15px;
    }
    
    .quantite-cell {
        display: inline-flex;
        align-items: center;
        padding: 4px 8px;
        border-radius: 12px;
        font-weight: 500;
    }
    
    .quantite-alerte {
        color: #d32f2f;
        background-color: #ffebee;
    }
    
    .commandes-table tbody tr:hover {
        background-color: #f5f5f5;
    }
</style>

<style>
    .factures-list {
        margin-top: 15px;
    }
    
    .etat-impayee {
        background-color: #f8d7da;
        color: #721c24;
    }
    
    .etat-payee {
        background-color: #d4edda;
        color: #155724;
    }
</style>

<footer>
    &copy; <?= date('Y') ?> Paristanbul — Gestionnaire de stock
</footer>


</body>
</html>
<script type="text/javascript" src="../src/assets/js/index.js"> </script>