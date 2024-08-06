<?php
// Démarre une nouvelle session ou reprend une session existante
session_start();
if (empty($_SESSION['username']) && empty($_SESSION['mdp'])) {
    header('Location: /COUD/codif/');
    exit();
}
//connexion à la base de données
require('../../traitement/fonction.php');
// Comptez le nombre total d'options dans la base de données: pagination liste lits d'une classe selon l'etudiant connecté dans la page codifier.php
$total_pagesEtudiant = getLitByStudent($_SESSION['classe'], $_SESSION['sexe']);
// Liste des chambres deja affecter a une classe selon le niveau de la classe
$resultRequeteLitClasseEtudiant = getLitValideByClasse($_SESSION['classe'], $_SESSION['sexe']);
if (isset($_GET['erreurLitCodifier'])) {
    $_SESSION['erreurLitCodifier'] = $_GET['erreurLitCodifier'];
} else {
    $_SESSION['erreurLitCodifier'] = '';
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>COUD: CODIFICATION </title>
    <link rel="stylesheet" href="../../assets/css/main.css">
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <link rel="stylesheet" href="../../assets/bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="../../assets/bootstrap/js/bootstrap.min.js">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    <?php include('../../head.php'); ?>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>Veuiller cliqué sur un boutton pour choisir votre lit !!!</h1>
            </div>
            <div class="col-md-12" style="display:flex; justify-content: center;">
                <?php if ($_SESSION['erreurLitCodifier']) { ?>
                    <div class="col-md-3">
                        <div class="alert alert-danger" role="alert">
                            <?= $_SESSION['erreurLitCodifier']; ?>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <ul class="options">
                    <form id="myForm" action="traitementCodifier.php" method="GET">
                        <!-- Affichez chaque option dans une liste -->
                        <div class='options-container'>
                            <?php
                            while ($row = mysqli_fetch_array($resultRequeteLitClasseEtudiant)) {
                                if ($counter % 10 == 0) { ?>
                                    <div class='column'>
                                    <?php
                                }
                                if ($row['statut_migration'] == 'Migré vers quotas uniquement') {
                                    ?>
                                        <label class="optionEtu" title="Lit non choisi !">
                                            <input type="radio" name="lit_selection" value="<?= $row['id_lit'] ?>"><?= $row['lit'] ?></input>
                                        </label>
                                    <?php
                                }
                                if ($row['statut_migration'] == 'Migré dans les deux') {
                                    ?>
                                        <label class="archive" title="Lit affecté"><?= $row['lit'] ?> </label>
                                    <?php
                                }
                                $counter++;
                                if ($counter % 10 == 0) { ?>
                                    </div>
                                <?php
                                }
                            }
                            // Fermeture de la dernière colonne si le nombre total d'options n'est pas un multiple de 4
                            if ($counter % 10 != 0) { ?>
                        </div>

                    <?php
                            } ?>
            </div><br><br>
            <div class="row justify-content-center">
                <div class="col-md-2">
                    <input type='reset' onclick="choi()" class="btn btn-outline-danger fw-bold" title="Annulé la selectionnée" value="REINITIALISER">
                </div>
                <div class="col-md-2">
                    <select class='form-select' onchange='location = this.value;'>
                        <?php
                        // Affichage de la liste déroulante de pagination
                        for ($i = 1; $i <= $total_pagesEtudiant; $i++) {
                            $offset_value = ($i - 1) * $limit;
                            $selected = ($i == $page) ? "selected" : "";
                            $lower_bound = $offset_value + 1;
                            $upper_bound = min($offset_value + $limit, $count_dataEtudiant['total']);
                            echo "<option value='codifier.php?page=$i' $selected>De $lower_bound à $upper_bound</option>";
                        } ?>
                    </select>
                </div>
                <?php
                // Fermez la connexion
                mysqli_close($connexion);
                ?>
                <div class="col-md-2">
                    <button class="btn btn-outline-success fw-bold" type="submit" title="Sauvegarder les lits selectionnés">VALIDER</button>
                </div>
            </div>
            </form>
            </ul>
        </div>
    </div>
</body>

<script>
    function choi() {
        window.location.href = "codifier.php";
    }
</script>
<script src="../../assets/js/script.js"></script>

</html>