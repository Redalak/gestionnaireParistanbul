<?php
require_once __DIR__ . '/../../src/repository/MouvementRepository.php';
require_once __DIR__ . '/../../src/model/Mouvement.php';
$mouvementRepository = new \repository\MouvementRepository();

// Récupération des 5 dernières entrées
$dernieresEntrees = $mouvementRepository->findWithFilters(
    null, // produitId
    null, // magasinId
    'entrée', // type
    null, // dateDebut
    null, // dateFin
    5, // limit
    0  // offset
);

// Récupération des 5 dernières sorties
$dernieresSorties = $mouvementRepository->findWithFilters(
    null, // produitId
    null, // magasinId
    'sortie', // type
    null, // dateDebut
    null, // dateFin
    5, // limit
    0  // offset
);
?>

<!-- Derniers mouvements -->
<div class="dashboard-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin: 20px 40px;">
    <!-- Dernières entrées -->
    <div class="dashboard-section">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
            <h2>Dernières entrées</h2>
            <div class="entree-counter" style="background-color: #e8f5e9; padding: 8px 15px; border-radius: 20px; font-weight: 500; display: flex; align-items: center; gap: 8px; color: #2e7d32;">
                <span class="material-symbols-rounded" style="font-size: 20px;">input</span>
                <?= count($dernieresEntrees) ?> entrée(s)
            </div>
        </div>
        <div class="table-responsive">
            <table class="commandes-table" style="width: 100%;">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Produit</th>
                        <th>Quantité</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($dernieresEntrees)): ?>
                        <tr>
                            <td colspan="3" style="text-align: center; color: #666;">Aucune entrée récente</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($dernieresEntrees as $entree): ?>
                            <tr>
                                <td><?= (new DateTime($entree->getDateMouvement()))->format('d/m/Y H:i') ?></td>
                                <td><?= htmlspecialchars($entree->getData('produit_libelle') ?? 'Inconnu') ?></td>
                                <td style="color: #2e7d32; font-weight: bold;">+<?= $entree->getQuantite() ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div style="text-align: right; margin-top: 10px;">
            <a href="mouvements.php?type=entrée" class="btn" style="background-color: #2e7d32; color: white; padding: 8px 16px; border-radius: 4px; text-decoration: none; font-size: 0.9em;">
                Voir toutes les entrées
            </a>
        </div>
    </div>

    <!-- Dernières sorties -->
    <div class="dashboard-section">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
            <h2>Dernières sorties</h2>
            <div class="sortie-counter" style="background-color: #ffebee; padding: 8px 15px; border-radius: 20px; font-weight: 500; display: flex; align-items: center; gap: 8px; color: #c62828;">
                <span class="material-symbols-rounded" style="font-size: 20px;">output</span>
                <?= count($dernieresSorties) ?> sortie(s)
            </div>
        </div>
        <div class="table-responsive">
            <table class="commandes-table" style="width: 100%;">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Produit</th>
                        <th>Quantité</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($dernieresSorties)): ?>
                        <tr>
                            <td colspan="3" style="text-align: center; color: #666;">Aucune sortie récente</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($dernieresSorties as $sortie): ?>
                            <tr>
                                <td><?= (new DateTime($sortie->getDateMouvement()))->format('d/m/Y H:i') ?></td>
                                <td><?= htmlspecialchars($sortie->getData('produit_libelle') ?? 'Inconnu') ?></td>
                                <td style="color: #c62828; font-weight: bold;">-<?= $sortie->getQuantite() ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div style="text-align: right; margin-top: 10px;">
            <a href="mouvements.php?type=sortie" class="btn" style="background-color: #c62828; color: white; padding: 8px 16px; border-radius: 4px; text-decoration: none; font-size: 0.9em;">
                Voir toutes les sorties
            </a>
        </div>
    </div>
</div>
