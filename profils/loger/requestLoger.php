<?php
session_start();
if (empty($_SESSION['username']) && empty($_SESSION['mdp'])) {
    header('Location: /COUD/codif/');
    exit();
}
include('../../traitement/fonction.php');
if (isset($_POST['numEtudiant'])) {
    $num_etu = $_POST['numEtudiant'];
    if ($is_forclu = getIsForclu($num_etu)) {
        $queryString = http_build_query(['data' => $is_forclu]);
        header('Location: loger.php?erreurForclo=Cet etudiant est forclu !!!&statut=forclu&' . $queryString);
    } else {
        if ($dataStudentConnect = studentConnect($num_etu)) {
            $dataStudentConnect_classe = $dataStudentConnect['niveauFormation'];
            $dataStudentConnect_quota = getQuotaClasse($dataStudentConnect_classe, $dataStudentConnect['sexe'])['COUNT(*)'];
            $dataStudentConnect_statut = getOnestudentStatus($dataStudentConnect_quota, $dataStudentConnect_classe, $dataStudentConnect['sexe'], $num_etu);
            $dataStudentConnect_rang = $dataStudentConnect_statut['rang'];
            if ($dataStudentConnect_statut['statut'] == 'attributaire') {
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
                    header("location: loger.php?erreurNonTrouver=CETTE ETUDIANT N'EST PAS RESIDENT DU PAVILLON" . $_SESSION['pavillon'] . " !!!");
                }
                mysqli_free_result($data);
            } else if ($dataStudentConnect_statut['statut'] == 'suppleant') {
                $monTitulaire = getOneTitulaireBySuppleant($dataStudentConnect_quota, $dataStudentConnect_classe, $dataStudentConnect['sexe'], $dataStudentConnect_rang);
                $monTitulaire_numEtu = $monTitulaire['num_etu'];
                if (getValidateLogerByTitulaire($monTitulaire_numEtu)) {
                    if (getValidateLitBySuppleant($num_etu)) {
                        if (getValidateLogerBySuppleant($num_etu)) {
                            $queryString = http_build_query(['data' => getValidateLogerBySuppleant($num_etu)]);
                            header('Location: loger.php?statut=' . $dataStudentConnect_statut['statut'] . '&erreurValider=Suppleant déja loger !!!&' . $queryString);
                            exit();
                        } else {
                            $arrayValidateSuppleant = getValidateLitBySuppleant($num_etu);
                            if ($arrayValidateSuppleant['etat_id_val'] == 'Migré') {
                                $queryString = http_build_query(['data' => $arrayValidateSuppleant]);
                                header('Location: loger.php?statut=' . $dataStudentConnect_statut['statut'] . '&erreurValider=Suppleant déja loger !!!&' . $queryString);
                                exit();
                            } else if ($arrayValidateSuppleant['etat_id_val'] == 'Non migré') {
                                $queryString = http_build_query(['data' => $arrayValidateSuppleant]);
                                header("location: loger.php?statut=" . $dataStudentConnect_statut['statut'] . '&' . $queryString);
                                exit();
                            }
                        }
                    } else {
                        header('Location: loger.php?erreurValider=Suppleant, vous n\'avez pas encore valider votre codification !!!');
                        exit();
                    }
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
        $requete = setLoger($id_aff, $user);
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
        $requete = setLogerSuppleant($id_val, $user);
        if ($requete == 1) {
            header('Location: loger.php?successValider=Logement Suppleant Effectuer avec success !!!');
        }
    } catch (mysqli_sql_exception $e) {
        header('Location: loger.php?erreurValider=Suppleant déja loger !!!');
    }
}
