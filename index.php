<?php

require_once 'affichage.php';
require_once 'db.php';

$memesParPage = 12;

$page = isset($_GET['page']) ? $_GET['page'] : 1;

$offset = ($page - 1) * $memesParPage;

$stmtContenus = db()->prepare("SELECT contenus.*, utilisateurs.pseudo FROM contenus INNER JOIN utilisateurs ON contenus.id_utilisateur = utilisateurs.id LIMIT :offset, :memesParPage;");
$stmtContenus->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmtContenus->bindParam(':memesParPage', $memesParPage, PDO::PARAM_INT);
$stmtContenus->execute();
$contenus = $stmtContenus->fetchAll();



function requestSQL($sql) {

    $stmt = db()->prepare($sql);

    $stmt->execute();

    $result = $stmt->fetch();

    return $result;
}

echo pageHeader("Bonjour");
?>

<link rel="stylesheet" href="styles/styleIndex.css">

<div class="tableauMemes">
    <?php
    foreach ($contenus as $contenu) {

        $sqlLikes = 'SELECT COUNT(*) FROM likes WHERE id_contenu = ' . $contenu['id'] . ';';
        $sqlCommentaires = 'SELECT commentaires.message, utilisateurs.pseudo FROM commentaires JOIN utilisateurs ON commentaires.id_utilisateur = utilisateurs.id WHERE commentaires.id_contenu = ' . $contenu['id'] . ' LIMIT 1';
        $stmtCommentaires = db()->prepare($sqlCommentaires);
        $stmtCommentaires->execute();
        $commentaires = $stmtCommentaires->fetchAll();

        echo '<div class="meme" id="' . $contenu['id'] . '">'
            . '<div class="utilisateur"><img src="imagesPP/ppVierge.png" alt="photo de profil" class="imagePP"><span class="pseudo"><a href="profil.php?pseudo=' . $contenu['pseudo'] . '">' . $contenu['pseudo'] . '</a></span></div>'
            . '<a href="contenu.php?id_contenu=' . $contenu['id'] . '"><img src="' . 'images/' . $contenu['chemin_image'] . '" class="image" /></a>'
            . '<div class="iconLikeCommentePartage">
            
                <form action="like.php" method="POST">
                    <input type="hidden" name="id_contenu" value="' . $contenu['id'] . '" />    
                    <button type="submit">
                        <ion-icon name="heart-outline"></ion-icon>
                    </button>
                </form>

                <form action="partage.php" method="POST">
                    <input type="hidden" name="id_contenu" value="' . $contenu['id'] . '" />
                    <button type="submit">
                        <ion-icon name="share-outline"></ion-icon>
                    </button>
                </form>
            </div>'
            . '<div class="likesDescription"><span class="likes">' . requestSQL($sqlLikes)['COUNT(*)'] . ' j\'aime</span>'
            . '<span class="description">' . $contenu['description'] . '</span></div>'
            .  '<form action="commentaire.php?id_contenu=' . $contenu['id'] . '" method="post">
                    <input type="text" name="message">
                    <button type="submit">
                        <ion-icon name="chatbox-outline"></ion-icon>
                    </button>
                </form>';
            foreach ($commentaires as $commentaire) { echo '<div>' . $commentaire['pseudo'] . " : " . $commentaire['message'] . '</div>'; };
            echo '<a href="contenu.php?id_contenu=' . $contenu['id'] . '" class="btnVoirplus">Voir plus</a></div>';
    }
    ?>
</div>
<div class="pagination">
    <?php
    $stmtNombresPages = db()->query("SELECT COUNT(*) FROM contenus");
    $totalMemes = $stmtNombresPages->fetchColumn();

    $totalPages = ceil($totalMemes / $memesParPage);

    for ($i = 1; $i <= $totalPages; $i++) {
        echo '<a href="?page=' . $i . '">' . $i . '</a>';
    }
    ?>
</div>

<?php echo pageFooter(); ?>
