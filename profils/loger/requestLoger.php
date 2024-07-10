<?php
// Verifier la session si elle est actif, sinon on redirige vers la racine
session_start();
if (empty($_SESSION['username']) && empty($_SESSION['mdp'])) {
    header('Location: /COUD/codif/');
    exit();
}
// Verifier si la session stock toujours la valeur du niveau de la classe, sinon on l'initialise
if (isset($_SESSION['classe'])) {
    $classe = $_SESSION['classe'];
} else {
    $classe = "";
}
// appelle la page fonction.php
include('../../traitement/fonction.php');

if (isset($_POST['numEtudiant'])) {
    $num_etu = $_POST['numEtudiant'];
    //Appel de la fonction de verification si l'hebergement a deja validé le lit ou pas
    $data = getOneByValidatePaiement($num_etu);
    // print_r($data);
    if (mysqli_num_rows($data) > 0) {
        while ($row = mysqli_fetch_array($data)) {
            $array = $row;
        }
        // print_r($array);
        if ($array['etat_id_paie'] == 'Non migré') {
            $queryString = http_build_query(['data' => $array]);
            header("location: loger.php?" . $queryString);
            exit();
        } else {
            // $queryString = http_build_query(['data' => $array]);
            // header("location: loger.php?" . $queryString);
            // exit();
            $queryString = http_build_query(['data' => $array]);
            header('Location: loger.php?erreurValider=Etudiant déja loger !!!&'. $queryString);
        }
    } else {
        header("location: loger.php?erreurNonTrouver=Aucun résultat trouvé !!!");
    }
    // Libérer la mémoire du résultat
    mysqli_free_result($data);
}

if (isset($_POST['valide'])) {
    try {
        $id_aff = $_POST['valide'];
        $user = $_SESSION['username'];
        // Appel de la fonction d'enregistrement du paiement de la caution
        $requete = setLoger($id_aff, $user);
        print_r($requete);
        if ($requete == 1) {
            header('Location: loger.php?successValider=Logement Effectuer avec success !!!');
        }
    } catch (mysqli_sql_exception $e) {
        header('Location: loger.php?erreurValider=Etudiant déja loger !!!');
    }
}
