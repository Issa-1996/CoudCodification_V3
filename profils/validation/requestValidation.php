<?php
// Verifier la session si elle est actif, sinon on redirige vers la racine
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
            $moyenneStudentSearch = studentConnect($num_etu)['moyenne'];
            $sexeStudentSearch =  studentConnect($num_etu)['sexe'];
            $classeStudentSearch = studentConnect($num_etu)['niveauFormation'];
            $idEtuStudentSearch = studentConnect($num_etu)['id_etu'];
            $quotaClasseStudentConnecte = getQuotaClasse($classeStudentSearch, $_SESSION['sexe'])['COUNT(*)'];
            // $dataStatutStudentSearch = getStatutByOneStudent($quotaClasseStudentConnecte, $classeStudentSearch, $sexeStudentSearch, $moyenneStudentSearch, $num_etu);
            $dataStatutStudentSearch = getOnestudentStatus($quotaClasseStudentConnecte, $classeStudentSearch, $sexeStudentSearch, $num_etu);
            $rangStudentSearch = $dataStatutStudentSearch['rang'];
            if ($dataStatutStudentSearch['statut'] == 'attributaire') {
                // Appel de la fonction de verification si l'etudiant a deja choisi un lit
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
                // Libérer la mémoire du résultat
                mysqli_free_result($data);
            } else if ($dataStatutStudentSearch['statut'] == 'suppleant') {
                // numero carte etudiant du suppleant
                $numEtudiantSuppleant = $_SESSION['numEtudiantSuppleant'] = $dataStatutStudentSearch['num_etu'];
                // les informations de l'etudiant titulaire son statut inclu
                $statutTitulaireOfStudentSearch = getStatutByOneStudentTitulaireOfSuppl($quotaClasseStudentConnecte, $classeStudentSearch, $sexeStudentSearch, $rangStudentSearch);
                // numero carte etudiant du titulaire
                $numStudentTitulaireOfSuppleant = $statutTitulaireOfStudentSearch['num_etu'];
                //  fonction pour verifier si le titulaire a deja choisi son lit
                $data = getOneByAffectation($numStudentTitulaireOfSuppleant);
                // si la condition est verifier, c'est a dire le titulaire a deja choisi son lit
                if (mysqli_num_rows($data) > 0) {
                    while ($row = mysqli_fetch_array($data)) {
                        $arrayTitulaire = $row;
                    }
                    // verification si le titulaire a valider son codification
                    $dataValiteTitulaire = getOneByValidate($numStudentTitulaireOfSuppleant);
                    if (mysqli_num_rows($dataValiteTitulaire) > 0) {
                        // Appel de la fonction pour verifier si le suppleant a deja choisi son lit ou pas encore
                        $dataSuppleantIfChoiseLit = getOneByAffectation($numEtudiantSuppleant);
                        // Si le resultat de mysqli_num_rows($dataSuppleantIfChoiseLit) est superieur à zero c'est dire, le suppleant à deja choisi son lit
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
                            // Si le resultat de mysqli_num_rows($dataSuppleantIfChoiseLit) est inferieur à zero c'est dire, le suppleant n'en pas choisi son lit
                        } else {
                            if ($arrayTitulaire['id_lit']) {
                                $idLitTitulaireOnSuppleant = $arrayTitulaire['id_lit'];
                                // Affecter le lit du titulaire à son suppleant
                                // $resulotatAffectationSuppleant = addAffectationOnSuppleant($idLitTitulaireOnSuppleant, $idEtuStudentSearch);
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
                // Libérer la mémoire du résultat
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
        // Appel de la fonction d'enregistrement de la validation du lit
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
    // Affecter le lit du titulaire à son suppleant
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
