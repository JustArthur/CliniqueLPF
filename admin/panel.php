<?php
    include_once('../include.php');

    //-- Empêche la connexion si un utilisateur n'est pas connecté ----------------
    if(!isset($_SESSION['utilisateur'][5])) {
        header('Location: ../index');
        exit;
    }

    $anneeChoix = date('Y');
    $semChoix = date('W');

    $timeStampPremierJanvier = strtotime($anneeChoix . '-01-01');
    $jourPremierJanvier = date('w', $timeStampPremierJanvier);
    
    //-- Recherche du N° de semaine du 1er janvier -------------------
    $numSemainePremierJanvier = date('W', $timeStampPremierJanvier);
    
    //-- Nombre à ajouter en fonction du numéro précédent ------------
    $decallage = ($numSemainePremierJanvier == 1) ? $semChoix - 1 : $semChoix;

    //-- Timestamp du jour dans la semaine recherchée ----------------
    $timeStampDate = strtotime('+' . $decallage . 'weeks', $timeStampPremierJanvier);
    
    //-- Recherche du lundi de la semaine en fonction de la ligne précédente ---------
    $jourDebutSemaine = ($jourPremierJanvier == 1) ? date('Y-m-d', $timeStampDate) : date('Y-m-d', strtotime('last monday', $timeStampDate));
    $jourFinSemaine = ($jourPremierJanvier == 1) ? date('Y-m-d', $timeStampDate) : date('Y-m-d',strtotime(' sunday', $timeStampDate));


    //-- Recherche de la date du jour ----------------
    $dateAujourdhui = date('Y-m-d');

    //-- Requête pour les admission ou autres ----------------
    $nbr_admission_today = $DB->prepare("SELECT * FROM operations WHERE dateOperation >= ? AND dateOperation <= ?");
    $nbr_admission_today->execute([$jourDebutSemaine, $jourFinSemaine]);
    $nbr_admission_today = $nbr_admission_today->rowcount();

    $nbr_patient_today = $DB->prepare("SELECT * FROM preadmission WHERE dateAdmission = ?");
    $nbr_patient_today->execute([$dateAujourdhui]);
    $nbr_patient_today = $nbr_patient_today->rowcount();

    $nbr_annule_today = $DB->prepare("SELECT p.idOperation, p.status, o.dateOperation FROM preadmission p INNER JOIN operations o ON p.idOperation = o.id WHERE p.status = 'Annulé' AND o.dateOperation >= ? AND o.dateOperation <= ?");
    $nbr_annule_today->execute([$jourDebutSemaine, $jourFinSemaine]);
    $nbr_annule_today = $nbr_annule_today->rowcount();

    $nbr_encours_today = $DB->prepare("SELECT p.idOperation, p.status, o.dateOperation FROM preadmission p INNER JOIN operations o ON p.idOperation = o.id WHERE p.status = 'En cours' AND o.dateOperation >= ? AND o.dateOperation <= ?");
    $nbr_encours_today->execute([$jourDebutSemaine, $jourFinSemaine]);
    $nbr_encours_today = $nbr_encours_today->rowcount();

    $nbr_termine_today = $DB->prepare("SELECT p.idOperation, p.status, o.dateOperation FROM preadmission p INNER JOIN operations o ON p.idOperation = o.id WHERE p.status = 'Terminé' AND o.dateOperation >= ? AND o.dateOperation <= ?");
    $nbr_termine_today->execute([$jourDebutSemaine, $jourFinSemaine]);
    $nbr_termine_today = $nbr_termine_today->rowcount();

    //-- Esthetique du site si 0 ----------------
    if($nbr_admission_today == 0) {
        $nbr_admission_today = 'Aucune';
    } else {
        $nbr_admission_today = $nbr_admission_today;
    }

    if($nbr_patient_today == 0) {
        $nbr_patient_today = 'Aucun';
    } else {
        $nbr_patient_today = $nbr_patient_today;
    }

    if($nbr_annule_today == 0) {
        $nbr_annule_today = 'Aucune';
    } else {
        $nbr_annule_today = $nbr_annule_today;
    }

    if($nbr_termine_today == 0) {
        $nbr_termine_today = 'Aucune';
    } else {
        $nbr_termine_today = $nbr_termine_today;
    }

    if($nbr_encours_today == 0) {
        $nbr_encours_today = 'Aucune';
    } else {
        $nbr_encours_today = $nbr_encours_today;
    }

    //-- Récupères les 20 dernières pré-admissions du jour ----------------
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
</head>
<body>

    <?php
        require_once('src/navbar.php');
    ?>
    
    <main>
        <?php if($_SESSION['utilisateur'][3] == 1) {?>
        <ul class="list-prea">
            <a href="#" class="list-item">
                <h3 class="titre">Pré-admissions</h3>
                <p class="desc"><span class="color"><?= $nbr_admission_today ?></span> pré-admission prévu pour cette semaine.</p>
            </a>

            <a href="#" class="list-item">
                <h3 class="titre">Patients</h3>
                <p class="desc"><span class="color"><?= $nbr_patient_today ?></span> patients admis pour une future pré-admission.</p>
            </a>

            <a href="#" class="list-item">
                <h3 class="titre">Annulé</h3>
                <p class="desc"><span class="color"><?= $nbr_annule_today ?></span> pré-admissions annulé pour cette semaine.</p>
            </a>

            <a href="#" class="list-item">
                <h3 class="titre">En cours</h3>
                <p class="desc"><span class="color"><?= $nbr_encours_today ?></span> pré-admissions en cours pour cette semaine.</p>
            </a>

            <a href="#" class="list-item">
                <h3 class="titre">Terminé</h3>
                <p class="desc"><span class="color"><?= $nbr_termine_today ?></span> pré-admissions terminé pour cette semaine.</p>
            </a>

            <a href="ajout_admission" class="list-item">
                <h3 class="titre">Créer une nouvelle pré-admission</h3>
            </a>
        </ul>
        <?php } ?>

        <div class="profil">
            <div class="infos">
                <div class="nom"><?= $_SESSION['utilisateur'][1] ?> <span class="color"><?= $_SESSION['utilisateur'][0] ?></span></div>
                <div class="role"><?= $_SESSION['utilisateur'][4] ?></div>
            </div>

            <div class="last-prea">
                <h3>Vos 20 dernières pré-admissions du jour</h3>

                <ul class="list-prea-last">
                    <?php 

                        if($dernierePreaCount == 0) { ?>
                            <li class="list-item">
                                <p>Aucune pré-admission réalisé aujourd'hui</p>
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