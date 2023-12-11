<?php

namespace App\Controller;

use App\Entity\Session;
use Exception;
use App\Controller\HomeController; 

class Controller
{
    public function route(): void
    {
        try {
            if (isset($_GET['controller'])) {
                switch ($_GET['controller']) {
                    //Permet d'afficher la page home
                    case 'home':
                        $this->render('pages/home');
                        break;
                    case 'disconnect':
                        Session::destroy();
                        break;
                    default:
                        throw new Exception("Page inconnue");
                }
            } else {
                $homeController = new HomeController();
                $homeController->home();
            }
        } catch (Exception $e) {
            header("Refresh: 5; URL=index.php");
            echo $e->getMessage();
        }
    }

    protected function render(string $path, array $params = []): void
    {
        $filePath = _ROOTPATH_ . '/templates/' . $path . '.php';
        try {
            if (!file_exists($filePath)) {
                throw new Exception("Page inconnue");
            } else {
                extract($params);
                require_once $filePath;
            }
        } catch (Exception $e) {
            $this->render('/errors/defaultError', ['error' => $e->getMessage()]);
        }
    }
}