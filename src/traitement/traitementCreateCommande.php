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
        var_dump($_POST['ref_magasin'], $_POST['ref_utilisateur']);

        $commande = new Commande([
            'ref_magasin' => (int) $_POST['ref_magasin'],
            'ref_utilisateur' => (int) $_POST['ref_utilisateur'],
            'date_commande' => $_POST['date_commande'],
            'etat' => $_POST['etat'],
            'commentaire' => $_POST['commentaire'] ?? '',
        ]);


        // Ajout de la commande via le repository
        if ($repoCommande->ajoutCommande($commande)) {
            $message = '<p style="color:green;">Commande ajoutée avec succès !</p>';
        } else {
            $message = '<p style="color:red;">Erreur lors de l’ajout de la commande.</p>';
        }

    } else {
        $message = '<p style="color:red;">Veuillez remplir tous les champs obligatoires.</p>';
    }
}

// Récupération des listes pour le formulaire
$magasins = $repoMagasin->getAllMagasins();
$utilisateurs = $repoUser->getAllUsers();


?>