<?php
require_once __DIR__ . '/../../src/auth/Auth.php';
\auth\Auth::startSession();
\auth\Auth::requireAnyRole(['admin','gestionnaire']);

require_once __DIR__ . '/../../src/bdd/Bdd.php';
use bdd\Bdd ;
require_once __DIR__ . '/../../src/repository/FactureRepository.php';
use repository\FactureRepository;
require_once __DIR__ . '/../../src/repository/DetailCommandeRepository.php';
use repository\DetailCommandeRepository;

$factureRepository = new \repository\FactureRepository();
$detailCommandeRepository = new \repository\DetailCommandeRepository();
$id_facture = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id_facture <= 0) {
    die("Facture introuvable.");
}

// Récupération de la facture + commande + magasin
$facture = $factureRepository ->getDetailFacture($id_facture);
if (!$facture) {
    die("Facture introuvable.");
}

// Détails de la commande associée
$detailCommande = $detailCommandeRepository->getDetailCommande($facture['ref_commande']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détail facture #<?= htmlspecialchars($facture['id_facture']) ?> • Paristanbul</title>
    <link rel="stylesheet" href="../../src/assets/css/index.css">
    <link rel="stylesheet" href="../../src/assets/css/detailFacture.css">
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />

</head>
<body>

<button class="sidebar-menu-button">
    <span class="material-symbols-rounded">menu</span>
</button>

<main class="main-content">
    <div class="facture-container">
        <div class="facture-header">
            <h1>Facture n°<?= htmlspecialchars($facture['id_facture']) ?></h1>
            <span class="badge <?= $facture['paye'] ? 'badge-success' : 'badge-warning' ?>">
                <?= $facture['paye'] ? 'Payée' : 'En attente' ?>
            </span>
        </div>

        <div class="facture-info">
            <div><strong>Commande liée :</strong> #<?= $facture['ref_commande'] ?></div>
            <div><strong>Date émission :</strong> <?= date('d/m/Y', strtotime($facture['date_emission'])) ?></div>
            <div><strong>Magasin :</strong> <?= htmlspecialchars($facture['magasin_nom']) ?></div>
            <div><strong>Ville :</strong> <?= htmlspecialchars($facture['ville']) ?></div>
            <div><strong>État commande :</strong> <?= htmlspecialchars($facture['etat_commande']) ?></div>
        </div>

        <h2>Détails de la commande</h2>
        <table>
            <thead>
            <tr>
                <th>Produit</th>
                <th>Marque</th>
                <th>Quantité</th>
                <th>Prix unitaire (€)</th>
                <th>Total ligne (€)</th>
            </tr>
            </thead>
            <tbody>
            <?php $total = 0; foreach ($detailCommande as $d):
                $total += (float) $d['total_ligne'];
                ?>
                <tr>
                    <td><?= htmlspecialchars($d['libelle']) ?></td>
                    <td><?= htmlspecialchars($d['marque']) ?></td>
                    <td><?= $d['quantite'] ?></td>
                    <td><?= number_format($d['prix_unitaire'], 2, ',', ' ') ?></td>
                    <td><?= number_format($d['total_ligne'], 2, ',', ' ') ?></td>
                </tr>
            <?php endforeach; ?>

            </tbody>
            <tfoot>
            <tr>
                <td colspan="4" style="text-align:right;">Total calculé :</td>
                <td><?= number_format($total, 2, ',', ' ') ?> €</td>
            </tr>
            </tfoot>
        </table>

        <div class="facture-actions">
            <a href="factures.php" class="btn btn-secondary">← Retour</a>
            <div>
                <button class="btn btn-success" id="btnPayer">
                    <?= $facture['paye'] ? 'Marquer comme non payée' : 'Marquer comme payée' ?>
                </button>
                <button class="btn btn-primary" id="btnPDF">Télécharger PDF</button>
            </div>
        </div>
    </div>

    <footer style="text-align:center;margin-top:40px;">
        &copy; <?= date('Y') ?> Paristanbul — Gestionnaire de stock
    </footer>
</main>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    $('#btnPayer').click(function() {
        $.post('update_paiement.php', { id_facture: <?= $id_facture ?> }, function(r) {
            if(r.success) location.reload();
        }, 'json');
    });
    $('#btnPDF').click(function(){
        window.location.href = "facturePDF.php?id=<?= $id_facture ?>";
    });
</script>

</body>
</html>
