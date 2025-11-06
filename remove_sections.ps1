# Script pour supprimer les sections inutiles du fichier index.php
$filePath = "C:\wamp64\www\gestionnaireParistanbul\vue\index.php"
$content = Get-Content -Path $filePath -Raw

# Suppression de la section "Derniers produits ajoutés" (première occurrence)
$content = $content -replace '(?s)<div style="padding:40px">\s*<h2>Derniers produits ajoutés<\/h2>\s*<p>Liste des 10 à 12 derniers produits \+ bouton "Voir tout le stock"\.<\/p>\s*<\/div>\s*', ''

# Suppression de la section "Statistiques simples"
$content = $content -replace '(?s)<div style="padding:40px">\s*<h2>Statistiques simples<\/h2>\s*<ul>\s*<li>Graphique "Stock par catégorie"<\/li>\s*<li>Graphique "Produits les plus vendus"<\/li>\s*<li>Chiffre d\'affaires mensuel / par magasin<\/li>\s*<\/ul>\s*<\/div>\s*', ''

# Suppression de la section "Alertes"
$content = $content -replace '(?s)<div style="padding:40px">\s*<h2>Alertes<\/h2>\s*<ul>\s*<li>Produits sous le seuil<\/li>\s*<li>Commandes non livrées depuis longtemps<\/li>\s*<\/ul>\s*<\/div>\s*', ''

# Suppression de la section complète "Derniers produits ajoutés" (avec le tableau)
$content = $content -replace '(?s)<!-- Section Derniers produits -->\s*<div class="dashboard-section">.*?<\/div>\s*<\/div>\s*<\/div>\s*', ''

# Suppression de la section complète "Stock par catégorie"
$content = $content -replace '(?s)<!-- Section Stock par catégorie -->\s*<div class="dashboard-section">.*?<\/div>\s*<\/div>\s*<\/div>\s*', ''

# Écriture du contenu modifié dans le fichier
$content | Set-Content -Path $filePath -Encoding UTF8

Write-Host "Les sections ont été supprimées avec succès."
