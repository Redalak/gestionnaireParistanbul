<?php
// Activer les erreurs pour le débogage
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../../src/repository/ProduitRepository.php';
require_once __DIR__ . '/../../src/model/Produit.php';
require_once __DIR__ . '/../../src/bdd/Bdd.php';

use repository\ProduitRepository;
use model\Produit;
use bdd\Bdd;

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

// Validation longueur colonne 'marque' selon le schéma SQL
try {
    $pdo = (new Bdd())->getBdd();
    // Vérifier d'abord l'existence de la catégorie pour éviter l'erreur de contrainte
    if ($ref_categorie > 0) {
        $chk = $pdo->prepare("SELECT 1 FROM categorie WHERE id_categorie = :id");
        $chk->execute([':id' => $ref_categorie]);
        if (!$chk->fetchColumn()) {
            echo "<script>alert('La catégorie sélectionnée (ID: " . addslashes((string)$ref_categorie) . ") n\'existe plus. Veuillez en choisir une autre.'); window.history.back();</script>";
            exit;
        }
    } else {
        echo "<script>alert('Catégorie invalide (valeur reçue: " . addslashes((string)$ref_categorie) . ").'); window.history.back();</script>";
        exit;
    }
    $stmt = $pdo->prepare(
        "SELECT CHARACTER_MAXIMUM_LENGTH
         FROM information_schema.COLUMNS
         WHERE TABLE_SCHEMA = DATABASE()
           AND TABLE_NAME = 'produit'
           AND COLUMN_NAME = 'marque'"
    );
    $stmt->execute();
    $maxLen = $stmt->fetchColumn();
    if ($maxLen && mb_strlen($marque) > (int)$maxLen) {
        $msg = "La valeur de 'marque' (".mb_strlen($marque)." chars) dépasse la limite autorisée (".(int)$maxLen."). Raccourcissez la marque ou augmentez la taille de la colonne en base.";
        echo "<script>alert('" . addslashes($msg) . "'); window.history.back();</script>";
        exit;
    }
} catch (\Throwable $e) {
    // Si la lecture du schéma échoue, on continue sans bloquer (le SGBD lèvera l'erreur si trop long)
}

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
