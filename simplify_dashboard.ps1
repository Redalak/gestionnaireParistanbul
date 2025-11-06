# Script pour simplifier le tableau de bord en supprimant des sections
$filePath = "C:\wamp64\www\gestionnaireParistanbul\vue\index.php"
$content = [System.IO.File]::ReadAllText($filePath)

# Suppression des sections entre les balises de commentaire spécifiques
$sectionsToRemove = @(
    @{
        Start = '<!-- Section Derniers produits -->'
        End = '<!-- /Section Derniers produits -->'
    },
    @{
        Start = '<!-- Section Stock par catégorie -->'
        End = '<!-- /Section Stock par catégorie -->'
    },
    @{
        Start = '<div style="padding:40px">\s*<h2>Derniers produits ajoutés</h2>'
        End = '</div>'
    },
    @{
        Start = '<div style="padding:40px">\s*<h2>Statistiques simples</h2>'
        End = '</div>'
    },
    @{
        Start = '<div style="padding:40px">\s*<h2>Alertes</h2>'
        End = '</div>'
    }
)

foreach ($section in $sectionsToRemove) {
    $pattern = [regex]::Escape($section.Start) + '[\s\S]*?' + [regex]::Escape($section.End)
    $content = [regex]::Replace($content, $pattern, '')
}

# Écriture du contenu modifié dans le fichier
[System.IO.File]::WriteAllText($filePath, $content, [System.Text.Encoding]::UTF8)

Write-Host "Le tableau de bord a été simplifié avec succès."
