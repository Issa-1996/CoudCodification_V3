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
    if (getIsForclu($num_etu)) {
        $queryString = http_build_query(['data' => getIsForclu($num_etu)]);
        header('Location: loger.php?erreurForclo=Cette etudiant est forclu !!!&statut=forclu&' . $queryString);
    } else {
        $dataStudentConnect = studentConnect($num_etu);
        if ($dataStudentConnect) {
            $dataStudentConnect_classe = $dataStudentConnect['niveauFormation'];
            $dataStudentConnect_sexe = $dataStudentConnect['sexe'];
            // $dataStudentConnect_moyenne = $dataStudentConnect['moyenne'];
            $dataStudentConnect_quota = getQuotaClasse($dataStudentConnect_classe, $dataStudentConnect_sexe)['COUNT(*)'];
            // $dataStudentConnect_rang = getStatutByOneStudent($dataStudentConnect_quota, $dataStudentConnect_classe, $dataStudentConnect_sexe, $dataStudentConnect_moyenne)['rang'];
            // $dataStudentConnect_statut = getStatutByOneStudent($dataStudentConnect_quota, $dataStudentConnect_classe, $dataStudentConnect_sexe, $dataStudentConnect_moyenne)['statut'];
            $dataStudentConnect_statut = getOnestudentStatus($dataStudentConnect_quota, $dataStudentConnect_classe, $dataStudentConnect_sexe, $num_etu);

            // print_r($dataStudentConnect_statut);
            if ($dataStudentConnect_statut['statut'] == 'attributaire') {
                // $dataStudent = getStatutByOneStudent($quota, $classe, $sexe, $moyenne);
                $data = getOneByValidatePaiement($num_etu, $_SESSION['pavillon']);
                if (mysqli_num_rows($data) > 0) {
                    while ($row = mysqli_fetch_array($data)) {
                        $array = $row;
                    }
                    if ($array['etat_id_paie'] == 'Non migré') {
                        $queryString = http_build_query(['data' => $array]);
                        header("location: loger.php?" . $queryString);
                        exit();
                    } else {
                        $queryString = http_build_query(['data' => $array]);
                        header('Location: loger.php?erreurValider=Etudiant déja loger !!!&' . $queryString);
                    }
                } else {
                    header("location: loger.php?erreurNonTrouver=Aucun résultat trouvé !!!");
                }
                mysqli_free_result($data);
            } else if ($dataStudentConnect_statut['statut'] == 'suppleant') {
                $monTitulaire = getStatutByOneStudentTitulaireOfSuppl($dataStudentConnect_quota, $dataStudentConnect_classe, $dataStudentConnect_sexe, $dataStudentConnect_rang);
                $monTitulaire_numEtu = $monTitulaire['num_etu'];
                if (getValidateLogerByStudentTitulaireOnSuppleant($monTitulaire_numEtu)) {
                    if (getValidateLitBySuppleant($num_etu)) {
                        if (getValidateLogerByStudentTitulaireOnSuppleant($num_etu)) {
                            header('Location: loger.php?erreurValider=Suppleant, vous etes deja logé !!!');
                            exit();
                        } else {
                            $arrayValidateSuppleant = getValidateLitBySuppleant($num_etu);
                            // print_r($arrayValidateSuppleant);
                            if ($arrayValidateSuppleant['etat_id_val'] == 'Migré') {
                                $queryString = http_build_query(['data' => $arrayValidateSuppleant]);
                                header('Location: loger.php?statut=' . $dataStudentConnect_statut . '&erreurValider=Suppleant déja loger !!!&' . $queryString);
                                exit();
                            } else if ($arrayValidateSuppleant['etat_id_val'] == 'Non migré') {
                                $queryString = http_build_query(['data' => $arrayValidateSuppleant]);
                                header("location: loger.php?statut=" . $dataStudentConnect_statut . '&' . $queryString);
                                exit();
                            }
                        }
                    } else {
                        header('Location: loger.php?erreurValider=Suppleant, vous n\'avez pas encore valider votre codification !!!');
                        exit();
                    }
                    // print_r(getValidateLogerByStudentTitulaireOnSuppleant($monTitulaire_numEtu));
                } else {
                    header('Location: loger.php?erreurValider=Suppleant, votre titulaire n\'en pas encore logé !!!');
                    exit();
                }
            }
        } else {
            header('Location: loger.php?erreurNonTrouver=ETUDIANT NON TROUVER DANS LA BASE DE DONNEES !!!');
        }
    }
}

if (isset($_POST['valide'])) {
    try {
        $id_aff = $_POST['valide'];
        $user = $_SESSION['username'];
        // Appel de la fonction d'enregistrement du paiement de la caution
        $requete = setLoger($id_aff, $user);
        print_r($requete);
        if ($requete == 1) {
            header('Location: loger.php?successValider=Logement titulaire Effectuer avec success !!!');
        }
    } catch (mysqli_sql_exception $e) {
        header('Location: loger.php?erreurValider=Titulaire déja loger !!!');
    }
}
if (isset($_POST['id_val'])) {
    try {
        $id_val = $_POST['id_val'];
        $user = $_SESSION['username'];
        // Appel de la fonction d'enregistrement du paiement de la caution
        $requete = setLogerSuppleant($id_val, $user);
        // print_r($requete);
        if ($requete == 1) {
            header('Location: loger.php?successValider=Logement Suppleant Effectuer avec success !!!');
        }
    } catch (mysqli_sql_exception $e) {
        header('Location: loger.php?erreurValider=Suppleant déja loger !!!');
    }
}
