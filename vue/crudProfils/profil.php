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
<main class="main-content">
    <hr>
    <div style="padding:50px">
        <h1> Gestion des utilisateurs</h1>
        <h2 style="margin-top:20px;">Comptes en attente</h2>
        <?php if (!$pending): ?>
            <p>Aucun compte en attente.</p>
        <?php else: ?>
            <table class="table" style="background:white; border-radius:8px;">
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
                                <option value="0">Aucun (0)</option>
                                <?php foreach ($magPairs as $m): ?>
                                    <option value="<?= (int)$m['id_magasin'] ?>">
                                        <?= htmlspecialchars($m['nom'] . (isset($m['ville']) && $m['ville'] ? ' — '.$m['ville'] : '')) ?>
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
    <hr>

    <footer>&copy; <?= date('Y') ?> Paristanbul — Gestionnaire de stock</footer>
</main>
</body>
</html>
<script type="text/javascript" src="../../src/assets/js/index.js"> </script>
<script>
document.addEventListener('click', async (e) => {
  const approveBtn = e.target.closest('.btn-approve');
  const rejectBtn = e.target.closest('.btn-reject');
  if (!approveBtn && !rejectBtn) return;
  const id = parseInt((approveBtn||rejectBtn).dataset.id, 10) || 0;
  if (!id) return;
  const action = approveBtn ? 'approve' : 'reject';
  if (action === 'reject' && !confirm('Refuser et supprimer ce compte ?')) return;
  try {
    let ref_magasin = 0;
    if (action === 'approve') {
      const select = document.querySelector(`.select-magasin[data-id="${id}"]`);
      if (select) ref_magasin = parseInt(select.value, 10) || 0;
    }
    const resp = await fetch('../../src/api/user_approve.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ id_user: id, action, ref_magasin })
    });
    const json = await resp.json();
    if (json && json.ok) {
      location.reload();
    } else {
      alert('Action échouée' + (json && json.error ? (' : ' + json.error) : ''));
    }
  } catch(err) {
    alert('Erreur réseau');
  }
});
</script>