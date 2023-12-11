<?php

namespace App\Entity;

use App\BDD\MySQL;
use Exception;
use PDOException;
use PDO; 

class SipicDB
{
    private $db;
    public $sipicDpPlanifiée = [];
    public $sipicDpAPlanifiée = [];
    public $sipicBLPrepare = [];
    public $sipicBLIntervEnCours = [];
    public $sipicBlAfacturerBloquer = [];
    public $sipicBlAfacturer = [];
    public $sipicBlNonRetourner = [];
    public $sipicSaisieNonTransmises = [];

    public function __construct()
    {
        $this->db = new MySQL("SIPIC");
    }

    public function sipicDpPlanifiée()
    {
        try {
            $mysql = $this->db;
            $connect = $mysql->getPDO();
            if (!$connect) {
                throw new Exception("<p class='error'>Unable to connect to the database.</p>");
            }
            $req = $connect->prepare("
    SELECT DateFromParts(year([dbo].[DP_VENTES_LIGNES].[VL_DODATELIVR]), month([dbo].[DP_VENTES_LIGNES].[VL_DODATELIVR]), 1) AS [VL_DODATELIVR], sum([dbo].[DP_VENTES_LIGNES].[CAHTNET]) AS [sum]
    FROM [dbo].[DP_VENTES_LIGNES]
    LEFT JOIN [dbo].[DP_VENTES] [Dp Ventes] ON [dbo].[DP_VENTES_LIGNES].[VL_DOCNUM] = [Dp Ventes].[V_DOCNUM]
    LEFT JOIN [dbo].[DP_ARTICLES] [Dp Articles] ON [dbo].[DP_VENTES_LIGNES].[VL_ART_UK] = [Dp Articles].[ART_UK]
    LEFT JOIN [dbo].[F_DOCENTETE] [F Docentete] ON [dbo].[DP_VENTES_LIGNES].[VL_DOCNUM] = [F Docentete].[DO_Piece]
    LEFT JOIN [dbo].[F_DOCLIGNE] [F Docligne] ON [dbo].[DP_VENTES_LIGNES].[VL_DLNO] = [F Docligne].[DL_No]
    WHERE ([dbo].[DP_VENTES_LIGNES].[VL_DOCTYPE] = 2
       AND [dbo].[DP_VENTES_LIGNES].[VL_DODATELIVR] >= DateTimeOffsetFromParts(2020, 1, 1, 0, 0, 0, 0, 1, 0, 7)
       AND [dbo].[DP_VENTES_LIGNES].[VL_DODATELIVR] < DateTimeOffsetFromParts(2024, 1, 1, 0, 0, 0, 0, 1, 0, 7)
       AND [Dp Ventes].[V_STATUT] = 'A livrer')
    GROUP BY year([dbo].[DP_VENTES_LIGNES].[VL_DODATELIVR]), month([dbo].[DP_VENTES_LIGNES].[VL_DODATELIVR])
    ORDER BY year([dbo].[DP_VENTES_LIGNES].[VL_DODATELIVR]) ASC, month([dbo].[DP_VENTES_LIGNES].[VL_DODATELIVR]) ASC
");

            if ($req->execute()) {
                $resultats = $req->fetchAll(\PDO::FETCH_ASSOC);
                return $this->sipicDpPlanifiée = $resultats;
            } else {
                throw new Exception("<p class='error'>Erreur lors de la récupération des données</p>'");
            }
        } catch (PDOException $e) {
            echo "Erreur PDO : " . $e->getMessage();
        } catch (Exception $e) {
            echo "Erreur : " . $e->getMessage();
        }
    }

    public function sipicDpAPlanifiée()
    {
        try {
            $mysql = $this->db;
            $connect = $mysql->getPDO();
            if (!$connect) {
                throw new Exception("<p class='error'>Unable to connect to the database.</p>");
            }
            $req = $connect->prepare("
            SELECT DATEFROMPARTS(YEAR([dbo].[DP_VENTES_LIGNES].[VL_DODATELIVR]), MONTH([dbo].[DP_VENTES_LIGNES].[VL_DODATELIVR]), 1) AS VL_DODATELIVR, 
            SUM([dbo].[DP_VENTES_LIGNES].[CAHTNET]) AS sum
     FROM [dbo].[DP_VENTES_LIGNES]
     LEFT JOIN [dbo].[DP_VENTES] AS [Dp Ventes] ON [dbo].[DP_VENTES_LIGNES].[VL_DOCNUM] = [Dp Ventes].[V_DOCNUM]
     LEFT JOIN [dbo].[DP_ARTICLES] AS [Dp Articles] ON [dbo].[DP_VENTES_LIGNES].[VL_ART_UK] = [Dp Articles].[ART_UK]
     LEFT JOIN [dbo].[F_DOCENTETE] AS [F Docentete] ON [dbo].[DP_VENTES_LIGNES].[VL_DOCNUM] = [F Docentete].[DO_Piece]
     LEFT JOIN [dbo].[F_DOCLIGNE] AS [F Docligne] ON [dbo].[DP_VENTES_LIGNES].[VL_DLNO] = [F Docligne].[DL_No]
     WHERE ([dbo].[DP_VENTES_LIGNES].[VL_DOCTYPE] = 2
            AND [dbo].[DP_VENTES_LIGNES].[VL_DODATELIVR] >= '2020-01-01' 
            AND [dbo].[DP_VENTES_LIGNES].[VL_DODATELIVR] < '2024-01-01' 
            AND [Dp Ventes].[V_TARIF] = 'DEVIS' 
            AND ([Dp Ventes].[V_STATUT] = 'Confirmé' OR [Dp Ventes].[V_STATUT] = 'Saisie'))
     GROUP BY YEAR([dbo].[DP_VENTES_LIGNES].[VL_DODATELIVR]), MONTH([dbo].[DP_VENTES_LIGNES].[VL_DODATELIVR])
     ORDER BY YEAR([dbo].[DP_VENTES_LIGNES].[VL_DODATELIVR]) ASC, MONTH([dbo].[DP_VENTES_LIGNES].[VL_DODATELIVR]) ASC;
");

            if ($req->execute()) {
                $resultats = $req->fetchAll(\PDO::FETCH_ASSOC);
                return $this->sipicDpAPlanifiée = $resultats;
            } else {
                throw new Exception("<p class='error'>Erreur lors de la récupération des données</p>'");
            }
        } catch (PDOException $e) {
            echo "Erreur PDO : " . $e->getMessage();
        } catch (Exception $e) {
            echo "Erreur : " . $e->getMessage();
        }
    }

    public function sipicBLPrepare()
    {
        try {
            $mysql = $this->db;
            $connect = $mysql->getPDO();
            if (!$connect) {
                throw new Exception("<p class='error'>Unable to connect to the database.</p>");
            }
            $req = $connect->prepare("
            SELECT 
    DATEFROMPARTS(YEAR([dbo].[DP_VENTES_LIGNES].[VL_DODATELIVR]), MONTH([dbo].[DP_VENTES_LIGNES].[VL_DODATELIVR]), 1) AS [VL_DODATELIVR],
    SUM([dbo].[DP_VENTES_LIGNES].[CAHTNET]) AS [sum]
FROM 
    [dbo].[DP_VENTES_LIGNES]
LEFT JOIN 
    [dbo].[DP_VENTES] AS [Dp Ventes] ON [dbo].[DP_VENTES_LIGNES].[VL_DOCNUM] = [Dp Ventes].[V_DOCNUM]
LEFT JOIN 
    [dbo].[DP_ARTICLES] AS [Dp Articles] ON [dbo].[DP_VENTES_LIGNES].[VL_ART_UK] = [Dp Articles].[ART_UK]
LEFT JOIN 
    [dbo].[F_DOCENTETE] AS [F Docentete] ON [dbo].[DP_VENTES_LIGNES].[VL_DOCNUM] = [F Docentete].[DO_Piece]
LEFT JOIN 
    [dbo].[F_DOCLIGNE] AS [F Docligne] ON [dbo].[DP_VENTES_LIGNES].[VL_DLNO] = [F Docligne].[DL_No]
WHERE 
    ([dbo].[DP_VENTES_LIGNES].[VL_DOCTYPE] = 3
    AND [dbo].[DP_VENTES_LIGNES].[VL_DODATELIVR] >= DATEADD(DAY, 7, '2020-01-01')
    AND [dbo].[DP_VENTES_LIGNES].[VL_DODATELIVR] < DATEADD(DAY, 7, '2024-01-01')
    AND [Dp Ventes].[V_STATUT] = 'Saisie')
GROUP BY 
    YEAR([dbo].[DP_VENTES_LIGNES].[VL_DODATELIVR]), MONTH([dbo].[DP_VENTES_LIGNES].[VL_DODATELIVR])
ORDER BY 
    YEAR([dbo].[DP_VENTES_LIGNES].[VL_DODATELIVR]) ASC, MONTH([dbo].[DP_VENTES_LIGNES].[VL_DODATELIVR]) ASC;

      
");
            if ($req->execute()) {                $resultats = $req->fetchAll(\PDO::FETCH_ASSOC);
                return $this->sipicBLPrepare = $resultats;
            } else {
                throw new Exception("<p class='error'>Erreur lors de la récupération des données</p>'");
            }
        } catch (PDOException $e) {
            echo "Erreur PDO : " . $e->getMessage();
        } catch (Exception $e) {
            echo "Erreur : " . $e->getMessage();
        }
    }

    public function sipicBLIntervEnCours()
    {
        try {
            $mysql = $this->db;
            $connect = $mysql->getPDO();
            if (!$connect) {
                throw new Exception("<p class='error'>Unable to connect to the database.</p>");
            }
            $vl_doctype = 3;
            $currentDate = date('Y-m-d');
            $nextDayDate = date('Y-m-d', strtotime('+1 day', strtotime($currentDate)));
            $v_statut = 'Confirmé';
        
            $req = $connect->prepare("
                SELECT 
                    DateFromParts(YEAR(VL_DODATELIVR), MONTH(VL_DODATELIVR), 1) AS VL_DODATELIVR,
                    SUM(CAHTNET) AS sum
                FROM 
                    DP_VENTES_LIGNES
                LEFT JOIN 
                    DP_VENTES AS DpVentes ON DP_VENTES_LIGNES.VL_DOCNUM = DpVentes.V_DOCNUM 
                LEFT JOIN 
                    DP_ARTICLES AS DpArticles ON DP_VENTES_LIGNES.VL_ART_UK = DpArticles.ART_UK 
                LEFT JOIN 
                    F_DOCENTETE AS FDocentete ON DP_VENTES_LIGNES.VL_DOCNUM = FDocentete.DO_Piece 
                LEFT JOIN 
                    F_DOCLIGNE AS FDocligne ON DP_VENTES_LIGNES.VL_DLNO = FDocligne.DL_No
                WHERE 
                    (DP_VENTES_LIGNES.VL_DOCTYPE = :vl_doctype
                    AND DP_VENTES_LIGNES.VL_DODATELIVR >= :currentDate
                    AND DP_VENTES_LIGNES.VL_DODATELIVR < :nextDayDate 
                    AND DpVentes.V_STATUT = :v_statut)
                GROUP BY 
                    YEAR(VL_DODATELIVR), MONTH(VL_DODATELIVR)
                ORDER BY 
                    YEAR(VL_DODATELIVR) ASC, MONTH(VL_DODATELIVR) ASC;
            ");
        
            // Liaison des paramètres avec les valeurs
            $req->bindParam(':vl_doctype', $vl_doctype, PDO::PARAM_INT);
            $req->bindParam(':currentDate', $currentDate, PDO::PARAM_STR);
            $req->bindParam(':nextDayDate', $nextDayDate, PDO::PARAM_STR);
            $req->bindParam(':v_statut', $v_statut, PDO::PARAM_STR);
        
            if ($req->execute()) {
                $resultats = $req->fetchAll(\PDO::FETCH_ASSOC);
                return $this->sipicBLIntervEnCours = $resultats; 
            } else {
                throw new Exception("<p class='error'>Erreur lors de la récupération des données</p>'"); 
            }
        } catch (PDOException $e) {
            echo "Erreur PDO : " . $e->getMessage();
        } catch (Exception $e) {
            echo "Erreur : " . $e->getMessage();
        }
    }

    public function sipicBlAfacturerBloquer()
    {
        try {
            $mysql = $this->db;
            $connect = $mysql->getPDO();
            if (!$connect) {
                throw new Exception("<p class='error'>Unable to connect to the database.</p>");
            }
            $req = $connect->prepare("
            SELECT 
            DATEFROMPARTS(YEAR([dbo].[DP_VENTES_LIGNES].[VL_DODATELIVR]), MONTH([dbo].[DP_VENTES_LIGNES].[VL_DODATELIVR]), 1) AS [VL_DODATELIVR], 
            SUM([dbo].[DP_VENTES_LIGNES].[CAHTNET]) AS [sum]
        FROM 
            [dbo].[DP_VENTES_LIGNES]
        LEFT JOIN 
            [dbo].[DP_VENTES] AS [Dp Ventes] ON [dbo].[DP_VENTES_LIGNES].[VL_DOCNUM] = [Dp Ventes].[V_DOCNUM] 
        LEFT JOIN 
            [dbo].[DP_ARTICLES] AS [Dp Articles] ON [dbo].[DP_VENTES_LIGNES].[VL_ART_UK] = [Dp Articles].[ART_UK]
        LEFT JOIN 
            [dbo].[F_DOCENTETE] AS [F Docentete] ON [dbo].[DP_VENTES_LIGNES].[VL_DOCNUM] = [F Docentete].[DO_Piece]
        LEFT JOIN 
            [dbo].[F_DOCLIGNE] AS [F Docligne] ON [dbo].[DP_VENTES_LIGNES].[VL_DLNO] = [F Docligne].[DL_No]
        WHERE 
            ([dbo].[DP_VENTES_LIGNES].[VL_DOCTYPE] = 3
            AND [dbo].[DP_VENTES_LIGNES].[VL_DODATELIVR] >= CAST('2020-01-01' AS DATETIMEOFFSET)
            AND [dbo].[DP_VENTES_LIGNES].[VL_DODATELIVR] < CAST('2024-01-01' AS DATETIMEOFFSET)
            AND [Dp Ventes].[V_STATUT] = 'A facturer' 
            AND ([Dp Ventes].[V_PERIODICITE] <> 'NEANT' OR [Dp Ventes].[V_PERIODICITE] IS NULL))
        GROUP BY 
            YEAR([dbo].[DP_VENTES_LIGNES].[VL_DODATELIVR]), MONTH([dbo].[DP_VENTES_LIGNES].[VL_DODATELIVR])
        ORDER BY 
            YEAR([dbo].[DP_VENTES_LIGNES].[VL_DODATELIVR]) ASC, MONTH([dbo].[DP_VENTES_LIGNES].[VL_DODATELIVR]) ASC
        
"); 
            if ($req->execute()) {
                $resultats = $req->fetchAll(\PDO::FETCH_ASSOC);
                return $this->sipicBlAfacturerBloquer = $resultats; 
            } else {
                throw new Exception("<p class='error'>Erreur lors de la récupération des données</p>'"); 
            }
        } catch (PDOException $e) {
            echo "Erreur PDO : " . $e->getMessage();
        } catch (Exception $e) {
            echo "Erreur : " . $e->getMessage();
        }
    }

    public function sipicBlAfacturer()
    {
        try {
            $mysql = $this->db;
            $connect = $mysql->getPDO();
            if (!$connect) {
                throw new Exception("<p class='error'>Unable to connect to the database.</p>");
            }
            $req = $connect->prepare("
            SELECT 
            DateFromParts(year([dbo].[DP_VENTES_LIGNES].[VL_DODATELIVR]), month([dbo].[DP_VENTES_LIGNES].[VL_DODATELIVR]), 1) AS [VL_DODATELIVR], 
            sum([dbo].[DP_VENTES_LIGNES].[CAHTNET]) AS [sum]
        FROM 
            [dbo].[DP_VENTES_LIGNES]
        LEFT JOIN 
            [dbo].[DP_VENTES] AS [Dp Ventes] ON [dbo].[DP_VENTES_LIGNES].[VL_DOCNUM] = [Dp Ventes].[V_DOCNUM] 
        LEFT JOIN 
            [dbo].[DP_ARTICLES] AS [Dp Articles] ON [dbo].[DP_VENTES_LIGNES].[VL_ART_UK] = [Dp Articles].[ART_UK]
        LEFT JOIN 
            [dbo].[F_DOCENTETE] AS [F Docentete] ON [dbo].[DP_VENTES_LIGNES].[VL_DOCNUM] = [F Docentete].[DO_Piece]
        LEFT JOIN 
            [dbo].[F_DOCLIGNE] AS [F Docligne] ON [dbo].[DP_VENTES_LIGNES].[VL_DLNO] = [F Docligne].[DL_No]
        WHERE 
            ([dbo].[DP_VENTES_LIGNES].[VL_DOCTYPE] = 3
            AND [dbo].[DP_VENTES_LIGNES].[VL_DODATELIVR] >= DateTimeOffsetFromParts(2020, 1, 1, 0, 0, 0, 0, 1, 0, 7) 
            AND [dbo].[DP_VENTES_LIGNES].[VL_DODATELIVR] < DateTimeOffsetFromParts(2024, 1, 1, 0, 0, 0, 0, 1, 0, 7) 
            AND [Dp Ventes].[V_STATUT] = 'A facturer' 
            AND [Dp Ventes].[V_PERIODICITE] = 'NEANT')
        GROUP BY 
            year([dbo].[DP_VENTES_LIGNES].[VL_DODATELIVR]), month([dbo].[DP_VENTES_LIGNES].[VL_DODATELIVR])
        ORDER BY 
            year([dbo].[DP_VENTES_LIGNES].[VL_DODATELIVR]) ASC, month([dbo].[DP_VENTES_LIGNES].[VL_DODATELIVR]) ASC   
"); 
            if ($req->execute()) {
                $resultats = $req->fetchAll(\PDO::FETCH_ASSOC);
                return $this->sipicBlAfacturer = $resultats; 
            } else {
                throw new Exception("<p class='error'>Erreur lors de la récupération des données</p>'"); 
            }
        } catch (PDOException $e) {
            echo "Erreur PDO : " . $e->getMessage();
        } catch (Exception $e) {
            echo "Erreur : " . $e->getMessage();
        }
    }

    
    public function sipicBlNonRetourner()
    {
        try {
            $mysql = $this->db;
            $connect = $mysql->getPDO();
            if (!$connect) {
                throw new Exception("<p class='error'>Unable to connect to the database.</p>");
            }
            $vl_doctype = 3;
            $v_statut = 'Confirmé';
            $daysToAdd = -1420;
            
            $req = $connect->prepare("
            SELECT 
            DATEFROMPARTS(YEAR(VL_DODATELIVR), MONTH(VL_DODATELIVR), 1) AS VL_DODATELIVR,
            SUM(CAHTNET) AS sum
        FROM 
            DP_VENTES_LIGNES
        LEFT JOIN 
            DP_VENTES AS DpVentes ON DP_VENTES_LIGNES.VL_DOCNUM = DpVentes.V_DOCNUM 
        LEFT JOIN 
            DP_ARTICLES AS DpArticles ON DP_VENTES_LIGNES.VL_ART_UK = DpArticles.ART_UK 
        LEFT JOIN 
            F_DOCENTETE AS FDocentete ON DP_VENTES_LIGNES.VL_DOCNUM = FDocentete.DO_Piece 
        LEFT JOIN 
            F_DOCLIGNE AS FDocligne ON DP_VENTES_LIGNES.VL_DLNO = FDocligne.DL_No
        WHERE 
            (
                DP_VENTES_LIGNES.VL_DOCTYPE = :vl_doctype
                AND DpVentes.V_STATUT = :v_statut 
                AND DP_VENTES_LIGNES.VL_DODATELIVR BETWEEN 
                    DATEADD(DAY, :daysToAdd, GETDATE())
                    AND GETDATE()
            )
        GROUP BY 
            YEAR(VL_DODATELIVR), MONTH(VL_DODATELIVR)
        ORDER BY 
            YEAR(VL_DODATELIVR) ASC, MONTH(VL_DODATELIVR) ASC;
            ");
            
            // Liaison des paramètres avec les valeurs
            $req->bindParam(':vl_doctype', $vl_doctype, PDO::PARAM_INT);
            $req->bindParam(':v_statut', $v_statut, PDO::PARAM_STR);
            $req->bindParam(':daysToAdd', $daysToAdd, PDO::PARAM_INT);
            if ($req->execute()) {
                $resultats = $req->fetchAll(\PDO::FETCH_ASSOC);
                return $this->sipicBlNonRetourner = $resultats; 
            } else {
                throw new Exception("<p class='error'>Erreur lors de la récupération des données</p>'"); 
            }
        } catch (PDOException $e) {
            echo "Erreur PDO : " . $e->getMessage();
        } catch (Exception $e) {
            echo "Erreur : " . $e->getMessage();
        }
    }

    public function sipicSaisieNonTransmises()
    {
        try {
            $mysql = $this->db;
            $connect = $mysql->getPDO();
            if (!$connect) {
                throw new Exception("<p class='error'>Unable to connect to the database.</p>");
            }
            $req = $connect->prepare("
            SELECT DATEFROMPARTS(YEAR([dbo].[DP_VENTES_LIGNES].[VL_DODATELIVR]), 1, 1) AS [VL_DODATELIVR],
       SUM([dbo].[DP_VENTES_LIGNES].[CAHTNET]) AS [sum]
FROM [dbo].[DP_VENTES_LIGNES]
LEFT JOIN [dbo].[DP_VENTES] [Dp Ventes] ON [dbo].[DP_VENTES_LIGNES].[VL_DOCNUM] = [Dp Ventes].[V_DOCNUM]
LEFT JOIN [dbo].[DP_ARTICLES] [Dp Articles] ON [dbo].[DP_VENTES_LIGNES].[VL_ART_UK] = [Dp Articles].[ART_UK]
LEFT JOIN [dbo].[F_DOCENTETE] [F Docentete] ON [dbo].[DP_VENTES_LIGNES].[VL_DOCNUM] = [F Docentete].[DO_Piece]
LEFT JOIN [dbo].[F_DOCLIGNE] [F Docligne] ON [dbo].[DP_VENTES_LIGNES].[VL_DLNO] = [F Docligne].[DL_No]
WHERE ([dbo].[DP_VENTES_LIGNES].[VL_DOCTYPE] = 2
   AND [dbo].[DP_VENTES_LIGNES].[VL_DODATELIVR] >= CONVERT(DATETIMEOFFSET, '2020-01-01T00:00:00.0000000+00:00', 127)
   AND [dbo].[DP_VENTES_LIGNES].[VL_DODATELIVR] < CONVERT(DATETIMEOFFSET, '2024-01-01T00:00:00.0000000+00:00', 127)
   AND [Dp Ventes].[V_STATUT] = 'Saisie'
   AND [Dp Ventes].[V_TYPE] = 'Devis')
GROUP BY YEAR([dbo].[DP_VENTES_LIGNES].[VL_DODATELIVR])
ORDER BY YEAR([dbo].[DP_VENTES_LIGNES].[VL_DODATELIVR]) ASC;
"); 
            if ($req->execute()) {
                $resultats = $req->fetchAll(\PDO::FETCH_ASSOC);
                return $this->sipicSaisieNonTransmises = $resultats; 
            } else {
                throw new Exception("<p class='error'>Erreur lors de la récupération des données</p>'"); 
            }
        } catch (PDOException $e) {
            echo "Erreur PDO : " . $e->getMessage();
        } catch (Exception $e) {
            echo "Erreur : " . $e->getMessage();
        }
    }
}
