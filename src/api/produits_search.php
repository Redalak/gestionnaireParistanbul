<?php
declare(strict_types=1);

use repository\ProduitRepository;
use model\Produit;

require_once __DIR__ . '/../repository/ProduitRepository.php';
require_once __DIR__ . '/../model/Produit.php';

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

try {
    $q = isset($_GET['q']) ? trim((string)$_GET['q']) : '';
    $repo = new ProduitRepository();
    // Assuming listeProduits() returns an array of Produit models
    $produits = $repo->listeProduits();

    $out = [];
    foreach ($produits as $p) {
        $lib = method_exists($p, 'getLibelle') ? (string)$p->getLibelle() : '';
        $id = method_exists($p, 'getIdProduit') ? (int)$p->getIdProduit() : 0;
        $prix = method_exists($p, 'getPrixUnitaire') ? (string)$p->getPrixUnitaire() : '0';
        $stock = method_exists($p, 'getQuantiteCentrale') ? (int)$p->getQuantiteCentrale() : 0;
        $cat = method_exists($p, 'getRefCategorie') ? (string)$p->getRefCategorie() : '';
        if ($q !== '') {
            if (stripos($lib, $q) === false) {
                continue;
            }
        }
        $out[] = [
            'id' => $id,
            'libelle' => $lib,
            'prix_unitaire' => (float)str_replace(',', '.', $prix),
            'quantite_centrale' => $stock,
            'ref_categorie' => $cat,
        ];
    }

    echo json_encode(['ok' => true, 'produits' => $out], JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => $e->getMessage()]);
}
