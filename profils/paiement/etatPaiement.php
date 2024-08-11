<?php
session_start();
if (empty($_SESSION['username']) && empty($_SESSION['mdp'])) {
    header('Location: /COUD/codif/');
    exit();
}
// if (empty($_SESSION['classe'])) {
//     header('location: /COUD/codif/profils/personnels/niveau.php');
//     exit();
// }
//connexion à la base de données
include('../../traitement/fonction.php');
connexionBD();
// Sélectionnez les options à partir de la base de données avec une pagination
include('../../traitement/requete.php');

// Comptez le nombre total d'options dans la base de données details lits affecter (quotas)
// $total_pagess = getLitByQuotas($_SESSION['classe'], $_SESSION['sexe']);
$countIn = 0;
if (isset($_GET['erreurValider'])) {
    $_SESSION['erreurValider'] = $_GET['erreurValider'];
} else {
    $_SESSION['erreurValider'] = '';
}
if (isset($_GET['successValider'])) {
    $_SESSION['successValider'] = $_GET['successValider'];
} else {
    $_SESSION['successValider'] = '';
}
if (isset($_GET['erreurNonTrouver'])) {
    $_SESSION['erreurNonTrouver'] = $_GET['erreurNonTrouver'];
} else {
    $_SESSION['erreurNonTrouver'] = '';
}
if (isset($_GET['erreurForclo'])) {
    $_SESSION['erreurForclo'] = $_GET['erreurForclo'];
} else {
    $_SESSION['erreurForclo'] = '';
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>COUD: CODIFICATION</title>
    <!-- CSS================================================== -->
    <link rel="stylesheet" href="../../assets/css/main.css">
    <!-- script================================================== -->
    <script src="../../assets/js/modernizr.js"></script>
    <script src="../../assets/js/pace.min.js"></script>
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <link rel="stylesheet" href="../../assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../assets/bootstrap/js/bootstrap.min.js">
    <link rel="stylesheet" href="../../assets/bootstrap/js/bootstrap.bundle.min.js">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    <?php include('../../head.php'); ?>
    <div class="container">
        <div class="row ">
            <div class="text-center">
                <h2>Etat des encaissements</h2>
            </div>
        </div>
        <br><br> <br>

        <!-- Interval date -->
        <form  action="requestEtatPaiement.php"method="POST"  >
            <div class=" col-md-9 d-flex justify-content-center; align-item:center " >
                <div class=" " style="margin: auto; font-size: 16px; font-weight: 400;">
                    <label for="start">date debut: </label>
                    
                    <input type="date" id="date_debut"  name="date_debut" value=""/>
                </div>
                <div class="" style="margin: auto; font-size: 16px; font-weight: 400;"> 
                    <label for="start">date fin:</label>
                    <input type="date" id="date_fin" name="date_fin"  value="<?php echo isset($_POST['date_fin']) ? htmlspecialchars($_POST['date_fin']) : ''; ?>"  />
                </div>
                <div class="" style="margin: auto; font-size: 16px; font-weight: 400;"> 
                    <button id="submitBtn" name="rechercher" type="submit" class="btn btn-success" style="font-size: 16px; font-weight: 400;" >Rechercher</button>
                </div>
                <div class="" style="margin: auto; "> 
                    <button id="submitBtn" name="imprimer" type="submit" class="btn btn-info" style="font-size: 16px; font-weight: 400;" >Imprimer</button>
                </div>
            </div>
        </form>  <br><br>
        
        <!-- table -->
         <div class="row">
            <div class="col-md-12">
                <div class="text-center ">
                <?php 
                    if(isset($_SESSION['debut']) && $_SESSION['fin']){
                        $timeD= strtotime($_SESSION['debut']);
                        $timeF= strtotime($_SESSION['fin']);
                        $dateD = date('d-m-Y',$timeD);
                        $dateF = date('d-m-Y',$timeF);
                        echo "Date debut: ".$dateD." et Date fin: ".$dateF;
                    }
                    ?>
                </div>
            </div>
         </div>
        <div>
            <table class="table table-hover">
                <tr class="table-secondary" style="font-size: 16px; font-weight: 400;">
                    <th>Num Étudiant</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Montant</th>
                    <th>Date</th>
                </tr>
                <?php if (!empty($_GET['data'])) : ?>
                    <?php foreach ($_GET['data'] as $row) :?>
                        <tr style="font-size: 14px;">
                            <td class="text-center"><?php echo htmlspecialchars($row['num_etu']); ?></td>
                            <td class="text-center"><?php echo htmlspecialchars($row['nom']); ?></td>
                            <td class="text-center"><?php echo htmlspecialchars($row['prenoms']); ?></td>
                            <td class="text-center"><?php echo htmlspecialchars($row['montant']); ?></td>
                            <td class="text-center"><?php echo htmlspecialchars($row['dateTime_paie']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <?php if (!empty($_GET['data'])) ?>
                        <td colspan="6">Aucun résultat trouvé</td>
                    </tr>
                <?php endif; ?>
            </table>
        </div>
    </div>   
    
        <script src="../../assets/js/jquery-3.2.1.min.js"></script>
        <script src="../../assets/js/plugins.js"></script>
        <script src="../../assets/js/main.js"></script>

        <!-- JavaScript de Bootstrap (assurez-vous d'ajuster le chemin si nécessaire) -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
<script src="../../assets/js/script.js"></script>
</body>

</html>