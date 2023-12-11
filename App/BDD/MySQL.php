<?php

namespace App\BDD;

use Exception;

class MySQL
{
    private $dsn;
    private $uid;
    private $pwd;
    private $additionalParams;
    private $conn_str;
    private $pdo = null;

    public function __construct(string $db)
    {
        switch ($db) {
            case "APIE":
                // Corriger le chemin relatif !
                $conf = require_once  _ROOTPATH_ . 'APIE_DB.php';
                break;
            case "SIPIC":
                $conf = require_once _ROOTPATH_ . 'SIPIC_DB.php';
                break;
            case "DEFAULT":
                $conf = require_once _ROOTPATH_ . 'DEFAULT.php';
                break;
        }

        if (isset($conf['dsn'])) {
            $this->dsn = $conf['dsn'];
        }
        if (isset($conf['uid'])) {
            $this->uid = $conf['uid'];
        }
        if (isset($conf['pwd'])) {
            $this->pwd = $conf['pwd'];
        }
        if (isset($conf['additional_params'])) {
            $this->additionalParams = $conf['additional_params'];
        }
        if (isset($this->dsn) && isset($this->additionalParams)) {
            $this->conn_str = "Driver={ODBC Driver 18 for SQL Server};Server=77.198.202.218;Port=1433;Database={$this->dsn};{$this->additionalParams}";
        }
    }

    public function getPDO(): ?\PDO
    {
        try {
            return new \PDO("odbc:{$this->conn_str}", $this->uid, $this->pwd);
        } catch (\PDOException $e) {
            return null;
        }
    }
}
