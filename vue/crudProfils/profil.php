<?php
/* profil.php */
require_once __DIR__ . '/../../src/auth/Auth.php';
require_once __DIR__ . '/../../src/repository/UserRepository.php';
require_once __DIR__ . '/../../src/repository/MagasinsRepository.php';

\auth\Auth::startSession();
\auth\Auth::requireAnyRole(['admin']);

use repository\UserRepository;
use repository\MagasinsRepository;

$repoUser = new UserRepository();
$pending = $repoUser->getPendingUsers();

$magRepo = new MagasinsRepository();
$magPairs = $magRepo->pairs();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Paristanbul • Utilisateurs</title>

    <link rel="stylesheet" href="../../src/assets/css/index.css" />
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />

    <style>
        .page-title {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 15px;
        }

        .subtitle {
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 20px;
            color: #444;
        }

        .card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0px 4px 12px rgba(0,0,0,0.06);
            margin-bottom: 35px;
            border: 1px solid #eee;
        }

        table.table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
        }

        table.table th {
            background: #f8f9fc;
            padding: 12px;
            font-size: 14px;
            text-align: left;
            border-bottom: 2px solid #eee;
        }

        table.table td {
            padding: 12px;
            border-bottom: 1px solid #f0f0f0;
            font-size: 14px;
        }

        .btn-approve {
            background: #16a34a;
            color: white;
            padding: 8px 14px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            font-weight: 600;
        }

        .btn-reject {
            background: #dc2626;
            color: white;
            padding: 8px 14px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            font-weight: 600;
            margin-left: 6px;
        }

        .btn-approve:hover { background: #15803d; }
        .btn-reject:hover { background: #b91c1c; }

        .select-magasin {
            padding: 8px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }

        footer {
            margin-top: 40px;
            text-align:center;
            color:#777;
            font-size: 14px;
        }
    </style>
</head>

<body>

<!-- BOUTON MENU MOBILE -->
<button class="sidebar-menu-button">
    <span class="material-symbols-rounded">menu</span>
</button>

<!-- SIDEBAR -->

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
                <!-- Logistique -->
                <li class="nav-item">
                    <a href="../agenda.php" class="nav-link">
                        <span class="material-symbols-rounded">query_stats</span>
                        <span class="nav-label">Agenda</span>
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

    <div style="padding:40px;">

        <h1 class="page-title">Gestion des utilisateurs</h1>

        <div class="card">
            <h2 class="subtitle">Comptes en attente de validation</h2>

            <?php if (!$pending): ?>
                <p style="color:#555;">Aucun compte n’est actuellement en attente.</p>

            <?php else: ?>
                <table class="table">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Rôle</th>
                        <th>Affecter magasin</th>
                        <th>Actions</th>
                    </tr>
                    </thead>

                    <tbody>
                    <?php foreach ($pending as $u): ?>
                        <tr>
                            <td><?= (int)$u['id_user'] ?></td>
                            <td><?= htmlspecialchars(($u['prenom'] ?? '').' '.($u['nom'] ?? '')) ?></td>
                            <td><?= htmlspecialchars($u['email'] ?? '') ?></td>
                            <td><?= htmlspecialchars($u['role'] ?? 'magasinier') ?></td>

                            <td>
                                <select class="select-magasin" data-id="<?= (int)$u['id_user'] ?>">
                                    <option value="0">Aucun</option>
                                    <?php foreach ($magPairs as $m): ?>
                                        <option value="<?= (int)$m['id_magasin'] ?>">
                                            <?= htmlspecialchars($m['nom'] . ($m['ville'] ? ' — '.$m['ville'] : '')) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </td>

                            <td>
                                <button class="btn-approve" data-id="<?= (int)$u['id_user'] ?>">Approuver</button>
                                <button class="btn-reject" data-id="<?= (int)$u['id_user'] ?>">Refuser</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>

        <footer>&copy; <?= date('Y') ?> Paristanbul — Gestionnaire de stock</footer>
    </div>
</main>

</body>
</html>


<script src="../../src/assets/js/index.js"></script>

<script>
    document.addEventListener('click', async (e) => {
        const approve = e.target.closest('.btn-approve');
        const reject = e.target.closest('.btn-reject');

        if (!approve && !reject) return;

        const id = (approve || reject).dataset.id;
        const action = approve ? "approve" : "reject";

        if (action === "reject" && !confirm("Refuser et supprimer ce compte ?")) return;

        try {
            let ref_magasin = 0;

            if (action === "approve") {
                const select = document.querySelector(`.select-magasin[data-id="${id}"]`);
                ref_magasin = select ? parseInt(select.value) : 0;
            }

            const res = await fetch("../../src/api/user_approve.php", {
                method: "POST",
                headers: {"Content-Type": "application/json"},
                body: JSON.stringify({ id_user: id, action, ref_magasin })
            });

            const json = await res.json();

            if (json.ok) location.reload();
            else alert("Erreur : " + (json.error || "Action échouée"));

        } catch (err) {
            alert("Erreur réseau");
        }
    });
</script>
