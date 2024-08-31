<?php
session_start();
if (empty($_SESSION['username']) && empty($_SESSION['mdp'])) {
    header('Location: /COUD/codif/');
    exit();
}
require_once('../../traitement/fonction.php');


if (isset($_GET['erreurNum_etu'])) {
    $_SESSION['erreurNum_etu'] = $_GET['erreurNum_etu'];
} else {
    $_SESSION['erreurNum_etu'] = '';
}

if (isset($_GET['data'])) {
    $tableau_data_etudiant = $_GET['data'];
} else {
    $num_etu = $_SESSION['num_etu'];
    $quota = getQuotaClasse($_SESSION['classe'], $_SESSION['sexe'])['COUNT(*)'];
    $listeDelai1 = getAllDelai('choix', info($num_etu)[5]);
    $listeDelai2 = getAllDelai('validation', info($num_etu)[5]);
    $listeDelai3 = getAllDelai('paiement', info($num_etu)[5]);
    $date_limite_choix = dateFromat($listeDelai1['data_limite']);
    $date_limite_val = dateFromat($listeDelai2['data_limite']);
    $date_limite_paye = dateFromat($listeDelai3['data_limite']);
    $date_sys = dateFromat(date('Y-m-d'));
    $tableau_data_etudiant = getAllDatastudentStatus($quota, $_SESSION['classe'], $_SESSION['sexe']);
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
    include('../../head.php'); ?>
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <form class="d-flex" role="search" method="POST" action="search_etudiant.php" id="filterForm">
                    <input type="text" class="form-control me-2" placeholder="Search" aria-label="Search" name="search" id="search">
                    <input type="submit" class="btn btn-success" value="Rechercher">
                </form>
                <?php if ($_SESSION['erreurNum_etu']) { ?>
                    <div class="text-danger" role="alert">
                        <h4>
                            <?= $_SESSION['erreurNum_etu']; ?>
                        </h4>
                    </div>
                <?php } ?>
            </div>
        </div>
        <?php
        if (isset($_SESSION['lit_choisi']) && $_SESSION['lit_choisi'] != '') {
        ?>
            <div class="alert alert-success" role="alert">
                Vous avez réservé le lit: <?= $_SESSION['lit_choisi'] ?>
            </div>
            <!-- <a href="../convention/pdf.php">Télécharger convention</a> -->
        <?php } else {
            echo "<h2>VOS RESULTATS S'AFFICHE ICI </h2>";
        }
        ?>
        <table class="table table-hover">
            <tr class="table-secondary" style="font-size: 16px; font-weight: 400;">
                <td>N° Etudiant</td>
                <td>PRENOM</td>
                <td>NOM</td>
                <td>SESSION</td>
                <td>MOYENNE</td>
                <td>RANG</td>
                <td>STATUT</td>
            </tr>
            <?php
            if (isset($_GET['data'])) {
                if ($tableau_data_etudiant['statut'] == 'attributaire') { ?>
                    <tr class="table-success" style="font-size: 14px;">
                        <td><?= $tableau_data_etudiant['num_etu'] ?></td>
                        <td><?= $tableau_data_etudiant['prenoms'] ?></td>
                        <td><?= $tableau_data_etudiant['nom'] ?></td>
                        <td><?= $tableau_data_etudiant['sessionId'] ?></td>
                        <td><?= $tableau_data_etudiant['moyenne'] ?></td>
                        <td><?= $tableau_data_etudiant['rang'] ?></td>
                        <td><?= $tableau_data_etudiant['statut'] ?></td>
                    </tr>
                <?php
                } else if ($tableau_data_etudiant['statut'] == 'forclus') { ?>
                    <tr class="table-dark" style="font-size: 14px;">
                        <td><?= $tableau_data_etudiant['num_etu'] ?></td>
                        <td><?= $tableau_data_etudiant['prenoms'] ?></td>
                        <td><?= $tableau_data_etudiant['nom'] ?></td>
                        <td><?= $tableau_data_etudiant['sessionId'] ?></td>
                        <td><?= $tableau_data_etudiant['moyenne'] ?></td>
                        <td><?= $tableau_data_etudiant['rang'] ?></td>
                        <td><?= $tableau_data_etudiant['statut'] ?></td>
                    </tr>
                <?php
                } else if ($tableau_data_etudiant['statut'] == 'suppleant') { ?>
                    <tr class="table-primary" style="font-size: 14px;">
                        <td><?= $tableau_data_etudiant['num_etu'] ?></td>
                        <td><?= $tableau_data_etudiant['prenoms'] ?></td>
                        <td><?= $tableau_data_etudiant['nom'] ?></td>
                        <td><?= $tableau_data_etudiant['sessionId'] ?></td>
                        <td><?= $tableau_data_etudiant['moyenne'] ?></td>
                        <td><?= $tableau_data_etudiant['rang'] ?></td>
                        <td><?= $tableau_data_etudiant['statut'] ?></td>
                    </tr>
                <?php } else if ($tableau_data_etudiant['statut'] == 'non attributaire') { ?>
                    <tr class="table-danger" style="font-size: 14px;">
                        <td><?= $tableau_data_etudiant['num_etu'] ?></td>
                        <td><?= $tableau_data_etudiant['prenoms'] ?></td>
                        <td><?= $tableau_data_etudiant['nom'] ?></td>
                        <td><?= $tableau_data_etudiant['sessionId'] ?></td>
                        <td><?= $tableau_data_etudiant['moyenne'] ?></td>
                        <td><?= $tableau_data_etudiant['rang'] ?></td>
                        <td><?= $tableau_data_etudiant['statut'] ?></td>
                    </tr>
                    <?php }
            } else {
                for ($i = 0; $i < count($tableau_data_etudiant); $i++) {
                    if ($tableau_data_etudiant[$i]['statut'] == 'attributaire') { ?>
                        <tr class="table-success" style="font-size: 14px;">
                            <td><?= $tableau_data_etudiant[$i]['num_etu'] ?></td>
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
                            <td><?= $tableau_data_etudiant[$i]['num_etu'] ?></td>
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
                            <td><?= $tableau_data_etudiant[$i]['num_etu'] ?></td>
                            <td><?= $tableau_data_etudiant[$i]['prenoms'] ?></td>
                            <td><?= $tableau_data_etudiant[$i]['nom'] ?></td>
                            <td><?= $tableau_data_etudiant[$i]['sessionId'] ?></td>
                            <td><?= $tableau_data_etudiant[$i]['moyenne'] ?></td>
                            <td><?= $tableau_data_etudiant[$i]['rang'] ?></td>
                            <td><?= $tableau_data_etudiant[$i]['statut'] ?></td>
                        </tr>
                    <?php } else if ($tableau_data_etudiant[$i]['statut'] == 'non attributaire') { ?>
                        <tr class="table-danger" style="font-size: 14px;">
                            <td><?= $tableau_data_etudiant[$i]['num_etu'] ?></td>
                            <td><?= $tableau_data_etudiant[$i]['prenoms'] ?></td>
                            <td><?= $tableau_data_etudiant[$i]['nom'] ?></td>
                            <td><?= $tableau_data_etudiant[$i]['sessionId'] ?></td>
                            <td><?= $tableau_data_etudiant[$i]['moyenne'] ?></td>
                            <td><?= $tableau_data_etudiant[$i]['rang'] ?></td>
                            <td><?= $tableau_data_etudiant[$i]['statut'] ?></td>
                        </tr>
            <?php }
                }
            } ?>
        </table>
    </div>
    <script src="../../assets/js/jquery-3.2.1.min.js"></script>
    <script src="../../assets/js/plugins.js"></script>
    <script src="../../assets/js/main.js"></script>
</body>

</html>