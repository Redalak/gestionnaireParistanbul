<?php
require_once __DIR__ . '/../../src/repository/CommandeRepository.php';
require_once __DIR__ . '/../../src/repository/MagasinsRepository.php';
require_once __DIR__ . '/../../src/repository/UserRepository.php';
require_once __DIR__ . '/../../src/model/Commande.php';

use repository\CommandeRepository;
use repository\MagasinsRepository;
use repository\UserRepository;
use model\Commande;

// Initialisation des repositories
$repoCommande = new CommandeRepository();
$repoMagasin = new MagasinsRepository();
$repoUser = new UserRepository();

$message = '';

// Vérification que le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Validation basique des champs
    if (!empty($_POST['ref_magasin']) && !empty($_POST['ref_utilisateur']) && !empty($_POST['date_commande']) && !empty($_POST['etat'])) {
        // Lire les lignes du panier envoyées en JSON
        $lignesJson = $_POST['lignes_json'] ?? '[]';
        $lignes = json_decode($lignesJson, true);
        if (!is_array($lignes)) { $lignes = []; }

        // Validation simple et calcul des totaux
        $nbLignes = 0; $sousTotal = 0.0;
        foreach ($lignes as $ligne) {
            $idProd = (int)($ligne['id_produit'] ?? 0);
            $lib    = (string)($ligne['libelle'] ?? '');
            $prix   = (float)($ligne['prix_unitaire'] ?? 0);
            $qte    = (int)($ligne['quantite'] ?? 0);
            $remise = (float)($ligne['remise'] ?? 0);
            if ($idProd <= 0 || $qte <= 0 || $prix < 0 || $remise < 0 || $remise > 100) {
                continue; // ignorer lignes invalides
            }
            $nbLignes++;
            $sousTotal += ($prix * $qte) * (1 - $remise/100);
        }

        // Construire un résumé à stocker dans le commentaire (temporaire tant que les lignes ne sont pas persistées)
        $commentaireUser = $_POST['commentaire'] ?? '';
        $resume = '';
        if ($nbLignes > 0) {
            $resume = "\n---\nLignes (JSON): " . substr($lignesJson, 0, 5000) . "\nSous-total: " . number_format($sousTotal, 2, '.', ' ') . " €\n";
        }

        $commande = new Commande([
            'ref_magasin' => (int) $_POST['ref_magasin'],
            'ref_utilisateur' => (int) $_POST['ref_utilisateur'],
            'date_commande' => $_POST['date_commande'],
            'etat' => $_POST['etat'],
            'commentaire' => $commentaireUser . $resume,
        ]);


        // Ajout de la commande via le repository
        if ($repoCommande->ajoutCommande($commande)) {
            header('Location: ../../vue/crudCommandes/listeCommandes.php?success=1');
            exit;
        } else {
            header('Location: ../../vue/crudCommandes/createCommande.php?error=1');
            exit;
        }

    } else {
        header('Location: ../../vue/crudCommandes/createCommande.php?error=missing');
        exit;
    }
}

// Récupération des listes pour le formulaire
$magasins = $repoMagasin->getAllMagasins();
$utilisateurs = $repoUser->getAllUsers();


?>