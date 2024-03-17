<?php

require_once 'affichage.php';
require_once 'db.php';

echo pageHeader("Créer");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['fdescription']) && !empty($_FILES['fimage']['name'])) {

        $sqlGetID = 'SELECT id FROM utilisateurs WHERE pseudo = :pseudo';
        $statement = db()->prepare($sqlGetID);
        $statement->bindParam(':pseudo', $_SESSION['isConnect'], PDO::PARAM_STR);
        $statement->execute();
        $getID = $statement->fetch();

        $id_utilisateur = $getID['id'];
        $description = htmlspecialchars($_POST['fdescription']);

        $file_name = $_FILES['fimage']['name'];

        $target_dir = "images/";
        $target_file = $target_dir . basename($file_name);

        if (move_uploaded_file($_FILES["fimage"]["tmp_name"], $target_file)) {
            $date_publication = date('Y-m-d H:i:s');

            $sqlPartage = 'INSERT INTO contenus(id_utilisateur, description, chemin_image, date_publication) VALUES (:id_utilisateur, :description, :chemin_image, :date_publication)';
            $stmtPartage = db()->prepare($sqlPartage);
            $stmtPartage->bindParam(':id_utilisateur', $id_utilisateur);
            $stmtPartage->bindParam(':description', $description);
            $stmtPartage->bindParam(':chemin_image', $file_name);
            $stmtPartage->bindParam(':date_publication', $date_publication);
            $stmtPartage->execute();

            header('location:index.php');

        } else {
            echo "Désolé, une erreur s'est produite lors du téléchargement de votre fichier.";
        }
    } else {
        echo "Veuillez remplir tous les champs.";
    }
}

if (!empty($_SESSION['isConnect'])) {
?>

<form action="creer.php" method="POST" enctype="multipart/form-data">

    <label for="fdescription">Description</label>
    <input type="text" id="fdescription" name="fdescription"><br><br>

    <label for="fimage">Votre Image (jpg, jpeg, png, gif seulement)</label>
    <input type="file" id="fimage" name="fimage" accept=".jpg, .jpeg, .png, .gif"><br><br>

    <input type="submit" value="Publier">
</form>

<?php
} else {
    header("location:connexion.php");
}

echo pageFooter();

?>