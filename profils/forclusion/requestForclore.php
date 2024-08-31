<?php
session_start();
if (empty($_SESSION['username']) && empty($_SESSION['mdp'])) {
    header('Location: /COUD/codif/');
    exit();
}
if (isset($_SESSION['classe'])) {
    $classe = $_SESSION['classe'];
} else {
    $classe = "";
}
include('../../traitement/fonction.php');

if (isset($_POST['numEtudiant'])) {
    $num_etu = $_POST['numEtudiant'];
    $etudiantVerifie = studentConnect($num_etu);
    $data = isEtudiantForclus($etudiantVerifie['id_etu']);
    if ($data == null) {
        $queryString = http_build_query(['data' => $etudiantVerifie]);
        header('Location: forclore.php?' . $queryString);
        exit();
    } else {
        $queryString = http_build_query(['data' => $data]);
        header('Location: forclore.php?statut=forclu&' . $queryString);
        exit();
    }
}

if (isset($_POST['id_etu']) && isset($_POST['motif'])) {
    try {
        $id_student = $_POST['id_etu'];
        $motif_for = $_POST['motif'];
        $requete = addForcloreManuel($id_student, $motif_for, $_SESSION['username']);
        if ($requete == 1) {
            header('Location: forclore.php?successValider=Etudiant forclus avec success !!!');
        }
    } catch (mysqli_sql_exception $e) {
        header('Location: forclore.php?erreurValider=Etudiant déja forclus !!!');
    }
}
