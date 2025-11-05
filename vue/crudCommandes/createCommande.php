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


        <form id="form-commande" method="post" class="p-5 bg-white rounded shadow-lg border border-light-subtle" style="font-size: 1.1rem;"
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

            <!-- Produits / Panier -->
            <hr class="my-4">
            <h3 class="fw-bold mb-3">Produits</h3>
            <div class="mb-3" style="position:relative;">
                <label for="search-produit" class="form-label fw-semibold">Rechercher un produit</label>
                <input type="text" id="search-produit" class="form-control form-control-lg" placeholder="Nom du produit...">
                <div id="search-results" class="list-group" style="position:absolute; z-index:10; width:100%; max-height:240px; overflow:auto; display:none;"></div>
            </div>

            <div class="table-responsive">
                <table id="panier" class="table table-striped align-middle">
                    <thead>
                    <tr>
                        <th>Produit</th>
                        <th class="text-end" style="width:140px;">Prix (€)</th>
                        <th class="text-end" style="width:120px;">Qté</th>
                        <th class="text-end" style="width:140px;">Remise (%)</th>
                        <th class="text-end" style="width:160px;">Total ligne (€)</th>
                        <th style="width:80px;">Actions</th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>

            <div class="d-flex justify-content-end gap-4 mt-3">
                <div><strong>Sous-total:</strong> <span id="sous-total">0.00 €</span></div>
                <div><strong>TVA (0%):</strong> <span id="tva-total">0.00 €</span></div>
                <div><strong>Total:</strong> <span id="total-ttc">0.00 €</span></div>
            </div>

            <input type="hidden" name="lignes_json" id="lignes_json" value="[]">

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
<script>
    (function(){
        const apiUrl = '../../src/api/produits_search.php';
        const $input = $('#search-produit');
        const $results = $('#search-results');
        const $tbody = $('#panier tbody');
        const $sousTotal = $('#sous-total');
        const $tvaTotal = $('#tva-total');
        const $totalTTC = $('#total-ttc');
        const $hidden = $('#lignes_json');

        let debounceTimer = null;
        let produitsCache = [];
        let lignes = [];

        function fmt(n){ return (Number(n)||0).toFixed(2); }

        function renderLignes(){
            $tbody.empty();
            let st = 0;
            lignes.forEach((l, idx) => {
                const totalLigne = (l.prix_unitaire * l.quantite) * (1 - (l.remise||0)/100);
                st += totalLigne;
                const tr = $(
                    `<tr>
                        <td>${l.libelle}</td>
                        <td class="text-end"><input type="number" class="form-control form-control-sm input-prix" step="0.01" min="0" value="${fmt(l.prix_unitaire)}"></td>
                        <td class="text-end"><input type="number" class="form-control form-control-sm input-qty" step="1" min="1" value="${l.quantite}"></td>
                        <td class="text-end"><input type="number" class="form-control form-control-sm input-remise" step="1" min="0" max="100" value="${l.remise||0}"></td>
                        <td class="text-end total-ligne">${fmt(totalLigne)} €</td>
                        <td><button class="btn btn-sm btn-danger btn-remove"><i class="bi bi-trash"></i></button></td>
                    </tr>`);
                tr.find('.input-prix').on('input', function(){ l.prix_unitaire = parseFloat(this.value)||0; renderLignes(); });
                tr.find('.input-qty').on('input', function(){ l.quantite = Math.max(1, parseInt(this.value)||1); renderLignes(); });
                tr.find('.input-remise').on('input', function(){ l.remise = Math.min(100, Math.max(0, parseFloat(this.value)||0)); renderLignes(); });
                tr.find('.btn-remove').on('click', function(e){ e.preventDefault(); lignes.splice(idx,1); renderLignes(); });
                $tbody.append(tr);
            });
            $sousTotal.text(fmt(st) + ' €');
            $tvaTotal.text(fmt(0) + ' €');
            $totalTTC.text(fmt(st) + ' €');
            $hidden.val(JSON.stringify(lignes));
        }

        function showResults(list){
            if(!list.length){ $results.hide(); return; }
            $results.empty();
            list.forEach(p => {
                const item = $(`<a href="#" class="list-group-item list-group-item-action">${p.libelle} · <span class="text-muted">Stock: ${p.quantite_centrale}</span> · <strong>${fmt(p.prix_unitaire)} €</strong></a>`);
                item.on('click', function(e){
                    e.preventDefault();
                    const exist = lignes.find(l => l.id_produit === p.id);
                    if (exist) { exist.quantite += 1; }
                    else {
                        lignes.push({ id_produit: p.id, libelle: p.libelle, prix_unitaire: p.prix_unitaire, quantite: 1, remise: 0 });
                    }
                    $results.hide();
                    $input.val('');
                    renderLignes();
                });
                $results.append(item);
            });
            $results.show();
        }

        function searchProduits(q){
            if (q.length < 2) { $results.hide(); return; }
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(function(){
                $.getJSON(apiUrl, { q }, function(resp){
                    if (resp && resp.ok) {
                        produitsCache = resp.produits || [];
                        showResults(produitsCache.slice(0,20));
                    } else {
                        showResults([]);
                    }
                }).fail(function(){ showResults([]); });
            }, 200);
        }

        $input.on('input', function(){ searchProduits(this.value.trim()); });
        $(document).on('click', function(e){ if(!$(e.target).closest('#search-results, #search-produit').length) $results.hide(); });

        $('#form-commande').on('submit', function(){
            if (lignes.length === 0) {
                if(!confirm('Aucune ligne produit. Continuer ?')) return false;
            }
            $hidden.val(JSON.stringify(lignes));
            return true;
        });
    })();
</script>
