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
    <title>COUD: CODIFICATION</title>
    <link rel="stylesheet" href="../../assets/css/main.css">
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <script src="../assets/js/modernizr.js"></script>
    <script src="../assets/js/pace.min.js"></script>
    <link rel="stylesheet" href="../../assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../assets/bootstrap/js/bootstrap.min.js">
    <link rel="stylesheet" href="../../assets/bootstrap/js/bootstrap.bundle.min.js">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        .form-check {
            display: flex;
            align-items: center;
        }

        input[type=checkbox] {
            position: relative;
            right: -4%;
        }

        .form-check-label {
            margin-left: 15px;
            margin-top: 15px;
        }

        form {
            box-shadow: 0 0 3px rgba(0, 0, 0, 0.2);
            padding: 20px;
        }
    </style>
</head>
<?php
if (isset($_GET['successAdd'])) {
    $_SESSION['successAdd'] = $_GET['successAdd'];
} else {
    $_SESSION['successAdd'] = '';
}
if (isset($_GET['erreurAdd'])) {
    $_SESSION['erreurAdd'] = $_GET['erreurAdd'];
} else {
    $_SESSION['erreurAdd'] = '';
}
?>

<body>
    <?php include('../../head.php'); ?>
    <div class="row" style="display: flex;justify-content: center;">
        <div class="col-md-8">
            <form action="traitement_add_delai.php" method="POST">
                <div class="row" style="display: flex;justify-content: center; text-align:center;">
                    <h2 class="text-center">DEFINIR DATE CODIFICATION</h2>
                    <?php if (!empty($_SESSION['successAdd'])) { ?>
                        <div class="col-md-6">
                            <div class="alert alert-success" role="alert">
                                <?= $_SESSION['successAdd']; ?>
                            </div>
                        </div>
                    <?php }
                    if (!empty($_SESSION['erreurAdd'])) { ?>
                        <div class="col-md-6">
                            <div class="alert alert-danger" role="alert">
                                <?= $_SESSION['erreurAdd']; ?>
                            </div>
                        </div>
                    <?php } ?>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <select name="nature" id="" class="form-select" required="required">
                            <option selected disabled>Choisir la nature de la date</option>
                            <option value="depart">Depart</option>
                            <option value="choix">Foclusion Choix Lit</option>
                            <option value="validation">Forclusion Validation Lit</option>
                            <option value="paiement">Forclusion Paiement Caution</option>
                            <option value="fermeture">Fermeture</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <input type="datetime-local" name="date" class="form-control" placeholder="Last name" required="required">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-check">
                            <input type="checkbox" name="fst" id="fst" class="form-check-input" placeholder="First name">
                            <label class="form-check-label" for="fst">FST</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="flsh" if="flsh" class="form-check-input" placeholder="First name">
                            <label class="form-check-label" for="flsh">FLSH</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="fsjp" id="fsjp" class="form-check-input" placeholder="First name">
                            <label class="form-check-label" name="fsjp">FSJP</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="fmpo" id="fmpo" class="form-check-input" placeholder="First name">
                            <label class="form-check-label" for="fmpo">FMPO</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-check">
                            <input type="checkbox" name="faseg" id="faseg" class="form-check-input" placeholder="First name">
                            <label class="form-check-label" for="faseg">FASEG</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="esp" id="esp" class="form-check-input" placeholder="First name">
                            <label class="form-check-label" for="esp">ESP</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="fastef" id="fastef" class="form-check-input" placeholder="First name">
                            <label class="form-check-label" for="fastef">FASTEF</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="esea" id="esea" class="form-check-input" placeholder="First name">
                            <label class="form-check-label" for="esea">ESEA</label>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-outline-success">ENREGISTERER</button>
            </form>
        </div>
    </div>
</body>

</html>