<?php
session_start();
if (empty($_SESSION['username']) && empty($_SESSION['mdp'])) {
    header('Location: /COUD/codif/');
    exit();
}
require_once __DIR__ . '/vendor/autoload.php';
require('../../../traitement/fonction.php');
$mpdf = new \Mpdf\Mpdf();
$test = getLastSituation($_SESSION['username']);
$html = '
<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="style.css">
        <title>Document</title>
    </head>
    <body>

    <header>
         <div class="row">
             <div class="col-md-4">
                 <p>Ministére de l\'Enseignement<br>Supérieur et de la Recherche <br/>
                     <b>________________________</b><br/>
                     <b> CENTRE DES ŒUVRES UNIVERSITAIRES DE DAKAR</b>
                 </p>
             </div>
         </div>           
     </header>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1 class="text-center">RECU PAIEMENT</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <p>Je soussigné(e), Madame/Monsieur :.....'.$test['prenom_user'].'  '.$test['nom_user'].'....................................................</p>
                <p>Certifie avoir reçu la somme de..........'.$test['montant_recu'].'...............................................................Fr cfa</p>
                <p>Comme paiement pour..............'.$test['libelle'].'......Le ................'.dateFromat($test['dateTime_paie']).'...............</p>
                <p>De la part de Madame/Monsieur......'.$test['prenoms'].'.......'.$test['nom'].'........................................</p>
            </div>
        </div>
    </div>
</body>';

// Charger le contenu HTML dans mPDF
$mpdf->WriteHTML($html);

// Générer le PDF et le sortir
$mpdf->Output('etat encaissement', \Mpdf\Output\Destination::INLINE);
