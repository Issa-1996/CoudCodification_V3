<?php
// Démarre une nouvelle session ou reprend une session existante
session_start();
if (empty($_SESSION['username']) && empty($_SESSION['mdp'])) {
    header('Location: /COUD/codif/');
    exit();
}
require_once('../../traitement/fonction.php');
$_SESSION['errorSaisi'] = 0;
if (!empty($_GET)) {
    $countError = 0;
    $lastValue = null;
    $_SESSION['erreurLitCodifier'] = '';
    $lastValue = $_GET['lit_selection'];
    $idEtu = $_SESSION['id_etu'];
    print_r($idEtu);
    $requeteInsertAff = "INSERT INTO `affectation` (`id_lit`, `id_etu`, `dateTime_aff`) VALUES ($lastValue, $idEtu, NOW())";
    $requeteEtu = $connexion->prepare($requeteInsertAff);
    $requeteEtu->execute();
    header('Location: resultat.php');
    exit();
} else {
    header('Location: codifier.php?erreurLitCodifier=VEUILLER SELECTIONNER UN LIT !!!');
    exit();
}
