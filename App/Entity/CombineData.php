<?php

namespace App\Entity;

use App\Entity\ApieDB;
use App\Entity\SipicDB;

class CombineData
{
    protected $apieDP;
    protected $sipicDP;
    protected $allData = [];
    protected $apie;
    protected $sipic;

    public function __construct()
    {
        $this->apie = new ApieDB;
        $this->sipic = new SipicDB;
    }

    public function fetchData($apieFunction, $sipicFunction)
    {
        $apieDP = $this->apie->$apieFunction();
        $sipicDP = $this->sipic->$sipicFunction();
        $mergedData = [];

        if (is_array($apieDP)) {
            foreach ($apieDP as $apieItem) {
                $date = $apieItem["VL_DODATELIVR"];
                if (!isset($mergedData[$date])) {
                    $mergedData[$date] = [
                        "VL_DODATELIVR" => $date,
                        "sum" => (float)$apieItem["sum"]
                    ];
                } else {
                    $mergedData[$date]["sum"] += (float)$apieItem["sum"];
                }
            }
        }

        if (is_array($sipicDP)) {
            foreach ($sipicDP as $sipicItem) {
                $date = $sipicItem["VL_DODATELIVR"];
                if (!isset($mergedData[$date])) {
                    $mergedData[$date] = [
                        "VL_DODATELIVR" => $date,
                        "sum" => (float)$sipicItem["sum"]
                    ];
                } else {
                    $mergedData[$date]["sum"] += (float)$sipicItem["sum"];
                }
            }
        }

        // Transformer le tableau associatif en tableau numérique
        $mergedData = array_values($mergedData);

        // Trier le tableau par date
        usort($mergedData, function ($a, $b) {
            return strtotime($a["VL_DODATELIVR"]) - strtotime($b["VL_DODATELIVR"]);
        });
        return array_reverse($mergedData);
    }

    public function TotalCAparcplanifiénonconfirmé()
    {
        $DPPlanifiée = $this->fetchData("apieDpPlanifiée", "sipicDpPlanifiée");
        $DPSaisieNonTransmise = $this->fetchData("apieDpPlanifiée", "sipicDpPlanifiée");
        $DPAPlanifiée = $this->fetchData("apieDpAPlanifiée", "sipicDpAPlanifiée");
        $mergedData = [];

        foreach ($DPPlanifiée as $DPPlanifiéeItem) {
            $date = $DPPlanifiéeItem["VL_DODATELIVR"];
            if (!isset($mergedData[$date])) {
                $mergedData[$date] = [
                    "VL_DODATELIVR" => $date,
                    "sum" => (float)$DPPlanifiéeItem["sum"]
                ];
            } else {
                $mergedData[$date]["sum"] += (float)$DPPlanifiéeItem["sum"];
            }
        }

        foreach ($DPSaisieNonTransmise as $DPSaisieNonTransmiseItem) {
            $date = $DPSaisieNonTransmiseItem["VL_DODATELIVR"];
            if (!isset($mergedData[$date])) {
                $mergedData[$date] = [
                    "VL_DODATELIVR" => $date,
                    "sum" => (float)$DPSaisieNonTransmiseItem["sum"]
                ];
            } else {
                $mergedData[$date]["sum"] += (float)$DPSaisieNonTransmiseItem["sum"];
            }
        }

        foreach ($DPAPlanifiée as $DPAPlanifiéeItem) {
            $date = $DPAPlanifiéeItem["VL_DODATELIVR"];
            if (!isset($mergedData[$date])) {
                $mergedData[$date] = [
                    "VL_DODATELIVR" => $date,
                    "sum" => (float)$DPAPlanifiéeItem["sum"]
                ];
            } else {
                $mergedData[$date]["sum"] += (float)$DPAPlanifiéeItem["sum"];
            }
        }

        // Transformer le tableau associatif en tableau numérique
        $mergedData = array_values($mergedData);

        // Trier le tableau par date
        usort($mergedData, function ($a, $b) {
            return strtotime($a["VL_DODATELIVR"]) - strtotime($b["VL_DODATELIVR"]);
        });
        return array_reverse($mergedData);
    }

    public function TotalCAPlanifierConfirmer()
    {
        $DPPlanifiée = $this->fetchData("apieBLPrepare", "sipicBLPrepare");
        $DPSaisieNonTransmise = $this->fetchData("apieBLIntervEnCours", "sipicBLIntervEnCours");
        $DPAPlanifiée = $this->fetchData("apieBlAfacturerBloquer", "sipicBlAfacturerBloquer");
        $BLAfacturer = $this->fetchData("apieBlAfacturer", "sipicBlAfacturer");
        $BLNonRetourner = $this->fetchData("apieBlNonRetourner", "sipicBlNonRetourner");

        $mergedData = [];

        foreach ($DPPlanifiée as $DPPlanifiéeItem) {
            $date = $DPPlanifiéeItem["VL_DODATELIVR"];
            if (!isset($mergedData[$date])) {
                $mergedData[$date] = [
                    "VL_DODATELIVR" => $date,
                    "sum" => (float)$DPPlanifiéeItem["sum"]
                ];
            } else {
                $mergedData[$date]["sum"] += (float)$DPPlanifiéeItem["sum"];
            }
        }

        foreach ($DPSaisieNonTransmise as $DPSaisieNonTransmiseItem) {
            $date = $DPSaisieNonTransmiseItem["VL_DODATELIVR"];
            if (!isset($mergedData[$date])) {
                $mergedData[$date] = [
                    "VL_DODATELIVR" => $date,
                    "sum" => (float)$DPSaisieNonTransmiseItem["sum"]
                ];
            } else {
                $mergedData[$date]["sum"] += (float)$DPSaisieNonTransmiseItem["sum"];
            }
        }

        foreach ($DPAPlanifiée as $DPAPlanifiéeItem) {
            $date = $DPAPlanifiéeItem["VL_DODATELIVR"];
            if (!isset($mergedData[$date])) {
                $mergedData[$date] = [
                    "VL_DODATELIVR" => $date,
                    "sum" => (float)$DPAPlanifiéeItem["sum"]
                ];
            } else {
                $mergedData[$date]["sum"] += (float)$DPAPlanifiéeItem["sum"];
            }
        }

        foreach ($BLAfacturer as $BLAfacturerItem) {
            $date = $BLAfacturerItem["VL_DODATELIVR"];
            if (!isset($mergedData[$date])) {
                $mergedData[$date] = [
                    "VL_DODATELIVR" => $date,
                    "sum" => (float)$BLAfacturerItem["sum"]
                ];
            } else {
                $mergedData[$date]["sum"] += (float)$DPAPlanifiéeItem["sum"];
            }
        }

        foreach ($BLNonRetourner as $BLNonRetournerItem) {
            $date = $BLNonRetournerItem["VL_DODATELIVR"];
            if (!isset($mergedData[$date])) {
                $mergedData[$date] = [
                    "VL_DODATELIVR" => $date,
                    "sum" => (float)$BLNonRetournerItem["sum"]
                ];
            } else {
                $mergedData[$date]["sum"] += (float)$DPAPlanifiéeItem["sum"];
            }
        }

        // Transformer le tableau associatif en tableau numérique
        $mergedData = array_values($mergedData);

        // Trier le tableau par date
        usort($mergedData, function ($a, $b) {
            return strtotime($a["VL_DODATELIVR"]) - strtotime($b["VL_DODATELIVR"]);
        });
        return array_reverse($mergedData);
    }

    public function TotalCAaFacturer()
    {
        $totalNonConfirme = $this->TotalCAparcplanifiénonconfirmé();
        $totalConfirme = $this->TotalCAPlanifierConfirmer();
        $mergedData = [];

        foreach ($totalNonConfirme as $totalNonConfirmeItem) {
            $date = $totalNonConfirmeItem["VL_DODATELIVR"];
            if (!isset($mergedData[$date])) {
                $mergedData[$date] = [
                    "VL_DODATELIVR" => $date,
                    "sum" => (float)$totalNonConfirmeItem["sum"]
                ];
            } else {
                $mergedData[$date]["sum"] += (float)$totalNonConfirmeItem["sum"];
            }
        }

        foreach ($totalConfirme as $totalConfirmeItem) {
            $date = $totalConfirmeItem["VL_DODATELIVR"];
            if (!isset($mergedData[$date])) {
                $mergedData[$date] = [
                    "VL_DODATELIVR" => $date,
                    "sum" => (float)$totalConfirmeItem["sum"]
                ];
            } else {
                $mergedData[$date]["sum"] += (float)$totalConfirmeItem["sum"];
            }
        }

        // Transformer le tableau associatif en tableau numérique
        $mergedData = array_values($mergedData);

        // Trier le tableau par date
        usort($mergedData, function ($a, $b) {
            return strtotime($a["VL_DODATELIVR"]) - strtotime($b["VL_DODATELIVR"]);
        });
        return array_reverse($mergedData);
    }

    public function AllDate()
    {
        $totalNonConfirme = $this->TotalCAparcplanifiénonconfirmé();
        $totalConfirme = $this->TotalCAPlanifierConfirmer();
        $mergedData = [];

        foreach ($totalNonConfirme as $totalNonConfirmeItem) {
            $date = $totalNonConfirmeItem["VL_DODATELIVR"];
            if (!isset($mergedData[$date])) {
                $mergedData[$date] = [
                    "VL_DODATELIVR" => $date,
                    "sum" => (float)$totalNonConfirmeItem["sum"]
                ];
            } else {
                $mergedData[$date]["sum"] += (float)$totalNonConfirmeItem["sum"];
            }
        }

        foreach ($totalConfirme as $totalConfirmeItem) {
            $date = $totalConfirmeItem["VL_DODATELIVR"];
            if (!isset($mergedData[$date])) {
                $mergedData[$date] = [
                    "VL_DODATELIVR" => $date,
                    "sum" => (float)$totalConfirmeItem["sum"]
                ];
            } else {
                $mergedData[$date]["sum"] += (float)$totalConfirmeItem["sum"];
            }
        }

        // Transformer le tableau associatif en tableau numérique avec les clés "VL_DODATELIVR"
        $mergedData = array_values($mergedData);

        // Trier le tableau par date
        usort($mergedData, function ($a, $b) {
            return strtotime($a["VL_DODATELIVR"]) - strtotime($b["VL_DODATELIVR"]);
        });

        // // Créer un tableau associatif avec les clés "VL_DODATELIVR"
        // $result = [];
        // foreach ($mergedData as $item) {
        //     $result[$item["VL_DODATELIVR"]] = $item["sum"];
        // }

        return array_reverse($mergedData);
    }
}
