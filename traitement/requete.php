<?php
if (empty($_SESSION['username']) && empty($_SESSION['mdp'])) {
    header('Location: /COUD/codif/');
    exit();
}
if (isset($_SESSION['classe'])) {
    $classe = $_SESSION['classe'];
} else {
    $classe = "";
}
require_once(__DIR__ . '/fonction.php');
$tableauDataFaculte = [];
$tableauDataNiveauFormation = [];
$erreurClasse = "";
$messageErreurFaculte = "";
$messageErreurDepartement = "";
// Appel de la fonction getAllEtablissement() dans fonction.php, celle-ci affiche la liste de tous les etablissements
$dataEtablissement = getAllEtablissement();
if (isset($_GET['fac']) && !empty($_GET['fac'])) {
    // Appel de la fonction getAllDepartement() dans fonction.php, celle-ci affiche la liste de tous les Departements
    $resultatRequeteDepartement = getAllDepartement($_GET['fac']);
    // Appel de la fonction getOneByDepartemennt() dans fonction.php, celle-ci affiche la liste de tous les Departements sous format tableau
    $tableauDataFaculte = getOneByDepartemennt($resultatRequeteDepartement);
    if (isset($_GET['dep']) && !empty($_GET['dep'])) {
        $getDataDepartement = $_GET['dep'];
        // Appel de la fonction getAllNiveau() dans fonction.php, celle-ci affiche la liste de tous les niveau de formation
        $tableauDataNiveauFormation = getAllNiveau($getDataDepartement);
        if (isset($_GET['fac']) && $_GET['dep'] && $_GET['classe']) {
            $_SESSION['classe'] = $_GET['classe'];
            if ($_SESSION['profil'] == 'quota') {
                header("location:../personnels/listeLits.php?classe=" . $_SESSION['classe']);
            }
        } else {
            $erreurClasse = "La Classe est obligatoire !";
        }
    } else {
        $messageErreurDepartement = "Le Département est obligatoire !";
    }
} else {
    $messageErreurFaculte = "La Faculté est obligatoire !";
}
// Liste des chambres deja affecter a une classe selon le niveau de la classe
$resultatRequeteLitClasse = getLitOneByNiveau($classe, $_SESSION['sexe']);
// Liste des pavillons deja affecter a une classe selon le niveau de la classe, elle sera appeler dans la page detailsLits.php (elle sert de filtre)
$resultatRequetePavillonClasse = getPavillonOneByNiveau($classe, $_SESSION['sexe']);
// Liste des lits a valider selon la classe, elle sera valider par le personnels
$resultatRequeteLitClasseByValidePerso = getLitOneByNiveauFromPersonnel($classe, $_SESSION['sexe']);
// affichage de toutes les lits de la table cofif_lit avec les option migré et non migré
$resultatRequeteTotalLit = getAllLit($_SESSION['sexe']);
//Affiché la liste total des pavillon
$resultatRequetePavillon = getAllPavillon($_SESSION['sexe']);
