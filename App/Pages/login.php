<?php

use App\Entity\Authentification;

if (isset($_POST)) {
    extract($_POST);
    if (isset($_POST['connect'])) {
        $username = trim($username);
        $password = trim($password);
        $auth = new Authentification($username, $password);
        if ($auth->informationVerify($username, $password)) {
            $auth->connect($username, $password);
        }
    }
}
?>

<div id='dataContainer'>
    <form action="" method="POST" class="connectForm">
        <div class="login-box">
            <div class="user-box">
                <input type="text" id="username" name="username"></input>
                <label>Entrez votre nom d'utilisateur</label>
            </div>
            <div class="user-box">
                <input type="text" id="password" name="password"></input>
                <label>Entrez votre mot de passe</label>
            </div>
            <button type="submit" name="connect" class="connectButton" id="loaderBTN">Se connecter</button>
        </div>
    </form>
</div>