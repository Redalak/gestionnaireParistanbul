<?php
/**
 * Renvoie l'URL de base pour les redirections.
 * On essaie d'être portable entre MAMP (port 8888) et WAMP (port 80).
 */
function baseUrl(): string
{
    // On récupère le host actuel et le port actuel vus par PHP.
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';

    // Exemple de $host possible :
    // - "localhost"
    // - "localhost:8888"
    // - "127.0.0.1"
    //
    // On veut juste "http://localhost:8888" ou "http://localhost"

    // Déterminer le schéma (http / https)
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        ? 'https'
        : 'http';

    return $scheme . '://' . $host;
}

/**
 * Helper de redirection propre + exit.
 */
function redirect(string $path, array $params = []): never
{
    // $path = "/listeProduits.php" ou "/updateProduit.php"
    // $params = ['deleted' => 1] etc

    $url = config . phprtrim(baseUrl(), '/') . $path;

    if (!empty($params)) {
        $url .= '?' . http_build_query($params);
    }

    header('Location: ' . $url);
    exit;
}