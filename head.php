<?php
if (empty($_SESSION['username']) && empty($_SESSION['mdp'])) {
  header('Location: /COUD/codif/');
  exit();
}
require_once(__DIR__ . '/traitement/fonction.php');
if ($_SESSION['profil'] == 'user') {
  $inforequeteAffectEtu = getStudentChoiseLit($_SESSION['id_etu']);

  $affecter = 0;
  while ($row = $inforequeteAffectEtu->fetch_assoc()) {
    $affecter++;
  }
  $resultatReqLitEtu = getOneLitByStudent($_SESSION['id_etu']);
  $quotaStudentConnect = getQuotaClasse($_SESSION['classe'], $_SESSION['sexe'])['COUNT(*)'];
  // $moyenneStudentConnect = studentConnect($_SESSION['num_etu'])['moyenne'];
  // $rangStudentConnect = getStatutByOneStudent($quotaStudentConnect, $_SESSION['classe'], $_SESSION['sexe'], $moyenneStudentConnect, $_SESSION['num_etu'])['rang'];

  // $statutStudentConnect = getStatutByOneStudent($quotaStudentConnect, $_SESSION['classe'], $_SESSION['sexe'], $moyenneStudentConnect, $_SESSION['num_etu'])['statut'];
  $statutStudentConnect = getOnestudentStatus($quotaStudentConnect, $_SESSION['classe'], $_SESSION['sexe'], $_SESSION['num_etu']);
  // print_r($statutStudentConnect);


  // $dataStudentConnect = studentConnect($_SESSION['username']);

  // $teste = getStatutByOneStudent($quotaStudentConnect, $_SESSION['classe'], $_SESSION['sexe'], $moyenneStudentConnect, $_SESSION['num_etu']);
  // print_r($teste);
}
?>

<head>
  <!--- basic page needs================================================== -->
  <meta charset="utf-8" />
  <title>CAMPUSCOUD</title>
  <meta name="description" content="" />
  <meta name="author" content="" />

  <!-- mobile specific metas================================================== -->
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <!-- CSS================================================== -->
  <link rel="stylesheet" href="assets/css/base.css" />
  <link rel="stylesheet" href="assets/css/vendor.css" />
  <link rel="stylesheet" href="assets/css/main.css" />

  <!-- script================================================== -->
  <script src="../assets/js/modernizr.js"></script>
  <script src="assets/js/pace.min.js"></script>

  <!-- favicons================================================== -->
  <link rel="shortcut icon" href="log.gif" type="image/x-icon" />
  <link rel="icon" href="log.gif" type="image/x-icon" />
</head>

<body id="top">
  <!-- header================================================== -->
  <header class="s-header">
    <div class="header-logo">
      <a class="site-logo" href="#"><img src="/COUD/codif/assets/images/logo.png" alt="Homepage" /></a>
      CAMPUSCOUD
    </div>
    <nav class="header-nav-wrap">
      <ul class="header-nav">
        <?php if (($_SESSION['profil'] == 'paiement') && isset($_SESSION['classe'])) { ?>
          <li class="nav-item">
            <a class="nav-link" href="paiement.php" title="Paiement de caution">Paiement</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../personnels/niveau.php" title="Changer de niveau de formation ">Changer-Classe</a>
          </li>
        <?php } ?>
        <?php if (($_SESSION['profil'] == 'validation') && isset($_SESSION['classe'])) { ?>
          <li class="nav-item">
            <a class="nav-link" href="validation.php" title="Paiement de caution">Validation</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../personnels/niveau.php" title="Changer de niveau de formation ">Changer-Classe</a>
          </li>
        <?php } ?>
        <?php if (($_SESSION['profil'] == 'quota') && isset($_SESSION['classe'])) { ?>
          <li class="nav-item active">
            <a class="nav-link" href="listeLits.php" title="Revenir à la page d'accueil">Accueil <span></span></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="detailsLits.php" title="Détail des lits affecté à cette classe">Détail</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="niveau.php" title="Changer de niveau de formation ">Changer-Classe</a>
          </li>
        <?php } ?>
        <?php if ($_SESSION['profil'] == 'user') { ?>
          <li class="nav-item active">
            <a class="nav-link" href="../etudiants/resultat.php" title="Revenir à la page d'accueil">Accueil</a>
          </li>
          <?php
          if (($affecter == 0) && ($statutStudentConnect['statut'] == 'attributaire')) {
            $_SESSION['lit_choisi'] = ''; ?>
            <li class="nav-item active">
              <a class="nav-link" href="../etudiants/codifier.php" title="Aller à la page des codifications">Codifier</a>
            </li>
          <?php } else {
            while ($rows = $resultatReqLitEtu->fetch_assoc()) {
              if($rows['lit']){
                $_SESSION['lit_choisi'] = $rows['lit'];
                $_SESSION['id_lit'] = $rows['id_lit'];
              }else{
                $_SESSION['lit_choisi'] = '';
                $_SESSION['id_lit'] = '';
              }
            }
          }
          ?>
        <?php } ?>
        <li class="nav-item">
          <a class="nav-link" href="/coud/codif/" title="Déconnexion"><i class="fa fa-sign-out" aria-hidden="true"></i> Déconnexion</a>
        </li>
      </ul>
    </nav>

    <a class="header-menu-toggle" href="#0"><span>Menu</span></a>
  </header>
  <!-- end s-header -->
</body>
<section id="homedesigne" class="s-homedesigne">
  <?php if (($_SESSION['profil'] == 'quota') || ($_SESSION['profil'] == 'paiement') || ($_SESSION['profil'] == 'validation') || ($_SESSION['profil'] == 'chef_pavillon')) { ?>
    <p class="lead">Espace Administration: Bienvenue! <br> <br> <span>
        (<?= $_SESSION['prenom'] . "  " . $_SESSION['nom'] ?>)
      </span></p>
  <?php } elseif ($_SESSION['profil'] == 'user') { ?>
    <p class="lead">Bienvenue <?= studentConnect($_SESSION['num_etu'])['prenoms'] . ' ' . studentConnect($_SESSION['num_etu'])['nom']; ?> !<br> <br>
      MA SITUATION: Classe : <?= $statutStudentConnect['niveauFormation']; ?>/ Quota: <?= $quotaStudentConnect; ?>Lits/
      Moyenne : <?= $statutStudentConnect['moyenne']; ?>/
      Rang : <?= $statutStudentConnect['rang']; ?>/
      Statut : <?= $statutStudentConnect['statut']; ?><br><br>
      <?php
      if ($statutStudentConnect['statut'] == 'suppleant') {
        // $monTitulaire = getStatutByOneStudentTitulaireOfSuppl($quotaStudentConnect, $_SESSION['classe'], $_SESSION['sexe'], $statutStudentConnect['rang']);
        $monTitulaire = getOneTitulaireBySuppleant($quotaStudentConnect, $_SESSION['classe'], $_SESSION['sexe'], $statutStudentConnect['rang']);
      ?>
        MON TITULAIRE : Prenom: <?= $monTitulaire['prenoms'] . '/ Nom : ' . $monTitulaire['nom'] . '/ Moyenne : ' . $monTitulaire['moyenne'] . '/ Rang : ' . $monTitulaire['rang']; ?><br><br>
      <?php
      }
      if ($statutStudentConnect['statut'] == 'attributaire') { ?>
        ACTION : <?= getValidateLogerByStudent($_SESSION['num_etu']); ?>
      <?php } else 
      if ($statutStudentConnect['statut'] == 'suppleant') { ?>
        ACTION : <?php
                  if (getValidateLitBySuppleant($monTitulaire['num_etu'])) {
                    if (getValidateLitBySuppleant($_SESSION['num_etu'])) {
                      if (getValidatePaiementLitBySuppleant($monTitulaire['num_etu'])) {
                        if (getValidateLogerBySuppleant($monTitulaire['num_etu'])) {
                          if (getValidateLogerBySuppleant($_SESSION['num_etu'])) {
                            echo "VOUS AVEZ BIEN LOGER !!!";
                          } else {
                            echo "VOTRE TITULAIRE A BIEN LOGER, MERCI DE FAIRE DE MEME AU NIVEAU DU CHEF DE PAVILLON !!!";
                          }
                        } else {
                          echo "VOTRE TITULAIRE A PAYER SA CAUTION, MAIS N'EN PAS ENCORE LOGER, VEUILLER PATIENTER";
                        }
                      } else {
                        echo "VEUILLER PATIENTER QUE VOTRE TITULAIRE PAYE SA CAUTION, POUR LOGER !!!";
                      }
                    } else {
                      echo "VOTRE TITULAIRE A VALIDER SA CODIFICATION, MERCI DE FAIRE DE MEME";
                    }
                  } else {
                    echo "VEUILLER PATIENTER QUE VOTRE TITULAIRE VALIDE SA CODIFICATION, POUR FAIRE DE MEME";
                  }
                  ?>
      <?php } ?>
    </p>
  <?php } ?>
</section>