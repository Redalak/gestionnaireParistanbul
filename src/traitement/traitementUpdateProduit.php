<?php
// Activer les erreurs pour le débogage
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../../src/repository/ProduitRepository.php';
require_once __DIR__ . '/../../src/model/Produit.php';

use repository\ProduitRepository;
use model\Produit;

// Récupération de l'ID du produit
$id = (int)($_POST['id'] ?? 0);

// URL de retour
$listeUrl = '../../vue/crudProduits/listeProduits.php';

// Vérification de l'ID
if ($id <= 0) {
    echo "<script>alert('Erreur : ID produit invalide'); window.location.href='$listeUrl';</script>";
    exit;
}

// Vérification des champs obligatoires
$requiredFields = ['libelle', 'marque', 'prix_unitaire', 'seuil', 'categorie', 'quantite', 'date_ajout'];
foreach ($requiredFields as $field) {
    if (empty($_POST[$field])) {
        echo "<script>alert('Veuillez remplir tous les champs obligatoires.'); window.history.back();</script>";
        exit;
    }
}

// Récupération et nettoyage des données
$libelle        = trim($_POST['libelle']);
$marque         = trim($_POST['marque']);
$quantite       = (int)$_POST['quantite'];
$prix_unitaire  = (float)$_POST['prix_unitaire'];
$seuil          = (int)$_POST['seuil'];
$ref_categorie  = (int)$_POST['categorie'];
$date_ajout     = $_POST['date_ajout'];

// Création du repository
$repo = new ProduitRepository();


$produit = new Produit([
    'id_produit'        => $id,
    'libelle'           => $libelle,
    'marque'            => $marque,
    'quantite_centrale' => $quantite,
    'prix_unitaire'     => $prix_unitaire,
    'seuil_alerte'      => $seuil,
    'ref_categorie'     => $ref_categorie,
    'date_ajout'        => $date_ajout
]);

// Exécution de la mise à jour
$ok = $repo->updateProduit($produit);

// Résultat de l'opération
if ($ok) {
    echo "<script>alert(' Produit modifié avec succès !'); window.location.href='$listeUrl';</script>";
} else {
    echo "<script>alert(' Erreur : la mise à jour a échoué.'); window.history.back();</script>";
}
exit;
