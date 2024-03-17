<?php

require_once 'affichage.php';
require_once 'db.php';

echo pageHeader("Partager");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_SESSION['isConnect']) {
        if (!empty($_POST['fdescription']) && !empty($_POST['id_contenu'])) {

            // id user
            $sqlID = 'SELECT id FROM utilisateurs WHERE pseudo = :pseudo';
            $stmtID = db()->prepare($sqlID);
            $stmtID->bindParam(':pseudo', $_SESSION['isConnect']);
            $stmtID->execute();
            $ID = $stmtID->fetch();

            // chemin_image
            $sql = 'SELECT chemin_image FROM contenus WHERE id = :id';
            $stmt = db()->prepare($sql);
            $stmt->bindParam(':id', $_POST['id_contenu']);
            $stmt->execute();
            $chemin_image = $stmt->fetch();

            var_dump($chemin_image['chemin_image']);

            if (!empty($chemin_image['chemin_image'])) {
                $date_publication = date('Y-m-d H:i:s');
                $sqlPartage = 'INSERT INTO contenus(id_utilisateur, description, chemin_image, date_publication) VALUES (:id_utilisateur, :description, :chemin_image, :date_publication)';
                $stmtPartage = db()->prepare($sqlPartage);
                $stmtPartage->bindParam(':id_utilisateur', $ID['id']);
                $stmtPartage->bindParam(':description', $_POST['fdescription']);
                $stmtPartage->bindParam(':chemin_image', $chemin_image['chemin_image']);
                $stmtPartage->bindParam(':date_publication', $date_publication);
                $stmtPartage->execute();
            
                header('Location: ' . $_SERVER['HTTP_REFERER']);
            } else {
                echo "L'image associée à ce contenu n'a pas été trouvée.";
            }
        } else {
            echo "Veuillez remplir tous les champs.";
        }
    } else {
        header('location:connexion.php');
        exit();
    }
}

?>

<form action="partage.php" method="post">
    <input type="hidden" name="id_contenu" value="<?php echo isset($_POST['id_contenu']) ? $_POST['id_contenu'] : ''; ?>">
    <input type="text" name="fdescription">
    <button type="submit">Partager</button>
</form>

<?php

echo pageFooter();

?>
