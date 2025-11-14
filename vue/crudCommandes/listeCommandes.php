<?php
/* listeCommandes.php */
require_once __DIR__ . '/../../src/auth/Auth.php';
\auth\Auth::startSession();
\auth\Auth::requireAnyRole(['admin','gestionnaire']);
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Paristanbul • Tableau de bord</title>

    <link rel="stylesheet" href="../../src/assets/css/index.css" />
    <link rel="stylesheet" href="../../src/assets/css/listeCommande.css" />

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
            <!-- Logistique -->
            <li class="nav-item">
                <a href="../agenda.php" class="nav-link">
                    <span class="material-symbols-rounded">query_stats</span>
                    <span class="nav-label">Agenda</span>
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
        <div>
        <a href="createCommande.php" class="btn-primary">+ Nouvelle commande</a>
        </div>
        <div>
        <a href="logistique_commandes.php" class="btn btn-secondary">Gestion des commandes</a>
        </div>
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
    </div> <!-- fin .toolbar -->

    <div id="chips" class="chips">
        <span class="chip" data-etat="">Tous <span class="count"></span></span>
        <span class="chip" data-etat="en attente">En attente <span class="count"></span></span>
        <span class="chip" data-etat="préparée">Préparée <span class="count"></span></span>
        <span class="chip" data-etat="expédiée">Expédiée <span class="count"></span></span>
        <span class="chip" data-etat="livrée">Livrée <span class="count"></span></span>
        <span class="chip" data-etat="annulée">Annulée <span class="count"></span></span>
    </div>

    
    <div class="table-responsive">
        <table id="liste-commandes">
            <thead>
            <tr>
                <th></th> <!-- + détails -->
                <th>ID</th>
                <th>Magasin</th>
                <th>Utilisateur</th>
                <th>Date_commande</th>
                <th>Etat</th>
                <th>Commentaire</th>
                <th>Montant total (€)</th>
                <th>Articles</th>
                <th>Lignes</th>
                <th>Facture</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($commandes as $commande): ?>
                <tr>
                    <td class="details-control"></td>
                    <td><?= $commande['id_commande']?></td>
                    <td><?= $commande['nom_magasin']?></td>
                    <td><?= $commande['nom_utilisateur']?></td>
                    <td><?= $commande['date_commande']?></td>
                    <td>
    <span class="badge etat-<?= htmlspecialchars(str_replace(' ', '_', strtolower($commande['etat']))) ?> js-etat-badge">
      <?= htmlspecialchars($commande['etat']) ?>
    </span>
    <br/>
    <select class="etat-select" data-id="<?= (int)$commande['id_commande']?>">
        <?php foreach (['en attente','préparée','expédiée','livrée','annulée'] as $opt): ?>
            <option value="<?= $opt ?>" <?= ($commande['etat'] === $opt ? 'selected' : '') ?>><?= $opt ?></option>
        <?php endforeach; ?>
    </select>
                        <!-- progression visuelle -->
                        <span class="status-progress" data-etat="<?= htmlspecialchars(strtolower($commande['etat'])) ?>">
      <span class="dot"></span><span class="dot"></span><span class="dot"></span><span class="dot"></span>
    </span>
                    </td>
                    <td><?= $commande['commentaire'] ?></td>
                    <td><?= number_format((float)($commande['montant_total'] ?? 0), 2, ',', ' ') ?></td>
                    <td><?= (int)($commande['nb_articles'] ?? 0) ?></td>
                    <td><?= (int)($commande['nb_lignes'] ?? 0) ?></td>
                    <td>
                        <?php if (!empty($commande['id_facture'])): ?>
                            <a href="../../vue/crudFactures/factures.php?ref_commande=<?= (int)$commande['id_commande']?>" title="Voir facture #<?= (int)$commande['id_facture'] ?>">
                                <i class="bi bi-receipt"></i> #<?= (int)$commande['id_facture'] ?>
                            </a>
                        <?php else: ?>
                            —
                        <?php endif; ?>
                    </td>
                    <td class="actions">
                        <a href="../../src/traitement/pdf_bc.php?id=<?= (int)$commande['id_commande']?>" class="btn btn-sm btn-ghost" title="Bon de commande (PDF)">
                            <i class="bi bi-file-earmark-text"></i>
                        </a>
                        <a href="../../src/traitement/pdf_bl.php?id=<?= (int)$commande['id_commande']?>" class="btn btn-sm btn-ghost" title="Bon de livraison (PDF)">
                            <i class="bi bi-truck"></i>
                        </a>
                        <a href="../../src/traitement/pdf_facture.php?id=<?= (int)$commande['id_commande']?>" class="btn btn-sm btn-ghost" title="Facture (PDF)">
                            <i class="bi bi-receipt"></i>
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
        /* ----- Filtre date personnalisé (colonne 4 devient index 4 → 0:+,1:ID,2:Magasin,3:User,4:Date) ----- */
        $.fn.dataTable.ext.search.push(function(settings, data) {
            const min = $('#date-min').val(), max = $('#date-max').val();
            const rowDateStr = (data[4] || '').toString().substring(0,10);
            if (!min && !max) return true;
            if (min && rowDateStr < min) return false;
            if (max && rowDateStr > max) return false;
            return true;
        });

        const table = $('#liste-commandes').DataTable({
            language: { url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json" },
            pageLength: 10, ordering: true, searching: true, responsive: true,
            dom: '<"top"fB>rt<"bottom"lip><"clear">',
            buttons: [
                { extend: 'copyHtml5', text: 'Copier' },
                { extend: 'csvHtml5', text: 'CSV', title: 'commandes' },
                { extend: 'excelHtml5', text: 'Excel', title: 'commandes' },
                { extend: 'pdfHtml5', text: 'PDF', title: 'commandes', orientation: 'landscape', pageSize: 'A4' },
                { extend: 'print', text: 'Imprimer', title: 'Liste des commandes' },
                { extend: 'colvis', text: 'Colonnes' }
            ],
            columnDefs: [
                { targets: 0, className: 'details-control', orderable: false },
            ]
        });

        /* ----- Filtre par utilisateur (col 3) ----- */
        $('#filtre-user').on('change', function() {
            const val = $(this).val();
            table.column(3).search(val ? '^' + $.fn.dataTable.util.escapeRegex(val) + '$' : '', true, false).draw();
            localStorage.setItem('cmd_user', val || '');
        });

        /* ----- Filtre par état (col 5) ----- */
        function setEtatFilter(val){
            table.column(5).search(val ? '^' + $.fn.dataTable.util.escapeRegex(val) + '$' : '', true, false).draw();
            localStorage.setItem('cmd_etat', val || '');
            // chips UI
            $('#chips .chip').removeClass('active')
                .filter('[data-etat="'+(val||'')+'"]').addClass('active');
        }
        $('#filtre-etat').on('change', function(){ setEtatFilter($(this).val()); });

        /* ----- Chips de statut ----- */
        $('#chips').on('click', '.chip', function(){
            const v = $(this).data('etat') || '';
            $('#filtre-etat').val(v);
            setEtatFilter(v);
        });

        /* ----- Filtre date ----- */
        $('#date-min, #date-max').on('change', function() {
            table.draw();
            localStorage.setItem('cmd_date_min', $('#date-min').val());
            localStorage.setItem('cmd_date_max', $('#date-max').val());
        });
        $('#date-reset').on('click', function() {
            $('#date-min').val(''); $('#date-max').val(''); table.draw();
            localStorage.removeItem('cmd_date_min'); localStorage.removeItem('cmd_date_max');
        });

        /* ----- Restaurer filtres ----- */
        const se = localStorage.getItem('cmd_etat') || '';
        if (se) { $('#filtre-etat').val(se); setEtatFilter(se); }
        const su = localStorage.getItem('cmd_user') || '';
        if (su) { $('#filtre-user').val(su).trigger('change'); }
        const dmin = localStorage.getItem('cmd_date_min') || '';
        const dmax = localStorage.getItem('cmd_date_max') || '';
        if (dmin) $('#date-min').val(dmin);
        if (dmax) $('#date-max').val(dmax);
        if (dmin || dmax) table.draw();

        /* ----- Chips counts + progression visuelle ----- */
        function updateUI() {
            const data = table.rows({ search: 'applied' }).data();
            const byEtat = {}, stepsMap = { 'en attente':1, 'préparée':2, 'expédiée':3, 'livrée':4, 'annulée':0 };
            for (let i=0; i<data.length; i++){
                // col 5 contient le badge avec le texte
                const etat = $('<div>').html(data[i][5]).text().trim().toLowerCase();
                byEtat[etat] = (byEtat[etat] || 0) + 1;
            }
            // MAJ des chips (compteurs)
            const count = data.length;
            $('#chips .chip').each(function(){
                const key = (($(this).data('etat')||'')+'').toLowerCase();
                const n = key ? (byEtat[key] || 0) : count;
                $(this).find('.count').text(n ? '('+n+')' : '');
            });

            // Progression visuelle dots (par badge)
            $('#liste-commandes tbody tr').each(function(){
                const $row = $(this);
                const etat = $row.find('td').eq(5).find('.badge').text().trim().toLowerCase();
                const step = stepsMap[etat] ?? 0;
                const $dots = $row.find('.status-progress .dot');
                $dots.removeClass('on').slice(0, step).addClass('on');
            });
        }
        table.on('draw', updateUI); updateUI();

        /* ----- Détails extensibles (+) ----- */
        function childTemplate($tr){
            const t = $tr.find('td');
            const id = t.eq(1).text().trim();
            const magasin = t.eq(2).text().trim();
            const user = t.eq(3).text().trim();
            const date = t.eq(4).text().trim();
            const etatHtml = t.eq(5).html();
            const commentaire = t.eq(6).text().trim() || '—';
            // récupère les liens d’action existants
            const $actionsCell = t.eq(11); // actions column index after adding new columns
            const editHref = $actionsCell.find('a.btn-primary').attr('href') || '#';
            const delHref  = $actionsCell.find('a.btn-danger').attr('href') || '#';

            return `
      <div class="cmd-child">
        <div class="grid">
          <div><strong>ID :</strong> ${id}</div>
          <div><strong>Date :</strong> ${date}</div>
          <div><strong>Magasin :</strong> ${magasin}</div>
          <div><strong>Utilisateur :</strong> ${user}</div>
          <div><strong>Etat :</strong> ${etatHtml}</div>
          <div><strong>Commentaire :</strong> ${$('<div>').text(commentaire).html()}</div>
        </div>
        <div class="actions">
          <a class="btn-ghost" href="${editHref}"><i class="bi bi-pencil"></i> Modifier</a>
          <a class="btn-ghost" href="${delHref}" onclick="return confirm('Supprimer cette commande ?')">
            <i class="bi bi-trash"></i> Supprimer
          </a>
        </div>
      </div>`;
        }

        $('#liste-commandes tbody').on('click','td.details-control', function(e){
            e.stopPropagation();
            const tr = $(this).closest('tr');
            const row = table.row(tr);
            if(row.child.isShown()){ row.child.hide(); tr.removeClass('shown'); }
            else { row.child(childTemplate(tr)).show(); tr.addClass('shown'); }
        });

        /* ----- Lignes cliquables (redirige vers édition) — ID en col 1 désormais ----- */
        $('#liste-commandes tbody').on('click', 'tr', function(e) {
            if ($(e.target).closest('a, button, i, td.details-control, select.etat-select').length) return;
            const id = $(this).find('td').eq(1).text().trim();
            if (id) window.location.href = 'updateCommande.php?id=' + id;
        });

        /* ----- Inline change statut (AJAX) ----- */
        $(document).on('change', 'select.etat-select', function(){
            const $sel = $(this);
            const id = parseInt($sel.data('id'), 10) || 0;
            const etat = $sel.val();
            if (!id || !etat) return;
            $.ajax({
                url: '../../src/api/commande_update_statut.php',
                method: 'POST',
                data: { id_commande: id, etat },
                dataType: 'json'
            }).done(function(resp){
                if (resp && resp.ok) {
                    // maj badge + progression
                    const $row = $sel.closest('tr');
                    const css = ('etat-' + String(etat).toLowerCase().replace(/\s+/g,'_'));
                    const $badge = $row.find('.js-etat-badge');
                    $badge.text(etat)
                          .attr('class', 'badge js-etat-badge ' + css);
                    // progression dots
                    const stepsMap = { 'en attente':1, 'préparée':2, 'expédiée':3, 'livrée':4, 'annulée':0 };
                    const step = stepsMap[String(etat).toLowerCase()] ?? 0;
                    const $dots = $row.find('.status-progress .dot');
                    $dots.removeClass('on').slice(0, step).addClass('on');
                } else {
                    alert('Mise à jour du statut échouée');
                }
            }).fail(function(){
                alert('Erreur réseau / serveur lors de la mise à jour du statut');
            });
        });
    });
</script>