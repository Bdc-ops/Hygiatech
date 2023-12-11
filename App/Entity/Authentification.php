<?php

namespace App\Entity;

use App\BDD\Mysql;
use App\Entity\Session; 
use Exception;
use PDO; 

class Authentification 
{
    protected string $username; 
    protected string $password; 

    public function __construct($email, $password) {
        $this->username = $email;
        $this->password = $password;
    }

    public function informationVerify($username, $password) {
        try {
            if(isset($username) && isset($password)) {
                if(strlen($username) > 1 && strlen($password) > 1 ) {
                    return true; 
                } else {
                    throw new Exception("<p class='error'>Le mot de passe ou le nom d'utilisateur est trop court</p>"); 
                }
            } else {
                throw new Exception("<p class='error'>Le mot de passe ou le nom d'utilisateur est absent</p>");  
            }
        } catch (Exception $e) {
            echo $e->getMessage(); 
        }
    }

    //Fonction de test avec données locale 
    public function connect(string $username, string $password) {
        try {
            if($username == "admin" && $password=="admin") {
                Session::set("id", 1); 
                header('Location: index.php');
                exit();
            } else {
                throw new Exception("<p class='error'>Le mot de passe ou le nom d'utilisateur est faux</p>"); 
            }
        }
        catch(Exception $e) {
            echo $e->getMessage(); 
        }
    } 

}