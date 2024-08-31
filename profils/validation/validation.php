<?php
session_start();
if (empty($_SESSION['username']) && empty($_SESSION['mdp'])) {
    header('Location: /COUD/codif/');
    exit();
}
include('../../traitement/fonction.php');
connexionBD();
include('../../traitement/requete.php');
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
    <link rel="stylesheet" href="../../assets/css/main.css">
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
        <div class="row">
            <div class="text-center">
                <h2>Validation des lits</h2>
            </div>
        </div>
        <div class="row" style="justify-content: center;">
            <?php if ($_SESSION['erreurValider']) { ?>
                <div class="col-md-6">
                    <div class="alert alert-warning" role="alert">
                        <?= $_SESSION['erreurValider']; ?>
                    </div>
                </div>
            <?php } elseif ($_SESSION['successValider']) { ?>
                <div class="col-md-6">
                    <div class="alert alert-success" role="alert">
                        <?= $_SESSION['successValider']; ?>
                    </div>
                </div>
            <?php } elseif ($_SESSION['erreurNonTrouver']) { ?>
                <div class="col-md-6">
                    <div class="alert alert-danger" role="alert">
                        <?= $_SESSION['erreurNonTrouver']; ?>
                    </div>
                </div>
            <?php } elseif ($_SESSION['erreurForclo']) { ?>
                <div class="col-md-6">
                    <div class="alert alert-dark" role="alert">
                        <?= $_SESSION['erreurForclo']; ?>
                    </div>
                </div>
            <?php } ?>
            <form action="requestValidation.php" method="POST" style="display: flex;justify-content: center">
                <div class="row">
                    <div class="col-md-10">
                        <input id="numEtudiant" name="numEtudiant" type="text" class="form-control" placeholder="NUMERO CARTE ETUDIANT" oninput="checkInput()" onblur="validateInput()">
                        <script>
                            var inputElement = document.getElementById('numEtudiant');
                            inputElement.addEventListener('input', function() {
                                var texte = inputElement.value;
                                var texteMajuscule = texte.toUpperCase();
                                inputElement.value = texteMajuscule;
                                var affichageElement = document.getElementById('affichage');
                                affichageElement.textContent = texteMajuscule;
                            });
                        </script>
                    </div>
                    <div class="col-md-2">
                        <button id="submitBtn" type="submit" class="btn btn-primary" disabled>Rechercher</button>
                    </div>
                </div>
            </form>
        </div><br><br>
        <div class="row">
            <div class="col-md-12">
                <ul class="options">
                    <?php
                    if (isset($_GET['data'])) {
                        $data = $_GET['data'];
                        if (isset($_GET['statut'])) { ?>
                            <form action="requestValidation.php" method="POST">
                                <div class="row" style="display: flex;justify-content: center;color:black;">
                                    <div class="col-md-4 mb-3">
                                        <input type="text" class="form-control" placeholder="Prénom : <?= $data['prenoms'] ?>" disabled>
                                        <input class="form-control" name="id_etu" value="<?= $data['id_etu'] ?>" style="visibility: hidden;">
                                    </div>
                                    <div class="col-md-4">
                                        <input class="form-control" placeholder="Nom : <?= $data['nom'] ?>" disabled>
                                        <?php if (isset($_GET['idLit'])) { ?>
                                            <input class="form-control" name="idLit" value="<?= $_GET['idLit'] ?>" style="visibility: hidden;">
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="row" style="display: flex;justify-content: center;color:black;">
                                    <div class="col-md-4 mb-3">
                                        <input class="form-control" placeholder="Faculté : <?= $data['etablissement'] ?>" disabled>
                                    </div>
                                    <div class="col-md-4">
                                        <input class="form-control" placeholder="Classe : <?= $data['niveauFormation'] ?>" disabled>
                                    </div>
                                </div><br>
                                <div class="row" style="display: flex;justify-content: center;color:black;">
                                    <div class="col-md-4 mb-3">
                                        <input class="form-control" placeholder="Moyenne : <?= $data['moyenne'] ?>" disabled>
                                    </div>
                                    <div class="col-md-4">
                                        <input class="form-control" placeholder="Statut : <?= $_GET['statut'] ?>" disabled>
                                    </div>
                                </div><br>
                                <?php
                                if (isset($_GET['statut']) && $_GET['statut'] != 'forclu') {
                                ?>
                                    <button class="btn btn-success" type="button" data-toggle="modal" data-target="#confirmationModal">VALIDER</button>
                                <?php
                                } else { ?>
                                    <div class="row" style="display: flex;justify-content: center;color:black;">
                                        <div class="col-md-4 mb-3">
                                            <input class="form-control" placeholder="Type : <?= $data['type'] ?>" disabled>
                                        </div>
                                        <div class="col-md-4">
                                            <input class="form-control" placeholder="Nature : <?= $data['motif_manuel'] ?>" disabled>
                                        </div>
                                    </div><br>
                                    <a class="btn btn-secondary" href="/COUD/codif/profils/validation/validation.php" type="button">RETOUR</a>
                                <?php } ?>
                                <div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog" aria-labelledby="confirmationModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="confirmationModalLabel">Confirmation</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                Êtes-vous sûr de vouloir effectuer cette action ?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                                                <button type="submit" class="btn btn-primary">Confirmer</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        <?php } else {
                        ?>
                            <form action="requestValidation.php" method="POST">
                                <div class="row" style="display: flex;justify-content: center;color:black;">
                                    <div class="col-md-4     mb-3">
                                        <input type="text" class="form-control" placeholder="Prénom : <?= $data['prenoms'] ?>" disabled>
                                        <input class="form-control" name="valide" value="<?= $data['0'] ?>" style="visibility: hidden;">
                                    </div>
                                    <div class="col-md-4    ">
                                        <input class="form-control" placeholder="Nom : <?= $data['nom'] ?>" disabled>
                                    </div>
                                </div>
                                <div class="row" style="display: flex;justify-content: center;color:black;">
                                    <div class="col-md-4     mb-3">
                                        <input class="form-control" placeholder="Faculté : <?= $data['etablissement'] ?>" disabled>
                                    </div>
                                    <div class="col-md-4    ">
                                        <input class="form-control" placeholder="Classe : <?= $data['niveauFormation'] ?>" disabled>
                                    </div>
                                </div><br>
                                <div class="row" style="display: flex;justify-content: center;color:black;">
                                    <div class="col-md-4     mb-3">
                                        <input class="form-control" placeholder="CNI : <?= $data['numIdentite'] ?>" disabled>
                                    </div>
                                    <div class="col-md-4    ">
                                        <input class="form-control" placeholder="Campus : <?= $data['campus'] ?>" disabled>
                                    </div>
                                </div><br>
                                <div class="row" style="display: flex;justify-content: center;color:black;">
                                    <div class="col-md-4     mb-3">
                                        <input class="form-control" placeholder="Pavillon : <?= $data['pavillon'] ?>" disabled>
                                    </div>
                                    <div class="col-md-4    ">
                                        <input class="form-control" placeholder="Lit : <?= $data['lit'] ?>" disabled>
                                    </div>
                                </div>
                                <?php if ($data['migration_status'] == 'Migré') { ?>
                                    <div class="row" style="display: flex;justify-content: center;color:black;">
                                        <div class="col-md-4     mb-3">
                                            <input class="form-control" placeholder="Validé le <?= dateFromat($data['dateTime_val']) ?>" disabled>
                                        </div>
                                    </div>
                                    <a class="btn btn-secondary" href="/COUD/codif/profils/validation/validation.php" type="button">RETOUR</a>
                                <?php } else { ?>
                                    <button class="btn btn-success" type="button" data-toggle="modal" data-target="#confirmationModal">VALIDER</button>
                                <?php } ?>
                                <div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog" aria-labelledby="confirmationModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="confirmationModalLabel">Confirmation</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                Êtes-vous sûr de vouloir effectuer cette action ?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                                                <button type="submit" class="btn btn-primary">Confirmer</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                    <?php }
                    } ?>
                </ul>
            </div>
        </div>
        <script src="../../assets/js/jquery-3.2.1.min.js"></script>
        <script src="../../assets/js/plugins.js"></script>
        <script src="../../assets/js/main.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
<script src="../../assets/js/script.js"></script>
</body>

</html>