<?php
// Connectez-vous à votre base de données MySQL
function connexionBD()
{
    $connexion = mysqli_connect("localhost", "root", "", "supercoud_codif");
    // Vérifiez la connexion
    if ($connexion === false) {
        die("Erreur : Impossible de se connecter. " . mysqli_connect_error());
    }
    return $connexion;
}
$connexion = connexionBD();

//Les attributs de la pagination: Pagination par page de 54 elements
function getAttributByPagination()
{
    global $page, $limit, $offset, $counter;
    $page = isset($_GET['page']) ? $_GET['page'] : 1;
    $limit = 90;
    $offset = ($page - 1) * $limit;
    $counter = 0;
}
getAttributByPagination();

//Fonction d'affichage de la liste des etablissements, elle est appeler dans requette.php et affiché dans la page niveau.php
function getAllEtablissement()
{
    global $connexion;
    $requeteListeEtablissement = "SELECT DISTINCT (etablissement) FROM `codif_etudiant`";
    $resultatRequeteEtablissement = mysqli_query($connexion, $requeteListeEtablissement);
    return $resultatRequeteEtablissement;
}

//Fonction d'affichage de la liste des departement, elle est appeler dans requette.php et affiché dans la page niveau.php
function getAllDepartement($dataFaculte)
{
    global $connexion;
    $requeteListeDepartement = "SELECT DISTINCT(departement) FROM `codif_etudiant` WHERE `etablissement`='" . $dataFaculte . "'";
    $resultatRequeteDepartement = mysqli_query($connexion, $requeteListeDepartement);
    return $resultatRequeteDepartement;
}

//Fonction d'affichage de la liste des departement sous forme d'un tableau de donnée, elle est appeler dans requette.php et affiché dans la page niveau.php
function getOneByDepartemennt($dataDepartement)
{
    $i = 0;
    while ($rowDepartement = mysqli_fetch_array($dataDepartement)) {
        $tableauDataFaculte[$i] = $rowDepartement['departement'];
        $i++;
    }
    return $tableauDataFaculte;
}

//Fonction d'affichage de la liste des niveaux de formation, elle est appeler dans requette.php et affiché dans la page niveau.php
function getAllNiveau($dataOneDepartement)
{
    global $connexion;
    $requeteNiveauFormation = "SELECT DISTINCT(niveauFormation) FROM `codif_etudiant` WHERE `departement`='" . $dataOneDepartement . "'";
    $resultatRequeteNiveauFormation = mysqli_query($connexion, $requeteNiveauFormation);
    $i = 0;
    while ($rowNiveauFormation = mysqli_fetch_array($resultatRequeteNiveauFormation)) {
        $tableauDataNiveauFormation[$i] = $rowNiveauFormation['niveauFormation'];
        $i++;
    }
    return $tableauDataNiveauFormation;
}

// Fonction d'affichage de la Liste des chambres deja affecter a une classe selon le niveau de formation, elle est appeler dans requette.php et affiché dans la page detailsLits.php
function getLitOneByNiveau($classe, $sexe)
{
    global $connexion, $limit, $offset;
    $requeteLitClasse = "SELECT codif_lit.*, CASE WHEN quotas.id_lit_q IS NOT NULL AND affectation.id_lit IS NOT NULL THEN 'Migré dans les deux' WHEN quotas.id_lit_q IS NOT NULL THEN 'Migré vers quotas uniquement' WHEN affectation.id_lit IS NOT NULL THEN 'Migré vers affectation uniquement' ELSE 'Non migré' END AS statut_migration FROM codif_lit LEFT JOIN quotas ON codif_lit.id_lit = quotas.id_lit_q LEFT JOIN affectation ON codif_lit.id_lit = affectation.id_lit WHERE quotas.NiveauFormation = '$classe' AND codif_lit.sexe='$sexe' LIMIT $limit OFFSET $offset";
    $resultatRequeteLitClasse = mysqli_query($connexion, $requeteLitClasse);
    return $resultatRequeteLitClasse;
}

// Fonction d'affichage de la Liste des pavillon deja affecter a une classe selon le niveau de formation, elle est appeler dans requette.php et affiché dans la page detailsLits.php (elle sert de filtre des pavillon)
function getPavillonOneByNiveau($classe, $sexe)
{
    global $connexion, $limit, $offset;
    $requeteLitClasse = "SELECT DISTINCT (pavillon), CASE WHEN quotas.id_lit_q IS NOT NULL AND affectation.id_lit IS NOT NULL THEN 'Migré dans les deux' WHEN quotas.id_lit_q IS NOT NULL THEN 'Migré vers quotas uniquement' WHEN affectation.id_lit IS NOT NULL THEN 'Migré vers affectation uniquement' ELSE 'Non migré' END AS statut_migration FROM codif_lit LEFT JOIN quotas ON codif_lit.id_lit = quotas.id_lit_q LEFT JOIN affectation ON codif_lit.id_lit = affectation.id_lit WHERE quotas.NiveauFormation = '$classe' AND codif_lit.sexe='$sexe' LIMIT $limit OFFSET $offset";
    $resultatRequeteLitClasse = mysqli_query($connexion, $requeteLitClasse);
    return $resultatRequeteLitClasse;
}

// Fonction d'affichage de la Liste des chambres deja affecter a une classe selon le niveau de formation, elle est appeler dans requette.php et affiché dans la page detailsLits.php
function getLitOneByNiveauFromPersonnel($classe, $sexe)
{
    global $connexion;
    global $limit, $offset;
    $requeteLitClasse = "SELECT affectation.*, codif_etudiant.*, codif_lit.*, CASE WHEN vl.id_aff IS NOT NULL THEN 'Migré' ELSE 'Non migré' END AS migration_status FROM affectation INNER JOIN codif_etudiant ON affectation.id_etu = codif_etudiant.id_etu INNER JOIN codif_lit ON affectation.id_lit = codif_lit.id_lit LEFT JOIN validation_lit vl ON affectation.id_aff = vl.id_aff WHERE codif_etudiant.niveauFormation = '$classe' AND codif_lit.sexe='$sexe'";
    $resultatRequeteLitClasse = mysqli_query($connexion, $requeteLitClasse);
    return $resultatRequeteLitClasse;
}

// Fonction d'affichage des information du lit deja choisi selon son numero etudiant, elle sera appeler dans la page validation
function getOneByAffectation($num_etu)
{
    global $connexion;
    $requeteLitClasse = "SELECT *, CASE WHEN vl.id_aff IS NOT NULL THEN 'Migré' ELSE 'Non migré' END AS migration_status FROM affectation INNER JOIN codif_etudiant ON affectation.id_etu = codif_etudiant.id_etu INNER JOIN codif_lit ON affectation.id_lit = codif_lit.id_lit LEFT JOIN validation_lit vl ON affectation.id_aff = vl.id_aff WHERE codif_etudiant.num_etu = '$num_etu'";
    $resultatRequeteLitClasse = mysqli_query($connexion, $requeteLitClasse);
    return $resultatRequeteLitClasse;
}
// Fonction d'affichage des information du lit deja valider par le personnel selon son numero etudiant, elle sera appeler dans la page paiement
function getOneByValidate($num_etu)
{
    global $connexion;
    $requeteLitClasseValide = "SELECT *, vl.id_val, CASE WHEN pc.id_val IS NOT NULL THEN 'Migré dans paiement_caution' WHEN paiement_caution.id_val IS NOT NULL THEN 'Migré dans autre_table' ELSE 'Non migré' END AS migration_status FROM validation_lit vl JOIN affectation a ON vl.id_aff = a.id_aff JOIN codif_etudiant ce ON a.id_etu = ce.id_etu JOIN codif_lit cl ON a.id_lit = cl.id_lit LEFT JOIN paiement_caution pc ON vl.id_val = pc.id_val LEFT JOIN paiement_caution ON vl.id_val = paiement_caution.id_val WHERE ce.num_etu = '$num_etu'";
    $resultatRequeteLitClasseValide = mysqli_query($connexion, $requeteLitClasseValide);
    return $resultatRequeteLitClasseValide;
}
// Fonction d'affichage des information du lit deja valider par le personnel selon son numero etudiant, elle sera appeler dans la page paiement
function getOneByValidatePaiement($num_etu)
{
    global $connexion;
    // $requeteLitClasseValide = "SELECT *, vl.id_val, CASE WHEN pc.id_val IS NOT NULL THEN 'Migré dans paiement_caution' WHEN paiement_caution.id_val IS NOT NULL THEN 'Migré dans autre_table' ELSE 'Non migré' END AS migration_status FROM validation_lit vl JOIN affectation a ON vl.id_aff = a.id_aff JOIN codif_etudiant ce ON a.id_etu = ce.id_etu JOIN codif_lit cl ON a.id_lit = cl.id_lit LEFT JOIN paiement_caution pc ON vl.id_val = pc.id_val LEFT JOIN paiement_caution ON vl.id_val = paiement_caution.id_val WHERE ce.num_etu = '$num_etu'";
    $requeteLitClasseValide = "SELECT ce.*, cl.*, vl.*, pc.*, CASE WHEN l.id_paie IS NOT NULL THEN 'Migré' ELSE 'Non migré' END AS etat_id_paie FROM codif_etudiant ce JOIN affectation a ON ce.id_etu = a.id_etu JOIN validation_lit vl ON a.id_aff = vl.id_aff JOIN codif_lit cl ON a.id_lit = cl.id_lit LEFT JOIN paiement_caution pc ON vl.id_val = pc.id_val LEFT JOIN loger l ON pc.id_paie = l.id_paie WHERE ce.num_etu = '$num_etu'";
    $resultatRequeteLitClasseValide = mysqli_query($connexion, $requeteLitClasseValide);
    return $resultatRequeteLitClasseValide;
}

// Fonction d'affichage de la Liste des chambres aavec les option migré et non migré, elle est appeler dans requette.php et affiché dans la page listeLits.php
function getAllLit($sexe)
{
    global $connexion;
    global $limit, $offset;
    $sql = "SELECT codif_lit.*, CASE WHEN quotas.id_lit_q IS NOT NULL THEN 'Migré' ELSE 'Non migré' END AS statut_migration FROM codif_lit LEFT JOIN quotas ON codif_lit.id_lit = quotas.id_lit_q WHERE codif_lit.sexe = '$sexe' LIMIT $limit OFFSET $offset";
    $resultatRequeteTotalLit = mysqli_query($connexion, $sql);
    return $resultatRequeteTotalLit;
}

// Fonction d'affichage de la Liste des chambres deja affecter a une classe selon la classe, elle est appeler dans requette.php et affiché dans la page codifier.php
function getLitValideByClasse($classe, $sexe)
{
    global $connexion;
    global $limit, $offset;
    $requeteLitClasseEtudiant = "SELECT codif_lit.*, CASE WHEN quotas.id_lit_q IS NOT NULL AND affectation.id_lit IS NOT NULL THEN 'Migré dans les deux' WHEN quotas.id_lit_q IS NOT NULL THEN 'Migré vers quotas uniquement' WHEN affectation.id_lit IS NOT NULL THEN 'Migré vers affectation uniquement' ELSE 'Non migré' END AS statut_migration FROM codif_lit LEFT JOIN quotas ON codif_lit.id_lit = quotas.id_lit_q LEFT JOIN affectation ON codif_lit.id_lit = affectation.id_lit WHERE quotas.NiveauFormation = '$classe' AND codif_lit.sexe = '$sexe' LIMIT $limit OFFSET $offset";
    $resultRequeteLitClasseEtudiant = mysqli_query($connexion, $requeteLitClasseEtudiant);
    return $resultRequeteLitClasseEtudiant;
}

// Fonction d'affichage de la Liste de toutes les pavillons, elle est appeler dans requette.php et affiché dans la page listeLits.php
function getAllPavillon($sexe)
{
    global $connexion;
    global $limit, $offset;
    $requetePavillon = "SELECT DISTINCT (pavillon) FROM `codif_lit` WHERE codif_lit.sexe = '$sexe'";
    $resultatRequetePavillon = mysqli_query($connexion, $requetePavillon);
    return $resultatRequetePavillon;
}

// Comptez le nombre total d'options dans la base de données: pagination total lit dans la page listeLits.php
function getAllLitPagination($sexe)
{
    global $connexion, $limit, $count_data_total;
    $count_queryTotalLit = "SELECT COUNT(*) as total FROM codif_lit WHERE codif_lit.sexe = '$sexe'";
    $count_resultat_total = mysqli_query($connexion, $count_queryTotalLit);
    if ($count_resultat_total) {
        $count_data_total = mysqli_fetch_assoc($count_resultat_total);
        $total_lit_pages = ceil($count_data_total['total'] / $limit);
        return $total_lit_pages;
    } else {
        $total_lit_pages = 1;
        return $total_lit_pages;
    }
}

// Comptez le nombre total d'options dans la base de données: pagination liste lits d'une classe selon l'etudiant connecté dans la page codifier.php
function getLitByStudent($classe, $sexe)
{
    global $connexion, $limit, $count_dataEtudiant;
    $count_queryEtudiant = "SELECT COUNT(*) as total FROM quotas JOIN codif_lit ON quotas.id_lit_q = codif_lit.id_lit WHERE `NiveauFormation`='$classe' AND codif_lit.sexe = '$sexe'";
    $count_resultEtudiant = mysqli_query($connexion, $count_queryEtudiant);
    if ($count_resultEtudiant) {
        $count_dataEtudiant = mysqli_fetch_assoc($count_resultEtudiant);
        $total_pagesEtudiant = ceil($count_dataEtudiant['total'] / $limit);
        return $total_pagesEtudiant;
    } else {
        $total_pagesEtudiant = 1;
        return $total_pagesEtudiant;
    }
}

// Comptez le nombre total d'options dans la base de données details lits affecter (quotas)
function getLitByQuotas($classe, $sexe)
{
    global $connexion, $limit, $count_datas;
    $count_querys = "SELECT COUNT(*) as total FROM quotas JOIN codif_lit ON quotas.id_lit_q = codif_lit.id_lit WHERE `NiveauFormation`='$classe' AND codif_lit.sexe = '$sexe'";
    $count_results = mysqli_query($connexion, $count_querys);
    if ($count_results) {
        $count_datas = mysqli_fetch_assoc($count_results);
        $total_pagess = ceil($count_datas['total'] / $limit);
        return $total_pagess;
    } else {
        $total_pagess = 1;
        return $total_pagess;
    }
}

//Fonction pour enregistrer les donnees des quotas
function addQuotas($buttonId, $user, $NiveauFormation)
{
    global $connexion;
    $date = date("Y-n-j");
    $requeteInsertQuotas = "INSERT INTO `quotas` (`id_lit_q`, `username_user`, `NiveauFormation`, `annee`) VALUES ('$buttonId', '$user', '$NiveauFormation', '$date')";
    $requete = $connexion->prepare($requeteInsertQuotas);
    $requete->execute();
    return header('Location: ../profils/personnels/listeLits.php');
}

//Fonction permet l'enregistrement des lit validé par le personnels
function setValidation($buttonId, $user)
{
    global $connexion, $requete;
    $date = date("Y-n-j");
    $requeteInsertQuotas = "INSERT INTO `validation_lit` (`id_aff`, `username_user`, `dateTime_val`) VALUES ('$buttonId', '$user', '$date')";
    $requete = $connexion->prepare($requeteInsertQuotas);
    return $requete->execute();
}

//Fonction permet l'enregistrement des paiements de lit validé par le personnels
function setPaiement($buttonId, $user)
{
    global $connexion, $requete;
    $date = date("Y-n-j");
    $requeteInsertQuotas = "INSERT INTO `paiement_caution` (`id_val`, `username_user`, `dateTime_paie`) VALUES ('$buttonId', '$user', '$date')";
    $requete = $connexion->prepare($requeteInsertQuotas);
    return $requete->execute();
}

//Fonction permet l'enregistrement des paiements de lit validé par le personnels
function setLoger($buttonId, $user)
{
    global $connexion, $requete;
    $date = date("Y-n-j");
    $requeteInsertQuotas = "INSERT INTO `loger` (`id_paie`, `dateTime_loger`, `username_user`) VALUES ('$buttonId', '$date', '$user')";
    $requete = $connexion->prepare($requeteInsertQuotas);
    return $requete->execute();
}

//Fonction pour retiré les quotas deja affecter
function removeQuotas($buttonId)
{
    global $connexion;
    $sql0 = "DELETE FROM quotas WHERE id_lit_q = '$buttonId'";
    $query0 = $connexion->prepare($sql0);
    $query0->execute();
    return header('Location: ../profils/personnels/detailsLits.php');
}

// Fonction d'affichage de l'etudiant ayant deja choisi une lit 
function getStudentChoiseLit($idEtu)
{
    global $connexion;
    $requeteAffectEtu = "SELECT * FROM `affectation` where `id_etu`=$idEtu";
    $inforequeteAffectEtu = $connexion->query($requeteAffectEtu);
    return $inforequeteAffectEtu;
}

// Fonction d'affichage du lit deja choisie par l'etudiant connecté
function getOneLitByStudent($idEtu)
{
    global $connexion;
    $requeteLitEtu = "SELECT codif_lit.* FROM affectation JOIN codif_lit ON affectation.id_lit = codif_lit.id_lit where `id_etu`='$idEtu'";
    $resultatReqLitEtu = $connexion->query($requeteLitEtu);
    return $resultatReqLitEtu;
}

// Fonction d'affichage du lit choisi par l'etudiant, cette fonction sera appeler dans le fichier du convention
function getLitOneStudentByConvention($lit)
{
    global $connexion;
    $i = 0;
    $requeteLit = "SELECT * FROM `codif_lit` WHERE `id_lit`='$lit'";
    $resultRequeteLit = mysqli_query($connexion, $requeteLit);
    while ($row = mysqli_fetch_array($resultRequeteLit)) {
        $tab[$i] = $row;
        $i++;
    }
    return $tab;
}

// Fonction d'affichage de la date que l'etudiant a choisi le lit
function getDateLitByStudent($idLit)
{
    global $connexion;
    $requeteDateLit = "SELECT `dateTime` FROM `affectation` WHERE `id_lit`='$idLit'";
    $resultRequeteDateLit = mysqli_query($connexion, $requeteDateLit);
    while ($row = mysqli_fetch_array($resultRequeteDateLit)) {
        $dateLit = $row;
    }
    $timestamp = strtotime($dateLit["dateTime"]);
    $date_formatee = date("d-m-Y", $timestamp);
    return $date_formatee;
}

// Fonction de connexion dans l'espace utilisateur
function login($username, $password)
{
    global $connexion;
    $users = "SELECT * FROM `codif_user` where `username_user`='$username' and `password_user`='$password'";
    $info = $connexion->query($users);
    return $info->fetch_assoc();
}

// Fonction de verification du politique de confidentialité
function getPolitiqueConf($id)
{
    global $connexion;
    $usersPolitique = "SELECT * FROM `politique_conf` where `id_etu`='$id'";
    $infoPolitique = mysqli_query($connexion, $usersPolitique);
    return $infoPolitique->fetch_assoc();
}

// Fonction de filtre de la liste des lits
function setFiltre($filter, $sexe)
{
    global $connexion, $limit, $offset;
    $sqlFilter = "SELECT codif_lit.*, CASE WHEN quotas.id_lit_q IS NOT NULL AND affectation.id_lit IS NOT NULL THEN 'Migré dans les deux' WHEN quotas.id_lit_q IS NOT NULL THEN 'Migré vers quotas uniquement' WHEN affectation.id_lit IS NOT NULL THEN 'Migré vers affectation uniquement' ELSE 'Non migré' END AS statut_migration FROM codif_lit LEFT JOIN quotas ON codif_lit.id_lit = quotas.id_lit_q LEFT JOIN affectation ON codif_lit.id_lit = affectation.id_lit WHERE pavillon='$filter' AND codif_lit.sexe = '$sexe' LIMIT $limit OFFSET $offset";
    if ($filter) {
        $resultatRequeteTotalLit = mysqli_query($connexion, $sqlFilter);
        return $resultatRequeteTotalLit;
    }
}

// Fonction du pagination du filtre, cette fonction sera appeler dans la page listeLits.php
function getPaginationFiltre($filter, $sexe)
{
    global $connexion, $limit, $offset, $count_data_total;
    $count_queryTotalLit = "SELECT COUNT(*) as total, CASE WHEN quotas.id_lit_q IS NOT NULL AND affectation.id_lit IS NOT NULL THEN 'Migré dans les deux' WHEN quotas.id_lit_q IS NOT NULL THEN 'Migré vers quotas uniquement' WHEN affectation.id_lit IS NOT NULL THEN 'Migré vers affectation uniquement' ELSE 'Non migré' END AS statut_migration FROM codif_lit LEFT JOIN quotas ON codif_lit.id_lit = quotas.id_lit_q LEFT JOIN affectation ON codif_lit.id_lit = affectation.id_lit WHERE pavillon='$filter' AND codif_lit.sexe = '$sexe' LIMIT $limit OFFSET $offset";
    $count_resultat_total = mysqli_query($connexion, $count_queryTotalLit);
    if ($count_resultat_total) {
        $count_data_total = mysqli_fetch_assoc($count_resultat_total);
        $total_lit_pages = ceil($count_data_total['total'] / $limit);
        return $total_lit_pages;
    } else {
        $total_lit_pages = 1;
        return $total_lit_pages;
    }
}
// Fonction du pagination du filtre, cette fonction sera appeler dans la page listeLits.php
function getPaginationFiltreClasse($filter, $sexe)
{
    global $connexion, $limit, $offset, $count_data_total;
    $count_queryTotalLit = "SELECT COUNT(*) as total, CASE WHEN quotas.id_lit_q IS NOT NULL AND affectation.id_lit IS NOT NULL THEN 'Migré dans les deux' WHEN quotas.id_lit_q IS NOT NULL THEN 'Migré vers quotas uniquement' WHEN affectation.id_lit IS NOT NULL THEN 'Migré vers affectation uniquement' ELSE 'Non migré' END AS statut_migration FROM codif_lit LEFT JOIN quotas ON codif_lit.id_lit = quotas.id_lit_q LEFT JOIN affectation ON codif_lit.id_lit = affectation.id_lit WHERE pavillon='$filter' AND codif_lit.sexe = '$sexe' LIMIT $limit OFFSET $offset";
    $count_resultat_total = mysqli_query($connexion, $count_queryTotalLit);
    if ($count_resultat_total) {
        $count_data_total = mysqli_fetch_assoc($count_resultat_total);
        $total_lit_pages = ceil($count_data_total['total'] / $limit);
        return $total_lit_pages;
    } else {
        $total_lit_pages = 1;
        return $total_lit_pages;
    }
}

// Fonction d'affichage les information de l'utilisateur connecté (etudiant)
function studentConnect($username)
{
    global $connexion;
    $users = "SELECT * FROM `codif_etudiant` where `num_etu`='$username'";
    $info = $connexion->query($users);
    return $info->fetch_assoc();
}

// Fonction d'affichage les information de l'utilisateur connecté (personnel)
function personnelConnect($username)
{
    global $connexion;
    $users = "SELECT * FROM `users` where `num_etu`='$username'";
    $info = $connexion->query($users);
    return $info->fetch_assoc();
}

// Fonction pour récupérer les informations de l'étudiant pour le paiement de la caution
function infoStudentPaie($numEtudiant)
{
    global $connexion;
    $sql = "SELECT e.nom, e.prenom,a.id, e.numEtudiant, e.niveau,e.datenaissance,e.lieu_naissance, l.pavillon, l.chambre, l.litFROM etudiant e JOIN affectation a ON e.id = a.idEtudiant JOIN lit l ON a.idLit = l.id WHERE e.numEtudiant = ?";
    $stmt = $connexion->prepare($sql);
    $stmt->bind_param("s", $numEtudiant);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result;
}

// Fonction d'affichage du format date
function dateFromat($date)
{
    $timestamp = strtotime($date);
    $date_formatee = date("d-m-Y", $timestamp);
    return $date_formatee;
}
