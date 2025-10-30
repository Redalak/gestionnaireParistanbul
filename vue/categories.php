<?php
declare(strict_types=1);
session_start();

require_once __DIR__ . '/../src/bdd/Bdd.php';
use bdd\Bdd;

$db = (new Bdd())->getBdd();

$msg = null;

/* Ajout */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom'] ?? '');
    if ($nom !== '') {
        $st = $db->prepare('INSERT INTO categorie (nom) VALUES (:nom)');
        $st->execute([':nom' => $nom]);
        $msg = '✅ Catégorie ajoutée.';
    } else {
        $msg = '⚠️ Saisis un nom.';
    }
}

/* Suppression (très basique) */
if (isset($_GET['del'])) {
    $id = (int)$_GET['del'];
    if ($id > 0) {
        $st = $db->prepare('DELETE FROM categorie WHERE id_categorie = :id');
        $st->execute([':id' => $id]);
        $msg = '🗑️ Catégorie supprimée.';
    }
}

/* Liste */
$cats = $db->query('SELECT id_categorie, nom FROM categorie ORDER BY nom')->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Catégories — Paristanbul</title>
    <style>
        :root{--primary:#c62828;--secondary:#000;--bg:#f5f5f5;--white:#fff;--radius:14px}
        *{box-sizing:border-box;font-family:"Plus Jakarta Sans",Arial,sans-serif}
        body{margin:0;background:var(--bg);color:var(--secondary)}
        header{background:var(--secondary);color:#fff;padding:1.2rem 2rem;display:flex;justify-content:space-between;align-items:center}
        header a{color:#fff;text-decoration:none;margin-left:1.2rem}
        main{max-width:900px;margin:2rem auto;background:var(--white);padding:2rem;border-radius:var(--radius);box-shadow:0 10px 30px rgba(0,0,0,.08)}
        h2{margin:0 0 1rem}
        .msg{padding:.8rem 1rem;border-radius:10px;margin-bottom:1rem}
        .ok{background:#e8f5e9;color:#2e7d32}.err{background:#ffebee;color:#c62828}
        form{display:flex;gap:.6rem;margin:1rem 0}
        input[type=text]{flex:1;padding:.7rem;border:1px solid #ccc;border-radius:10px}
        button{background:var(--primary);color:#fff;border:none;border-radius:10px;padding:.7rem 1rem;font-weight:600;cursor:pointer}
        table{width:100%;border-collapse:collapse;margin-top:1rem}
        th,td{padding:.7rem;border-bottom:1px solid #eee;text-align:left}
        a.btn-del{background:#c62828;color:#fff;text-decoration:none;padding:.35rem .6rem;border-radius:8px;font-size:.8rem}
    </style>
</head>
<body>
<header>
    <div><strong>Catégories</strong></div>
    <nav>
        <a href="index.php">Accueil</a>
        <a href="ajouter.php">Ajout produit</a>
        <a href="produit.php">Produits</a>
    </nav>
</header>
<main>
    <h2>Gérer les catégories</h2>

    <?php if ($msg): ?>
        <div class="msg <?= str_starts_with($msg,'✅')||str_starts_with($msg,'🗑️')?'ok':'err' ?>"><?= htmlspecialchars($msg) ?></div>
    <?php endif; ?>

    <form method="post" action="">
        <input type="text" name="nom" placeholder="Nouvelle catégorie…" required>
        <button type="submit">Ajouter</button>
    </form>

    <?php if (empty($cats)): ?>
        <p>Aucune catégorie pour l’instant.</p>
    <?php else: ?>
        <table>
            <thead><tr><th>#</th><th>Nom</th><th>Actions</th></tr></thead>
            <tbody>
            <?php foreach ($cats as $c): ?>
                <tr>
                    <td>#<?= (int)$c['id_categorie'] ?></td>
                    <td><?= htmlspecialchars($c['nom']) ?></td>
                    <td><a class="btn-del" href="categories.php?del=<?= (int)$c['id_categorie'] ?>" onclick="return confirm('Supprimer ?');">Supprimer</a></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</main>
</body>
</html>