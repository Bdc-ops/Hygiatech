<?php

use App\Entity\CombineData;
use App\Entity\CreateExcel;
use App\Entity\Session;

$combinedData = new CombineData();
$excel = new CreateExcel();

$datasets = [
  "Date" => $combinedData->AllDate(),
  "Total CA parc planifié non confirmé" => $combinedData->TotalCAparcplanifiénonconfirmé(),
  "DP saisie non transmise" => $combinedData->fetchData("apieSaisieNonTransmises", "sipicSaisieNonTransmises"),
  "DP à planifier" => $combinedData->fetchData("apieDpAPlanifiée", "sipicDpAPlanifiée"),
  "DP planifié" => $combinedData->fetchData("apieDpPlanifiée", "sipicDpPlanifiée"),
  "Total CA planifié Confirmé" => $combinedData->TotalCAPlanifierConfirmer(),
  "BL préparé" => $combinedData->fetchData("apieBLPrepare", "sipicBLPrepare"),
  "BL interv en cours" => $combinedData->fetchData("apieBLIntervEnCours", "sipicBLIntervEnCours"),
  "BL non retourné" => $combinedData->fetchData("apieBlNonRetourner", "sipicBlNonRetourner"),
  "BL a facturé bloqué" => $combinedData->fetchData("apieBlAfacturerBloquer", "sipicBlAfacturerBloquer"),
  "BL a facturer" => $combinedData->fetchData("apieBlAfacturer", "sipicBlAfacturer"),
  "Total CA Facturé" => $combinedData->TotalCAaFacturer(),
];

if (isset($_POST['data'])) {
  try {
    $i = 1;
    $excel->addData($datasets["Date"], 'date', $i++, 2, 'Date');
    foreach ($datasets as $label => $data) {
      if ($label !== "Date") {
        $excel->addData($data, 'number', $i++, 2, $label);
      }
    }
    $excel->createXlxs();
    exit;
  } catch (Exception $e) {
    echo 'Erreur: ', $e->getMessage(), "\n";
  }
}

if (isset($_POST['disconnect'])) {
  Session::delete('id');
  header('Location: index.php');
  exit();
}
?>

<div class="data" id="dataContainer">

  <form method="POST" class="dataForm">
    <button type="submit" class="refreshButton" name='data' id="downloadData">Télécharger des données</button>
    <button type="submit" name="disconnect" class="disconnectButton" id='loaderBTN'>Se déconnecter</button>
  </form>


  <div id="popup-container" class="popup-container">
    <div class="popup-content">
      <p>Votre téléchargement est en cours, merci de patienter quelques instants</p>
      <button id='closePopup'>Fermer</button>
    </div>
  </div>

</div>

<article class="arrayData">
  <?php
  $i = 0;
  foreach ($datasets as $title => $data) {
    echo '<div class="dataFlex" >
        <div class="columnTitle">' . $title . '</div>';
    if ($title === "Date") {
      foreach ($data as $item) {
        $timestamp = strtotime($item['VL_DODATELIVR']);
        $formattedDate = strftime('%B %Y', $timestamp);
        // Ajouter la classe "darker" chaque deuxième itération
        $class = ($i % 2 == 0) ? 'dataItem darker' : 'dataItem';
        echo '<div class="' . $class . '">' . $formattedDate . '</div>';
        $i++;
      }
    } else {
      $i = 0; // Réinitialiser $i avant la boucle interne
      foreach ($data as $item) {
        $formattedNumber = number_format($item["sum"], 2, ',', ' ');
        // Ajouter la classe "darker" chaque deuxième itération
        $class = ($i % 2 == 0) ? 'dataItem darker' : 'dataItem';
        echo '<div class="' . $class . '">' . $formattedNumber . '</div>';
        $i++;
      }
    }
    echo '</div>';
  }

  ?>

</article>
</div>

<?php
