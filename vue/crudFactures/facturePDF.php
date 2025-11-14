<?php
require_once __DIR__ . '/../../src/lib/fpdf186/fpdf.php';
require_once __DIR__ . '/../../src/repository/FactureRepository.php';
require_once __DIR__ . '/../../src/repository/DetailCommandeRepository.php';

use repository\FactureRepository;
use repository\DetailCommandeRepository;

// Vérification ID
$id_facture = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id_facture <= 0) {
    die("Facture introuvable.");
}

$factureRepo = new FactureRepository();
$detailCommandeRepo = new DetailCommandeRepository();

$facture = $factureRepo->getDetailFacture($id_facture);
if (!$facture) {
    die("Facture introuvable.");
}

$details = $detailCommandeRepo->getDetailCommande($facture['ref_commande']);

// Création du PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);

// Titre
$pdf->Cell(0, 10, 'Facture #' . $facture['id_facture'], 0, 1, 'C');
$pdf->Ln(5);

// Infos facture
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(50, 8, 'Commande:', 0, 0);
$pdf->Cell(0, 8, '#' . $facture['ref_commande'], 0, 1);

$pdf->Cell(50, 8, 'Magasin:', 0, 0);
$pdf->Cell(0, 8, $facture['magasin_nom'], 0, 1);

$pdf->Cell(50, 8, 'Ville:', 0, 0);
$pdf->Cell(0, 8, $facture['ville'], 0, 1);

$pdf->Cell(50, 8, 'Date emission:', 0, 0);
$pdf->Cell(0, 8, date('d/m/Y', strtotime($facture['date_emission'])), 0, 1);

$pdf->Cell(50, 8, 'Etat paiement:', 0, 0);
$pdf->Cell(0, 8, $facture['paye'] ? 'Payée' : 'En attente', 0, 1);

$pdf->Ln(5);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(60, 8, 'Produit', 1);
$pdf->Cell(40, 8, 'Marque', 1);
$pdf->Cell(20, 8, 'Quantite', 1, 0, 'C');
$pdf->Cell(30, 8, 'Prix unitaire', 1, 0, 'R');
$pdf->Cell(40, 8, 'Total ligne', 1, 1, 'R');

$pdf->SetFont('Arial', '', 12);
$total = 0;
foreach ($details as $d) {
    $pdf->Cell(60, 8, $d['libelle'], 1);
    $pdf->Cell(40, 8, $d['marque'], 1);
    $pdf->Cell(20, 8, $d['quantite'], 1, 0, 'C');
    $pdf->Cell(30, 8, number_format($d['prix_unitaire'], 2, ',', ' '), 1, 0, 'R');
    $pdf->Cell(40, 8, number_format($d['total_ligne'], 2, ',', ' '), 1, 1, 'R');
    $total += $d['total_ligne'];
}

// Total général
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(150, 8, 'Total', 1, 0, 'R');
$pdf->Cell(40, 8, number_format($total, 2, ',', ' '), 1, 1, 'R');

// Génération PDF
$pdf->Output('D', 'Facture_' . $facture['id_facture'] . '.pdf');
