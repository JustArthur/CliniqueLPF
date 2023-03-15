<?php
    include_once('../include.php');


?>

<html>
    <head>

    </head>
    <body>
        <form method="post">
            <label for="login">login</label>
            <input name="login" type="text" required>
            <label for="password">Password</label>
            <input name="password" type="password" required>
            <label for="nom">Nom</label>
            <input name="nom">
            <label for="prenom">Prenom</label>
            <input name="prenom" type="text" required>
            <label for="service">service</label>
            <input name="service" type="text" required>
            <label for="role">role</label>

            <select name="" id="">
                <option value=1>Secretaire</option>
            </select>
            <button name="ajouter">Ajouter</button>
        </form>

        <script src="js/expireConnexion.js"></script>
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
        if($reponse){
            echo 'Données insérées';
        }else{
            echo "Échec de l'opération d'insertion";
        }
    }
?>
</html>