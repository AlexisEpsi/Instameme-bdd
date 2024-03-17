<?php

require_once 'affichage.php';
require_once 'db.php';

echo pageHeader("contenu");

$id_contenu = $_GET['id_contenu'];

$sql = 'SELECT contenus.chemin_image, contenus.description, commentaires.message, utilisateurs_commentaires.pseudo AS pseudo_commentaire, utilisateurs.pseudo AS pseudo_utilisateur, COUNT(likes.id_utilisateur) AS nombre_likes FROM contenus LEFT JOIN commentaires ON contenus.id = commentaires.id_contenu LEFT JOIN utilisateurs utilisateurs_commentaires ON commentaires.id_utilisateur = utilisateurs_commentaires.id LEFT JOIN utilisateurs ON contenus.id_utilisateur = utilisateurs.id LEFT JOIN likes ON contenus.id = likes.id_contenu WHERE contenus.id = :id_contenu GROUP BY contenus.id, commentaires.id;';
$stmt = db()->prepare($sql);
$stmt->bindParam(':id_contenu', $id_contenu);
$stmt->execute();
$results = $stmt->fetchAll();

?>
<section class="contenu">
    <div class="image">
        <?php 
        if (!empty($results)) {
            echo '<img src="images/' . $results[0]['chemin_image'] . '" alt="image contenu"><div>pseudo : ' 
            . $results[0]['pseudo_utilisateur']
            . ' <form action="like.php" method="POST">
                    <input type="hidden" name="id_contenu" value="' . $id_contenu . '" />    
                    <button type="submit">
                        <ion-icon name="heart-outline" class="iconLike"></ion-icon>
                    </button>
                </form>

                <form action="commentaire.php?id_contenu=' . $id_contenu . '" method="POST">
                    <input type="text" name="message">
                    <button type="submit">
                        <ion-icon name="chatbox-outline"></ion-icon>
                    </button>
                </form>

                <form action="partage.php" method="POST">
                    <input type="hidden" name="id_contenu" value="' . $id_contenu . '" />
                    <button type="submit">
                        <ion-icon name="share-outline"></ion-icon>
                    </button>
                </form>'
            . $results[0]['description'] . ' j\'aime '
            . $results[0]['nombre_likes'] . '</div>';
            foreach ($results as $result) {
                if (isset($result['message'])) {
                    echo '<div><a href="profil.php?pseudo=' . $result['pseudo_commentaire'] . '">' . $result['pseudo_commentaire'] . '</a> : ' . $result['message'] . '</div>';
                }
            }
        } else {
            echo 'Aucun contenu trouvé';
        }
        ?>
    </div>
</section>

<?php echo pageFooter(); ?>
