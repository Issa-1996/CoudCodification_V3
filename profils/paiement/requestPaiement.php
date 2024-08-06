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
        header('Location: paiement.php?erreurForclo=Cette etudiant est forclu !!!&statut=forclu&' . $queryString);
    } else {
        if ($dataStudentConnect = studentConnect($num_etu)) {
            $dataStudentConnect_classe = $dataStudentConnect['niveauFormation'];
            $dataStudentConnect_sexe = $dataStudentConnect['sexe'];
            // $dataStudentConnect_moyenne = $dataStudentConnect['moyenne'];
            $dataStudentConnect_quota = getQuotaClasse($dataStudentConnect_classe, $dataStudentConnect_sexe)['COUNT(*)'];
            // $dataStudentConnect_rang = getStatutByOneStudent($dataStudentConnect_quota, $dataStudentConnect_classe, $dataStudentConnect_sexe, $dataStudentConnect_moyenne)['rang'];
            // $dataStudentConnect_statut = getStatutByOneStudent($dataStudentConnect_quota, $dataStudentConnect_classe, $dataStudentConnect_sexe, $dataStudentConnect_moyenne, $num_etu)['statut'];
            $dataStudentConnect_statut = getOnestudentStatus($dataStudentConnect_quota, $dataStudentConnect_classe, $dataStudentConnect_sexe, $num_etu);

            if ($dataStudentConnect_statut['statut'] == 'attributaire') {
                // getStatutByOneStudent($quota, $classe, $sexe, $moyenne);
                $data = getOneByValidate($num_etu);
                if (mysqli_num_rows($data) > 0) {
                    while ($row = mysqli_fetch_array($data)) {
                        $array = $row;
                    }
                    if ($array['migration_status'] == 'Non migré') {
                        $queryString = http_build_query(['data' => $array]);
                        header("location: paiement.php?" . $queryString);
                        exit();
                    } else {
                        $queryString = http_build_query(['data' => $array]);
                        header('Location: paiement.php?erreurValider=Etudiant déja payer !!!&' . $queryString);
                        exit();
                    }
                } else {
                    header("location: paiement.php?erreurNonTrouver=Vous n'avez pas encore valider votre lit !!!");
                }
                // Libérer la mémoire du résultat
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
    try {
        $id_aff = $_POST['valide'];
        $user = $_SESSION['username'];
        // Appel de la fonction d'enregistrement du paiement de la caution
        $requete = setPaiement($id_aff, $user);
        print_r($requete);
        if ($requete == 1) {
            header('Location: paiement.php?successValider=Paiement valider avec success !!!');
        }
    } catch (mysqli_sql_exception $e) {
        header('Location: paiement.php?erreurValider=Etudiant déja payer !!!');
    }
}
