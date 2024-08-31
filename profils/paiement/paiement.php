<?php
session_start();
if (empty($_SESSION['username']) && empty($_SESSION['mdp'])) {
    header('Location: /COUD/codif/');
    exit();
}
include('../../traitement/fonction.php');
connexionBD();
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
                <h2>Paiement de caution des lits</h2>
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
            <form action="requestPaiement.php" method="POST" style="display: flex;justify-content: center">
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
                        print_r($data['libelle']);
                    ?>
                        <form action="requestPaiement.php" method="POST">
                            <div class="row" style="display: flex;justify-content: center;color:black;">
                                <div class="col-md-4 mb-3">
                                    <input type="text" class="form-control" placeholder="<?= $data['prenoms'] ?>" disabled>
                                    <?php if (isset($data['id_val'])) { ?>
                                        <input class="form-control" name="valide" value="<?= $data['id_val'] ?>" style="visibility: hidden;">
                                    <?php } ?>
                                </div>
                                <div class="col-md-4">
                                    <input class="form-control" placeholder="<?= $data['nom'] ?>" disabled>
                                </div>
                            </div>
                            <div class="row" style="display: flex;justify-content: center;color:black;">
                                <div class="col-md-4 mb-3">
                                    <input class="form-control" placeholder="<?= $data['etablissement'] ?>" disabled>
                                </div>
                                <div class="col-md-4">
                                    <input class="form-control" placeholder="<?= $data['niveauFormation'] ?>" disabled>
                                </div>
                            </div><br>
                            <?php
                            if (isset($_GET['statut']) && $_GET['statut'] == 'forclu') { ?>
                                <div class="row" style="display: flex;justify-content: center;color:black;">
                                    <div class="col-md-4 mb-3">
                                        <input class="form-control" placeholder="Type : <?= $data['type'] ?>" disabled>
                                    </div>
                                    <div class="col-md-4">
                                        <input class="form-control" placeholder="Motif :<?= $data['motif_manuel'] ?>" disabled>
                                    </div>
                                </div><br>
                            <?php } ?>
                            <?php if (isset($data['id_aff'])) { ?>
                                <div class="row" style="display: flex;justify-content: center;color:black;">
                                    <div class="col-md-4 mb-3">
                                        <input class="form-control" placeholder="<?= $data['numIdentite'] ?>" disabled>
                                    </div>
                                    <div class="col-md-4">
                                        <input class="form-control" placeholder="<?= $data['campus'] ?>" disabled>
                                    </div>
                                </div><br>
                                <div class="row" style="display: flex;justify-content: center;color:black;">
                                    <div class="col-md-4 mb-3">
                                        <input class="form-control" placeholder="<?= $data['pavillon'] ?>" disabled>
                                    </div>
                                    <div class="col-md-4">
                                        <input class="form-control" placeholder="<?= $data['lit'] ?>" disabled>
                                    </div>
                                </div>
                                <div class="row" style="display: flex;justify-content: center;color:black;">
                                    <div class="col-md-4 mb-3">
                                        <input class="form-control" placeholder="<?= dateFromat($data['dateTime_aff']) ?>" disabled>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <input class="form-control" placeholder="<?= dateFromat($data['dateTime_val']) ?>" disabled>
                                    </div>
                                </div>
                                <?php
                                if ($data['migration_status'] == 'Migré dans codif_paiement') {
                                ?>
                                    <div class="row" style="display: flex;justify-content: center;color:black;">
                                        <div class="col-md-4 mb-3">
                                            <input type="number" class="form-control" name="montant" placeholder="<?= $data['montant']; ?> Fr cfa" disabled>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <textarea class="form-control" placeholder="<?= $data['libelle']; ?>" name="libelle" disabled></textarea>
                                        </div>
                                    </div>
                                    <div class="row" style="display: flex;justify-content: center;color:black;">
                                        <div class="col-md-4 mb-3">
                                            <input class="form-control" placeholder="<?= dateFromat($data['dateTime_paie']) ?>" disabled>
                                        </div>
                                    </div>
                                    <a class="btn btn-secondary" href="/COUD/codif/profils/paiement/paiement.php" type="button">RETOUR</a>
                                <?php
                                } else {
                                ?>
                                    <div class="row" style="display: flex;justify-content: center;color:black;">
                                        <div class="col-md-4 mb-3">
                                            <input type="number" class="form-control" name="montant" disabled value="<?= getMontantPaye($data['num_etu']); ?>" placeholder="Montant à payer : <?= getMontantPaye($data['num_etu']); ?> fr cfa">
                                            <input type="number" class="form-control" name="montant" value="<?= getMontantPaye($data['num_etu']); ?>" style="visibility: hidden;">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <!-- <div class="form-check"> -->
                                            <input type="checkbox" name="caution" id="caution" class="form-check-input" placeholder="First name">
                                            <label class="form-check-label" for="caution">CAUTION</label>

                                            <input type="checkbox" name="janvier" id="janvier" class="form-check-input" placeholder="First name">
                                            <label class="form-check-label" for="janvier">JANVIER</label>
                                            <input type="checkbox" name="fevrier" id="fevrier" class="form-check-input" placeholder="First name">
                                            <label class="form-check-label" for="fevrier">FEVRIER</label>

                                            <input type="checkbox" name="mars" id="mars" class="form-check-input" placeholder="First name">
                                            <label class="form-check-label" for="mars">MARS</label><br>
                                            <input type="checkbox" name="avril" id="avril" class="form-check-input" placeholder="First name">
                                            <label class="form-check-label" for="avril">AVRIL</label>

                                            <input type="checkbox" name="mai" id="mai" class="form-check-input" placeholder="First name">
                                            <label class="form-check-label" for="mai">MAI</label>
                                            <input type="checkbox" name="juin" id="juin" class="form-check-input" placeholder="First name">
                                            <label class="form-check-label" for="juin">JUIN</label>

                                            <input type="checkbox" name="juillet" id="juillet" class="form-check-input" placeholder="First name">
                                            <label class="form-check-label" for="juillet">JUILLET</label>
                                            <input type="checkbox" name="aout" id="aout" class="form-check-input" placeholder="First name">
                                            <label class="form-check-label" for="aout">AOUT</label>
                                            <!-- </div> -->
                                        </div>
                                    </div>
                                    <!-- <div class="row" style="display: flex;justify-content: center;color:black;">
                                        <div class="col-md-4 mb-3">
                                            <input type="number" class="form-control" name="montant" placeholder="Le montant recu">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <textarea class="form-control" placeholder="Dicriver le libelle ici..." name="libelle"></textarea>
                                        </div>
                                    </div> -->
                                    <button class="btn btn-success" type="button" data-toggle="modal" data-target="#confirmationModal">VALIDER</button>
                                <?php }
                            } else { ?>
                                <a class="btn btn-secondary" href="/COUD/codif/profils/paiement/paiement.php" type="button">RETOUR</a>
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
                    <?php } ?>
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