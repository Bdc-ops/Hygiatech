<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistiques</title>
</head>
<body>

    <!-- Affichage de la page en fonction de la connexion utilisateur -->
  <?php
    if(isset($_SESSION['id'])) {
        include_once("./data_front.php"); 
    } else {
        include_once("./login.php"); 
    }
    
    ?>

</body>
</html>

<?php
