<?php
// Permet l'affichage de toute les erreurs
error_reporting(E_ALL);
ini_set('display_errors', 1);

//Défini une constante pour le dossier principal
define('_ROOTPATH_', __DIR__ . '/..');

// Autoload toutes les différentes classes de l'application 
spl_autoload_register(function ($className) {
    require '../vendor/autoload.php';
    $filePath = _ROOTPATH_ . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $className) . '.php';

    if (file_exists($filePath)) {
        require_once $filePath;
    }
});
session_start();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistiques</title>
    <link href="./style.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&display=swap">
</head>

<body>
    <main class="main">
        <div class="loader-container" id='loader'>
            <div class="loader"></div>
        </div>
        <!-- Affichage de la page en fonction de la connexion utilisateur -->
        <?php
        if (isset($_SESSION['id'])) {
            include_once("./Pages/data_front.php");
        } else {
            include_once("./Pages/login.php");
        }
        ?>
    </main>
    <script src="script.js"></script>
</body>

</html>