<?php
require_once __DIR__ . '/../src/auth/Auth.php';
require_once __DIR__ . '/../src/repository/UserRepository.php';
use auth\Auth;
use repository\UserRepository;

Auth::startSession();

$error = '';
$ret = isset($_GET['ret']) ? (string)$_GET['ret'] : '';
if (isset($_GET['pending']) && $_GET['pending'] === '1') {
    $error = "Votre compte est en attente de validation par un administrateur.";
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim((string)($_POST['email'] ?? ''));
    $password = (string)($_POST['password'] ?? '');
    if ($email === '' || $password === '') {
        $error = 'Email et mot de passe requis';
    } else {
        if (Auth::login($email, $password)) {
            $target = $ret !== '' ? $ret : './index.php';
            header('Location: ' . $target);
            exit;
        }
        // Pending detection: same credentials but ref_magasin is NULL
        $repo = new UserRepository();
        $u = $repo->getUserByEmail($email);
        if ($u && password_verify($password, (string)$u->getMdp()) && $u->getRefMagasin() === null) {
            $error = "Votre compte est en attente d'approbation par un administrateur.";
        } else {
            $error = 'Identifiants invalides';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Connexion • Paristanbul</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <style>
    body{ background:#f3f4f6; }
    .card{ border:1px solid #e5e7eb; border-radius:12px; }
  </style>
</head>
<body class="d-flex align-items-center" style="min-height:100vh;">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-12 col-sm-10 col-md-6 col-lg-4">
        <div class="text-center mb-3">
          <img src="../src/assets/img/logo.png" alt="Paristanbul" style="max-width:180px;"/>
        </div>
        <div class="card shadow-sm">
          <div class="card-body p-4">
            <h1 class="h4 mb-3">Connexion</h1>
            <?php if ($error): ?>
              <div class="alert alert-danger" role="alert"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <form method="post">
              <input type="hidden" name="ret" value="<?= htmlspecialchars($ret) ?>"/>
              <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" name="email" required />
              </div>
              <div class="mb-3">
                <label class="form-label">Mot de passe</label>
                <input type="password" class="form-control" name="password" required />
              </div>
              <button class="btn btn-primary w-100" type="submit">Se connecter</button>
              <div class="text-center mt-3">
                <a href="./register.php">Créer un compte</a>
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
