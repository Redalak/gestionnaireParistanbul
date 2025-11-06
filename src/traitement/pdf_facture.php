<?php
declare(strict_types=1);

require_once __DIR__ . '/../auth/Auth.php';
\auth\Auth::startSession();
\auth\Auth::requireAnyRole(['admin','gestionnaire']);

require_once __DIR__ . '/../bdd/Bdd.php';

use bdd\Bdd;

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) { http_response_code(400); echo 'ID commande invalide'; exit; }

$pdo = (new Bdd())->getBdd();

// Facture liée (la plus récente)
$fac = $pdo->prepare(
    "SELECT f.*
     FROM facture f
     WHERE f.ref_commande = :id
     ORDER BY f.date_emission DESC, f.id_facture DESC
     LIMIT 1"
);
$fac->execute([':id' => $id]);
$facture = $fac->fetch(PDO::FETCH_ASSOC);

if (!$facture) { http_response_code(404); echo 'Aucune facture pour cette commande'; exit; }

// Infos commande
$cmdSt = $pdo->prepare("SELECT * FROM commande WHERE id_commande = :id");
$cmdSt->execute([':id' => $id]);
$cmd = $cmdSt->fetch(PDO::FETCH_ASSOC);

?><!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8" />
  <title>Facture #<?= htmlspecialchars((string)$facture['id_facture']) ?></title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <style>
    body{ padding:24px; }
    .doc{ background:#fff; border:1px solid #e5e7eb; border-radius:10px; padding:24px; }
    .doc h1{ font-size:22px; margin:0 0 6px; }
    .meta{ color:#374151; margin-bottom:16px; }
    .paid{ color:#065f46; font-weight:600; }
    @media print{ .noprint{ display:none !important; } }
  </style>
</head>
<body>
<div class="doc">
  <div class="d-flex justify-content-between align-items-center">
    <h1>Facture</h1>
    <button class="btn btn-primary noprint" onclick="window.print()">Imprimer</button>
  </div>
  <div class="meta">
    <div><strong>Facture:</strong> #<?= htmlspecialchars((string)$facture['id_facture']) ?></div>
    <div><strong>Commande:</strong> #<?= htmlspecialchars((string)$id) ?></div>
    <div><strong>Date émission:</strong> <?= htmlspecialchars((string)$facture['date_emission']) ?></div>
    <div><strong>Montant:</strong> <?= number_format((float)$facture['montant'], 2, ',', ' ') ?> €</div>
    <div><strong>Statut:</strong> <span class="<?= ((int)$facture['paye'] ? 'paid' : '') ?>"><?= ( (int)$facture['paye'] ? 'Payé' : 'Non payé') ?></span></div>
  </div>

  <p>Cette facture est générée à partir des données existantes. Pour une version PDF réelle, brancher un moteur (ex: dompdf, TCPDF) ultérieurement.</p>
</div>
</body>
</html>
