<?php
session_start();
if (empty($_SESSION['username']) && empty($_SESSION['mdp'])) {
    header('Location: /COUD/codif/');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>COUD: CODIFICATION </title>
    <link rel="stylesheet" href="../../assets/css/main.css">
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <link rel="stylesheet" href="../../assets/bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="../../assets/bootstrap/js/bootstrap.min.js">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    <?php include('../../head.php'); ?>
    <div class="container">
        <h1>Bienvenue étudiant</h1>
        <?php
        if ($_SESSION['lit_choisi'] != '') {
        ?>
            <h2 class="text-success">Vous avez réservé le lit: <?= $_SESSION['lit_choisi'] ?> </h2>
            <h1>VEUILLEZ-VOUS RAPPROCHER DU SERVICE DE L'HEBERGEMENT POUR COMPLETER VOTRE CODIFICATION !!!</h1>
            <!-- <a href="../convention/pdf.php">Télécharger convention</a> -->
        <?php }
        ?>
    </div>
</body>

</html>