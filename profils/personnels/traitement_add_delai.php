<?php
include('../../traitement/fonction.php');
if (isset($_POST) && count($_POST) > 0) {
    if (($_POST['nature']) && isset($_POST['nature'])) {
        $nature = $_POST['nature'];
        $date = $_POST['date'];
        $compt = 0;
        foreach ($_POST as $faculte => $value) {
            if ($value === "on") {
                try {
                    $compt++;
                    addDelai($nature, $faculte, $date);
                } catch (Exception $e) {
                    header('Location: add_delai.php?erreurAdd=' . $e->getMessage());
                    exit();
                }
            }
        }
        if ($compt > 0) {
            header('Location: add_delai.php?successAdd=Date ajoutée avec success avec success!!!');
            exit();
        } else {
            header('Location: add_delai.php?erreurAdd=Faculté obligatoire');
            exit();
        }
    } else {
        header('Location: add_delai.php?erreurAdd=Nature de la date obligatoire !!!');
        exit();
    }
}
