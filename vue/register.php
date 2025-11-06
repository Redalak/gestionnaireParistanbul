<?php
require_once __DIR__ . '/../src/auth/Auth.php';
require_once __DIR__ . '/../src/repository/UserRepository.php';
use auth\Auth;
use repository\UserRepository;

Auth::startSession();
if (Auth::isLoggedIn()) {
    header('Location: ./index.php');
    exit;
}

$error = '';
$ok = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim((string)($_POST['nom'] ?? ''));
    $prenom = trim((string)($_POST['prenom'] ?? ''));
    $email = trim((string)($_POST['email'] ?? ''));
    $password = (string)($_POST['password'] ?? '');
    $requestedRole = (string)($_POST['role'] ?? 'magasinier');

    if ($nom === '' || $prenom === '' || $email === '' || $password === '') {
        $error = 'Tous les champs obligatoires doivent être renseignés.';
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Email invalide.';
    } else if (!in_array($requestedRole, ['magasinier','gestionnaire'], true)) {
        // Par sécurité, ne pas laisser demander admin
        $requestedRole = 'magasinier';
    } else {
        $repo = new UserRepository();
        $existing = $repo->getUserByEmail($email);
        if ($existing) {
            $error = 'Un compte avec cet email existe déjà.';
        } else {
            // Handler: POST to traitement/register.php
            header('Location: ../src/traitement/register.php?' . http_build_query([
                'nom' => $nom,
                'prenom' => $prenom,
                'email' => $email,
                'password' => $password,
                'requestedRole' => $requestedRole,
            ]));
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Créer un compte • Paristanbul</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="d-flex align-items-center" style="min-height:100vh; background:#f3f4f6;">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-12 col-sm-10 col-md-6 col-lg-5">
        <div class="text-center mb-3">
          <img src="../src/assets/img/logo.png" alt="Paristanbul" style="max-width:180px;"/>
        </div>
        <div class="card shadow-sm">
          <div class="card-body p-4">
            <h1 class="h4 mb-3">Créer un compte</h1>
            <?php if ($error): ?>
              <div class="alert alert-danger" role="alert"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <form method="post">
              <div class="mb-3">
                <label class="form-label">Nom</label>
                <input type="text" class="form-control" name="nom" required />
              </div>
              <div class="mb-3">
                <label class="form-label">Prénom</label>
                <input type="text" class="form-control" name="prenom" required />
              </div>
              <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" name="email" required />
              </div>
              <div class="mb-3">
                <label class="form-label">Mot de passe</label>
                <input type="password" class="form-control" name="password" required />
              </div>
              <div class="mb-3">
                
              </div>
              <div class="mb-3">
                <label class="form-label">Rôle souhaité</label>
                <select class="form-select" name="role">
                  <option value="magasinier">Magasinier</option>
                  <option value="gestionnaire">Gestionnaire</option>
                </select>
              </div>
              <button class="btn btn-primary w-100" type="submit">S'inscrire</button>
              <div class="text-center mt-3">
                <a href="./login.php">Déjà un compte ? Se connecter</a>
              </div>
            </form>
          </div>
        </div>
        <p class="text-center text-muted mt-3 mb-0">© <?= date('Y') ?> Paristanbul</p>
      </div>
    </div>
  </div>
</body>
</html>
