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
function getOneByValidatePaiement($num_etu, $pavillon)
{
    global $connexion;
    $requeteLitClasseValide = "SELECT ce.*, cl.*, vl.*, pc.*, CASE WHEN l.id_paie IS NOT NULL THEN 'Migré' ELSE 'Non migré' END AS etat_id_paie FROM codif_etudiant ce JOIN affectation a ON ce.id_etu = a.id_etu JOIN validation_lit vl ON a.id_aff = vl.id_aff JOIN codif_lit cl ON a.id_lit = cl.id_lit LEFT JOIN paiement_caution pc ON vl.id_val = pc.id_val LEFT JOIN loger l ON pc.id_paie = l.id_paie WHERE ce.num_etu = '$num_etu' && CL.pavillon ='$pavillon'";
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

//Fonction permet l'enregistrement du logement du titulaire
function setLoger($buttonId, $user)
{
    global $connexion, $requete;
    $date = date("Y-n-j");
    $requeteInsertQuotas = "INSERT INTO `loger` (`id_paie`, `dateTime_loger`, `username_user`) VALUES ('$buttonId', '$date', '$user')";
    $requete = $connexion->prepare($requeteInsertQuotas);
    return $requete->execute();
}

//Fonction permet l'enregistrement du lpgement du suppleant
function setLogerSuppleant($buttonId, $user)
{
    global $connexion, $requete;
    $date = date("Y-n-j");
    $requeteInsertQuotas = "INSERT INTO `loger` (`id_val`, `dateTime_loger`, `username_user`) VALUES ('$buttonId', '$date', '$user')";
    $requete = $connexion->prepare($requeteInsertQuotas);
    return $requete->execute();
}

//Fonction pour retiré les quotas deja affecter
function removeQuotas($buttonId)
{
    global $connexion;
    $sql0 = "DELETE FROM quotas WHERE id_lit_q = '$buttonId'";
    $query0 = $connexion->prepare($sql0);
    return $query0->execute();
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
    global $connexion;
    $sqlFilter = "SELECT codif_lit.*, CASE WHEN quotas.id_lit_q IS NOT NULL AND affectation.id_lit IS NOT NULL THEN 'Migré dans les deux' WHEN quotas.id_lit_q IS NOT NULL THEN 'Migré vers quotas uniquement' WHEN affectation.id_lit IS NOT NULL THEN 'Migré vers affectation uniquement' ELSE 'Non migré' END AS statut_migration FROM codif_lit LEFT JOIN quotas ON codif_lit.id_lit = quotas.id_lit_q LEFT JOIN affectation ON codif_lit.id_lit = affectation.id_lit WHERE pavillon='$filter' AND codif_lit.sexe = '$sexe'";
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
    $date_formatee = date("Y-m-d", $timestamp);
    return $date_formatee;
}

// Fonction pour verifier si lE TITULAIRE a valider son hebergement
function getChoixLitByStudent($numEtudiant)
{
    global $connexion;
    $studentValidate = "SELECT * FROM affectation JOIN codif_etudiant ON codif_etudiant.id_etu = affectation.id_etu WHERE codif_etudiant.num_etu='$numEtudiant'";
    $infoValite = mysqli_query($connexion, $studentValidate);
    $data = $infoValite->fetch_assoc();
    // return $data;
    if ($data) {
        return "VEUILLEZ-VOUS RAPPROCHER DU SERVICE DE L'HEBERGEMENT POUR COMPLETER VOTRE CODIFICATION !!!";
    } else {
        return "VEUILLER CHOISIR UN LIT POUR DEMMARRER VOTRE CODIFICATION, <a href='/coud/codif/profils/etudiants/codifier.php'>CLIQUER ICI</a>";
    }
}

// Fonction pour verifier au suppleant que si son etudiant titulaire a valider son lit
function getChoixLitByTitulaireOfSuppleant($numEtudiantTitulaireOfSupp)
{
    global $connexion;
    $studentValidate = "SELECT * FROM affectation JOIN codif_etudiant ON codif_etudiant.id_etu = affectation.id_etu WHERE codif_etudiant.num_etu='$numEtudiantTitulaireOfSupp'";
    $infoValite = mysqli_query($connexion, $studentValidate);
    $data = $infoValite->fetch_assoc();
    // return $data;
    if ($data) {
        return "Votre titulaire a valider son lit, Veuillez-vous rapprocher du service de l'hebergement pour completer votre codification !!!";
    } else {
        return "Votre Titulaire n'en pas encore faire le choix de son lit, veuiller lui patienter !!!";
    }
}

// Fonction pour verifier si le TITULAIRE a valider son hebergement
function getValidateLitByStudent($numEtudiant)
{
    global $connexion;
    $studentValidate = "SELECT * FROM validation_lit JOIN affectation ON validation_lit.id_aff = affectation.id_aff JOIN codif_etudiant ON codif_etudiant.id_etu = affectation.id_etu WHERE codif_etudiant.num_etu='$numEtudiant'";
    $infoValite = mysqli_query($connexion, $studentValidate);
    $data = $infoValite->fetch_assoc();
    // return $data;
    if ($data) {
        return "VOTRE LIT est VALIDER, VEUILLEZ-VOUS RAPPROCHER DE L'ACP POUR PAYER VOTRE CAUTION";
    }
}
// Fonction pour verifier si le TITULAIRE a valider son hebergement
function getValidateLitByStudent2($numEtudiant)
{
    global $connexion;
    $studentValidate = "SELECT * FROM validation_lit JOIN affectation ON validation_lit.id_aff = affectation.id_aff JOIN codif_etudiant ON codif_etudiant.id_etu = affectation.id_etu WHERE codif_etudiant.num_etu='$numEtudiant'";
    $infoValite = mysqli_query($connexion, $studentValidate);
    $data = $infoValite->fetch_assoc();
    // return $data;
    if ($data) {
        return "VOTRE LIT est VALIDER, VEUILLEZ-VOUS RAPPROCHER DE L'ACP POUR PAYER VOTRE CAUTION";
    } else {
        return "VEUILLEZ-VOUS RAPPROCHER DU SERVICE DE L'HEBERGEMENT POUR COMPLETER VOTRE CODIFICATION !!!";
    }
}

// Fonction pour verifier si le TITULAIRE a valider son hebergement
function getValidateLitByTitulaireOfSuppleant($numEtudiant)
{
    global $connexion;
    $studentValidate = "SELECT * FROM validation_lit JOIN affectation ON validation_lit.id_aff = affectation.id_aff JOIN codif_etudiant ON codif_etudiant.id_etu = affectation.id_etu WHERE codif_etudiant.num_etu='$numEtudiant'";
    $infoValite = mysqli_query($connexion, $studentValidate);
    return $infoValite->fetch_assoc();
}

// Fonction pour verifier si le SUPPLEANT a valider son hebergement
function getValidateLitBySuppleant($numEtudiant)
{
    global $connexion;
    $studentValidate = "SELECT 
    affectation.*,
    codif_etudiant.*,
    codif_lit.*,
    loger.*,
    validation_lit.*,
    CASE WHEN loger.id_val IS NOT NULL THEN 'Migré' ELSE 'Non migré' END AS etat_id_val 
FROM 
    validation_lit
    JOIN affectation ON validation_lit.id_aff = affectation.id_aff
    JOIN codif_etudiant ON codif_etudiant.id_etu = affectation.id_etu
    JOIN codif_lit ON codif_lit.id_lit = affectation.id_lit
    LEFT JOIN loger ON loger.id_val = validation_lit.id_val  
WHERE 
    codif_etudiant.num_etu = '$numEtudiant'";
    $infoValite = mysqli_query($connexion, $studentValidate);
    return $infoValite->fetch_assoc();
}

// Fonction pour verifier si le TITULAIRE a valider son hebergement
function getValidateLogerByStudent($numEtudiant)
{
    global $connexion;
    $studentValidatePaie = "SELECT * FROM `loger` JOIN paiement_caution ON paiement_caution.id_paie = loger.id_paie JOIN validation_lit ON validation_lit.id_val = paiement_caution.id_val JOIN affectation ON affectation.id_aff = validation_lit.id_aff JOIN codif_etudiant ON codif_etudiant.id_etu = affectation.id_etu WHERE codif_etudiant.num_etu ='$numEtudiant'";
    $infoValitePaie = mysqli_query($connexion, $studentValidatePaie);
    $data = $infoValitePaie->fetch_assoc();
    // return $data;
    if ($data) {
        return "VOUS AVEZ DEJA LOGER !!!";
    } else {
        if (getValidatePaiementLitByStudent($numEtudiant)) {
            return getValidatePaiementLitByStudent($numEtudiant);
        } else {
            if (getValidateLitByStudent($numEtudiant)) {
                return getValidateLitByStudent($numEtudiant);
            } else {
                if (getChoixLitByStudent($numEtudiant)) {
                    return getChoixLitByStudent($numEtudiant);
                }
            }
        }
    }
}
// Fonction pour verifier si le TITULAIRE au suppleant a valider son hebergement
function getValidateLogerByStudentTitulaireOnSuppleant($numEtudiant)
{
    global $connexion;
    $studentValidatePaie = "SELECT * FROM `loger` JOIN paiement_caution ON paiement_caution.id_paie = loger.id_paie JOIN validation_lit ON validation_lit.id_val = paiement_caution.id_val JOIN affectation ON affectation.id_aff = validation_lit.id_aff JOIN codif_etudiant ON codif_etudiant.id_etu = affectation.id_etu WHERE codif_etudiant.num_etu ='$numEtudiant'";
    $infoValitePaie = mysqli_query($connexion, $studentValidatePaie);
    return $infoValitePaie->fetch_assoc();
}

// Fonction pour verifier si le SUPPLEANT a valider son hebergement
function getValidateLogerBySuppleant($numEtudiant)
{
    global $connexion;
    $studentValidatePaie = "SELECT * FROM `loger` JOIN paiement_caution ON paiement_caution.id_paie = loger.id_paie JOIN validation_lit ON validation_lit.id_val = paiement_caution.id_val JOIN affectation ON affectation.id_aff = validation_lit.id_aff JOIN codif_etudiant ON codif_etudiant.id_etu = affectation.id_etu WHERE codif_etudiant.num_etu ='$numEtudiant'";
    $infoValitePaie = mysqli_query($connexion, $studentValidatePaie);
    return $infoValitePaie->fetch_assoc();
}

// Fonction pour verifier si l'etudiant a valider son hebergement
function getValidatePaiementLitBySuppleant($numEtudiant)
{
    global $connexion;
    $studentValidatePaie = "SELECT * FROM paiement_caution JOIN validation_lit ON paiement_caution.id_val = validation_lit.id_val JOIN affectation ON affectation.id_aff = validation_lit.id_aff JOIN codif_etudiant ON codif_etudiant.id_etu =affectation.id_etu WHERE codif_etudiant.num_etu='$numEtudiant'";
    $infoValitePaie = mysqli_query($connexion, $studentValidatePaie);
    return $infoValitePaie->fetch_assoc();
}

// Fonction pour verifier si l'etudiant a valider son hebergement
function getValidatePaiementLitByStudent($numEtudiant)
{
    global $connexion;
    $studentValidatePaie = "SELECT * FROM paiement_caution JOIN validation_lit ON paiement_caution.id_val = validation_lit.id_val JOIN affectation ON affectation.id_aff = validation_lit.id_aff JOIN codif_etudiant ON codif_etudiant.id_etu =affectation.id_etu WHERE codif_etudiant.num_etu='$numEtudiant'";
    $infoValitePaie = mysqli_query($connexion, $studentValidatePaie);
    $data = $infoValitePaie->fetch_assoc();
    // return $data;
    if ($data) {
        return "VOUS AVEZ DEJA PAYER VOTRE CAUTION, MERCI DE VOUS RAPPROCHER DU CHEF DE PAVILLON POUR RECUPERER VOTRE CLE DE CHAMBRE";
    } else {
        if (getValidateLitByStudent($numEtudiant)) {
            return getValidateLitByStudent($numEtudiant);
        } else {
            if (getChoixLitByStudent($numEtudiant)) {
                return getChoixLitByStudent($numEtudiant);
            }
        }
    }
}

// Ajouter dans la table affectation lorsque l'etudiant choisi une lit
function addAffectation($lastValue, $idEtu)
{
    global $connexion;
    $requeteInsertAff = "INSERT INTO `affectation` (`id_lit`, `id_etu`, `dateTime_aff`, `statut`) VALUES ($lastValue, $idEtu, NOW(), 'attributaire')";
    $requeteEtu = $connexion->prepare($requeteInsertAff);
    return $requeteEtu->execute();
}

// Ajouter dans la table affectation l'etudiant suppleant via son titulaire
function addAffectationOnSuppleant($lastValue, $idEtu)
{
    global $connexion;
    $requeteInsertAff = "INSERT INTO `affectation` (`id_lit`, `id_etu`, `dateTime_aff`, `statut`) VALUES ($lastValue, $idEtu, NOW(), 'suppleant')";
    $requeteEtu = $connexion->prepare($requeteInsertAff);
    return $requeteEtu->execute();
}

// Fonction de traitement du politique de confidentiellité
function addPolitiqueConf($idEtu)
{
    global $connexion;
    $requeteInsert = "INSERT INTO `politique_conf` (`id_etu`, `dateTime`) VALUES ($idEtu, NOW())";
    $sql = $connexion->prepare($requeteInsert);
    return $sql->execute();
}


/********************************************************************************** */
// Fonction qui me retourne le quota de n'importe quelle classe
function getQuotaClasse($classe, $sexe)
{
    global $connexion;
    $requeteQuotaClasse = "SELECT COUNT(*) FROM `quotas` JOIN codif_lit ON codif_lit.id_lit = quotas.id_lit_q WHERE `NiveauFormation` = '$classe' AND codif_lit.sexe = '$sexe'";
    $resultRequeteQuotaClasse = mysqli_query($connexion, $requeteQuotaClasse);
    return $resultRequeteQuotaClasse->fetch_assoc();
}

// Fonction d'affichage de la liste des etudiant beneficiaire de lit titulaire et quota
function getStatutStudentByQuota($quota, $classe, $sexe)
{
    global $connexion;
    $requeteListeClasse = "SELECT 
    ce.id_etu, 
    ce.prenoms, 
    ce.nom, 
    ce.num_etu, 
    ce.sessionId, 
    ce.moyenne, 
    ce.niveauFormation,
    COALESCE(ranks.rang, 'N/A') AS rang, 
    CASE 
        WHEN cf.id_etu IS NOT NULL THEN 'forclus' 
        WHEN ranks.rang <= $quota THEN 'attributaire' 
        WHEN ranks.rang <= $quota*2 THEN 'suppleant' 
        ELSE 'non attributaire' 
    END AS statut 
FROM codif_etudiant ce
LEFT JOIN (
    SELECT 
        id_etu, 
        ROW_NUMBER() OVER (ORDER BY sessionId ASC, moyenne DESC, dateNaissance ASC, id_etu ASC) AS rang 
    FROM codif_etudiant 
    WHERE niveauFormation = '$classe' 
      AND sexe = '$sexe' 
      AND id_etu NOT IN (SELECT id_etu FROM codif_forclusion)
) ranks ON ce.id_etu = ranks.id_etu
LEFT JOIN codif_forclusion cf ON ce.id_etu = cf.id_etu
WHERE ce.niveauFormation = '$classe' 
  AND ce.sexe = 'F' 
ORDER BY rang ASC;
";
    $resultRequeteListeClasse = mysqli_query($connexion, $requeteListeClasse);
    return $resultRequeteListeClasse;
}
// Fonction d'affichage du statu de l'etudiant (titulaire ou suppleant) selon le quota de sa classe, le sexe et la moyenne
// function getStatutByOneStudent($quota, $classe, $sexe, $moyenne, $num_etu)
// {
//     global $connexion;
//     $requeteListeClasse = "SELECT prenoms, nom, num_etu, sessionId, rang, Min(dateNaissance), num_etu, CASE WHEN rang <= $quota THEN 'attributaire' WHEN rang <= $quota*2 THEN 'suppleant' ELSE 'non attributaire' END AS statut FROM ( SELECT prenoms, nom, num_etu, sessionId, moyenne, dateNaissance, ROW_NUMBER() OVER (order by sessionId ASC, moyenne desc, dateNaissance ASC) AS rang FROM codif_etudiant WHERE niveauFormation = '$classe' AND sexe = '$sexe' order by sessionId ASC,  moyenne DESC, dateNaissance ASC) AS ranked_students WHERE moyenne = $moyenne AND num_etu = '$num_etu' ORDER BY rang ";
//     $resultRequeteListeClasse = mysqli_query($connexion, $requeteListeClasse);
//     return $resultRequeteListeClasse->fetch_assoc();
// }

// Fonction d'affichage du statu de titulaire selon le rang de l'etudiant suppleant
function getStatutByOneStudentTitulaireOfSuppl($quota, $classe, $sexe, $rang)
{
    global $connexion;
    $requeteListeClasse = "SELECT prenoms, nom, num_etu, sessionId, moyenne, rang, CASE WHEN rang <= $quota THEN 'attributaire' WHEN rang <= $quota*2 THEN 'suppleant' ELSE 'non attributaire' END AS statut FROM ( SELECT prenoms, nom, num_etu, sessionId, moyenne, ROW_NUMBER() OVER (order by sessionId ASC, moyenne desc,dateNaissance ASC,id_etu asc) AS rang FROM codif_etudiant WHERE niveauFormation = '$classe' AND sexe = '$sexe' ) AS ranked_students WHERE rang = $rang-$quota ORDER BY rang";
    $resultRequeteListeClasse = mysqli_query($connexion, $requeteListeClasse);
    return $resultRequeteListeClasse->fetch_assoc();
}

// fonction d'affichage de la table delai
function getAllDelai($nature)
{
    global $connexion;
    $requete =  "SELECT * FROM codif_delai where nature ='$nature'";
    $resultRequete = mysqli_query($connexion, $requete);
    return $resultRequete->fetch_assoc();
}

// fonction pour recuperer le dernier etudiant avoir choisi un lit;
function getLastAffectation($numEtudiant)
{
    global $connexion;
    $studentValidate = "SELECT * FROM affectation JOIN codif_etudiant ON codif_etudiant.id_etu = affectation.id_etu WHERE codif_etudiant.num_etu='$numEtudiant' ORDER BY codif_etudiant.rang LIMIT 1";
    $infoValite = mysqli_query($connexion, $studentValidate);
    $data = $infoValite->fetch_assoc();
}

// Ajouter des etudiants dans la table forclu
function addForclu($id_etu, $id_delai)
{
    global $connexion;
    deleteValidation($id_etu);
    deleteAffectation($id_etu);
    $requeteFor = "INSERT INTO `codif_forclusion` (`id_etu`, `id_del`, `dateTime_for`) VALUES ($id_etu, $id_delai, NOW())";
    $a = $connexion->prepare($requeteFor);
    $a->execute();
}
// supprimer validation lit de l'etudiant forclu
function deleteValidation($id_etu)
{
    global $connexion;
    $requeteFor0 = "DELETE FROM validation_lit WHERE EXISTS (SELECT $id_etu FROM affectation JOIN codif_etudiant ON affectation.id_etu = codif_etudiant.id_etu WHERE validation_lit.id_aff = affectation.id_aff AND codif_etudiant.id_etu = '$id_etu')";
    $b = $connexion->prepare($requeteFor0);
    $b->execute();
}

// supprimer affectation lit de l'etudiant forclu
function deleteAffectation($id_etu)
{
    global $connexion;
    $requeteFor1 = "DELETE FROM affectation WHERE id_aff = (SELECT id_aff FROM affectation JOIN codif_etudiant ON affectation.id_etu = codif_etudiant.id_etu AND codif_etudiant.id_etu = '$id_etu')";
    $c = $connexion->prepare($requeteFor1);
    $c->execute();
}

// Verifier si l'etudiant est deja forclu
function getIsForclu($num_etu)
{
    global $connexion;
    $studentValidate = "SELECT * FROM `codif_forclusion` JOIN codif_etudiant ON codif_etudiant.id_etu =codif_forclusion.id_etu JOIN codif_delai ON codif_delai.id_delai = codif_forclusion.id_del WHERE codif_etudiant.num_etu = '$num_etu'";
    $infoValite = mysqli_query($connexion, $studentValidate);
    $data = $infoValite->fetch_assoc();
    return $data;
}

// Verifier si au moins un etudiant est forclus
function getAllForclu()
{
    global $connexion;
    $studentValidate = "SELECT * FROM codif_forclusion JOIN codif_delai ON codif_delai.id_delai = codif_forclusion.id_del";
    $infoValite = mysqli_query($connexion, $studentValidate);
    return $infoValite;
}

// Fonction pour recuperer le tableaux d'etudiants attributaire, suppleant, non-attributaire et forclos
function getAllDatastudentStatus($quota, $classe, $sexe)
{
    $listeClasse = getStatutStudentByQuota($quota, $classe, $sexe);
    $tableau_data_etudiant = [];
    $i = 0;
    while ($row = mysqli_fetch_array($listeClasse)) {
        $tableau_data_etudiant[$i] = $row;
        $i++;
    }
    return $tableau_data_etudiant;
}

// Fonction pour recuperer les données d'un etudiants attributaire, suppleant, non-attributaire et forclos
function getOnestudentStatus($quota, $classe, $sexe, $num_etu)
{
    $row_one_student = getAllDatastudentStatus($quota, $classe, $sexe);
    for ($i = 0; $i < count($row_one_student); $i++) {
        if ($num_etu == $row_one_student[$i]['num_etu']) {
            return $row_one_student[$i];
        }
    }
}

// Fonction pour recuperer les données de l'attributaire selon le rang du suppleant
function getOneTitulaireBySuppleant($quota, $classe, $sexe, $rang)
{
    $row_one_student = getAllDatastudentStatus($quota, $classe, $sexe);
    for ($i = 0; $i < count($row_one_student); $i++) {
        if ($row_one_student[$i]['rang'] == $rang - $quota) {
            return $row_one_student[$i];
        }
    }
}
