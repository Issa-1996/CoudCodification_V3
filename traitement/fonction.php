<?php

/********************************************************************************** 
Connectez-vous à votre base de données MySQL 
 **********************************************************************************/
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

/********************************************************************************** 
Les attributs de la pagination: Pagination par page de 54 elements
 ********************************************************************************* */
function getAttributByPagination()
{
    global $page, $limit, $offset, $counter;
    $page = isset($_GET['page']) ? $_GET['page'] : 1;
    $limit = 90;
    $offset = ($page - 1) * $limit;
    $counter = 0;
}
getAttributByPagination();

/********************************************************************************** 
Fonction d'affichage de la liste des etablissements, elle est appeler dans requette.php et affiché dans la page niveau.php
 ********************************************************************************* */
function getAllEtablissement()
{
    global $connexion;
    $requeteListeEtablissement = "SELECT DISTINCT (etablissement) FROM `codif_etudiant`";
    $resultatRequeteEtablissement = mysqli_query($connexion, $requeteListeEtablissement);
    return $resultatRequeteEtablissement;
}

/********************************************************************************** 
Fonction d'affichage de la liste des Niveau de formation, elle est appeler dans connecte.php 
 ********************************************************************************* */
function getAllNiveauFormation()
{
    global $connexion;
    $requeteListeEtablissement = "SELECT DISTINCT (niveauFormation) FROM `codif_etudiant`";
    $resultatRequeteEtablissement = mysqli_query($connexion, $requeteListeEtablissement);
    return $resultatRequeteEtablissement;
}

/********************************************************************************** 
<<<<<<< HEAD
Fonction d'affichage de la liste des Niveau de formation, elle est appeler dans connecte.php dans la table quota
 ********************************************************************************* */
function getAllNiveauFormationByQuota()
{
    global $connexion;
    $requeteListeEtablissement = "SELECT DISTINCT (niveauFormation) FROM `codif_quota`";
    $resultatRequeteEtablissement = mysqli_query($connexion, $requeteListeEtablissement);
    return $resultatRequeteEtablissement;
}

/********************************************************************************** 
=======
>>>>>>> 4ab3e8d6e0d4478baf0139928fb896d9191d7523
Fonction d'affichage de la liste des departement, elle est appeler dans requette.php et affiché dans la page niveau.php
 ********************************************************************************* */
function getAllDepartement($dataFaculte)
{
    global $connexion;
    $requeteListeDepartement = "SELECT DISTINCT(departement) FROM `codif_etudiant` WHERE `etablissement`='" . $dataFaculte . "'";
    $resultatRequeteDepartement = mysqli_query($connexion, $requeteListeDepartement);
    return $resultatRequeteDepartement;
}

/********************************************************************************** 
Fonction d'affichage de la liste des departement sous forme d'un tableau de donnée, elle est appeler dans requette.php et affiché dans la page niveau.php
 ********************************************************************************* */
function getOneByDepartemennt($dataDepartement)
{
    $i = 0;
    while ($rowDepartement = mysqli_fetch_array($dataDepartement)) {
        $tableauDataFaculte[$i] = $rowDepartement['departement'];
        $i++;
    }
    return $tableauDataFaculte;
}

/********************************************************************************** 
Fonction d'affichage de la liste des niveaux de formation, elle est appeler dans requette.php et affiché dans la page niveau.php
 ********************************************************************************* */
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

/********************************************************************************** 
Fonction d'affichage de la Liste des chambres deja affecter a une classe selon le niveau de formation, elle est appeler dans requette.php et affiché dans la page detailsLits.php
 ********************************************************************************* */
function getLitOneByNiveau($classe, $sexe)
{
    global $connexion, $limit, $offset;
    $requeteLitClasse = "SELECT DISTINCT codif_lit.*, CASE WHEN codif_quota.id_lit_q IS NOT NULL AND codif_affectation.id_lit IS NOT NULL THEN 'Migré dans les deux' WHEN codif_quota.id_lit_q IS NOT NULL THEN 'Migré vers codif_quota uniquement' WHEN codif_affectation.id_lit IS NOT NULL THEN 'Migré vers codif_affectation uniquement' ELSE 'Non migré' END AS statut_migration FROM codif_lit LEFT JOIN codif_quota ON codif_lit.id_lit = codif_quota.id_lit_q LEFT JOIN codif_affectation ON codif_lit.id_lit = codif_affectation.id_lit WHERE codif_quota.NiveauFormation = '$classe' AND codif_lit.sexe='$sexe' LIMIT $limit OFFSET $offset";
    $resultatRequeteLitClasse = mysqli_query($connexion, $requeteLitClasse);
    return $resultatRequeteLitClasse;
}

/********************************************************************************** 
Fonction d'affichage de la Liste des pavillon deja affecter a une classe selon le niveau de formation, elle est appeler dans requette.php et affiché dans la page detailsLits.php (elle sert de filtre des pavillon)
 ********************************************************************************* */
function getPavillonOneByNiveau($classe, $sexe)
{
    global $connexion, $limit, $offset;
    $requeteLitClasse = "SELECT DISTINCT pavillon, CASE WHEN codif_quota.id_lit_q IS NOT NULL AND codif_affectation.id_lit IS NOT NULL THEN 'Migré dans les deux' WHEN codif_quota.id_lit_q IS NOT NULL THEN 'Migré vers codif_quota uniquement' WHEN codif_affectation.id_lit IS NOT NULL THEN 'Migré vers codif_affectation uniquement' ELSE 'Non migré' END AS statut_migration FROM codif_lit LEFT JOIN codif_quota ON codif_lit.id_lit = codif_quota.id_lit_q LEFT JOIN codif_affectation ON codif_lit.id_lit = codif_affectation.id_lit WHERE codif_quota.NiveauFormation = '$classe' AND codif_lit.sexe='$sexe' LIMIT $limit OFFSET $offset";
    $resultatRequeteLitClasse = mysqli_query($connexion, $requeteLitClasse);
    return $resultatRequeteLitClasse;
}

/********************************************************************************** 
Fonction d'affichage de la Liste des chambres deja affecter a une classe selon le niveau de formation, elle est appeler dans requette.php et affiché dans la page detailsLits.php
 ********************************************************************************* */
function getLitOneByNiveauFromPersonnel($classe, $sexe)
{
    global $connexion;
    $requeteLitClasse = "SELECT codif_affectation.*, codif_etudiant.*, codif_lit.*, CASE WHEN vl.id_aff IS NOT NULL THEN 'Migré' ELSE 'Non migré' END AS migration_status FROM codif_affectation INNER JOIN codif_etudiant ON codif_affectation.id_etu = codif_etudiant.id_etu INNER JOIN codif_lit ON codif_affectation.id_lit = codif_lit.id_lit LEFT JOIN codif_validation vl ON codif_affectation.id_aff = vl.id_aff WHERE codif_etudiant.niveauFormation = '$classe' AND codif_lit.sexe='$sexe'";
    $resultatRequeteLitClasse = mysqli_query($connexion, $requeteLitClasse);
    return $resultatRequeteLitClasse;
}

/********************************************************************************** 
Fonction d'affichage des information du lit deja choisi selon son numero etudiant, elle sera appeler dans la page validation
 ********************************************************************************* */
function getOneByAffectation($num_etu)
{
    global $connexion;
    $requeteLitClasse = "SELECT *, CASE WHEN vl.id_aff IS NOT NULL THEN 'Migré' ELSE 'Non migré' END AS migration_status FROM codif_affectation INNER JOIN codif_etudiant ON codif_affectation.id_etu = codif_etudiant.id_etu INNER JOIN codif_lit ON codif_affectation.id_lit = codif_lit.id_lit LEFT JOIN codif_validation vl ON codif_affectation.id_aff = vl.id_aff WHERE codif_etudiant.num_etu = '$num_etu'";
    $resultatRequeteLitClasse = mysqli_query($connexion, $requeteLitClasse);
    return $resultatRequeteLitClasse;
}
function getOneByAffectation2($num_etu)
{
    global $connexion;
    $requeteLitClasse = "SELECT *, CASE WHEN vl.id_aff IS NOT NULL THEN 'Migré' ELSE 'Non migré' END AS migration_status FROM codif_affectation INNER JOIN codif_etudiant ON codif_affectation.id_etu = codif_etudiant.id_etu INNER JOIN codif_lit ON codif_affectation.id_lit = codif_lit.id_lit LEFT JOIN codif_validation vl ON codif_affectation.id_aff = vl.id_aff WHERE codif_etudiant.num_etu = '$num_etu'";
    $resultatRequeteLitClasse = mysqli_query($connexion, $requeteLitClasse);
    return $resultatRequeteLitClasse->fetch_assoc();
}

/********************************************************************************** 
Fonction d'affichage des information du lit deja valider par le personnel selon son numero etudiant, elle sera appeler dans la page paiement
 ********************************************************************************* */
function getOneByValidate($num_etu)
{
    global $connexion;
    $requeteLitClasseValide = "SELECT *, vl.id_val, CASE WHEN pc.id_val IS NOT NULL THEN 'Migré dans codif_paiement' WHEN codif_paiement.id_val IS NOT NULL THEN 'Migré dans autre_table' ELSE 'Non migré' END AS migration_status FROM codif_validation vl JOIN codif_affectation a ON vl.id_aff = a.id_aff JOIN codif_etudiant ce ON a.id_etu = ce.id_etu JOIN codif_lit cl ON a.id_lit = cl.id_lit LEFT JOIN codif_paiement pc ON vl.id_val = pc.id_val LEFT JOIN codif_paiement ON vl.id_val = codif_paiement.id_val WHERE ce.num_etu = '$num_etu'";
    $resultatRequeteLitClasseValide = mysqli_query($connexion, $requeteLitClasseValide);
    return $resultatRequeteLitClasseValide;
}

/********************************************************************************** 
Fonction d'affichage des information du lit deja valider par le personnel selon son numero etudiant, elle sera appeler dans la page paiement
 ********************************************************************************* */
function getOneByValidatePaiement($num_etu, $pavillon)
{
    global $connexion;
    $requeteLitClasseValide = "SELECT *, CASE WHEN l.id_paie IS NOT NULL THEN 'Migré' ELSE 'Non migré' END AS etat_id_paie FROM codif_etudiant ce JOIN codif_affectation a ON ce.id_etu = a.id_etu JOIN codif_validation vl ON a.id_aff = vl.id_aff JOIN codif_lit cl ON a.id_lit = cl.id_lit LEFT JOIN codif_paiement pc ON vl.id_val = pc.id_val LEFT JOIN codif_loger l ON pc.id_paie = l.id_paie WHERE ce.num_etu = '$num_etu' && CL.pavillon ='$pavillon'";
    $resultatRequeteLitClasseValide = mysqli_query($connexion, $requeteLitClasseValide);
    return $resultatRequeteLitClasseValide;
}

/********************************************************************************** 
Fonction d'affichage de la Liste des chambres aavec les option migré et non migré, elle est appeler dans requette.php et affiché dans la page listeLits.php
 ********************************************************************************* */
function getAllLit($sexe)
{
    global $connexion, $limit, $offset;
    $sql = "SELECT codif_lit.*, CASE WHEN codif_quota.id_lit_q IS NOT NULL THEN 'Migré' ELSE 'Non migré' END AS statut_migration FROM codif_lit LEFT JOIN codif_quota ON codif_lit.id_lit = codif_quota.id_lit_q WHERE codif_lit.sexe = '$sexe' LIMIT $limit OFFSET $offset";
    $resultatRequeteTotalLit = mysqli_query($connexion, $sql);
    return $resultatRequeteTotalLit;
}

/********************************************************************************** 
Fonction d'affichage de la Liste des chambres deja affecter a une classe selon la classe, elle est appeler dans requette.php et affiché dans la page codifier.php
 ********************************************************************************* */
function getLitValideByClasse($classe, $sexe)
{
    global $connexion, $limit, $offset;
    $requeteLitClasseEtudiant = "SELECT DISTINCT codif_lit.*, CASE WHEN codif_quota.id_lit_q IS NOT NULL AND codif_affectation.id_lit IS NOT NULL THEN 'Migré dans les deux' WHEN codif_quota.id_lit_q IS NOT NULL THEN 'Migré vers codif_quota uniquement' WHEN codif_affectation.id_lit IS NOT NULL THEN 'Migré vers codif_affectation uniquement' ELSE 'Non migré' END AS statut_migration FROM codif_lit LEFT JOIN codif_quota ON codif_lit.id_lit = codif_quota.id_lit_q LEFT JOIN codif_affectation ON codif_lit.id_lit = codif_affectation.id_lit WHERE codif_quota.NiveauFormation = '$classe' AND codif_lit.sexe = '$sexe' LIMIT $limit OFFSET $offset";
    $resultRequeteLitClasseEtudiant = mysqli_query($connexion, $requeteLitClasseEtudiant);
    return $resultRequeteLitClasseEtudiant;
}

/********************************************************************************** 
Fonction d'affichage de la Liste de toutes les pavillons, elle est appeler dans requette.php et affiché dans la page listeLits.php
 ********************************************************************************* */
function getAllPavillon($sexe)
{
    global $connexion;
    $requetePavillon = "SELECT DISTINCT (pavillon) FROM `codif_lit` WHERE codif_lit.sexe = '$sexe'";
    $resultatRequetePavillon = mysqli_query($connexion, $requetePavillon);
    return $resultatRequetePavillon;
}

/********************************************************************************** 
Comptez le nombre total d'options dans la base de données: pagination total lit dans la page listeLits.php
 ********************************************************************************* */
function getAllLitPagination($sexe)
{
    global $connexion, $limit, $count_data_total, $offset;
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

/********************************************************************************** 
Comptez le nombre total d'options dans la base de données: pagination liste lits d'une classe selon l'etudiant connecté dans la page codifier.php
 ********************************************************************************* */
function getLitByStudent($classe, $sexe)
{
    global $connexion, $limit, $count_datas;
<<<<<<< HEAD
    $count_queryEtudiant = "SELECT DISTINCT COUNT(*) as total FROM codif_quota JOIN codif_lit ON codif_quota.id_lit_q = codif_lit.id_lit WHERE `NiveauFormation`='$classe' AND codif_lit.sexe = '$sexe'";
=======
    $count_queryEtudiant = "SELECT COUNT(*) as total FROM codif_quota JOIN codif_lit ON codif_quota.id_lit_q = codif_lit.id_lit WHERE `NiveauFormation`='$classe' AND codif_lit.sexe = '$sexe'";
>>>>>>> 4ab3e8d6e0d4478baf0139928fb896d9191d7523
    $count_resultEtudiant = mysqli_query($connexion, $count_queryEtudiant);
    if ($count_resultEtudiant) {
        $count_datas = mysqli_fetch_assoc($count_resultEtudiant);
        $total_pagesEtudiant = ceil($count_datas['total'] / $limit);
        return $total_pagesEtudiant;
    } else {
        $total_pagesEtudiant = 1;
        return $total_pagesEtudiant;
    }
}

/********************************************************************************** 
Comptez le nombre total d'options dans la base de données details lits affecter (codif_quota)
 ********************************************************************************* */
function getLitByQuotas($classe, $sexe)
{
    global $connexion, $limit, $count_datas;
    $count_querys = "SELECT DISTINCT COUNT(*) as total FROM codif_quota JOIN codif_lit ON codif_quota.id_lit_q = codif_lit.id_lit WHERE `NiveauFormation`='$classe' AND codif_lit.sexe = '$sexe'";
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

/********************************************************************************** 
Fonction pour enregistrer les donnees des codif_quota
 ********************************************************************************* */
function addQuotas($buttonId, $user, $NiveauFormation)
{
    global $connexion;
    $date = date("Y-n-j");
    $requeteInsertcodif_quota = "INSERT INTO `codif_quota` (`id_lit_q`, `username_user`, `NiveauFormation`, `annee`) VALUES ('$buttonId', '$user', '$NiveauFormation', '$date')";
    $requete = $connexion->prepare($requeteInsertcodif_quota);
    $requete->execute();
    return header('Location: ../profils/personnels/listeLits.php');
}

/********************************************************************************** 
Fonction permet l'enregistrement des lit validé par le personnels
 ********************************************************************************* */
function setValidation($buttonId, $user)
{
    global $connexion, $requete;
    $date = date("Y-n-j");
    $requeteInsertcodif_quota = "INSERT INTO `codif_validation` (`id_aff`, `username_user`, `dateTime_val`) VALUES ('$buttonId', '$user', '$date')";
    $requete = $connexion->prepare($requeteInsertcodif_quota);
    return $requete->execute();
}

/********************************************************************************** 
Fonction permet l'enregistrement des paiements de lit validé par le personnels
 ********************************************************************************* */
function setPaiement($buttonId, $user, $montant, $montant_recu, $restant, $libelle)
{
    global $connexion;
    $date = date("Y-m-d");
    $requeteInsertcodif_quota = "INSERT INTO `codif_paiement` (`id_val`, `username_user`, `dateTime_paie`, `montant`, `montant_recu`, `restant`, `libelle`) VALUES ('$buttonId', '$user', '$date', '$montant', '$montant_recu', '$restant', '$libelle')";
    $requeteResult = $connexion->prepare($requeteInsertcodif_quota);
    return $requeteResult->execute();
}


/********************************************************************************** 
Fonction d'affichage de la situation de l'etudiant (paiement caution et mensualité)
 ********************************************************************************* */
function getAllSituation($num_etu)
{
    global $connexion;
    $requeteSelect = "SELECT * FROM `codif_paiement` JOIN `codif_validation` ON codif_validation.id_val = codif_paiement.id_val JOIN `codif_affectation` on codif_affectation.id_aff = codif_validation.id_aff JOIN `codif_etudiant` on codif_etudiant.id_etu = codif_affectation.id_etu JOIN `codif_lit` on codif_lit.id_lit = codif_affectation.id_lit WHERE codif_etudiant.num_etu='$num_etu' ORDER BY id_paie ASC";
    $resulteRequete = $connexion->query($requeteSelect);
    return $resulteRequete;
}

/********************************************************************************** 
Fonction d'affichage de la situation du dernier etudiant  (paiement caution et mensualité)
 ********************************************************************************* */
function getLastSituation($username_user)
{
    global $connexion;
    $requeteSelect = "SELECT codif_user.prenom_user, codif_user.nom_user, codif_paiement.montant_recu, codif_paiement.dateTime_paie, codif_paiement.libelle, codif_etudiant.prenoms, codif_etudiant.nom FROM `codif_paiement` JOIN `codif_validation` ON codif_validation.id_val = codif_paiement.id_val JOIN `codif_affectation` on codif_affectation.id_aff = codif_validation.id_aff JOIN `codif_etudiant` on codif_etudiant.id_etu = codif_affectation.id_etu JOIN `codif_lit` on codif_lit.id_lit = codif_affectation.id_lit JOIN codif_user ON codif_user.username_user= '$username_user' ORDER BY id_paie DESC;";
    $resulteRequete = $connexion->query($requeteSelect);
    return $resulteRequete->fetch_assoc();
}

/********************************************************************************** 
Fonction pour recuperer le mois en chiffre a traver le nom du mois en lettre, puis le concataine avec l'annee en cour et le premier de chaque mois
 ********************************************************************************* */
function getMois($mois)
{
    $annee = array(
        "01" => "JANVIER",
        "02" => "FEVRIER",
        "03" => "MARS",
        "04" => "AVRIL",
        "05" => "MAI",
        "06" => "JUIN",
        "07" => "JUILLET",
        "08" => "AOUT",
        "09" => "SEPTEMBRE",
        "10" => "OCTOBRE",
        "11" => "NOVEMBRE",
        "12" => "DECEMBRE",
    );
    $date_sys = date("Y", strtotime(date("Y-m-d")));
    foreach ($annee as $cle => $value) {
        if ($value == $mois) {
            if ($cle < 9) {
                return $date_sys . "-" . $cle . "-01";
            } else {
                return $date_sys - 1 . "-" . $cle . "-01";
            }
        }
    }
}


/********************************************************************************** 
Fonction permet l'enregistrement du logement du titulaire
 ********************************************************************************* */
// function setLoger($id_paie, $user, $id_clando = NULL)
// {
//     global $connexion, $requete;
//     $date = date("Y-d-j");
//     $requeteInsertcodif_quota = "INSERT INTO `codif_loger` (`id_paie`, `dateTime_loger`, `username_user`, `id_clando`) VALUES ('$id_paie', '$date', '$user', '$id_clando')";
//     $requete = $connexion->prepare($requeteInsertcodif_quota);
//     return $requete->execute();
// }
function setLoger($id_paie, $user, $id_etu)
{
    global $connexion;
    $date = date("Y-m-d H:i:s");
    $requeteInsertcodif_quota = "INSERT INTO `codif_loger` (`id_paie`, `dateTime_loger`, `username_user`, `id_etu`, `statut`) 
                                 VALUES (?, ?, ?, ?, ?)";
    $requete = $connexion->prepare($requeteInsertcodif_quota);
    if ($requete === false) {
        die('Erreur de préparation de la requête : ' . $connexion->error);
    }
    $requete->bind_param('issis', $id_paie, $date, $user, $id_etu, 'attributaire');
    return $requete->execute();
}
function setLogerClando($id_paie, $user, $id_etu)
{
    global $connexion;
    $date = date("Y-m-d H:i:s");
    $clando ="clando";
    $requeteInsertcodif_quota = "INSERT INTO `codif_loger` (`id_paie`, `dateTime_loger`, `username_user`, `id_etu`, `statut`) 
                                 VALUES (?, ?, ?, ?, ?)";
    $requete = $connexion->prepare($requeteInsertcodif_quota);
    if ($requete === false) {
        die('Erreur de préparation de la requête : ' . $connexion->error);
    }
    $requete->bind_param('issis', $id_paie, $date, $user, $id_etu, $clando);
    return $requete->execute();
}


/********************************************************************************** 
Fonction permet l'enregistrement du lpgement du suppleant
 ********************************************************************************* */
function setLogerSuppleant($buttonId, $user, $id_etu)
{
    global $connexion, $requete;
    $date = date("Y-n-j");
    $requeteInsertcodif_quota = "INSERT INTO `codif_loger` (`id_val`, `dateTime_loger`, `username_user`, `id_etu`, `statut`) VALUES ('$buttonId', '$date', '$user', '$id_etu', 'suppleant')";
    $requete = $connexion->prepare($requeteInsertcodif_quota);
    return $requete->execute();
}

/********************************************************************************** 
Fonction pour retiré les codif_quota deja affecter
 ********************************************************************************* */
function removeQuotas($buttonId)
{
    global $connexion;
    $sql0 = "DELETE FROM codif_quota WHERE id_lit_q = '$buttonId'";
    $query0 = $connexion->prepare($sql0);
    return $query0->execute();
}

/********************************************************************************** 
Fonction pour afficher le niveau de formation et le sexe du lit
 ********************************************************************************* */
function getNiveauFormationAndSexeLitByQuota()
{
    global $connexion;
    $requete = "SELECT  DISTINCT (NiveauFormation) FROM `codif_quota` JOIN codif_lit on codif_lit.id_lit = codif_quota.id_lit_q";
    $result = $connexion->query($requete);
    return $result;
}

/********************************************************************************** 
Fonction d'affichage de l'etudiant ayant deja choisi une lit
 ********************************************************************************* */
function getStudentChoiseLit($idEtu)
{
    global $connexion;
    $requeteAffectEtu = "SELECT * FROM `codif_affectation` where `id_etu`=$idEtu";
    $inforequeteAffectEtu = $connexion->query($requeteAffectEtu);
    return $inforequeteAffectEtu;
}

/********************************************************************************** 
Fonction d'affichage du lit deja choisie par l'etudiant connecté
 ********************************************************************************* */
function getOneLitByStudent($num_etu)
{
    global $connexion;
    $requeteLitEtu = "SELECT codif_lit.* FROM codif_affectation JOIN codif_lit ON codif_affectation.id_lit = codif_lit.id_lit JOIN codif_etudiant ON codif_etudiant.id_etu = codif_affectation.id_etu where codif_etudiant.num_etu='$num_etu'";
    $resultatReqLitEtu = $connexion->query($requeteLitEtu);
    return $resultatReqLitEtu;
}

/********************************************************************************** 
Fonction d'affichage du lit choisi par l'etudiant, cette fonction sera appeler dans le fichier du convention
 ********************************************************************************* */
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

/********************************************************************************** 
Fonction d'affichage de la date que l'etudiant a choisi le lit
 ********************************************************************************* */
function getDateLitByStudent($idLit)
{
    global $connexion;
    $requeteDateLit = "SELECT `dateTime` FROM `codif_affectation` WHERE `id_lit`='$idLit'";
    $resultRequeteDateLit = mysqli_query($connexion, $requeteDateLit);
    while ($row = mysqli_fetch_array($resultRequeteDateLit)) {
        $dateLit = $row;
    }
    $timestamp = strtotime($dateLit["dateTime"]);
    $date_formatee = date("d-m-Y", $timestamp);
    return $date_formatee;
}

/********************************************************************************** 
Fonction de connexion dans l'espace utilisateur
 ********************************************************************************* */
function login($username, $password)
{
    global $connexion;
    $users = "SELECT * FROM `codif_user` where `username_user`='$username' and `password_user`='$password'";
    $info = $connexion->query($users);
    return $info->fetch_assoc();
}

/********************************************************************************** 
Fonction de verification du politique de confidentialité
 ********************************************************************************* */
function getPolitiqueConf($id)
{
    global $connexion;
    $usersPolitique = "SELECT * FROM `codif_politique` where `id_etu`='$id'";
    $infoPolitique = mysqli_query($connexion, $usersPolitique);
    return $infoPolitique->fetch_assoc();
}

/********************************************************************************** 
Fonction de filtre de la liste des lits
 ********************************************************************************* */
function setFiltre($filter, $sexe)
{
    global $connexion, $limit, $offset;
<<<<<<< HEAD
    $sqlFilter = "SELECT DISTINCT codif_lit.*, CASE WHEN codif_quota.id_lit_q IS NOT NULL AND codif_affectation.id_lit IS NOT NULL THEN 'Migré dans les deux' WHEN codif_quota.id_lit_q IS NOT NULL THEN 'Migré vers codif_quota uniquement' WHEN codif_affectation.id_lit IS NOT NULL THEN 'Migré vers codif_affectation uniquement' ELSE 'Non migré' END AS statut_migration FROM codif_lit LEFT JOIN codif_quota ON codif_lit.id_lit = codif_quota.id_lit_q LEFT JOIN codif_affectation ON codif_lit.id_lit = codif_affectation.id_lit WHERE pavillon='$filter' AND codif_lit.sexe = '$sexe' LIMIT $limit OFFSET $offset";
=======
    $sqlFilter = "SELECT codif_lit.*, CASE WHEN codif_quota.id_lit_q IS NOT NULL AND codif_affectation.id_lit IS NOT NULL THEN 'Migré dans les deux' WHEN codif_quota.id_lit_q IS NOT NULL THEN 'Migré vers codif_quota uniquement' WHEN codif_affectation.id_lit IS NOT NULL THEN 'Migré vers codif_affectation uniquement' ELSE 'Non migré' END AS statut_migration FROM codif_lit LEFT JOIN codif_quota ON codif_lit.id_lit = codif_quota.id_lit_q LEFT JOIN codif_affectation ON codif_lit.id_lit = codif_affectation.id_lit WHERE pavillon='$filter' AND codif_lit.sexe = '$sexe' LIMIT $limit OFFSET $offset";
>>>>>>> 4ab3e8d6e0d4478baf0139928fb896d9191d7523
    if ($filter) {
        $resultatRequeteTotalLit = mysqli_query($connexion, $sqlFilter);
        return $resultatRequeteTotalLit;
    }
}

/**********************************************************************************
 Fonction du pagination du filtre, cette fonction sera appeler dans la page listeLits.php
 **********************************************************************************/
function getPaginationFiltre($filter, $sexe)
{
    global $connexion, $limit, $offset, $count_data_total;
    $count_queryTotalLit = "SELECT COUNT(*) as total, CASE WHEN codif_quota.id_lit_q IS NOT NULL AND codif_affectation.id_lit IS NOT NULL THEN 'Migré dans les deux' WHEN codif_quota.id_lit_q IS NOT NULL THEN 'Migré vers codif_quota uniquement' WHEN codif_affectation.id_lit IS NOT NULL THEN 'Migré vers codif_affectation uniquement' ELSE 'Non migré' END AS statut_migration FROM codif_lit LEFT JOIN codif_quota ON codif_lit.id_lit = codif_quota.id_lit_q LEFT JOIN codif_affectation ON codif_lit.id_lit = codif_affectation.id_lit WHERE pavillon='$filter' AND codif_lit.sexe = '$sexe'";
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

/********************************************************************************** 
Fonction du pagination du filtre, cette fonction sera appeler dans la page listeLits.php
 ********************************************************************************* */
function getPaginationFiltreClasse($classe, $filter, $sexe)
{
    global $connexion, $limit, $offset, $count_datas;
    // $count_queryTotalLit = "SELECT COUNT(*) as total, CASE WHEN codif_quota.id_lit_q IS NOT NULL AND codif_affectation.id_lit IS NOT NULL THEN 'Migré dans les deux' WHEN codif_quota.id_lit_q IS NOT NULL THEN 'Migré vers codif_quota uniquement' WHEN codif_affectation.id_lit IS NOT NULL THEN 'Migré vers codif_affectation uniquement' ELSE 'Non migré' END AS statut_migration FROM codif_lit LEFT JOIN codif_quota ON codif_lit.id_lit = codif_quota.id_lit_q LEFT JOIN codif_affectation ON codif_lit.id_lit = codif_affectation.id_lit WHERE NiveauFormation='Licence 1 en Energies Renouvelables' AND codif_lit.pavillon='$filter' AND codif_lit.sexe = '$sexe'";
<<<<<<< HEAD
    $count_queryTotalLit = "SELECT DISTINCT COUNT(*) as total, CASE WHEN codif_quota.id_lit_q IS NOT NULL AND codif_affectation.id_lit IS NOT NULL THEN 'Migré dans les deux' WHEN codif_quota.id_lit_q IS NOT NULL THEN 'Migré vers codif_quota uniquement' WHEN codif_affectation.id_lit IS NOT NULL THEN 'Migré vers codif_affectation uniquement' ELSE 'Non migré' END AS statut_migration FROM codif_lit LEFT JOIN codif_quota ON codif_lit.id_lit = codif_quota.id_lit_q LEFT JOIN codif_affectation ON codif_lit.id_lit = codif_affectation.id_lit WHERE NiveauFormation='$classe' AND codif_lit.pavillon='$filter' AND codif_lit.sexe = '$sexe';";
=======
    $count_queryTotalLit = "SELECT COUNT(*) as total, CASE WHEN codif_quota.id_lit_q IS NOT NULL AND codif_affectation.id_lit IS NOT NULL THEN 'Migré dans les deux' WHEN codif_quota.id_lit_q IS NOT NULL THEN 'Migré vers codif_quota uniquement' WHEN codif_affectation.id_lit IS NOT NULL THEN 'Migré vers codif_affectation uniquement' ELSE 'Non migré' END AS statut_migration FROM codif_lit LEFT JOIN codif_quota ON codif_lit.id_lit = codif_quota.id_lit_q LEFT JOIN codif_affectation ON codif_lit.id_lit = codif_affectation.id_lit WHERE NiveauFormation='Licence 1 en Energies Renouvelables' AND codif_lit.pavillon='$filter' AND codif_lit.sexe = '$sexe';";
>>>>>>> 4ab3e8d6e0d4478baf0139928fb896d9191d7523
    $count_resultat_total = mysqli_query($connexion, $count_queryTotalLit);
    if ($count_resultat_total) {
        $count_datas = mysqli_fetch_assoc($count_resultat_total);
        $total_lit_pages = ceil($count_datas['total'] / $limit);
        return $total_lit_pages;
    } else {
        $total_lit_pages = 1;
        return $total_lit_pages;
    }
}

/********************************************************************************** 
Fonction d'affichage les information de l'utilisateur connecté (etudiant)
 ********************************************************************************* */
function studentConnect($username)
{
    global $connexion;
    $users = "SELECT * FROM `codif_etudiant` where `num_etu`='$username'";
    $info = $connexion->query($users);
    return $info->fetch_assoc();
}

/********************************************************************************** 
Fonction d'affichage les information de l'utilisateur connecté (personnel)
 ********************************************************************************* */
function personnelConnect($username)
{
    global $connexion;
    $users = "SELECT * FROM `users` where `num_etu`='$username'";
    $info = $connexion->query($users);
    return $info->fetch_assoc();
}

/********************************************************************************** 
Fonction pour récupérer les informations de l'étudiant pour le paiement de la caution
********************************************************************************* */
function infoStudentPaie($numEtudiant)
{
    global $connexion;
    $sql = "SELECT e.nom, e.prenom,a.id, e.numEtudiant, e.niveau,e.datenaissance,e.lieu_naissance, l.pavillon, l.chambre, l.litFROM etudiant e JOIN codif_affectation a ON e.id = a.idEtudiant JOIN lit l ON a.idLit = l.id WHERE e.numEtudiant = ?";
    $stmt = $connexion->prepare($sql);
    $stmt->bind_param("s", $numEtudiant);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result;
}

/********************************************************************************** 
Fonction d'affichage du format date
 ********************************************************************************* */
function dateFromat($date)
{
    $timestamp = strtotime($date);
    $date_formatee = date("Y-m-d", $timestamp);
    return $date_formatee;
}

/********************************************************************************** 
Fonction pour verifier si lE TITULAIRE a valider son hebergement
 ********************************************************************************* */
function getChoixLitByStudent($numEtudiant)
{
    global $connexion;
    $studentValidate = "SELECT * FROM codif_affectation JOIN codif_etudiant ON codif_etudiant.id_etu = codif_affectation.id_etu WHERE codif_etudiant.num_etu='$numEtudiant'";
    $infoValite = mysqli_query($connexion, $studentValidate);
    $data = $infoValite->fetch_assoc();
    // return $data;
    if ($data) {
        return "VEUILLEZ-VOUS RAPPROCHER DU SERVICE DE L'HEBERGEMENT POUR COMPLETER VOTRE CODIFICATION !!!";
    } else {
        return "VEUILLER CHOISIR UN LIT POUR DEMMARRER VOTRE CODIFICATION, <a href='/coud/codif/profils/etudiants/codifier.php'>CLIQUER ICI</a>";
    }
}
function getChoixLitByStudent_2($id_etu)
{
    global $connexion;
    $studentValidate = "SELECT * FROM codif_affectation JOIN codif_etudiant ON codif_etudiant.id_etu = codif_affectation.id_etu WHERE codif_etudiant.id_etu='$id_etu'";
    $infoValite = mysqli_query($connexion, $studentValidate);
    $data = $infoValite->fetch_assoc();
    return $data;
}

/********************************************************************************** 
Fonction pour verifier au suppleant que si son etudiant titulaire a valider son lit
 ********************************************************************************* */
function getChoixLitByTitulaireOfSuppleant($numEtudiantTitulaireOfSupp)
{
    global $connexion;
    $studentValidate = "SELECT * FROM codif_affectation JOIN codif_etudiant ON codif_etudiant.id_etu = codif_affectation.id_etu WHERE codif_etudiant.num_etu='$numEtudiantTitulaireOfSupp'";
    $infoValite = mysqli_query($connexion, $studentValidate);
    $data = $infoValite->fetch_assoc();
    // return $data;
    if ($data) {
        return "Votre titulaire a valider son lit, Veuillez-vous rapprocher du service de l'hebergement pour completer votre codification !!!";
    } else {
        return "Votre Titulaire n'en pas encore faire le choix de son lit, veuiller lui patienter !!!";
    }
}

/********************************************************************************** 
Fonction pour verifier si le TITULAIRE a valider son hebergement
 ********************************************************************************* */
function getValidateLitByStudent($numEtudiant)
{
    global $connexion;
    $studentValidate = "SELECT * FROM codif_validation JOIN codif_affectation ON codif_validation.id_aff = codif_affectation.id_aff JOIN codif_etudiant ON codif_etudiant.id_etu = codif_affectation.id_etu WHERE codif_etudiant.num_etu='$numEtudiant'";
    $infoValite = mysqli_query($connexion, $studentValidate);
    $data = $infoValite->fetch_assoc();
    // return $data;
    if ($data) {
        return "VOTRE LIT est VALIDER, VEUILLEZ-VOUS RAPPROCHER DE L'ACP POUR PAYER VOTRE CAUTION, montant à payer: " . getMontantPaye($numEtudiant) . " (Caution + mensualité(s))";
    }
}

/********************************************************************************** 
Fonction pour verifier si le TITULAIRE a valider son hebergement
 ********************************************************************************* */
function getValidateLitByStudent2($numEtudiant)
{
    global $connexion;
    $studentValidate = "SELECT * FROM codif_validation JOIN codif_affectation ON codif_validation.id_aff = codif_affectation.id_aff JOIN codif_etudiant ON codif_etudiant.id_etu = codif_affectation.id_etu WHERE codif_etudiant.num_etu='$numEtudiant'";
    $infoValite = mysqli_query($connexion, $studentValidate);
    $data = $infoValite->fetch_assoc();
    // return $data;
    if ($data) {
        return "VOTRE LIT est VALIDER, VEUILLEZ-VOUS RAPPROCHER DE L'ACP POUR PAYER VOTRE CAUTION";
    } else {
        return "VEUILLEZ-VOUS RAPPROCHER DU SERVICE DE L'HEBERGEMENT POUR COMPLETER VOTRE CODIFICATION !!!";
    }
}

/*********************************************************************************** 
Fonction pour verifier si le TITULAIRE a valider son hebergement
 ********************************************************************************* */
function getValidateLitByTitulaireOfSuppleant($numEtudiant)
{
    global $connexion;
    $studentValidate = "SELECT * FROM codif_validation JOIN codif_affectation ON codif_validation.id_aff = codif_affectation.id_aff JOIN codif_etudiant ON codif_etudiant.id_etu = codif_affectation.id_etu WHERE codif_etudiant.num_etu='$numEtudiant'";
    $infoValite = mysqli_query($connexion, $studentValidate);
    return $infoValite->fetch_assoc();
}

/********************************************************************************** 
Fonction pour verifier si le SUPPLEANT a valider son hebergement
 ********************************************************************************* */
function getValidateLitBySuppleant($numEtudiant)
{
    global $connexion;
    $studentValidate = "SELECT 
    codif_affectation.*,
    codif_etudiant.*,
    codif_lit.*,
    codif_loger.*,
    codif_validation.*,
    CASE WHEN codif_loger.id_val IS NOT NULL THEN 'Migré' ELSE 'Non migré' END AS etat_id_val 
FROM 
    codif_validation
    JOIN codif_affectation ON codif_validation.id_aff = codif_affectation.id_aff
    JOIN codif_etudiant ON codif_etudiant.id_etu = codif_affectation.id_etu
    JOIN codif_lit ON codif_lit.id_lit = codif_affectation.id_lit
    LEFT JOIN codif_loger ON codif_loger.id_val = codif_validation.id_val  
WHERE 
    codif_etudiant.num_etu = '$numEtudiant'";
    $infoValite = mysqli_query($connexion, $studentValidate);
    return $infoValite->fetch_assoc();
}

/********************************************************************************** 
Fonction pour verifier si le TITULAIRE a valider son hebergement
 ********************************************************************************* */
function getValidateLogerByStudent($numEtudiant)
{
    global $connexion;
    $studentValidatePaie = "SELECT * FROM `codif_loger` JOIN codif_paiement ON codif_paiement.id_paie = codif_loger.id_paie JOIN codif_validation ON codif_validation.id_val = codif_paiement.id_val JOIN codif_affectation ON codif_affectation.id_aff = codif_validation.id_aff JOIN codif_etudiant ON codif_etudiant.id_etu = codif_affectation.id_etu WHERE codif_etudiant.num_etu ='$numEtudiant'";
    $infoValitePaie = mysqli_query($connexion, $studentValidatePaie);
    $data = $infoValitePaie->fetch_assoc();
    // return $data;
    if ($data) {
        return "VOUS AVEZ DEJA codif_loger !!!";
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

/********************************************************************************** 
Fonction pour verifier si le TITULAIRE au suppleant a valider son hebergement
 ********************************************************************************* */
function getValidateLogerByTitulaire($numEtudiant)
{
    global $connexion;
    $studentValidatePaie = "SELECT * FROM `codif_loger` JOIN codif_paiement ON codif_paiement.id_paie = codif_loger.id_paie JOIN codif_validation ON codif_validation.id_val = codif_paiement.id_val JOIN codif_affectation ON codif_affectation.id_aff = codif_validation.id_aff JOIN codif_etudiant ON codif_etudiant.id_etu = codif_affectation.id_etu WHERE codif_etudiant.num_etu ='$numEtudiant'";
    $infoValitePaie = mysqli_query($connexion, $studentValidatePaie);
    return $infoValitePaie->fetch_assoc();
}

function getValidateLogerByTitulaire2($id_etu)
{
    global $connexion;
    $studentValidatePaie = "SELECT * FROM `codif_loger` JOIN codif_paiement ON codif_paiement.id_paie = codif_loger.id_paie JOIN codif_validation ON codif_validation.id_val = codif_paiement.id_val JOIN codif_affectation ON codif_affectation.id_aff = codif_validation.id_aff JOIN codif_etudiant ON codif_etudiant.id_etu = codif_affectation.id_etu WHERE codif_etudiant.id_etu ='$id_etu'";
    $infoValitePaie = mysqli_query($connexion, $studentValidatePaie);
    return $infoValitePaie->fetch_assoc();
}

/********************************************************************************** 
Fonction pour verifier si le SUPPLEANT a valider son hebergement
 ********************************************************************************* */
function getValidateLogerBySuppleant($numEtudiant)
{
    global $connexion;
    $studentValidatePaie = "SELECT * FROM `codif_loger` JOIN codif_validation ON codif_validation.id_val = codif_loger.id_val JOIN codif_affectation ON codif_affectation.id_aff = codif_validation.id_aff JOIN codif_etudiant ON codif_etudiant.id_etu = codif_affectation.id_etu JOIN codif_lit on codif_lit.id_lit = codif_affectation.id_lit WHERE codif_etudiant.num_etu ='$numEtudiant'";
    $infoValitePaie = mysqli_query($connexion, $studentValidatePaie);
    return $infoValitePaie->fetch_assoc();
}

/********************************************************************************** 
Fonction pour verifier si l'etudiant a valider son hebergement
 ********************************************************************************* */
function getValidatePaiementLitBySuppleant($numEtudiant)
{
    global $connexion;
    $studentValidatePaie = "SELECT * FROM codif_paiement JOIN codif_validation ON codif_paiement.id_val = codif_validation.id_val JOIN codif_affectation ON codif_affectation.id_aff = codif_validation.id_aff JOIN codif_etudiant ON codif_etudiant.id_etu =codif_affectation.id_etu WHERE codif_etudiant.num_etu='$numEtudiant'";
    $infoValitePaie = mysqli_query($connexion, $studentValidatePaie);
    return $infoValitePaie->fetch_assoc();
}
function getValidatePaiementLitBySuppleant2($id_etu)
{
    global $connexion;
    $studentValidatePaie = "SELECT codif_paiement.id_paie, codif_paiement.montant, codif_paiement.montant_recu, codif_paiement.restant, codif_paiement.libelle, codif_paiement.dateTime_paie, codif_paiement.username_user, codif_etudiant.id_etu FROM codif_paiement JOIN codif_validation ON codif_paiement.id_val = codif_validation.id_val JOIN codif_affectation ON codif_affectation.id_aff = codif_validation.id_aff JOIN codif_etudiant ON codif_etudiant.id_etu =codif_affectation.id_etu WHERE codif_etudiant.id_etu='$id_etu'";
    $infoValitePaie = mysqli_query($connexion, $studentValidatePaie);
    return $infoValitePaie;
}

/********************************************************************************** 
Fonction pour verifier si l'etudiant a valider son hebergement
 ********************************************************************************* */
function getValidatePaiementLitByStudent($numEtudiant)
{
    global $connexion;
    $studentValidatePaie = "SELECT * FROM codif_paiement JOIN codif_validation ON codif_paiement.id_val = codif_validation.id_val JOIN codif_affectation ON codif_affectation.id_aff = codif_validation.id_aff JOIN codif_etudiant ON codif_etudiant.id_etu =codif_affectation.id_etu WHERE codif_etudiant.num_etu='$numEtudiant'";
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

/********************************************************************************** 
Ajouter dans la table codif_affectation lorsque l'etudiant choisi une lit
 ********************************************************************************* */
function addAffectation($lastValue, $idEtu)
{
    global $connexion;
    $requeteInsertAff = "INSERT INTO `codif_affectation` (`id_lit`, `id_etu`, `dateTime_aff`, `statut`) VALUES ($lastValue, $idEtu, NOW(), 'attributaire')";
    $requeteEtu = $connexion->prepare($requeteInsertAff);
    return $requeteEtu->execute();
}

/********************************************************************************** 
Modifier le lit choisi par l'etudiant
 ********************************************************************************* */
function updateCodifAffectation($id_heritier, $idEtu)
{
    // S'assurer que les valeurs sont des entiers
    $id_heritier = (int) $id_heritier;
    $idEtu = (int) $idEtu;
    global $connexion;

    // Préparer la requête SQL
    $sql = "UPDATE `codif_affectation`
            SET `dateTime_aff` = NOW(), `statut` = 'attributaire', `id_etu` = ? WHERE `id_etu` = ?";

    // Initialiser une déclaration préparée
    $stmt = $connexion->prepare($sql);

    if ($stmt) {
        // Bind parameters (s for string, i for integer, etc. as needed)
        $stmt->bind_param('si', $id_heritier, $idEtu); // Adjust types accordingly

        // Execute the statement
        if ($stmt->execute()) {
            return $stmt;
        } else {
            echo "Error updating record: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "Prepare failed: " . $connexion->error;
    }
}


/********************************************************************************** 
Ajouter dans la table codif_affectation l'etudiant suppleant via son titulaire
 ********************************************************************************* */
function addAffectationOnSuppleant($lastValue, $idEtu)
{
    global $connexion;
    $requeteInsertAff = "INSERT INTO `codif_affectation` (`id_lit`, `id_etu`, `dateTime_aff`, `statut`) VALUES ($lastValue, $idEtu, NOW(), 'suppleant')";
    $requeteEtu = $connexion->prepare($requeteInsertAff);
    return $requeteEtu->execute();
}

/**********************************************************************************
 * *********************************************************************************
 */
// Fonction de traitement du politique de confidentiellité
function addPolitiqueConf($idEtu)
{
    global $connexion;
    $requeteInsert = "INSERT INTO `codif_politique` (`id_etu`, `dateTime`) VALUES ($idEtu, NOW())";
    $sql = $connexion->prepare($requeteInsert);
    return $sql->execute();
}

/**********************************************************************************
 * *********************************************************************************
 */
// Fonction qui me retourne le quota de n'importe quelle classe
function getQuotaClasse($classe, $sexe)
{
    global $connexion;
    $requeteQuotaClasse = "SELECT COUNT(*) FROM `codif_quota` JOIN codif_lit ON codif_lit.id_lit = codif_quota.id_lit_q WHERE `NiveauFormation` = '$classe' AND codif_lit.sexe = '$sexe'";
    $resultRequeteQuotaClasse = mysqli_query($connexion, $requeteQuotaClasse);
    return $resultRequeteQuotaClasse->fetch_assoc();
}

/**********************************************************************************
Fonction d'affichage de la liste des etudiant beneficiaire de lit titulaire et quota
 ********************************************************************************* */
function getStatutStudentByQuota($quota, $classe, $sexe)
{
    global $connexion;
    $requeteListeClasse = "SELECT 
    ce.id_etu, 
    ce.prenoms, 
    ce.nom, 
    ce.sexe, 
    ce.num_etu, 
    ce.dateNaissance, 
    ce.sessionId, 
    ce.moyenne, 
    ce.niveauFormation,
    ce.etablissement,
<<<<<<< HEAD
    ranks.rang, 
=======
    ce.departement,
    ce.dateNaissance,
    ce.lieuNaissance,
    ce.sexe,
    ce.nationalite,
    ce.numIdentite,
    ce.typeEtudiant,
    ce.niveau,
    ce.email_perso,
    ce.email_ucad,
    COALESCE(ranks.rang, 'N/A') AS rang, 
>>>>>>> 4ab3e8d6e0d4478baf0139928fb896d9191d7523
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
  AND ce.sexe = '$sexe' 
ORDER BY rang ASC;
";
    $resultRequeteListeClasse = mysqli_query($connexion, $requeteListeClasse);

    $students = [];
    while ($row = mysqli_fetch_assoc($resultRequeteListeClasse)) {
        $students[] = $row;
    }
    return $students;
}

/********************************************************************************** 
Fonction d'affichage du statu de titulaire selon le rang de l'etudiant suppleant
 ********************************************************************************* */
function getStatutByOneStudentTitulaireOfSuppl($quota, $classe, $sexe, $rang)
{
    global $connexion;
    $requeteListeClasse = "SELECT prenoms, nom, num_etu, sessionId, moyenne, rang, CASE WHEN rang <= $quota THEN 'attributaire' WHEN rang <= $quota*2 THEN 'suppleant' ELSE 'non attributaire' END AS statut FROM ( SELECT prenoms, nom, num_etu, sessionId, moyenne, ROW_NUMBER() OVER (order by sessionId ASC, moyenne desc,dateNaissance ASC,id_etu asc) AS rang FROM codif_etudiant WHERE niveauFormation = '$classe' AND sexe = '$sexe' ) AS ranked_students WHERE rang = $rang-$quota ORDER BY rang";
    $resultRequeteListeClasse = mysqli_query($connexion, $requeteListeClasse);
    return $resultRequeteListeClasse->fetch_assoc();
}

/********************************************************************************** 
fonction d'affichage de la table delai selon la nature et la faculte
 ********************************************************************************* */
function getAllDelai($nature, $faculte)
{
    global $connexion;
    $requete =  "SELECT * FROM codif_delai where nature ='$nature' AND faculte ='$faculte'";
    $resultRequete = mysqli_query($connexion, $requete);
    return $resultRequete->fetch_assoc();
}

/********************************************************************************** 
<<<<<<< HEAD
fonction d'affichage de toute les delais
 ********************************************************************************* */
function getDelai()
{
    global $connexion;
    $requete =  "SELECT DISTINCT (faculte) FROM codif_delai";
    $resultRequete = mysqli_query($connexion, $requete);
    return $resultRequete;
}

/********************************************************************************** 
fonction d'affichage de toute les delais
 ********************************************************************************* */
function getDelai2($nature)
{
    global $connexion;
    $requete =  "SELECT DISTINCT(faculte) FROM codif_delai WHERE nature='$nature'";
    $resultRequete = mysqli_query($connexion, $requete);
    return $resultRequete;
}

/********************************************************************************** 
fonction d'ajout dans la table delai
 ********************************************************************************* */
function addDelai($nature, $faculte, $date, $user)
{
    global $connexion;
    $date_sys = date("Y-m-d");
    $requete =  "INSERT INTO codif_delai (`nature`, `faculte`, `data_limite`, `dateTime_sys`, `username_user`) VALUES ('$nature', '$faculte', '$date', '$date_sys', '$user')";
=======
fonction d'ajout dans la table delai
 ********************************************************************************* */
function addDelai($nature, $faculte, $date)
{
    global $connexion;
    $requete =  "INSERT INTO codif_delai (`nature`, `faculte`,`data_limite`) VALUES ('$nature', '$faculte', '$date')";
>>>>>>> 4ab3e8d6e0d4478baf0139928fb896d9191d7523
    $add = $connexion->prepare($requete);
    $add->execute();
}

/********************************************************************************** 
fonction pour recuperer le lit choisi par l'etudiant selon son numero carte
 ********************************************************************************* */
function isIndivLitStudent($numEtudiant)
{
    global $connexion;
    $studentValidate = "SELECT * FROM codif_affectation JOIN codif_etudiant ON codif_etudiant.id_etu = codif_affectation.id_etu JOIN codif_lit ON codif_lit.id_lit = codif_affectation.id_lit WHERE codif_etudiant.num_etu='$numEtudiant'";
    $infoValite = mysqli_query($connexion, $studentValidate);
    $data = $infoValite->fetch_assoc();
    if (isset($data)) {
        if ($data['indiv'] == 1) {
            return 'oui';
        } else {
            return 'non';
        }
    }
}

/********************************************************************************** 
Ajouter des etudiants dans la table forclu
 ********************************************************************************* */
function addForclu($id_etu, $id_delai)
{
    $info_studentsForclu = info2($id_etu);
    $info_studentsForclu_sexe = $info_studentsForclu[13];
    $info_studentsForclu_niv = $info_studentsForclu[9];
    $info_studentsForclu_moyenne = $info_studentsForclu[10];
    $info_studentsForclu_session = $info_studentsForclu[12];
    $info_studentsForclu_naissance = $info_studentsForclu[3];
    $info_student_quota = getQuotaClasse($info_studentsForclu_niv, $info_studentsForclu_sexe)['COUNT(*)'];

    $total_forclu = getAllForclu($info_studentsForclu_niv, $info_studentsForclu_sexe)->num_rows;
    $id_studentHeritier = ((2 * $info_student_quota) + $total_forclu + 1);
    $info_heritier = info2($id_studentHeritier);
    $info_heritier_dateNaissance = $info_heritier[3];
    $info_heritier_moyenne = $info_heritier[10];
    $info_heritier_sessionId = $info_heritier[12];
    $req_archive = addArchive($id_etu, $username_user = NULL, $id_studentHeritier, $info_heritier_dateNaissance, $info_heritier_sessionId, $info_heritier_moyenne);
    if ($req_archive) {
        $aff = updateCodifAffectation($id_studentHeritier, $id_etu);
        if ($aff) {
            $resulte = updateEtudiant($info_studentsForclu_naissance, $info_studentsForclu_moyenne, $info_studentsForclu_session, $id_studentHeritier);
            if ($resulte) {
                global $connexion;
                $requeteInsertForclusion = "INSERT INTO `codif_forclusion` (`id_etu`, `id_del`, `dateTime_for`) VALUES ($id_etu, $id_delai, NOW())";
                $requete = $connexion->prepare($requeteInsertForclusion);
                return $requete->execute();
            }
        }
    }
}

/********************************************************************************** 
supprimer validation lit de l'etudiant forclu
 ********************************************************************************* */
function deleteValidation($id_etu)
{
    global $connexion;
    $requeteFor0 = "DELETE FROM codif_validation WHERE EXISTS (SELECT $id_etu FROM codif_affectation JOIN codif_etudiant ON codif_affectation.id_etu = codif_etudiant.id_etu WHERE codif_validation.id_aff = codif_affectation.id_aff AND codif_etudiant.id_etu = '$id_etu')";
    $b = $connexion->prepare($requeteFor0);
    $b->execute();
}

/********************************************************************************** 
supprimer logement lit de l'etudiant forclu
 ********************************************************************************* */
function deleteLogement($id_etu)
{
    global $connexion;
    $requeteFor0 = "DELETE FROM codif_loger WHERE codif_loger.id_paie IN (SELECT codif_paiement.id_paie FROM codif_paiement JOIN codif_validation ON codif_validation.id_val = codif_paiement.id_val JOIN codif_affectation ON codif_affectation.id_aff = codif_validation.id_aff JOIN codif_etudiant ON codif_etudiant.id_etu = codif_affectation.id_etu WHERE codif_etudiant.id_etu = '$id_etu')";
    $b = $connexion->prepare($requeteFor0);
    $b->execute();
}

/********************************************************************************** 
supprimer paiements lit de l'etudiant forclu
 ********************************************************************************* */
function deletePaiement($id_etu)
{
    global $connexion;
    $requeteFor0 = "DELETE FROM codif_paiement WHERE codif_paiement.id_val = (SELECT codif_validation.id_val FROM codif_validation JOIN codif_paiement ON codif_paiement.id_val = codif_validation.id_val JOIN codif_affectation ON codif_affectation.id_aff = codif_validation.id_aff JOIN codif_etudiant ON codif_etudiant.id_etu = codif_affectation.id_etu WHERE codif_etudiant.id_etu = codif_affectation.id_etu AND codif_etudiant.id_etu = '$id_etu' LIMIT 1)";
    $b = $connexion->prepare($requeteFor0);
    $b->execute();
}

/********************************************************************************** 
supprimer codif_affectation lit de l'etudiant forclu
 ********************************************************************************* */
function deleteAffectation($id_etu)
{
    global $connexion;
    $requeteFor1 = "DELETE FROM codif_affectation WHERE id_aff = (SELECT id_aff FROM codif_affectation JOIN codif_etudiant ON codif_affectation.id_etu = codif_etudiant.id_etu AND codif_etudiant.id_etu = '$id_etu')";
    $c = $connexion->prepare($requeteFor1);
    $c->execute();
}

/********************************************************************************** 
Verifier si l'etudiant est deja forclu
 ********************************************************************************* */
function getIsForclu($num_etu)
{
    global $connexion;
    $studentValidate = "SELECT * FROM `codif_forclusion` JOIN codif_etudiant ON codif_etudiant.id_etu =codif_forclusion.id_etu WHERE codif_etudiant.num_etu = '$num_etu'";
    $infoValite = mysqli_query($connexion, $studentValidate);
    $data = $infoValite->fetch_assoc();
    return $data;
}

/********************************************************************************** 
Verifier si au moins un etudiant est forclus
 ********************************************************************************* */
function getAllForclu($niveauFormation, $sexe)
{
    global $connexion;
    $studentValidate = "SELECT DISTINCT * FROM codif_forclusion JOIN codif_etudiant ON codif_etudiant.id_etu = codif_forclusion.id_etu JOIN codif_delai on codif_delai.id_delai = codif_forclusion.id_del WHERE codif_etudiant.niveauFormation='$niveauFormation' AND codif_etudiant.sexe='$sexe'";
    $infoValite = mysqli_query($connexion, $studentValidate);
    return $infoValite;
}

/********************************************************************************** 
Fonction pour modifier les informations de la table codif_etudiant
 ********************************************************************************* */
function updateEtudiant($dateNaissance, $moyenne, $sessionId, $id_etu)
{
    global $connexion;
    $req_put = "UPDATE `codif_etudiant` SET `dateNaissance` = ?, `moyenne` = ?, `sessionId` = ? WHERE `id_etu` = ?";
    $stmt = $connexion->prepare($req_put);
    if ($stmt) {
        $stmt->bind_param('sssi', $dateNaissance, $moyenne, $sessionId, $id_etu);
        if ($stmt->execute()) {
            return $stmt;
        } else {
            echo "Erreur lors de la mise à jour : " . $stmt->error;
        }
    } else {
        echo "Échec de la préparation de la requête : " . $connexion->error;
    }
}



/********************************************************************************** 
Fonction permet l'enregistrement forclusions manuel
 ********************************************************************************* */
function addForcloreManuel($id_etu, $motif, $username_user)
{
<<<<<<< HEAD
    $info_studentsForclu = info2($id_etu);
    $info_studentsForclu_num_etu = $info_studentsForclu[2];
    $info_studentsForclu_sexe = $info_studentsForclu[13];
    $info_studentsForclu_niv = $info_studentsForclu[9];
    $info_student_quota = getQuotaClasse($info_studentsForclu_niv, $info_studentsForclu_sexe)['COUNT(*)'];

    // Les informations de l'etudiant heritier (le non attributaire le mieux placer)
    $total_forclu = getAllForclu($info_studentsForclu_niv, $info_studentsForclu_sexe)->num_rows;
    $id_studentHeritier = ((2 * $info_student_quota) + $total_forclu + 1);
    $info_heritier = info2($id_studentHeritier);
    $info_heritier_dateNaissance = $info_heritier[3];
    $info_heritier_moyenne = $info_heritier[10];
    $info_heritier_sessionId = $info_heritier[12];

    $all_students = getStatutStudentByQuota($info_student_quota, $info_studentsForclu_niv, $info_studentsForclu_sexe);
    for ($i = 0; $i < count($all_students); $i++) {
        if ($all_students[$i]['num_etu'] == $info_studentsForclu_num_etu) {
            $id_etu = $all_students[$i]['id_etu'];
            $dateNaissance = $all_students[$i]['dateNaissance'];
            $moyenne = $all_students[$i]['moyenne'];
            $sessionId = $all_students[$i]['sessionId'];

            $req_archive = addArchive($id_etu, $username_user, $id_studentHeritier, $info_heritier_dateNaissance, $info_heritier_sessionId, $info_heritier_moyenne);
            if ($req_archive) {
                // deleteValidation($id_etu);
                $aff = updateCodifAffectation($id_studentHeritier, $id_etu);
                if ($aff) {
                    $resulte = updateEtudiant($dateNaissance, $moyenne, $sessionId, $id_studentHeritier);
                    if ($resulte) {
                        global $connexion;
                        // deleteAffectation($id_etu);
                        $requeteInsertForclusion = "INSERT INTO codif_forclusion (id_etu, dateTime_for, type, motif_manuel, username_user) VALUES ('$id_etu', NOW(), 'manuel', '$motif', '$username_user' )";
                        $requete = $connexion->prepare($requeteInsertForclusion);
                        return $requete->execute();
                    }
                }
            }
        }
    }
=======
    addArchive($id_etu, $username_user);
    global $connexion;
    deleteValidation($id_etu);
    deleteAffectation($id_etu);
    $requeteInsertForclusion = "INSERT INTO codif_forclusion (id_etu, dateTime_for, type, motif_manuel, username_user) VALUES ('$id_etu', NOW(), 'manuel', '$motif', '$username_user' )";
    $requete = $connexion->prepare($requeteInsertForclusion);
    return $requete->execute();
>>>>>>> 4ab3e8d6e0d4478baf0139928fb896d9191d7523
}

/********************************************************************************** 
Fonction permet de tester si l'etudiant est forclus ou pas
 ********************************************************************************* */
function isEtudiantForclus($id_etu)
{
    global $connexion;
    $req = "SELECT * FROM codif_forclusion JOIN codif_etudiant ON codif_etudiant.id_etu = codif_forclusion.id_etu WHERE codif_etudiant.id_etu = $id_etu";
    $result = $connexion->query($req);
    return $result->fetch_assoc();
}

/********************************************************************************** 
Fonction pour recuperer le tableaux d'etudiants attributaire, suppleant, non-attributaire et forclos
 ********************************************************************************* */
function getAllDatastudentStatus($quota, $classe, $sexe)
{
    $listeClasse = getStatutStudentByQuota($quota, $classe, $sexe);
    // $tableau_data_etudiant = [];
    // $i = 0;
    // while ($row = mysqli_fetch_array($listeClasse)) {
    //     $tableau_data_etudiant[$i] = $row;
    //     $i++;
    // }
    return $listeClasse;
}

/********************************************************************************** 
Fonction pour recuperer les données d'un etudiants attributaire, suppleant, non-attributaire et forclos
 ********************************************************************************* */
function getOnestudentStatus($quota, $classe, $sexe, $num_etu)
{
    $row_one_student = getAllDatastudentStatus($quota, $classe, $sexe);
    for ($i = 0; $i < count($row_one_student); $i++) {
        if ($num_etu == $row_one_student[$i]['num_etu']) {
            return $row_one_student[$i];
        }
    }
}

/* * ******************************************************************************** 
Fonction pour recuperer les données de l'attributaire selon le rang du suppleant
********************************************************************************* */
function getOneTitulaireBySuppleant($quota, $classe, $sexe, $rang)
{
    $row_one_student = getAllDatastudentStatus($quota, $classe, $sexe);
    for ($i = 0; $i < count($row_one_student); $i++) {
        if ($row_one_student[$i]['rang'] == $rang - $quota) {
            return $row_one_student[$i];
        }
    }
}

<<<<<<< HEAD
/********************************************************************************** 
Fonction pour recuperer les données du suppleant selon le rang du titulaire
 ***********************************************************************************/
function getOneSuppleantByTitulaire($quota, $classe, $sexe, $rang)
{
    $row_one_student = getAllDatastudentStatus($quota, $classe, $sexe);
    for ($i = 0; $i < count($row_one_student); $i++) {
        if ($row_one_student[$i]['rang'] == $rang + $quota) {
            return $row_one_student[$i];
        }
    }
}

/********************************************************************************** 
Fonction stocké toutes les informations de l'etudiant forclu automatique
 ********************************************************************************* */
function addArchive($id_etu, $username_user = null, $id_etu_heritier = null, $naissance_heritier = null, $sessionId_heritier = null, $moyenne_heritier = null)
{
    global $connexion;
    try {
        // Verification du lit choisi par l'etudiant s'il existe
        $affectation = getLitStudentForclu($id_etu);
        if ($affectation) {
=======
/* * ******************************************************************************** 
Fonction stocké toutes les informations de l'etudiant forclu automatique
********************************************************************************* */
function addArchive($id_etu, $username_user = null)
{
    global $connexion;

    try {
        // Récupérer les informations
        if ($affectation = getLitStudentForclu($id_etu)) {
>>>>>>> 4ab3e8d6e0d4478baf0139928fb896d9191d7523
            $id_lit = $affectation['id_lit'];
            $date_choix = $affectation['dateTime_aff'];
        } else {
            $id_lit = null;
            $date_choix = null;
        }
<<<<<<< HEAD

        // Verification de la validation du lit choisi par l'etudiant s'il existe
=======
>>>>>>> 4ab3e8d6e0d4478baf0139928fb896d9191d7523
        if ($validation = getDateValStudentForclu($id_etu)) {
            $date_val = $validation['dateTime_val'];
        } else {
            $date_val = null;
        }
<<<<<<< HEAD

        // Verification du paiement du lit choisi par l'etudiant s'il existe
        $paiement = getValidatePaiementLitBySuppleant2($id_etu);
        if ($paiement->num_rows != 0) {
            while ($archi_paie = mysqli_fetch_array($paiement)) {
                add_archive_paie($archi_paie['id_etu'], $archi_paie['montant'], $archi_paie['montant_recu'], $archi_paie['restant'], $archi_paie['libelle'], $archi_paie['dateTime_paie']);
                $date_paie = $archi_paie['dateTime_paie'];
            }
        } else {
            $date_paie = NULL;
        }

        // Verification du logement de l'etudiant s'il existe
        $loger = getValidateLogerByTitulaire2($id_etu);
        if ($loger) {
            $date_loger = $loger['dateTime_loger'];
        } else {
            $date_loger = NULL;
        }
        $archive_paie =  getArchivePaiement($id_etu);
        if ($archive_paie) {
            $id_paie_archive = $archive_paie['id_archive_paie'];
        } else {
            $id_paie_archive = NULL;
        }
        $req_add_archive = "INSERT INTO codif_archive (`id_etu`, `id_lit`, `date_choix`, `date_val`, `id_paie_archive`, `date_paie`, `date_log`, `dateTime_sys`, `username_user`, `id_etu_heritier`, `naissance_heritier`, `sessionId_heritier`, `moyenne_heritier`) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $insert_archive = $connexion->prepare($req_add_archive);
        $date = '12/12/2020';
        $insert_archive->bind_param(
            "iississssisss",
            $id_etu,
            $id_lit,
            $date_choix,
            $date_val,
            $id_paie_archive,
            $date_paie,
            $date_loger,
            $date,
            $username_user,
            $id_etu_heritier,
            $naissance_heritier,
            $sessionId_heritier,
            $moyenne_heritier
        );
        if (!($date_loger || $date_paie)) {
            deleteValidation($id_etu);
        } else {
            deleteLogement($id_etu);
            deletePaiement($id_etu);
            deleteValidation($id_etu);
        }
=======
        // Préparer et exécuter la requête d'insertion
        $req_add_archive = "INSERT INTO codif_archive (`id_etu`, `id_lit`, `date_choix`, `date_val`, `dateTime_sys`, `username_user`) VALUES (?, ?, ?, ?, NOW(), ?)";
        $insert_archive = $connexion->prepare($req_add_archive);
        $insert_archive->bind_param("iisss", $id_etu, $id_lit, $date_choix, $date_val, $username_user);
>>>>>>> 4ab3e8d6e0d4478baf0139928fb896d9191d7523
        return $insert_archive->execute();
    } catch (mysqli_sql_exception $e) {
        echo "Erreur SQL : " . $e->getMessage();
    } catch (Exception $e) {
        echo "Erreur : " . $e->getMessage();
    }
}

/********************************************************************************** 
Fonction pour recuperer l'identifiant de codif_archive de des paiement de l'etudiant
 ********************************************************************************* */
function getArchivePaiement($id_etu)
{
    global $connexion;
    $requete = "SELECT * FROM `codif_archive_paie` WHERE id_etu='$id_etu'";
    $result = $connexion->query($requete);
    return $result->fetch_assoc();
}

/********************************************************************************** 
Fonction stocké toutes les informations de paiement de l'etudiant forclu
 ********************************************************************************* */
function add_archive_paie($id_etu, $montant_due, $montant_recu, $restant, $libelle, $dateTime_paie)
{
    global $connexion;
    $requete = "INSERT INTO codif_archive_paie (`id_etu`, `montant_due`, `montant_recu`, `restant`, `libelle`, `dateTime_paie`) VALUES (?, ?, ?, ?, ?, ?)";
    $add_requette = $connexion->prepare($requete);
    $add_requette->bind_param(
        "isssss",
        $id_etu,
        $montant_due,
        $montant_recu,
        $restant,
        $libelle,
        $dateTime_paie
    );
    return $add_requette->execute();
}

/* * ******************************************************************************** 
Fonction pour recuperer l'id du lit et la date de choix du lit de l'etudiant deja forclu
********************************************************************************* */
function getLitStudentForclu($id_etu)
{
    global $connexion;
    $req_lit_student = "SELECT id_lit, dateTime_aff FROM codif_affectation JOIN codif_etudiant ON codif_etudiant.id_etu = codif_affectation.id_etu WHERE codif_etudiant.id_etu = $id_etu";
    $_get_req = $connexion->query($req_lit_student);
    return $_get_req->fetch_assoc();
}

/********************************************************************************** 
Fonction pour recuperer l'id du lit de l'etudiant deja forclu dans la table archive
 ********************************************************************************* */
function getLitStudentForclu_archive()
{
    global $connexion;
    $req_lit_student = "SELECT DISTINCT (id_lit) FROM codif_archive JOIN codif_etudiant ON codif_etudiant.id_etu = codif_archive.id_etu WHERE id_lit IS NOT NULL ORDER BY id_archi DESC LIMIT 1";
    $_get_req = $connexion->query($req_lit_student);
    return $_get_req->fetch_assoc();
}

/* ********************************************************************************* 
Fonction pour recuperer la date de validation de l'etudiant deja forclu
********************************************************************************* */
function getDateValStudentForclu($id_etu)
{
    global $connexion;
    $req_lit_student = "SELECT dateTime_val FROM codif_validation JOIN codif_affectation ON codif_affectation.id_aff = codif_validation.id_aff JOIN codif_etudiant ON codif_etudiant.id_etu = codif_affectation.id_etu WHERE codif_etudiant.id_etu = $id_etu";
    $_get_req = $connexion->query($req_lit_student);
    return $_get_req->fetch_assoc();
}

/* ********************************************************************************* 
Fonction pour recuperer la table facturation des lits
********************************************************************************* */
function getFacturation($indiv)
{
    global $connexion;
    $req_facturation_lit = "SELECT * FROM `codif_facturation` WHERE indiv= '$indiv'";
    $_get_req = $connexion->query($req_facturation_lit);
    return $_get_req->fetch_assoc();
}

/* ********************************************************************************* 
Fonction pour calculer le caution et le nombre de mois a payer
********************************************************************************* */
function getMontantPaye($numEtudiant)
{
<<<<<<< HEAD
    $faculte =  info($numEtudiant)[7];
    $dateDepart = getAllDelai('depart', $faculte)['data_limite'];
    $date_fermeture = getAllDelai('fermeture', $faculte)['data_limite'];
    $nbr_mois = calcul_nbreMois($dateDepart, $date_fermeture);
=======
    $dateDepart = getAllDelai("depart", info($numEtudiant)[5]);
    $date_debut = DateTime::createFromFormat('Y-m-d', dateFromat($dateDepart['data_limite']));
    $date_sys = DateTime::createFromFormat('Y-m-d', dateFromat(date("Y-n-j")));
    $nbr_mois = $date_debut->diff($date_sys);
    $nbr_mois = $nbr_mois->format('%m');
>>>>>>> 4ab3e8d6e0d4478baf0139928fb896d9191d7523
    if (!getValidatePaiementLitBySuppleant($numEtudiant)) {
        if (isIndivLitStudent($numEtudiant) == 'non') {
            $montant = 5000 + getFacturation('non')['montant'] * $nbr_mois;
            return $montant;
        } else {
            $montant = 5000 + getFacturation('oui')['montant'] * $nbr_mois;
            return $montant;
        }
    } else {
        if (isIndivLitStudent($numEtudiant) == 'non') {
            $montant = getFacturation('non')['montant'];
            return $montant;
        } else {
            $montant = getFacturation('oui')['montant'];
            return $montant;
        }
    }
}

// /* ********************************************************************************* 
// Fonction pour calculer la diffenrence de mois entre une date donné et la date du systeme
// ********************************************************************************* */
// function calcul_diff_mois($date)
// {
//     $date_debut = DateTime::createFromFormat('Y-m-d', dateFromat($date));
//     $date_sys = DateTime::createFromFormat('Y-m-d', dateFromat(date("Y-m-d")));
//     $nbr_mois = $date_debut->diff($date_sys);
//     $nbr_mois = $nbr_mois->format('%m');
//     return $nbr_mois;
// }


/* ********************************************************************************* 
Fonction pour calculer le nombre de mois total à payer par l'etudiant
********************************************************************************* */
function getNbreMois($numEtudiant)
{
    $dateDepart = getAllDelai("depart", info($numEtudiant)[7]);
    $date_debut = DateTime::createFromFormat('Y-m-d', dateFromat($dateDepart['data_limite']));
    $date_sys = DateTime::createFromFormat('Y-m-d', dateFromat(date("Y-n-j")));
    $nbr_mois = $date_debut->diff($date_sys);
    $nbr_mois = $nbr_mois->format('%m');
    return $nbr_mois;
}

/* ********************************************************************************* 
Fonction pour calculer la difference entre deux mois d'une annee
********************************************************************************* */
function calcul_nbreMois($date1, $date_fermeture)
{
    $date_debut = DateTime::createFromFormat('Y-m-d', dateFromat($date1));
    $date_ferme = DateTime::createFromFormat('Y-m-d', dateFromat($date_fermeture));
    $date_sys = DateTime::createFromFormat('Y-m-d', dateFromat(date("Y-m-d")));
    $date_systeme = dateFromat($date_sys->format('Y-m-d'));
    if ($date_systeme <= $date_fermeture) {
        $nbr_mois = $date_debut->diff($date_sys);
        $nbr_mois = $nbr_mois->format('%m');
        return $nbr_mois + 1;
    } else {
        $nbr_mois = $date_debut->diff($date_ferme);
        $nbr_mois = $nbr_mois->format('%m');
        return $nbr_mois;
    }
}

/* ********************************************************************************* 
Recuperer les paiments dans un intervalle de date données
********************************************************************************* */
function getPaiementWithDateInterval($date_debut, $date_fin)
{
    global $connexion;
    $sql = "SELECT ce.num_etu, ce.nom, ce.prenoms, pc.dateTime_paie, pc.montant FROM codif_etudiant ce JOIN codif_affectation a ON ce.id_etu = a.id_etu JOIN codif_validation vl ON a.id_aff = vl.id_aff JOIN codif_paiement pc ON pc.id_val = vl.id_val WHERE pc.dateTime_paie BETWEEN '$date_debut' AND '$date_fin'";
    $result = mysqli_query($connexion, $sql);
    return $result->fetch_assoc();
}

//Fonction permettant de recuperer toustes les infos de la table etudiant
function info($login)
{
    //Recherche des infos de l'etudiant
    global $connexion;
    $rr = "select * from codif_etudiant where num_etu='$login'";
    $ee = mysqli_query($connexion, $rr);
    $ss = mysqli_fetch_array($ee);

<<<<<<< HEAD
    $id_etu = $ss['id_etu'];
    $numIdentite = $ss['numIdentite'];
    $num_etu = $ss['num_etu'];
=======
    $numIdentite = $ss['numIdentite'];
>>>>>>> 4ab3e8d6e0d4478baf0139928fb896d9191d7523
    $dateNaissance = $ss['dateNaissance'];
    $lieuNaissance = $ss['lieuNaissance'];
    $nom = $ss['nom'];
    $prenoms = $ss['prenoms'];
    $etablissement = $ss['etablissement'];
    $departement = $ss['departement'];
    $typeEtudiant = $ss['typeEtudiant'];
    $sessionId = $ss['sessionId'];
    $niveauFormation = $ss['niveauFormation'];
    $moyenne = $ss['moyenne'];
    $sexe = $ss['sexe'];
    $email = $ss['email_ucad'];
    $email2 = $ss['email_perso'];
    ///////////Recuperer le 1er caractere de la cni pour determiner le sexe	
    $sexeL = "";
    if ($sexe == "G" or $sexe == "M") {
        $sexeL = "Garçons";
    }
    if ($sexe == "F") {
        $sexeL = "Filles";
    }
    ////////////Fin

<<<<<<< HEAD
    return array($id_etu, $numIdentite, $num_etu, $dateNaissance, $lieuNaissance, $nom, $prenoms, $etablissement, $departement, $niveauFormation, $moyenne, $typeEtudiant, $sessionId, $sexe, $sexeL, $email, $email2);
    //fin
}
//Fonction permettant de recuperer toustes les infos de la table etudiant
function info2($id)
{
    //Recherche des infos de l'etudiant
    global $connexion;
    $rr = "select * from codif_etudiant where id_etu='$id'";
    $ee = mysqli_query($connexion, $rr);
    $ss = mysqli_fetch_array($ee);

    $id_etu = $ss['id_etu'];
    $numIdentite = $ss['numIdentite'];
    $num_etu = $ss['num_etu'];
    $dateNaissance = $ss['dateNaissance'];
    $lieuNaissance = $ss['lieuNaissance'];
    $nom = $ss['nom'];
    $prenoms = $ss['prenoms'];
    $etablissement = $ss['etablissement'];
    $departement = $ss['departement'];
    $typeEtudiant = $ss['typeEtudiant'];
    $sessionId = $ss['sessionId'];
    $niveauFormation = $ss['niveauFormation'];
    $moyenne = $ss['moyenne'];
    $sexe = $ss['sexe'];
    $email = $ss['email_ucad'];
    $email2 = $ss['email_perso'];
    ///////////Recuperer le 1er caractere de la cni pour determiner le sexe	
    $sexeL = "";
    if ($sexe == "G" or $sexe == "M") {
        $sexeL = "Garçons";
    }
    if ($sexe == "F") {
        $sexeL = "Filles";
    }
    return array($id_etu, $numIdentite, $num_etu, $dateNaissance, $lieuNaissance, $nom, $prenoms, $etablissement, $departement, $niveauFormation, $moyenne, $typeEtudiant, $sessionId, $sexe, $sexeL, $email, $email2);
}

/* ********************************************************************************* 
Compter le nombre de mots dans une chaîne de caractères tout en ignorant les espaces et les virgules
********************************************************************************* */
function countWords($string)
{
    // Utiliser preg_split pour séparer les mots en ignorant les espaces et les virgules
    $words = preg_split('/[\s,]+/', trim($string), -1, PREG_SPLIT_NO_EMPTY);
    // Compter le nombre de mots
    return count($words);
}

/* ********************************************************************************* 
Fonction pour verifier si le supleant a deja valider sa validation et est sur le meme pavillon que le chef de residence
********************************************************************************* */
function getLogerSuppleant($num_etu, $pavillon)
{
    global $connexion;
    $sql = "SELECT * FROM `codif_validation` JOIN codif_affectation ON codif_affectation.id_aff = codif_validation.id_aff JOIN codif_lit ON codif_lit.id_lit = codif_affectation.id_lit JOIN codif_etudiant ON codif_etudiant.id_etu = codif_affectation.id_etu WHERE codif_etudiant.num_etu ='$num_etu' AND codif_lit.pavillon='$pavillon'";
    $result = mysqli_query($connexion, $sql);
    return $result->fetch_assoc();
}
=======
    return array($numIdentite, $dateNaissance, $lieuNaissance, $nom, $prenoms, $etablissement, $departement, $niveauFormation, $moyenne, $typeEtudiant, $sessionId, $sexe, $sexeL, $email, $email2);
    //fin
}
>>>>>>> 4ab3e8d6e0d4478baf0139928fb896d9191d7523
