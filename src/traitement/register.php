<?php
declare(strict_types=1);

require_once __DIR__ . '/../repository/UserRepository.php';
require_once __DIR__ . '/../model/User.php';

use repository\UserRepository;
use model\User;

try {
    // Data comes from redirect via GET (from vue/register.php). In production, prefer POST.
    $nom = trim((string)($_GET['nom'] ?? ''));
    $prenom = trim((string)($_GET['prenom'] ?? ''));
    $email = trim((string)($_GET['email'] ?? ''));
    $password = (string)($_GET['password'] ?? '');
    $genre = trim((string)($_GET['genre'] ?? ''));
    $requestedRole = (string)($_GET['requestedRole'] ?? 'magasinier');

    if ($nom === '' || $prenom === '' || $email === '' || $password === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header('Location: ../../vue/register.php');
        exit;
    }

    if (!in_array($requestedRole, ['magasinier','gestionnaire'], true)) {
        $requestedRole = 'magasinier';
    }

    $repo = new UserRepository();
    $existing = $repo->getUserByEmail($email);
    if ($existing) {
        header('Location: ../../vue/register.php');
        exit;
    }

    $hash = password_hash($password, PASSWORD_DEFAULT);

    $user = new User([
        'nom' => $nom,
        'prenom' => $prenom,
        'email' => $email,
        'mdp' => $hash,
        'role' => 'pending',       // not approved yet
        'genre' => $genre,
        'poste' => $requestedRole, // store requested role here until approval
    ]);

    $ok = $repo->inscription($user);

    // Redirect to login with message
    header('Location: ../../vue/login.php?pending=1');
    exit;
} catch (Throwable $e) {
    header('Location: ../../vue/register.php');
    exit;
}
