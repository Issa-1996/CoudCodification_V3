<?php
include('fonction.php');
$error = "";

if (!empty($_GET['username_user']) && !empty($_GET['password_user'])) {
    $username = $_GET['username_user'];
    $password = $_GET['password_user'];
    $row = login($username, $password);
    if ($row) {
        session_start();
        $_SESSION['id_user'] = $row['id_user'];
        $_SESSION['username'] = $row['username_user'];
        $_SESSION['mdp'] = $row['password_user'];
        $_SESSION['sexe'] = $row['sexe_user'];
        $_SESSION['profil'] = $row['profil_user'];
        $_SESSION['prenom'] = $row['prenom_user'];
        $_SESSION['nom'] = $row['nom_user'];
        if ($row['profil_user'] == 'quota') {
            header('Location: /COUD/codif/profils/personnels/niveau.php');
            exit();
        } else if ($row['profil_user'] == 'validation') {
            header('Location: /COUD/codif/profils/validation/validation.php');
            exit();
        } else if ($row['profil_user'] == 'paiement') {
            header('Location: /COUD/codif/profils/paiement/paiement.php');
            exit();
        } 
        else if ($row['profil_user'] == 'chef_pavillon') {
            header('Location: /COUD/codif/profils/loger/loger.php');
            exit();
        } 
        else if ($row['profil_user'] == 'user') {
            $dataStudent = studentConnect($username);
            $_SESSION['id_etu'] = $dataStudent['id_etu'];
            $_SESSION['nationalite'] = $dataStudent['nationalite'];
            $_SESSION['niveau'] = $dataStudent['niveau'];
            $_SESSION['num_etu '] = $dataStudent['num_etu'];
            $_SESSION['etablissement'] = $dataStudent['etablissement'];
            $_SESSION['num_etu '] = $dataStudent['num_etu'];
            $_SESSION['classe'] = $dataStudent['niveauFormation'];
            $_SESSION['dateNaissance'] = $dataStudent['dateNaissance'];
            $_SESSION['lieuNaissance'] = $dataStudent['lieuNaissance'];
            $resultat = getPolitiqueConf($_SESSION['id_etu']);
            if ($resultat) {
                header('Location: ../profils/etudiants/resultat.php');
                exit();
            } else {
                header('Location: ../profils/etudiants/accueilEtudiant.php');
                exit();
            }
        }
    } else {
        $error_message = 'Incorrect username or password!';
        $error = "Nom d'utilisateur ou mot de passe Incorrect";
        header('Location: /COUD/codif/?error=' . $error);
        exit();
    }
}
