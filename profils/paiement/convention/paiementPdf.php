<?php
session_start();
if (empty($_SESSION['username']) && empty($_SESSION['mdp'])) {
    header('Location: /COUD/codif/');
    exit();
}

require_once __DIR__ . '/vendor/autoload.php';
require('../../../traitement/fonction.php');

$mpdf = new \Mpdf\Mpdf();

$data[] = getPaiementWithDateInterval($_SESSION['debut'], $_SESSION['fin']);

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

    <div class="container">
    <div class="row">
           <header>
                <div class="row">
                    <div class="col-md-4">
                        <p>Ministére de l\'Enseignement<br>Supérieur et de la Recherche <br/>
                            <u>________________________</u><br/>
                            <b> CENTRE DES ŒUVRES UNIVERSITAIRES DE DAKAR</b>
                        </p>
                    </div>
                    <div class="col-md-8">
                        <div class="data-room">
                            <h4>Annés scolaire : <b>' .  date("Y") - 1 . '/' . date("Y") . '</b></h4>    
                        </div>
                    </div> <br><br><br>

                </div> 
                   <div class="row">
                <div class="col-md-12 text-center">
                    <b> ETAT DES ENCAISSEMENT</b>
                </div><br/>
               
              
                
               </div><br/>               
            </header>
        <table style="width:100%; border-collapse:collapse;">
            <thead>
                <tr>
                    <th style="border: 1px solid #dddddd; text-align: left; padding: 8px;">Num Étudiant</th>
                    <th style="border: 1px solid #dddddd; text-align: left; padding: 8px;">Nom</th>
                    <th style="border: 1px solid #dddddd; text-align: left; padding: 8px;">Prenom</th>
                    <th style="border: 1px solid #dddddd; text-align: left; padding: 8px;">ontant</th>
                    <th style="border: 1px solid #dddddd; text-align: left; padding: 8px;">Date</th>
                </tr>
            </thead>
            <tbody>
            ';
            foreach ($data as $row) {
            $html .= '
               <tr>
                    <td style="border: 1px solid #dddddd; text-align: left; padding: 8px;">' . htmlspecialchars($row['num_etu']) . '</td>
                    <td style="border: 1px solid #dddddd; text-align: left; padding: 8px;">' . htmlspecialchars($row['nom']) . '</td>
                    <td style="border: 1px solid #dddddd; text-align: left; padding: 8px;">' . htmlspecialchars($row['prenoms']) . '</td>
                    <td style="border: 1px solid #dddddd; text-align: left; padding: 8px;">' . htmlspecialchars($row['montant']) . '</td>
                    <td style="border: 1px solid #dddddd; text-align: left; padding: 8px;">' . htmlspecialchars($row['dateTime_paie']) . '</td>
               </tr>';
            }

            $html .= '
            </tbody>
        </table>
        </div>
    </div>    
</body>';

// Charger le contenu HTML dans mPDF
$mpdf->WriteHTML($html);

// Générer le PDF et le sortir
$mpdf->Output('etat encaissement', \Mpdf\Output\Destination::INLINE);
