<?php
require_once __DIR__ . '/../../src/repository/ProduitRepository.php';
require_once __DIR__ . '/../../src/repository/CategoriesRepository.php';
require_once __DIR__ . '/../../src/model/Produit.php';

use repository\ProduitRepository;

use repository\CategoriesRepository;
use model\Produit;

$repo = new ProduitRepository();
$produits = $repo->listeProduits();

// Charger les noms de catégories pour affichage
$catRepo = new CategoriesRepository();
$catPairs = $catRepo->pairs(); // [ [id_categorie, nom], ... ]
$catNames = [];
foreach ($catPairs as $c) {
    $catNames[(string)$c['id_categorie']] = $c['nom'];
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- DataTables CSS + JS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Paristanbul • Tableau de bord</title>

    <link rel="stylesheet" href="../../src/assets/css/index.css" />
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
    <style>
        body { background: #f3f4f6; color: #111827; }
        .page-header {
            display:flex; align-items:center; justify-content:space-between;
            padding:16px 0; margin:0 0 8px 0;
            border-bottom:1px solid #e5e7eb;
        }
        .page-header h1 { font-size:22px; margin:0; }
        .toolbar {
            display:flex; gap:18px; align-items:center; flex-wrap:wrap;
            background:#fff; border:1px solid #e5e7eb; border-radius:10px;
            padding:12px 14px; margin: 8px 0 14px 0;
            box-shadow: 0 1px 2px rgba(0,0,0,0.04);
        }
        .toolbar select, .toolbar input {
            padding:8px 10px; border:1px solid #d1d5db; border-radius:8px;
            background:#fff; color: #111827;
        }
        .btn-ghost { border:1px solid #d1d5db; background:#fff; color: #111827; border-radius:8px; padding:8px 12px; }
        .btn-primary { background:#2563eb; color:#fff; border:none; border-radius:8px; padding:10px 14px; }
        .btn-primary:hover { background:#1d4ed8; }
        .dataTables_wrapper .dataTables_filter input { border-radius:8px; background:#fff; color:#111827; border:1px solid #d1d5db; }
        table.dataTable.display tbody tr:hover { background:#f8fafc; }
        table.dataTable thead th { background:#f3f4f6; color: #111827; }
        .dataTables_wrapper .dataTables_paginate .paginate_button { color: #111827 !important; }
        .dataTables_wrapper .dataTables_length select { background:#fff; color:#111827; border:1px solid #d1d5db; }
        .dataTables_wrapper .dataTables_info { color: #6b7280; }
        .dataTables_wrapper .dt-buttons .dt-button { background: #fff; color: #111827; border:1px solid #d1d5db; border-radius:8px; padding:6px 10px; }
        .dataTables_wrapper .dt-buttons .dt-button:hover { background: #f8fafc; }
        .card { background: #fff; border:1px solid #e5e7eb; border-radius:12px; }
        .main-content { color: #111827; }
        .nav-link, .nav-label { color: inherit; }
        .badge { display:inline-block; padding:4px 8px; background:#eef2ff; color:#3730a3; border-radius:999px; font-size:12px; border:1px solid #c7d2fe; }
        td.actions a.btn { border-radius:8px; padding:6px 10px; }
        td.actions a.btn-primary { background:#3b82f6; border:none; }
        td.actions a.btn-danger { background:#ef4444; border:none; }
        .stats-bar { margin: 8px 0 14px 0; padding: 10px 12px; background:#fff; border:1px solid #e5e7eb; border-radius:10px; color:#374151; }
        table.dataTable tbody tr { cursor: pointer; }
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
<main class="main-content" style="padding-right: 16px;">
    <div class="page-header">
        <h1>Liste des produits</h1>
        <div style="display:flex; gap:10px; align-items:center;">
            <a href="createProduit.php" class="btn-primary">+ Ajouter un produit</a>
        </div>
    </div>
    <div class="toolbar">
        <div style="display:flex; gap:12px; align-items:center;">
            <label for="filtre-categorie"><strong>Catégorie:</strong></label>
            <select id="filtre-categorie">
                <option value="">Toutes</option>
                <?php foreach ($catPairs as $c): ?>
                    <option value="<?= htmlspecialchars($c['nom']) ?>"><?= htmlspecialchars($c['nom']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div style="display:flex; gap:8px; align-items:center;">
            <label><strong>Seuil alerte:</strong></label>
            <input type="number" id="seuil-min" placeholder="Min" style="width:100px;">
            <span>—</span>
            <input type="number" id="seuil-max" placeholder="Max" style="width:100px;">
            <button id="seuil-reset" type="button" class="btn-ghost">Réinitialiser</button>
        </div>
    </div>
    <div id="stats" class="stats-bar">Chargement des statistiques…</div>
    <div class="table-responsive">
        <table id="liste-produits" class="display stripe hover order-column" style="width:100%">
            <thead>
            <tr>
                <th>ID</th>
                <th>Libellé</th>
                <th>Catégorie</th>
                <th>Quantité centrale</th>
                <th>Seuil alerte</th>
                <th>Prix unitaire</th>
                <th>Date ajout</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($produits as $produit): ?>
                <tr>
                    <td><?= $produit->getIdProduit() ?></td>
                    <td><?= htmlspecialchars($produit->getLibelle()) ?></td>
                    <td><span class="badge"><?= htmlspecialchars($catNames[(string)$produit->getRefCategorie()] ?? '-') ?></span></td>
                    <td><?= (int)$produit->getQuantiteCentrale() ?></td>
                    <td><?= (int)$produit->getSeuilAlerte() ?></td>
                    <td><?= htmlspecialchars($produit->getPrixUnitaire()) ?></td>
                    <td><?= htmlspecialchars($produit->getDateAjout()) ?></td>

                    <td class="actions">
                        <a href="updateProduit.php?id=<?= $produit->getIdProduit() ?>" class="btn btn-sm btn-primary" title="Modifier">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <a href="../../src/traitement/traitementDeleteProduit.php?id=<?= $produit->getIdProduit() ?>"
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
    // Thème: initialisation depuis localStorage
    (function() {
        const saved = localStorage.getItem('theme');
        if (saved === 'dark') {
            document.body.classList.add('theme-dark');
        }
        $(document).on('click', '#theme-toggle', function() {
            document.body.classList.toggle('theme-dark');
            const isDark = document.body.classList.contains('theme-dark');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
        });
    })();

    $(document).ready(function () {
        // Filtre personnalisé pour le seuil (colonne 4)
        $.fn.dataTable.ext.search.push(function(settings, data) {
            const min = parseFloat($('#seuil-min').val());
            const max = parseFloat($('#seuil-max').val());
            const seuilColIndex = 4; // index de la colonne "Seuil alerte"
            const val = parseFloat(data[seuilColIndex]) || 0;

            const hasMin = !isNaN(min);
            const hasMax = !isNaN(max);

            if (!hasMin && !hasMax) return true;
            if (hasMin && !hasMax) return val >= min;
            if (!hasMin && hasMax) return val <= max;
            return val >= min && val <= max;
        });

        const table = $('#liste-produits').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json"
            },
            "pageLength": 10,  // nombre de lignes par page
            "ordering": true,  // tri des colonnes activé
            "searching": true, // barre de recherche activée
            "responsive": true, // design responsive
            "dom": '<"top"fB>rt<"bottom"lip><"clear">',
            "buttons": [
                { extend: 'copyHtml5', text: 'Copier' },
                { extend: 'csvHtml5', text: 'CSV', title: 'produits' },
                { extend: 'excelHtml5', text: 'Excel', title: 'produits' },
                { extend: 'pdfHtml5', text: 'PDF', title: 'produits', orientation: 'landscape', pageSize: 'A4' },
                { extend: 'print', text: 'Imprimer', title: 'Liste des produits' },
                { extend: 'colvis', text: 'Colonnes' }
            ],
            "columnDefs": [
                { targets: [0,3,4], className: 'dt-body-right' },
            ]
        });

        // Filtre par catégorie (colonne index 2)
        $('#filtre-categorie').on('change', function() {
            const val = $(this).val();
            // Recherche exacte pour la catégorie, sinon reset
            if (val) {
                table.column(2).search('^' + $.fn.dataTable.util.escapeRegex(val) + '$', true, false).draw();
            } else {
                table.column(2).search('').draw();
            }
            localStorage.setItem('filter_categorie', val || '');
        });

        // Rafraîchir quand min/max changent
        $('#seuil-min, #seuil-max').on('input', function() {
            table.draw();
            localStorage.setItem('seuil_min', $('#seuil-min').val());
            localStorage.setItem('seuil_max', $('#seuil-max').val());
        });
        $('#seuil-reset').on('click', function() {
            $('#seuil-min').val('');
            $('#seuil-max').val('');
            table.draw();
            localStorage.removeItem('seuil_min');
            localStorage.removeItem('seuil_max');
        });

        // Restaurer filtres depuis localStorage
        const savedCat = localStorage.getItem('filter_categorie') || '';
        if (savedCat) {
            $('#filtre-categorie').val(savedCat).trigger('change');
        }
        const savedMin = localStorage.getItem('seuil_min') || '';
        const savedMax = localStorage.getItem('seuil_max') || '';
        if (savedMin) $('#seuil-min').val(savedMin);
        if (savedMax) $('#seuil-max').val(savedMax);
        if (savedMin || savedMax) table.draw();

        // Stats dynamiques: nombre affiché, somme quantités, valeur totale
        function updateStats() {
            const data = table.rows({ search: 'applied' }).data();
            let count = data.length;
            let sumQty = 0;
            let sumVal = 0;
            for (let i = 0; i < data.length; i++) {
                const qty = parseFloat(data[i][3]) || 0; // Quantité centrale
                const prix = parseFloat((data[i][5]+'').toString().replace(',', '.')) || 0; // Prix unitaire
                sumQty += qty;
                sumVal += qty * prix;
            }
            $('#stats').text(`${count} produit(s) affiché(s) — Quantité totale: ${sumQty} — Valeur totale: ${sumVal.toFixed(2)} €`);
        }
        table.on('draw', updateStats);
        updateStats();

        // Rendre les lignes cliquables (sauf clic sur actions)
        $('#liste-produits tbody').on('click', 'tr', function(e) {
            if ($(e.target).closest('a, button, i').length) return;
            const id = $(this).find('td').eq(0).text().trim();
            if (id) window.location.href = 'updateProduit.php?id=' + id;
        });
    });
</script>
<script type="text/javascript" src="../../src/assets/js/index.js"> </script>
