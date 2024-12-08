<?php
session_start();
if (empty($_SESSION['username']) && empty($_SESSION['mdp'])) {
    header('Location: /COUD/codif/');
    exit();
}
include('../../traitement/fonction.php');
if (isset($_POST['numEtudiant'])) {
    $num_etu = $_POST['numEtudiant'];
<<<<<<< HEAD
    $_SESSION['num_etu'] = $_POST['numEtudiant'];
=======
>>>>>>> 4ab3e8d6e0d4478baf0139928fb896d9191d7523
    if (getIsForclu($num_etu)) {
        $queryString = http_build_query(['data' => getIsForclu($num_etu)]);
        header('Location: paiement.php?erreurForclo=ETUDIANT FORCLU !!!&statut=forclu&' . $queryString);
    } else {
        if ($dataStudentConnect = studentConnect($num_etu)) {
            $dataStudentConnect_classe = $dataStudentConnect['niveauFormation'];
            $dataStudentConnect_sexe = $dataStudentConnect['sexe'];
            $dataStudentConnect_quota = getQuotaClasse($dataStudentConnect_classe, $dataStudentConnect_sexe)['COUNT(*)'];
            $dataStudentConnect_statut = getOnestudentStatus($dataStudentConnect_quota, $dataStudentConnect_classe, $dataStudentConnect_sexe, $num_etu);
            if ($dataStudentConnect_statut['statut'] == 'attributaire') {
                $data = getOneByValidate($num_etu);
                if (mysqli_num_rows($data) > 0) {
                    while ($row = mysqli_fetch_array($data)) {
                        $array = $row;
                    }

                    $dernier_mois_paye = explode(" ", trim($array['libelle']));
                    $dernier_mois_paye = $dernier_mois_paye[count($dernier_mois_paye) - 1];
                    $dernier_mois_paye = getMois($dernier_mois_paye);
                    $date_sys = dateFromat(date("Y-m-d"));
                    if (date("Y-m", strtotime($dernier_mois_paye)) == date("Y-m", strtotime($date_sys))) {
                        $queryString = http_build_query(['data' => $array]);
                        header('Location: paiement.php?erreurValider=ETUDIANT DEJA PAYER !!!&' . $queryString);
                        exit();
                    } else {
                        $queryString = http_build_query(['data' => $array]);
                        header("location: paiement.php?" . $queryString);
                        exit();
                    }
                } else {
                    header("location: paiement.php?erreurNonTrouver=VOUS N'AVEZ PAS ENCORE VALIDER VOTRE LIT !!!");
                }
                mysqli_free_result($data);
            } else if ($dataStudentConnect_statut['statut'] == 'suppleant') {
                header("location: paiement.php?erreurNonTrouver=VOUS ETES SUPPLEANT, C'EST VOTRE TITULAIRE QUI DOIT PAYER LA CAUTION !!!");
            } else {
                header("location: paiement.php?erreurNonTrouver=VOUS N'ETES PAS ATTRIBUTAIRE DE LIT !!!");
            }
        } else {
            header("location: paiement.php?erreurNonTrouver=ETUDIANT NON TROUVER DANS LA BASE DE DONNEES !!!");
        }
    }
}

if (isset($_POST['valide'])) {
    $i = 0;
    $libelle = "";
    try {
        $id_etu = $_POST['id_etu'];
        $id_val = $_POST['valide'];
        $user = $_SESSION['username'];
        $montant = $_POST['montant'];
<<<<<<< HEAD
        $montant_recu = $_POST['montant_recu'];
        $restant = ($montant - $montant_recu);
        $libelle = [];
        foreach ($_POST['libelle'] as $mois_caution => $value) {
            try {
                $libelle[$i] = $value;
                $i++;
            } catch (Exception $e) {
                header('Location: paiement.php?erreurValider=VEUILLER INDIQUER LES MOIS OU LA CAUTION !!!');
                exit();
            }
        }
        $chaine_libelle = json_encode($libelle);
        $chaine_libelle = str_replace(['[', ']', '"'], ' ', $chaine_libelle);
        $tableau_situation_paye = getAllSituation($_SESSION['num_etu']);
        $compt = 0;
        while ($situation = mysqli_fetch_array($tableau_situation_paye)) {
            $motsA = explode(' ', $chaine_libelle);
            $motsA = str_replace(' ', '', $motsA);
            foreach ($motsA as $mot) {
                if (strlen($mot) > 2) {
                    if (strpos($situation['libelle'], $mot) !== false) {
                        $compt++;
                        $queryString = http_build_query(['data' => $situation]);
                        header('Location: paiement.php?erreurMois=' . $mot . '&' . $queryString);
                        exit();
                    }
                }
            }
        }
        if ($compt == 0) {
            $requete = setPaiement($id_val, $user, $montant, $montant_recu, $restant, $chaine_libelle);
            if ($requete == 1) {
                header('Location: paiement.php?successValider=PAIEMENT AVEC SUCCESS !!!');
                // header('Location: /COUD/codif/profils/paiement/recu/recu.php');
            }
=======
        $libelle = $_POST['libelle'];
        foreach ($_POST as $mois_caution => $value) {
            if ($value === "on") {
                try {
                    $libelle[$i] = $mois_caution;
                    $i++;
                } catch (Exception $e) {
                    header('Location: paiement.php?erreurValider=Veuiller indiqué les mois ou la cautionr !!!');
                    exit();
                }
            }
        }
        $chaine = json_encode($libelle);
        $requete = setPaiement($id_aff, $user, $montant, $chaine);
        print_r($requete);
        if ($requete == 1) {
            header('Location: paiement.php?successValider=Paiement valider avec success !!!');
>>>>>>> 4ab3e8d6e0d4478baf0139928fb896d9191d7523
        }
    } catch (mysqli_sql_exception $e) {
        header('Location: paiement.php?erreurValider=ETUDIANT DEJA PAYER !!!');
    }
}
