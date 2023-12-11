<?php

namespace Entity\authentification;
use BDD\MySQL\Mysql;
use App\Entity\Session; 
use Exception;
use PDO; 

class Authentification 
{
    private string $email; 
    private string $password; 

    public function __construct($email, $password) {
        $this->$email = $email;
        $this->$password = $password;
    }

    // Connexion utilisateur
    public function connect(string $email, string $password) {
        try {

            $db = Mysql::getInstance(); 
            $req = $db->getPDO(); 
            $query = $req->prepare("SELECT * from users WHERE email = :email"); 
            $query->bindValue(':email', $email, \PDO::PARAM_STR); 
            if($query->execute()) {
                $user = $query->fetch(PDO::FETCH_ASSOC); 
                if ($user == false) {
                    throw new Exception("Email invalide"); 
                } else {
                    if(password_verify($password, $user['password'])) {
                        foreach($user as $id=>$value) {
                            Session::set("$id", $value); 
                            header('Location: index.php');
                            exit();
                        }
                    } else {
                        throw new Exception("Mot de passe invalide"); 
                    }
                }
            }
        } catch(Exception $e) {
            var_dump($e); 
        }


    }
}