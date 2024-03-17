<?php

require_once 'affichage.php';
require_once 'db.php';

echo pageHeader("Inscription");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (!empty($_POST['fpseudo']) == true && !empty($_POST['fpassword']) == true) {

        $pseudo = htmlspecialchars($_POST['fpseudo']);
        $password = md5(htmlspecialchars($_POST['fpassword']));

        $sqlQuery = 'SELECT pseudo FROM utilisateurs';
        $recipesStatement = db()->prepare($sqlQuery);
        $recipesStatement->execute();
        $recipes = $recipesStatement->fetchAll(PDO::FETCH_COLUMN);

        if (!in_array($pseudo, $recipes)) {
            $date_inscription = date('Y-m-d H:i:s');

            $sql = 'INSERT INTO utilisateurs(pseudo, mot_de_passe, date_inscription) VALUES (:pseudo, :mot_de_passe, :date_inscription)';
            $statement = db()->prepare($sql);
            $statement->bindParam(':pseudo', $pseudo, PDO::PARAM_STR);
            $statement->bindParam(':mot_de_passe', $password, PDO::PARAM_STR);
            $statement->bindParam(':date_inscription', $date_inscription, PDO::PARAM_STR);
            $statement->execute();
            $_SESSION['isConnect'] = $_POST['fpseudo'];
            header('Location: ' . $_SERVER['HTTP_REFERER']);
        } else {
            echo "<div style='color: red;'>Veuillez choisir un autre nom d'utilisateur.</div>";
        }
    }
}

?>
<form action="inscription.php" method="POST">
    <input type="text" name="fpseudo" placeholder="pseudo">
    <input type="text" name="fpassword" placeholder="password">
    <input type="submit" value="Inscription">
</form>
<div>je me <a href="connexion.php">connecte</a></div>
<?php echo pageFooter(); ?>