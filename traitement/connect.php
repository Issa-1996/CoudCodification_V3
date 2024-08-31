<?php
include('fonction.php');
$error = "";

if (!empty($_GET['username_user']) && !empty($_GET['password_user'])) {
    $username = $_GET['username_user'];
    $password = $_GET['password_user'];
    /**************************************************************************
     * Traitement de la forclusion automatique
     * **************************************************************** */
    $dataNiveauFormation = getAllNiveauFormation();
    $dataEtablissement = getAllEtablissement();
    $niveauFormation = [];
    $etablissement = [];
    $k = 0;
    $c = 0;
    while ($row = mysqli_fetch_array($dataNiveauFormation)) {
        $niveauFormation[$k] = $row;
        $k++;
    }
    while ($row = mysqli_fetch_array($dataEtablissement)) {
        $etablissement[$c] = $row;
        $c++;
    }
    for ($j = 0; $j < count($etablissement); $j++) {
        for ($i = 0; $i < count($niveauFormation); $i++) {
            $quota = getQuotaClasse($niveauFormation[$i][0], 'F')['COUNT(*)'];
            $tableau_data_etudiant = getAllDatastudentStatus($quota, $niveauFormation[$i][0], 'F');
            $listeDelai1 = getAllDelai('choix', $etablissement[$j][0]);
            $listeDelai2 = getAllDelai('validation', $etablissement[$j][0]);
            $listeDelai3 = getAllDelai('paiement', $etablissement[$j][0]);
            $date_limite_choix = dateFromat($listeDelai1['data_limite']);
            $date_limite_val = dateFromat($listeDelai2['data_limite']);
            $date_limite_paye = dateFromat($listeDelai3['data_limite']);
            $date_sys = dateFromat(date('Y-m-d'));
            if ($date_sys >= $date_limite_choix) {
                if ($date_sys < $date_limite_val) {
                    $forclos = getAllForclu();
                    if ($forclos->num_rows != 0) {
                        $compt = 0;
                        while ($row = mysqli_fetch_array($forclos)) {
                            $dateFor = dateFromat($row['dateTime_for']);
                            if (($row['type'] == 'auto')) {
                                if ($dateFor >= $date_limite_choix) {
                                    if ($row['nature'] == 'choix') {
                                        $compt++;
                                    }
                                }
                            }
                        }
                        if ($compt == 0) {
                            for ($i = 0; $i < count($tableau_data_etudiant); $i++) {
                                if ($tableau_data_etudiant[$i]['statut'] == 'attributaire') {
                                    $choix_lit = getChoixLitByStudent($tableau_data_etudiant[$i]['num_etu']);
                                    if ($choix_lit == "VEUILLER CHOISIR UN LIT POUR DEMMARRER VOTRE CODIFICATION, <a href='/coud/codif/profils/etudiants/codifier.php'>CLIQUER ICI</a>") {
                                        addForclu($tableau_data_etudiant[$i]['id_etu'], $listeDelai1['id_delai']);
                                    }
                                }
                            }
                        }
                    } else {
                        for ($i = 0; $i < count($tableau_data_etudiant); $i++) {
                            if ($tableau_data_etudiant[$i]['statut'] == 'attributaire') {
                                $choix_lit = getChoixLitByStudent($tableau_data_etudiant[$i]['num_etu']);
                                if ($choix_lit == "VEUILLER CHOISIR UN LIT POUR DEMMARRER VOTRE CODIFICATION, <a href='/coud/codif/profils/etudiants/codifier.php'>CLIQUER ICI</a>") {
                                    addForclu($tableau_data_etudiant[$i]['id_etu'], $listeDelai1['id_delai']);
                                }
                            }
                        }
                    }
                }
                if ($date_sys >= $date_limite_val) {
                    if ($date_sys < $date_limite_paye) {
                        $forclos_validation = getAllForclu();
                        if ($forclos_validation->num_rows != 0) {
                            $compt = 0;
                            while ($row = mysqli_fetch_array($forclos_validation)) {
                                $dateFor = dateFromat($row['dateTime_for']);
                                if (($row['type'] == 'auto')) {
                                    if ($dateFor >= $date_limite_choix) {
                                        if ($row['nature'] == 'validation') {
                                            $compt++;
                                        }
                                    }
                                }
                            }
                            if ($compt == 0) {
                                for ($i = 0; $i < count($tableau_data_etudiant); $i++) {
                                    if ($tableau_data_etudiant[$i]['statut'] == 'attributaire') {
                                        $choix_lit = getValidateLitByStudent2($tableau_data_etudiant[$i]['num_etu']);
                                        if ($choix_lit == "VEUILLEZ-VOUS RAPPROCHER DU SERVICE DE L'HEBERGEMENT POUR COMPLETER VOTRE CODIFICATION !!!") {
                                            addForclu($tableau_data_etudiant[$i]['id_etu'], $listeDelai2['id_delai']);
                                        }
                                    }
                                }
                            }
                        } else {
                            for ($i = 0; $i < count($tableau_data_etudiant); $i++) {
                                if ($tableau_data_etudiant[$i]['statut'] == 'attributaire') {
                                    $choix_lit = getValidateLitByStudent2($tableau_data_etudiant[$i]['num_etu']);
                                    if ($choix_lit == "VEUILLEZ-VOUS RAPPROCHER DU SERVICE DE L'HEBERGEMENT POUR COMPLETER VOTRE CODIFICATION !!!") {
                                        addForclu($tableau_data_etudiant[$i]['id_etu'], $listeDelai2['id_delai']);
                                    }
                                }
                            }
                        }
                    }
                    if ($date_sys >= $date_limite_paye) {
                        $forclos_paiement = getAllForclu();
                        if ($forclos_paiement->num_rows != 0) {
                            $compt = 0;
                            while ($row = mysqli_fetch_array($forclos_paiement)) {
                                $dateFor_paiement = dateFromat($row['dateTime_for']);
                                if (($row['type'] == 'auto')) {
                                    if ($dateFor_paiement >= $date_limite_paye) {
                                        if ($row['nature'] == 'paiement') {
                                            $compt++;
                                        }
                                    }
                                }
                            }
                            if ($compt == 0) {
                                for ($i = 0; $i < count($tableau_data_etudiant); $i++) {
                                    if ($tableau_data_etudiant[$i]['statut'] == 'attributaire') {
                                        $choix_lit = getValidatePaiementLitBySuppleant($tableau_data_etudiant[$i]['num_etu']);
                                        if (!$choix_lit) {
                                            addForclu($tableau_data_etudiant[$i]['id_etu'], $listeDelai3['id_delai']);
                                        }
                                    }
                                }
                            }
                        } else {
                            for ($i = 0; $i < count($tableau_data_etudiant); $i++) {
                                if ($tableau_data_etudiant[$i]['statut'] == 'attributaire') {
                                    $choix_lit = getValidatePaiementLitBySuppleant($tableau_data_etudiant[$i]['num_etu']);
                                    if (!$choix_lit) {
                                        addForclu($tableau_data_etudiant[$i]['id_etu'], $listeDelai3['id_delai']);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
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
            } else if ($row['profil_user'] == 'delai') {
                header('Location: /COUD/codif/profils/personnels/add_delai.php');
                exit();
            } else if ($row['profil_user'] == 'forclu') {
                header('Location: /COUD/codif/profils/forclusion/forclore.php');
                exit();
            } else if ($row['profil_user'] == 'validation') {
                header('Location: /COUD/codif/profils/validation/validation.php');
                exit();
            } else if ($row['profil_user'] == 'paiement') {
                header('Location: /COUD/codif/profils/paiement/paiement.php');
                exit();
            } else if ($row['profil_user'] == 'chef_pavillon') {
                $_SESSION['pavillon'] = $row['pavillon'];
                header('Location: /COUD/codif/profils/loger/loger.php');
                exit();
            } else if ($row['profil_user'] == 'user') {
                $dataStudent = studentConnect($username);
                $_SESSION['id_etu'] = $dataStudent['id_etu'];
                $_SESSION['nationalite'] = $dataStudent['nationalite'];
                $_SESSION['niveau'] = $dataStudent['niveau'];
                $_SESSION['num_etu'] = $dataStudent['num_etu'];
                $_SESSION['etablissement'] = $dataStudent['etablissement'];
                $_SESSION['num_etu'] = $dataStudent['num_etu'];
                $_SESSION['classe'] = $dataStudent['niveauFormation'];
                $_SESSION['dateNaissance'] = $dataStudent['dateNaissance'];
                $_SESSION['lieuNaissance'] = $dataStudent['lieuNaissance'];
                $resultat = getPolitiqueConf($_SESSION['id_etu']);
                if ($resultat) {
                    header('Location: ../profils/etudiants/resultat.php?refresh=1');
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
}
