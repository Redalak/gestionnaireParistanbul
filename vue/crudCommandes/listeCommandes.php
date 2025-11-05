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
        .page-header { display:flex; align-items:center; justify-content:space-between; padding:16px 0; margin:0 0 8px 0; border-bottom:1px solid #e5e7eb; }
        .page-header h1 { font-size:22px; margin:0; }
        .toolbar { display:flex; gap:18px; align-items:center; flex-wrap:wrap; background:#fff; border:1px solid #e5e7eb; border-radius:10px; padding:12px 14px; margin: 8px 0 14px 0; box-shadow: 0 1px 2px rgba(0,0,0,0.04); }
        .toolbar select, .toolbar input { padding:8px 10px; border:1px solid #d1d5db; border-radius:8px; background:#fff; color:#111827; }
        .btn-ghost { border:1px solid #d1d5db; background:#fff; color: #111827; border-radius:8px; padding:8px 12px; }
        .btn-primary { background:#2563eb; color:#fff; border:none; border-radius:8px; padding:10px 14px; }
        .btn-primary:hover { background:#1d4ed8; }
        .dataTables_wrapper .dataTables_filter input { border-radius:8px; background:#fff; color:#111827; border:1px solid #d1d5db; }
        table.dataTable.display tbody tr:hover { background:#f8fafc; cursor:pointer; }
        table.dataTable thead th { background:#f3f4f6; color: #111827; }
        .dataTables_wrapper .dataTables_paginate .paginate_button { color: #111827 !important; }
        .dataTables_wrapper .dataTables_length select { background:#fff; color:#111827; border:1px solid #d1d5db; }
        .dataTables_wrapper .dataTables_info { color: #6b7280; }
        .dataTables_wrapper .dt-buttons .dt-button { background: #fff; color: #111827; border:1px solid #d1d5db; border-radius:8px; padding:6px 10px; }
        .dataTables_wrapper .dt-buttons .dt-button:hover { background: #f8fafc; }
        .badge { display:inline-block; padding:4px 8px; border-radius:999px; font-size:12px; border:1px solid transparent; }
        .etat-en_attente { background:#fff7ed; color:#9a3412; border-color:#fed7aa; }
        .etat-préparée { background:#ecfeff; color:#155e75; border-color:#a5f3fc; }
        .etat-expédiée { background:#eef2ff; color:#3730a3; border-color:#c7d2fe; }
        .etat-livrée { background:#ecfdf5; color:#065f46; border-color:#a7f3d0; }
        .etat-annulée { background:#fee2e2; color:#991b1b; border-color:#fecaca; }
        .stats-bar { margin: 8px 0 14px 0; padding: 10px 12px; background:#fff; border:1px solid #e5e7eb; border-radius:10px; color:#374151; }
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
<main class="main-content" style="padding-right:16px;">
    <div class="page-header">
        <h1>Liste des commandes</h1>
        <a href="createCommande.php" class="btn-primary">+ Nouvelle commande</a>
    </div>
    <div class="toolbar">
        <div style="display:flex; gap:10px; align-items:center;">
            <label for="filtre-etat"><strong>État:</strong></label>
            <select id="filtre-etat">
                <option value="">Tous</option>
                <option value="en attente">En attente</option>
                <option value="préparée">Préparée</option>
                <option value="expédiée">Expédiée</option>
                <option value="livrée">Livrée</option>
                <option value="annulée">Annulée</option>
            </select>
        </div>
        <div style="display:flex; gap:10px; align-items:center;">
            <label for="filtre-user"><strong>Utilisateur:</strong></label>
            <select id="filtre-user">
                <option value="">Tous</option>
                <?php
                $usersList = [];
                foreach ($commandes as $c) {
                    $name = (string)$c['nom_utilisateur'];
                    $usersList[$name] = true;
                }
                ksort($usersList);
                foreach (array_keys($usersList) as $name): ?>
                    <option value="<?= htmlspecialchars($name) ?>"><?= htmlspecialchars($name) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div style="display:flex; gap:8px; align-items:center;">
            <label><strong>Date:</strong></label>
            <input type="date" id="date-min">
            <span>—</span>
            <input type="date" id="date-max">
            <button id="date-reset" type="button" class="btn-ghost">Réinitialiser</button>
        </div>
    </div>
    <div id="stats" class="stats-bar">Chargement des statistiques…</div>
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
                    <td><span class="badge etat-<?= htmlspecialchars(str_replace(' ', '_', strtolower($commande['etat']))) ?>"><?= htmlspecialchars($commande['etat']) ?></span></td>
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
        // Filtre date personnalisé (colonne 3)
        $.fn.dataTable.ext.search.push(function(settings, data) {
            const min = $('#date-min').val();
            const max = $('#date-max').val();
            // data[3] contient "YYYY-MM-DD HH:MM:SS" => on ne prend que la date
            const rowDateStr = (data[3] || '').toString().substring(0,10);
            if (!min && !max) return true;
            if (min && rowDateStr < min) return false;
            if (max && rowDateStr > max) return false;
            return true;
        });

        const table = $('#liste-commandes').DataTable({
            "language": { "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json" },
            "pageLength": 10,
            "ordering": true,
            "searching": true,
            "responsive": true,
            "dom": '<"top"fB>rt<"bottom"lip><"clear">',
            "buttons": [
                { extend: 'copyHtml5', text: 'Copier' },
                { extend: 'csvHtml5', text: 'CSV', title: 'commandes' },
                { extend: 'excelHtml5', text: 'Excel', title: 'commandes' },
                { extend: 'pdfHtml5', text: 'PDF', title: 'commandes', orientation: 'landscape', pageSize: 'A4' },
                { extend: 'print', text: 'Imprimer', title: 'Liste des commandes' },
                { extend: 'colvis', text: 'Colonnes' }
            ]
        });

        // Filtre par utilisateur (colonne 2)
        $('#filtre-user').on('change', function() {
            const val = $(this).val();
            if (val) {
                table.column(2).search('^' + $.fn.dataTable.util.escapeRegex(val) + '$', true, false).draw();
            } else {
                table.column(2).search('').draw();
            }
            localStorage.setItem('cmd_user', val || '');
        });

        // Filtre par état (colonne 4)
        $('#filtre-etat').on('change', function() {
            const val = $(this).val();
            if (val) {
                table.column(4).search('^' + $.fn.dataTable.util.escapeRegex(val) + '$', true, false).draw();
            } else {
                table.column(4).search('').draw();
            }
            localStorage.setItem('cmd_etat', val || '');
        });

        // Filtre par date
        $('#date-min, #date-max').on('change', function() {
            table.draw();
            localStorage.setItem('cmd_date_min', $('#date-min').val());
            localStorage.setItem('cmd_date_max', $('#date-max').val());
        });
        $('#date-reset').on('click', function() {
            $('#date-min').val('');
            $('#date-max').val('');
            table.draw();
            localStorage.removeItem('cmd_date_min');
            localStorage.removeItem('cmd_date_max');
        });

        // Restaurer filtres
        const se = localStorage.getItem('cmd_etat') || '';
        if (se) { $('#filtre-etat').val(se).trigger('change'); }
        const su = localStorage.getItem('cmd_user') || '';
        if (su) { $('#filtre-user').val(su).trigger('change'); }
        const dmin = localStorage.getItem('cmd_date_min') || '';
        const dmax = localStorage.getItem('cmd_date_max') || '';
        if (dmin) $('#date-min').val(dmin);
        if (dmax) $('#date-max').val(dmax);
        if (dmin || dmax) table.draw();

        // Stats
        function updateStats() {
            const data = table.rows({ search: 'applied' }).data();
            const count = data.length;
            // Regrouper par état
            const byEtat = {};
            for (let i = 0; i < data.length; i++) {
                const etat = $(data[i][4]).text() || data[i][4];
                byEtat[etat] = (byEtat[etat] || 0) + 1;
            }
            const parts = [count + ' commande(s)'];
            Object.keys(byEtat).forEach(k => parts.push(k + ': ' + byEtat[k]));
            $('#stats').text(parts.join(' — '));
        }
        table.on('draw', updateStats);
        updateStats();

        // Lignes cliquables (sauf clic actions)
        $('#liste-commandes tbody').on('click', 'tr', function(e) {
            if ($(e.target).closest('a, button, i').length) return;
            const id = $(this).find('td').eq(0).text().trim();
            if (id) window.location.href = 'updateCommande.php?id=' + id;
        });
    });
</script>
