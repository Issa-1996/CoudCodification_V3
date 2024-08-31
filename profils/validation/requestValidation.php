<?php
session_start();
if (empty($_SESSION['username']) && empty($_SESSION['mdp'])) {
    header('Location: /COUD/codif/');
    exit();
}
include('../../traitement/fonction.php');
if (isset($_POST['numEtudiant'])) {
    $num_etu = $_POST['numEtudiant'];
    if (getIsForclu($num_etu)) {
        $queryString = http_build_query(['data' => getIsForclu($num_etu)]);
        header('Location: validation.php?erreurForclo=Cette etudiant est forclu !!!&statut=forclu&' . $queryString);
    } else {
        $dataStudentConnect = studentConnect($num_etu);
        if ($dataStudentConnect) {
            $quotaClasseStudentConnecte = getQuotaClasse($dataStudentConnect['niveauFormation'], $_SESSION['sexe'])['COUNT(*)'];
            $dataStatutStudentSearch = getOnestudentStatus($quotaClasseStudentConnecte, $dataStudentConnect['niveauFormation'], $dataStudentConnect['sexe'], $num_etu);
            $rangStudentSearch = $dataStatutStudentSearch['rang'];
            if ($dataStatutStudentSearch['statut'] == 'attributaire') {
                $data = getOneByAffectation($num_etu);
                if (mysqli_num_rows($data) > 0) {
                    while ($row = mysqli_fetch_array($data)) {
                        $array = $row;
                    }
                    if ($array['migration_status'] == 'Non migré') {
                        $queryString = http_build_query(['data' => $array]);
                        header("location: validation.php?" . $queryString);
                        exit();
                    } else {
                        $queryString = http_build_query(['data' => $array]);
                        header('Location: validation.php?erreurValider=Etudiant Titulaire déja valider !!!&' . $queryString);
                        exit();
                    }
                } else {
                    header("location: validation.php?erreurNonTrouver=Etudiant attributaire, mais n'en pas fait le choix de lit !!!");
                }
                mysqli_free_result($data);
            } else if ($dataStatutStudentSearch['statut'] == 'suppleant') {
                $numEtudiantSuppleant = $_SESSION['numEtudiantSuppleant'] = $dataStatutStudentSearch['num_etu'];
                $statutTitulaireOfStudentSearch = getOneTitulaireBySuppleant($quotaClasseStudentConnecte, $dataStudentConnect['niveauFormation'], $dataStudentConnect['sexe'], $rangStudentSearch);
                $numStudentTitulaireOfSuppleant = $statutTitulaireOfStudentSearch['num_etu'];
                $data = getOneByAffectation($numStudentTitulaireOfSuppleant);
                if (mysqli_num_rows($data) > 0) {
                    while ($row = mysqli_fetch_array($data)) {
                        $arrayTitulaire = $row;
                    }
                    $dataValiteTitulaire = getOneByValidate($numStudentTitulaireOfSuppleant);
                    if (mysqli_num_rows($dataValiteTitulaire) > 0) {
                        $dataSuppleantIfChoiseLit = getOneByAffectation($numEtudiantSuppleant);
                        if (mysqli_num_rows($dataSuppleantIfChoiseLit) > 0) {
                            while ($rowSuppleant = mysqli_fetch_array($dataSuppleantIfChoiseLit)) {
                                $arraySuppleant = $rowSuppleant;
                            }
                            if ($arraySuppleant['migration_status'] == 'Non migré') {
                                $queryString = http_build_query(['data' => $arraySuppleant]);
                                header("location: validation.php?erreurValider=Suppleant déja affecter une lit et non valider !!!&" . $queryString);
                                exit();
                            } else {
                                $queryString = http_build_query(['data' => $arraySuppleant]);
                                header('Location: validation.php?erreurValider=Suppleant déja valider !!!&' . $queryString);
                                exit();
                            }
                        } else {
                            if ($arrayTitulaire['id_lit']) {
                                $idLitTitulaireOnSuppleant = $arrayTitulaire['id_lit'];
                                $queryString = http_build_query(['data' => $dataStudentConnect]);
                                header("location: validation.php?statut=suppleant&idLit=" . $idLitTitulaireOnSuppleant . '&' . $queryString);
                                exit();
                            }
                        }
                    } else {
                        header("location: validation.php?erreurNonTrouver=VOTRE TITULAIRE A CHOISI SON LIT MAIS N'EN PAS ENCORE VALIDER, VEUILLER PATIENTER QU'IL FAIT SA VALIDATION !!!");
                        exit();
                    }
                } else {
                    header("location: validation.php?erreurNonTrouver=SON TITULAIRE N\'EN PAS ENCORE CHOISI DE LIT, VEUILLER PATIENTER SVP !!!");
                }
                mysqli_free_result($data);
            } else {
                header("location: validation.php?erreurNonTrouver=ETDUANT NON ATTRIBUTAIRE DE LIT !!!");
            }
        } else {
            header("location: validation.php?erreurNonTrouver=ETUDIANT NON TROUVER DANS LA BASE DE DONNEES !!!");
        }
    }
}

if (isset($_POST['valide'])) {
    try {
        $id_aff = $_POST['valide'];
        $user = $_SESSION['username'];
        $requete = setValidation($id_aff, $user);
        print_r($requete);
        if ($requete == 1) {
            header('Location: validation.php?successValider=Etudiant valider avec success !!!');
        }
    } catch (mysqli_sql_exception $e) {
        header('Location: validation.php?erreurValider=Etudiant déja valider !!!');
    }
} elseif (isset($_POST['idLit']) && isset($_POST['id_etu'])) {
    $idLit = $_POST['idLit'];
    $idEtudiantSuppleant = $_POST['id_etu'];
    $numEtudiantSuppleant = $_SESSION['numEtudiantSuppleant'];
    $resulotatAffectationSuppleant = addAffectationOnSuppleant($idLit, $idEtudiantSuppleant);
    if ($resulotatAffectationSuppleant == 1) {
        $dataSuppleantAffectation = getOneByAffectation($numEtudiantSuppleant);
        $user = $_SESSION['username'];
        if (mysqli_num_rows($dataSuppleantAffectation) > 0) {
            while ($rowSuppleantAff = mysqli_fetch_array($dataSuppleantAffectation)) {
                $arraySuppleant = $rowSuppleantAff;
            }
            unset($_SESSION['numEtudiantSuppleant']);
        }
        $id_aff = $arraySuppleant[0];
        $requete = setValidation($id_aff, $user);
        if ($requete == 1) {
            header('Location: validation.php?successValider=Suppleant valider avec success !!!');
        }
    }
}
