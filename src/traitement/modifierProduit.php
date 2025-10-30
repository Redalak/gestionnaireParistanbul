<?php
declare(strict_types=1);

require_once __DIR__ . '/../repository/ProduitRepository.php';

use repository\ProduitRepository;

$id           = (int)($_POST['id'] ?? 0);
$nom          = trim((string)($_POST['nom'] ?? ''));
$refCategorie = (int)($_POST['categorie'] ?? 0); // ID de categorie
$nbUnites     = (int)($_POST['quantite'] ?? 0);  // nb_unite_pack
$prix         = (float)($_POST['prix_unitaire'] ?? 0);

// Redirection (depuis /src/traitement/ vers /vue/ )
$modifierBase = '../../vue/modifier.php';

if ($id > 0 && $nom !== '' && $refCategorie > 0 && $nbUnites >= 0 && $prix >= 0) {
    $repo = new ProduitRepository();
    $ok   = $repo->update($id, $nom, $refCategorie, $nbUnites, $prix);

    header('Location: ' . $modifierBase . '?id=' . $id . '&success=' . ($ok ? '1' : '0'));
    exit;
}

header('Location: ' . $modifierBase . '?id=' . $id . '&error=1');
exit;