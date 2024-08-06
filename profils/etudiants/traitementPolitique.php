<?php
// Démarre une nouvelle session ou reprend une session existante
session_start();
if (empty($_SESSION['username']) && empty($_SESSION['mdp'])) {
    header('Location: /COUD/codif/');
    exit();
}
require_once('../../traitement/fonction.php');

if (isset($_GET) && count($_GET) > 0) { 
    $idEtu = $_SESSION['id_etu'];
    addPolitiqueConf($idEtu);
    header('Location: resultat.php');
    exit();
}