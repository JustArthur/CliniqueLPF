<?php
    include_once('../include.php');

    if(empty($_SESSION['utilisateur'][5]) || $_SESSION['utilisateur'][3] != 2) {
        header('Location: panel');
        exit;
    }
    
?>

<html>
    <head>

    </head>
    <body>
        <form method="post">
            <label for="login">login</label>
            <input name="login" type="text" required>
            <br>
            <label for="password">Password</label>
            <input name="password" type="password" required>
            <br>
            <label for="nom">Nom</label>
            <input name="nom">
            <br>
            <label for="prenom">Prenom</label>
            <input name="prenom" type="text" required>
            <br>
            <label for="service">service</label>
            <input name="service" type="text" required>
            <br>
            <label for="role">role</label>
            <input name="role" type="number" required>
            <br>
            <button name="ajouter">Ajouter</button>
        </form>
        <button name="voir">Voir</button>
        <br><br>
    </body>
    <?php
        if (isset($_POST['ajouter'])) {
            $login = htmlspecialchars($_POST['login']);
            $password = htmlspecialchars($_POST['password']);
            $nom = htmlspecialchars($_POST['nom']);
            $prenom  = htmlspecialchars($_POST['prenom']);
            $service = htmlspecialchars($_POST['service']);
            $role = htmlspecialchars($_POST['role']);
            $sql =("INSERT INTO personnel (login, password,nom,prenom,service,role) VALUE (?,?,?,?,?,?)");
            $reponse = $DB->prepare($sql);
            $reponse = $reponse->execute(array($login,$password,$nom, $prenom,$service,$role));
            
        }   
    ?> 
    <?php
        $reqpersonnels= $DB->prepare('SELECT id,nom,prenom,service,role FROM personnel');
        $reqpersonnels->execute();
        $personnels = $reqpersonnels->fetchall();
        foreach($personnels as $personnel){
           
        }
    ?>
    <table border="2">
        <caption>Personnelle </caption>
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>prenom</th>
                    <th>service</th>
                    <th>role</th>
                    <th> action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($personnels as $personnel):?>
                    <tr>
                        <td> <?php echo $personnel['nom'];?></td>
                        <td> <?php echo $personnel['prenom'];?></td>
                        <td> <?php echo $personnel['service'];?></td>
                        <td> <?php echo $personnel['role'];?></td>

                        <td>
                            <a href="ModifPersonnel.php?id=<?= $personnel['id'];?>">Modifier</a>
                            <a href="SupprPersonnel.php?id=<?= $personnel['id'];?>">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach ?>
            </tbody>
    </table>
</html>
