<?php
session_start();
if (empty($_SESSION['username']) && empty($_SESSION['mdp'])) {
    header('Location: /COUD/codif/');
    exit();
}
require_once('../../traitement/fonction.php');
if (!empty($_GET)) {
    $countError = 0;
    $lastValue = null;
    $_SESSION['erreurLitCodifier'] = '';
    $lastValue = $_GET['lit_selection'];
    $idEtu = $_SESSION['id_etu'];
    addAffectation($lastValue, $idEtu);
    header('Location: resultat.php');
    exit();
} else {
    header('Location: codifier.php?erreurLitCodifier=VEUILLER SELECTIONNER UN LIT !!!');
    exit();
}
