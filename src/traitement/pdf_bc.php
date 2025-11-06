<?php
declare(strict_types=1);

require_once __DIR__ . '/../auth/Auth.php';
\auth\Auth::startSession();
\auth\Auth::requireAnyRole(['admin','gestionnaire']);

require_once __DIR__ . '/../bdd/Bdd.php';
require_once __DIR__ . '/../repository/CommandeRepository.php';

use bdd\Bdd;
use repository\CommandeRepository;

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) { http_response_code(400); echo 'ID commande invalide'; exit; }

$repo = new CommandeRepository();
$pdo = (new Bdd())->getBdd();

// Infos commande + magasin + utilisateur
$st = $pdo->prepare(
    "SELECT c.*, m.nom AS nom_magasin, u.nom AS nom_utilisateur, u.prenom AS prenom_utilisateur
     FROM commande c
     LEFT JOIN magasin m ON m.id_magasin = c.ref_magasin
     LEFT JOIN utilisateur u ON u.id_user = c.ref_utilisateur
     WHERE c.id_commande = :id"
);
$st->execute([':id' => $id]);
$cmd = $st->fetch(PDO::FETCH_ASSOC);
if (!$cmd) { http_response_code(404); echo 'Commande introuvable'; exit; }

// Détails
$dt = $pdo->prepare(
    "SELECT cd.*, p.libelle
     FROM commande_detail cd
     LEFT JOIN produit p ON p.id_produit = cd.ref_produit
     WHERE cd.ref_commande = :id
     ORDER BY cd.id_detail"
);
$dt->execute([':id' => $id]);
$details = $dt->fetchAll(PDO::FETCH_ASSOC);

$tot_articles = 0; $tot_montant = 0.0;
foreach ($details as $d) {
    $q = (int)$d['quantite'];
    $pu = (float)$d['prix_unitaire'];
    $tot_articles += $q;
    $tot_montant += $q * $pu;
}
?><!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8" />
  <title>Bon de commande #<?= htmlspecialchars((string)$id) ?></title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <style>
    body{ padding:24px; }
    .doc{ background:#fff; border:1px solid #e5e7eb; border-radius:10px; padding:24px; }
    .doc h1{ font-size:22px; margin:0 0 6px; }
    .meta{ color:#374151; margin-bottom:16px; }
    .totaux{ margin-top:10px; font-weight:600; }
    @media print{ .noprint{ display:none !important; } }
  </style>
</head>
<body>
<div class="doc">
  <div class="d-flex justify-content-between align-items-center">
    <h1>Bon de commande</h1>
    <button class="btn btn-primary noprint" onclick="window.print()">Imprimer</button>
  </div>
  <div class="meta">
    <div><strong>Commande:</strong> #<?= htmlspecialchars((string)$cmd['id_commande']) ?></div>
    <div><strong>Date:</strong> <?= htmlspecialchars((string)$cmd['date_commande']) ?></div>
    <div><strong>Magasin:</strong> <?= htmlspecialchars((string)$cmd['nom_magasin']) ?></div>
    <div><strong>Utilisateur:</strong> <?= htmlspecialchars((string)$cmd['nom_utilisateur'] . ' ' . ($cmd['prenom_utilisateur']??'')) ?></div>
    <div><strong>Etat:</strong> <?= htmlspecialchars((string)$cmd['etat']) ?></div>
  </div>

  <table class="table table-sm table-bordered align-middle">
    <thead class="table-light">
      <tr>
        <th>#</th>
        <th>Produit</th>
        <th class="text-end">Qté</th>
        <th class="text-end">PU (€)</th>
        <th class="text-end">Total (€)</th>
      </tr>
    </thead>
    <tbody>
    <?php foreach ($details as $i => $d): $line = (int)$d['quantite'] * (float)$d['prix_unitaire']; ?>
      <tr>
        <td><?= $i+1 ?></td>
        <td><?= htmlspecialchars((string)($d['libelle'] ?? ('#'.$d['ref_produit']))) ?></td>
        <td class="text-end"><?= (int)$d['quantite'] ?></td>
        <td class="text-end"><?= number_format((float)$d['prix_unitaire'], 2, ',', ' ') ?></td>
        <td class="text-end"><?= number_format($line, 2, ',', ' ') ?></td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>

  <div class="totaux">
    Articles: <?= (int)$tot_articles ?> — Montant total: <?= number_format($tot_montant, 2, ',', ' ') ?> €
  </div>

  <?php if (!empty($cmd['commentaire'])): ?>
  <div class="mt-2"><strong>Commentaire:</strong> <?= nl2br(htmlspecialchars((string)$cmd['commentaire'])) ?></div>
  <?php endif; ?>
</div>
</body>
</html>
