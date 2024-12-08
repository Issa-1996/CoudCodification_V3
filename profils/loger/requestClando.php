<?php
session_start();
if (empty($_SESSION['username']) && empty($_SESSION['mdp'])) {
    header('Location: /COUD/codif/');
    exit();
}
include('../../traitement/fonction.php');
if (isset($_POST['numEtudiant'])) {
    $num_etu = $_POST['numEtudiant'];
    $_SESSION['num_etu'] = $num_etu;
    if ($is_forclu = getIsForclu($num_etu)) {
        $queryString = http_build_query(['data' => $is_forclu]);
        header('Location: clando.php?erreurForclo=Cet etudiant est forclu !!!&statut=forclu&' . $queryString);
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
                    if (!isset($array[35])) {
                        $queryString = http_build_query(['data' => $array]);
                        header('Location: clando.php?erreurValider=VEUILLER PROCEDER AU PAIEMENT DABORD !!!&' . $queryString);
                    } else {
                        if ($array['etat_id_paie'] == 'Non migré') {
                            $queryString = http_build_query(['data' => $array]);
                            header("location: clando.php?erreurValider=ETUDIANT DEJA PAYER ET PAS ENCORE LOGER &" . $queryString);
                            exit();
                        } else {
                            $queryString = http_build_query(['data' => $array]);
                            header('Location: clando.php?erreurValider=ETUDIANT PRET A RECEVOIR DES CLANDOS &' . $queryString);
                        }
                    }
                } else {
                    header("location: clando.php?erreurNonTrouver=CETTE ETUDIANT N'EST PAS RESIDENT DU PAVILLON =>" . $_SESSION['pavillon'] . " !!!");
                }
                mysqli_free_result($data);
            } else {
                header('Location: clando.php?erreurNonTrouver=ETUDIANT NON ATTRIBUTAIRE !!!');
                exit();
            }
        } else {
            header('Location: clando.php?erreurNonTrouver=ETUDIANT NON TROUVER DANS LA BASE DE DONNEES !!!');
        }
    }
}
if (isset($_POST['id_paie'])) {
    $info = info($_SESSION['num_etu']);
    $id_etu = $info[0];
    try {
        $id_etu = info($_POST['num_etu'])[0];
        $id_paie = $_POST['id_paie'];
        $user = $_SESSION['username'];
        $requete = setLogerClando($id_paie, $user, $id_etu);
        if ($requete == 1) {
            header('Location: clando.php?successValider=Logement clando Effectuer avec success !!!');
        }
    } catch (mysqli_sql_exception $e) {
        header('Location: clando.php?erreurValider=Titulaire déja loger !!!');
    }
}
