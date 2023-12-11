<?php

namespace App\Controller;

use Exception;

class HomeController extends Controller
{
    public function route(): void
    {
        try {

            if (isset($_GET['action'])) {
                $this->home();
            } else {
                throw new Exception('L\'action demandée n\'existe pas');
            }
        } catch (Exception $e) {
            $this->render('/errors/default', ['error' => $e->getMessage()]);
        }
    }
    protected function home()
    {
        $this->render('pages/home');
    }

    public function menuHome()
    {
    }
}