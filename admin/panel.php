<?php
    include_once('../include.php');

    //-- Empêche la connexion si un utilisateur n'est pas connecté ----------------
    if(!isset($_SESSION['utilisateur'][5])) {
        header('Location: ../index');
        exit;
    }

    $dateAujourdhui = date('Y-m-d');

    //-- Vide les SESSIONS pour une pré-admission ----------------
    $_SESSION['patient'] = array();
    $_SESSION['personneConfiance'] = array();
    $_SESSION['personnePrevenir'] = array();
    $_SESSION['hospitalisation'] = array();
    $_SESSION['couvertureSociale'] = array();

    //-- Pour empêcher l'ouverture des pages suivantes sans passer par les requises ----------------
    $_SESSION['creer_admission'] = array(
        false, //0
        false, //1
        false, //2
        false, //3
        false //4
    );

    //-- Récupères les pré-admissions prévu pour les 5 semaines à venir ----------------
    $dernierePrea = $DB->prepare("SELECT * FROM preadmission WHERE faitPar = ? AND dateAdmission = ? LIMIT 20");
    $dernierePrea->execute([$_SESSION['utilisateur'][5], $dateAujourdhui]);
    $dernierePreaFetch = $dernierePrea->fetchAll();
    $dernierePreaCount = $dernierePrea->rowcount();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="../style/panel.css">
    <link rel="stylesheet" href="../style/navBar.css">

    <title>Bienvenue, <?= $_SESSION['utilisateur'][0] . ' ' . $_SESSION['utilisateur'][1] ?></title>

    <link rel="icon" href="../img/logo.png" type="image/icon type">
</head>
<body>

    <?php
        //-- Appel le fichier navbar ----------------
        require_once('src/navbar.php');
    ?>
    
    <main>
        <?php if($_SESSION['utilisateur'][3] == 1) {
            //-- Appel le fichier information ----------------
            require_once('src/information.php');
        } ?>

        <div class="profil">
            <div class="infos">
                <div class="nom"><?= $_SESSION['utilisateur'][1] ?> <span class="color"><?= $_SESSION['utilisateur'][0] ?></span></div>
                <div class="role"><?= $_SESSION['utilisateur'][4] ?></div>
            </div>

            <div class="last-prea">
                <h3>Vos pré-admissions des 5 prochaines semaines</h3>

                <ul class="list-prea-last">
                    <?php 

                        if($dernierePreaCount == 0) { ?>
                            <li class="list-item">
                                <p>Aucune pré-admission prévu dans les 5 semaines</p>
                            </li>
                        <?php } else {
                            foreach($dernierePreaFetch as $preadmission) {
                                $cherchePrea = $DB->prepare("SELECT * FROM operations WHERE idPatient = ?");
                                $cherchePrea->execute([$preadmission['idPatient']]);
                                $cherchePrea = $cherchePrea->fetch();

                                $chercheMedecin = $DB->prepare("SELECT * FROM personnel WHERE id = ?");
                                $chercheMedecin->execute([$preadmission['idMedecin']]);
                                $chercheMedecin = $chercheMedecin->fetch();
                            
                            ?>
                                <li class="list-item">
                                    <div class="infos-prea">
                                        <p><?= $preadmission['idPatient'] ?></p>
                                        <p>Dr <?= $chercheMedecin['nom'] . ' ' . $chercheMedecin['prenom'] ?></p>
                                        <p><?= $cherchePrea['dateOperation'] ?></p>
                                        <p><?= $cherchePrea['heureOperation'] ?></p>
                                    </div>

                                    <div class="btns">
                                        <a href="modif_admission?id=<?= $preadmission['id']?>">Modifier</a>
                                    </div>
                                </li>
                    <?php } } ?>
                </ul>
            </div>
        </div>
    </main>
</body>
</html>