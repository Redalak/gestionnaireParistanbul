<?php
require_once __DIR__ . '/../../src/repository/CommandeRepository.php';
require_once __DIR__ . '/../../src/repository/MagasinsRepository.php';
require_once __DIR__ . '/../../src/repository/UserRepository.php';
require_once __DIR__ . '/../../src/model/Commande.php';

use repository\CommandeRepository;
use repository\MagasinsRepository;
use repository\UserRepository;
use model\Commande;

// Instanciation des repositories
$repoCommande = new CommandeRepository();
$repoMagasin = new MagasinsRepository();
$repoUser = new UserRepository();

// Vérification que le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id_commande = $_POST['id_commande'] ?? null;
    $ref_magasin = $_POST['ref_magasin'] ?? null;
    $ref_utilisateur = $_POST['ref_utilisateur'] ?? null;
    $date_commande = $_POST['date_commande'] ?? null;
    $etat = $_POST['etat'] ?? 'en attente';
    $commentaire = $_POST['commentaire'] ?? null;

    // Validation des IDs
    if (!$id_commande) {
        die("ID de la commande manquant.");
    }

    // Vérifier que le magasin existe
    if (!$repoMagasin->getMagasinById((int)$ref_magasin)) {
        die("Le magasin sélectionné n'existe pas.");
    }

    // Vérifier que l'utilisateur existe
    if (!$repoUser->getUserById((int)$ref_utilisateur)) {
        die("L'utilisateur sélectionné n'existe pas.");
    }

    // Créer un objet Commande avec les nouvelles données
    $commande = $repoCommande->getCommandeById((int)$id_commande);
    if (!$commande) {
        die("Commande introuvable.");
    }

    $commande->setRefMagasin((int)$ref_magasin);
    $commande->setRefUtilisateur((int)$ref_utilisateur);
    $commande->setDateCommande($date_commande);
    $commande->setEtat($etat);
    $commande->setCommentaire($commentaire);

    // Mettre à jour la commande dans la base
    if ($repoCommande->updateCommande($commande)) {
        header("Location: ../vue/crudCommandes/listeCommandes.php?success=1");
        exit;
    } else {
        die("Erreur lors de la mise à jour de la commande.");
    }
}
