Initialisation du projet : 

Architecture : Utilise les namespace et un controller pour gérer l'affichage des pages. 
Utilise la superglobale SESSSION afin de garder l'utilisateur connecté.

Index.php récupère les données de la session pour vérifier si une connexion est faites, en fonction de la réponse (connecté ou non), il s'affichera soit le formulaire de connexion, soit les données récupérées depuis les bade de données. 