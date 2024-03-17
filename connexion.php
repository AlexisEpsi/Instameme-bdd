<?php

require_once 'affichage.php';
require_once 'db.php';

echo pageHeader("Connexion");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (!empty($_POST['fpseudo']) == true && !empty($_POST['fpassword']) == true) {

        $pseudo = htmlspecialchars($_POST['fpseudo']);
        $password = htmlspecialchars($_POST['fpassword']);

        $sqlQuery = 'SELECT pseudo FROM utilisateurs';
        $recipesStatement = db()->prepare($sqlQuery);
        $recipesStatement->execute();
        $recipes = $recipesStatement->fetchAll(PDO::FETCH_COLUMN);

        if (in_array($pseudo, $recipes)) {
            $sql = 'SELECT mot_de_passe FROM utilisateurs WHERE pseudo = :pseudo;';
            $statement = db()->prepare($sql);
            $statement->bindParam(':pseudo', $pseudo, PDO::PARAM_STR);
            $statement->execute();
            $result = $statement->fetch();

            if (md5($password) == $result['mot_de_passe']) {
                $_SESSION['isConnect'] = $_POST['fpseudo'];
                header('Location: index.php');
            } else {
                echo "Mot de passe incorrect.";
            }
        }
    }
}

?>
<form action="connexion.php" method="POST">
    <input type="text" name="fpseudo" placeholder="pseudo">
    <input type="text" name="fpassword" placeholder="password">
    <input type="submit" value="Connexion">
</form>
<div>je <a href="inscription.php">m'inscris</a></div>
<?php echo pageFooter(); ?>