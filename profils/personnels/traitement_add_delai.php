<?php
<<<<<<< HEAD
session_start();
if (empty($_SESSION['username']) && empty($_SESSION['mdp'])) {
    header('Location: /COUD/codif/');
    exit();
}
=======
>>>>>>> 4ab3e8d6e0d4478baf0139928fb896d9191d7523
include('../../traitement/fonction.php');
if (isset($_POST) && count($_POST) > 0) {
    if (($_POST['nature']) && isset($_POST['nature'])) {
        $nature = $_POST['nature'];
<<<<<<< HEAD
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
=======
        $date = $_POST['date'];
        $compt = 0;
        foreach ($_POST as $faculte => $value) {
            if ($value === "on") {
                try {
                    $compt++;
                    addDelai($nature, $faculte, $date);
>>>>>>> 4ab3e8d6e0d4478baf0139928fb896d9191d7523
                } catch (Exception $e) {
                    header('Location: add_delai.php?erreurAdd=' . $e->getMessage());
                    exit();
                }
<<<<<<< HEAD
            } else {
                header('Location: add_delai.php?erreurAdd=Faculté obligatoire !!!');
                exit();
            }
        } else {
            header('Location: add_delai.php?erreurAdd=Date obligatoire !!!');
=======
            }
        }
        if ($compt > 0) {
            header('Location: add_delai.php?successAdd=Date ajoutée avec success avec success!!!');
            exit();
        } else {
            header('Location: add_delai.php?erreurAdd=Faculté obligatoire');
>>>>>>> 4ab3e8d6e0d4478baf0139928fb896d9191d7523
            exit();
        }
    } else {
        header('Location: add_delai.php?erreurAdd=Nature de la date obligatoire !!!');
        exit();
    }
}
