<?php
session_start();
if (empty($_SESSION['username']) && empty($_SESSION['mdp'])) {
    header('Location: /COUD/codif/');
    exit();
}
require('../../traitement/fonction.php');
$tableau_data_etudiant = getAllSituation($_SESSION['num_etu']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../../assets/css/main.css">
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <link rel="stylesheet" href="../../assets/bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="../../assets/bootstrap/js/bootstrap.min.js">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    <?php include('../../head.php'); ?>
    <div class="container">
        <?php
        $montantLit = getMontantPaye($_SESSION['num_etu']);
        if (isIndivLitStudent($_SESSION['num_etu']) == "oui") {
            $indiv = 'Lit individuel';
        } else {
            $indiv = 'Lit normal';
        }
        // Recuperation du date debut de codification du niveauFormation de l'etudiant
        $date_debut = getAllDelai("depart", info($_SESSION['num_etu'])[7]);
        $date_debut = dateFromat($date_debut['data_limite']);
        $date_ferme = getAllDelai("fermeture", info($_SESSION['num_etu'])[7]);
        $date_ferme = dateFromat($date_ferme['data_limite']);
        // Calcul nombre de mois entre date debut et date systeme
        $nbr_mois_systeme_debut = calcul_nbreMois($date_debut, $date_ferme);

        $tableau_situation_paye = getAllSituation($_SESSION['num_etu']);
        $i = 0;
        while ($situation = mysqli_fetch_array($tableau_situation_paye)) {
            $libelle[$i] = $situation['libelle'];
            $i++;
            $_montant_restant = $situation['restant'];
        }
        if (isset($libelle)) {
            global $nbr_mois_impaye;
            $chaine_libelle = json_encode($libelle);
            $chaine_libelle = str_replace(['[', ']', '"', 'CAUTION'], ' ', $chaine_libelle);
            $nbr_mois_payer = countWords($chaine_libelle);
            $nbr_mois_impaye = $nbr_mois_systeme_debut - $nbr_mois_payer;
        } else {
            global $nbr_mois_impaye;
            $nbr_mois_payer = 0;
            $nbr_mois_impaye = $nbr_mois_systeme_debut;
        }


        if (isset($_montant_restant)) {
            global $_a_payer;
            $_a_payer = $_montant_restant;
        } else {
            global $_a_payer;
            $_a_payer = getMontantPaye($_SESSION['num_etu']);
        }
        ?>
        <div class="row">
            <div class="col-md-3">
                <table class="table table-hover">
                    <tr class="table-primary" style="font-size: 16px; font-weight: 400; background-color:#3777b0;">
                        <td>Type de lit : <?= $indiv; ?> </td>
                    </tr>
                    <tr class="table-secondary" style="font-size: 16px; font-weight: 400; background-color:#3777b0;">
                        <td>Lit mensuelle : <?= $montantLit; ?> Fcfa</td>
                    </tr>
                    <tr class="table-info" style="font-size: 16px; font-weight: 400; background-color:#3777b0;">
                        <td>Mois impayer : <?= $nbr_mois_impaye; ?> mois</td>
                    </tr>
                    <tr class="table-primary" style="font-size: 16px; font-weight: 400; background-color:#3777b0;">
                        <td>Montant à payer : <?= $_a_payer; ?> Fcfa</td>
                        <!-- </tr>
                    <tr class="table-dark" style="font-size: 16px; font-weight: 400; background-color:#3777b0;">
                        <td>SOLDE :</td>
                    </tr> -->
                </table>
            </div>
            <div class="col-md-9">
                <table class="table table-hover">
                    <tr class="table-success" style="font-size: 16px; font-weight: 400; background-color:#3777b0;">
                        <td>N°</td>
                        <td>Date Paie</td>
                        <td>Libelle</td>
                        <td>Montant</td>
                        <td>Recu</td>
                        <td>Restant</td>
                        <td>Agent ACP</td>
                    </tr>
                    <?php while ($row = mysqli_fetch_array($tableau_data_etudiant)) {
                    ?>
                        <tr class="table-secondary" style="font-size: 14px;">
                            <td><?= $row['id_paie'] ?></td>
                            <td><?= dateFromat($row['dateTime_paie']) ?></td>
                            <td><?= $row['libelle'] ?></td>
                            <td><?= $row['montant'] ?></td>
                            <td><?= $row['montant_recu'] ?></td>
                            <td><?= $row['restant'] ?></td>
                            <td><?= $row[2] ?></td>
                        </tr>
                    <?php } ?>
                </table>
            </div>
        </div>
    </div>
    <script src="../../assets/js/jquery-3.2.1.min.js"></script>
    <script src="../../assets/js/plugins.js"></script>
    <script src="../../assets/js/main.js"></script>
</body>

</html>