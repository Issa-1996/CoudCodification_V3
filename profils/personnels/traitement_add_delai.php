<?php
session_start();
if (empty($_SESSION['username']) && empty($_SESSION['mdp'])) {
    header('Location: /COUD/codif/');
    exit();
}
include('../../traitement/fonction.php');
if (isset($_POST) && count($_POST) > 0) {
    if (($_POST['nature']) && isset($_POST['nature'])) {
        $nature = $_POST['nature'];
        if (($_POST['date']) && isset($_POST['date'])) {
            $date = $_POST['date'];
            if (($_POST['faculte']) && isset($_POST['faculte'])) {
                $faculte = $_POST['faculte'];
                try {
                    foreach ($faculte as $fac) {
                        addDelai($nature, $fac, $date, $_SESSION['username']);
                    }
                    header('Location: add_delai.php?successAdd=Date ajoutée avec success avec success!!!');
                    exit();
                } catch (Exception $e) {
                    header('Location: add_delai.php?erreurAdd=' . $e->getMessage());
                    exit();
                }
            } else {
                header('Location: add_delai.php?erreurAdd=Faculté obligatoire !!!');
                exit();
            }
        } else {
            header('Location: add_delai.php?erreurAdd=Date obligatoire !!!');
            exit();
        }
    } else {
        header('Location: add_delai.php?erreurAdd=Nature de la date obligatoire !!!');
        exit();
    }
}
