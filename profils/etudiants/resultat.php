<?php
session_start();
if (empty($_SESSION['username']) && empty($_SESSION['mdp'])) {
    header('Location: /COUD/codif/');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>COUD: CODIFICATION </title>
    <link rel="stylesheet" href="../../assets/css/main.css">
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <link rel="stylesheet" href="../../assets/bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="../../assets/bootstrap/js/bootstrap.min.js">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    <?php
    include('../../head.php');

    $quota = getQuotaClasse($_SESSION['classe'], $_SESSION['sexe'])['COUNT(*)'];
    // $listeClasse = getStatutStudentByQuota($quota, $_SESSION['classe'], $_SESSION['sexe']);
    // $listeTitulaire = getStatutStudentByQuota($quota, $_SESSION['classe'], $_SESSION['sexe']);
    $listeDelai1 = getAllDelai('choix');
    $listeDelai2 = getAllDelai('validation');
    $listeDelai3 = getAllDelai('paiement');
    $date_limite_choix = dateFromat($listeDelai1['data_limite']);
    $date_limite_val = dateFromat($listeDelai2['data_limite']);
    $date_limite_paye = dateFromat($listeDelai3['data_limite']);
    $date_sys = dateFromat(date('Y-m-d'));

    // Appel de la fonction liste total d'etudiant avec leurs status
    $tableau_data_etudiant = getAllDatastudentStatus($quota, $_SESSION['classe'], $_SESSION['sexe']);
    if ($date_sys >= $date_limite_choix) {
        if ($date_sys < $date_limite_val) {
            $forclos = getAllForclu();
            if ($forclos->num_rows != 0) {
                $compt = 0;
                while ($row = mysqli_fetch_array($forclos)) {
                    $dateFor = dateFromat($row['dateTime_for']);
                    if (($row['type'] == 'auto')) {
                        if ($dateFor >= $date_limite_choix) {
                            if ($row['nature'] == 'choix') {
                                $compt++;
                            }
                        }
                    }
                }
                if ($compt == 0) {
                    for ($i = 0; $i < count($tableau_data_etudiant); $i++) {
                        if ($tableau_data_etudiant[$i]['statut'] == 'attributaire') {
                            $choix_lit = getChoixLitByStudent($tableau_data_etudiant[$i]['num_etu']);
                            if ($choix_lit == "VEUILLER CHOISIR UN LIT POUR DEMMARRER VOTRE CODIFICATION, <a href='/coud/codif/profils/etudiants/codifier.php'>CLIQUER ICI</a>") {
                                addForclu($tableau_data_etudiant[$i]['id_etu'], $listeDelai1['id_delai']);
                            }
                        }
                    }
                }
            } else {
                for ($i = 0; $i < count($tableau_data_etudiant); $i++) {
                    if ($tableau_data_etudiant[$i]['statut'] == 'attributaire') {
                        $choix_lit = getChoixLitByStudent($tableau_data_etudiant[$i]['num_etu']);
                        if ($choix_lit == "VEUILLER CHOISIR UN LIT POUR DEMMARRER VOTRE CODIFICATION, <a href='/coud/codif/profils/etudiants/codifier.php'>CLIQUER ICI</a>") {
                            addForclu($tableau_data_etudiant[$i]['id_etu'], $listeDelai1['id_delai']);
                        }
                    }
                }
            }
        }
        if ($date_sys >= $date_limite_val) {
            if ($date_sys < $date_limite_paye) {
                $forclos_validation = getAllForclu();
                if ($forclos_validation->num_rows != 0) {
                    $compt = 0;
                    while ($row = mysqli_fetch_array($forclos_validation)) {
                        $dateFor = dateFromat($row['dateTime_for']);
                        if (($row['type'] == 'auto')) {
                            if ($dateFor >= $date_limite_choix) {
                                if ($row['nature'] == 'validation') {
                                    $compt++;
                                }
                            }
                        }
                    }
                    if ($compt == 0) {
                        for ($i = 0; $i < count($tableau_data_etudiant); $i++) {
                            if ($tableau_data_etudiant[$i]['statut'] == 'attributaire') {
                                $choix_lit = getValidateLitByStudent2($tableau_data_etudiant[$i]['num_etu']);
                                if ($choix_lit == "VEUILLEZ-VOUS RAPPROCHER DU SERVICE DE L'HEBERGEMENT POUR COMPLETER VOTRE CODIFICATION !!!") {
                                    addForclu($tableau_data_etudiant[$i]['id_etu'], $listeDelai2['id_delai']);
                                }
                            }
                        }
                    }
                } else {
                    for ($i = 0; $i < count($tableau_data_etudiant); $i++) {
                        if ($tableau_data_etudiant[$i]['statut'] == 'attributaire') {
                            $choix_lit = getValidateLitByStudent2($tableau_data_etudiant[$i]['num_etu']);
                            if ($choix_lit == "VEUILLEZ-VOUS RAPPROCHER DU SERVICE DE L'HEBERGEMENT POUR COMPLETER VOTRE CODIFICATION !!!") {
                                addForclu($tableau_data_etudiant[$i]['id_etu'], $listeDelai2['id_delai']);
                            }
                        }
                    }
                }
            }
            if ($date_sys >= $date_limite_paye) {
                $forclos_paiement = getAllForclu();
                if ($forclos_paiement->num_rows != 0) {
                    $compt = 0;
                    while ($row = mysqli_fetch_array($forclos_paiement)) {
                        $dateFor_paiement = dateFromat($row['dateTime_for']);
                        if (($row['type'] == 'auto')) {
                            if ($dateFor_paiement >= $date_limite_paye) {
                                if ($row['nature'] == 'paiement') {
                                    $compt++;
                                }
                            }
                        }
                    }
                    if ($compt == 0) {
                        for ($i = 0; $i < count($tableau_data_etudiant); $i++) {
                            if ($tableau_data_etudiant[$i]['statut'] == 'attributaire') {
                                $choix_lit = getValidatePaiementLitBySuppleant($tableau_data_etudiant[$i]['num_etu']);
                                if (!$choix_lit) {
                                    addForclu($tableau_data_etudiant[$i]['id_etu'], $listeDelai3['id_delai']);
                                }
                            }
                        }
                    }
                } else {
                    for ($i = 0; $i < count($tableau_data_etudiant); $i++) {
                        if ($tableau_data_etudiant[$i]['statut'] == 'attributaire') {
                            $choix_lit = getValidatePaiementLitBySuppleant($tableau_data_etudiant[$i]['num_etu']);
                            if (!$choix_lit) {
                                addForclu($tableau_data_etudiant[$i]['id_etu'], $listeDelai3['id_delai']);
                            }
                        }
                    }
                }
            }
        }
    }

    ?>
    <div class="container">
        <!-- <h4>Le quota de votre classe <b><?= $_SESSION['classe']; ?> / <?= $_SESSION['sexe']; ?></b> est de: <?= getQuotaClasse($_SESSION['classe'], $_SESSION['sexe'])['COUNT(*)']; ?> lits.</h4> -->
        <?php
        if (isset($_SESSION['lit_choisi']) && $_SESSION['lit_choisi'] != '') {
        ?>
            <div class="alert alert-success" role="alert">
                Vous avez réservé le lit: <?= $_SESSION['lit_choisi'] ?>
            </div>
            <!-- Appliqué la forclusion -->
            <!-- <h1><?= getValidateLogerByStudent($_SESSION['num_etu']) ?></h1> -->
            <!-- <a href="../convention/pdf.php">Télécharger convention</a> -->
        <?php } else {
            echo "<h2>VOS RESULTATS S'AFFICHE ICI </h2>";
        }
        ?>
        <!-- On tables -->
        <table class="table table-hover">
            <tr class="table-secondary" style="font-size: 16px; font-weight: 400;">
                <td>PRENOM</td>
                <td>NOM</td>
                <td>SESSION</td>
                <td>MOYENNE</td>
                <td>RANG</td>
                <td>STATUT</td>
            </tr>
            <?php
            for ($i = 0; $i < count($tableau_data_etudiant); $i++) {
                if ($tableau_data_etudiant[$i]['statut'] == 'attributaire') { ?>
                    <tr class="table-success" style="font-size: 14px;">
                        <td><?= $tableau_data_etudiant[$i]['prenoms'] ?></td>
                        <td><?= $tableau_data_etudiant[$i]['nom'] ?></td>
                        <td><?= $tableau_data_etudiant[$i]['sessionId'] ?></td>
                        <td><?= $tableau_data_etudiant[$i]['moyenne'] ?></td>
                        <td><?= $tableau_data_etudiant[$i]['rang'] ?></td>
                        <td><?= $tableau_data_etudiant[$i]['statut'] ?></td>
                    </tr>
                <?php
                } else if ($tableau_data_etudiant[$i]['statut'] == 'forclus') { ?>
                    <tr class="table-dark" style="font-size: 14px;">
                        <td><?= $tableau_data_etudiant[$i]['prenoms'] ?></td>
                        <td><?= $tableau_data_etudiant[$i]['nom'] ?></td>
                        <td><?= $tableau_data_etudiant[$i]['sessionId'] ?></td>
                        <td><?= $tableau_data_etudiant[$i]['moyenne'] ?></td>
                        <td><?= $tableau_data_etudiant[$i]['rang'] ?></td>
                        <td><?= $tableau_data_etudiant[$i]['statut'] ?></td>
                    </tr>
                <?php
                } else if ($tableau_data_etudiant[$i]['statut'] == 'suppleant') { ?>
                    <tr class="table-primary" style="font-size: 14px;">
                        <td><?= $tableau_data_etudiant[$i]['prenoms'] ?></td>
                        <td><?= $tableau_data_etudiant[$i]['nom'] ?></td>
                        <td><?= $tableau_data_etudiant[$i]['sessionId'] ?></td>
                        <td><?= $tableau_data_etudiant[$i]['moyenne'] ?></td>
                        <td><?= $tableau_data_etudiant[$i]['rang'] ?></td>
                        <td><?= $tableau_data_etudiant[$i]['statut'] ?></td>
                    </tr>
                <?php } else if ($tableau_data_etudiant[$i]['statut'] == 'non attributaire') { ?>
                    <tr class="table-danger" style="font-size: 14px;">
                        <td><?= $tableau_data_etudiant[$i]['prenoms'] ?></td>
                        <td><?= $tableau_data_etudiant[$i]['nom'] ?></td>
                        <td><?= $tableau_data_etudiant[$i]['sessionId'] ?></td>
                        <td><?= $tableau_data_etudiant[$i]['moyenne'] ?></td>
                        <td><?= $tableau_data_etudiant[$i]['rang'] ?></td>
                        <td><?= $tableau_data_etudiant[$i]['statut'] ?></td>
                    </tr>
            <?php }
            } ?>
        </table>
    </div>
    <script src="../../assets/js/jquery-3.2.1.min.js"></script>
    <script src="../../assets/js/plugins.js"></script>
    <script src="../../assets/js/main.js"></script>
</body>

</html>