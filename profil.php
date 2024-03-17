<?php

require_once 'affichage.php';
require_once 'db.php';

if (empty($_GET['pseudo'])) {
    header('Location: ' . $_SERVER['HTTP_REFERER']);
}

$pseudo = htmlspecialchars($_GET['pseudo']);

echo pageHeader($pseudo);
?>
<link rel="stylesheet" href="styles/styleProfil.css">
<section class="sectionProfil">
    <div class="profil">
        <img src="imagesPP/ppVierge.png" alt="Photo de profil" class="pp">
        <div><?php echo $pseudo; ?></div>
    </div>
</section>
<section class="publication">
<?php

$sqlPubliProfil = 'SELECT contenus.id, contenus.chemin_image FROM contenus JOIN utilisateurs ON contenus.id_utilisateur = utilisateurs.id WHERE utilisateurs.pseudo = :pseudo ORDER BY contenus.date_publication DESC;';
$stmtPubliProfil = db()->prepare($sqlPubliProfil);
$stmtPubliProfil->bindParam(':pseudo', $pseudo, PDO::PARAM_STR);
$stmtPubliProfil->execute();
$publiProfil = $stmtPubliProfil->fetchAll();
?>
<section class="publication">
    <?php foreach ($publiProfil as $publi) : ?>
        <div class="carrePubli">
            <a href="contenu.php?id_contenu=<?= $publi['id'] ?>">
                <img src="images/<?= $publi['chemin_image'] ?>" class="imagePubli">
            </a>
            <form action="like.php" method="POST">
                <input type="hidden" name="id_contenu" value="<?= $publi['id'] ?>" />    
                <button type="submit" class="like">
                    <ion-icon name="heart-outline"></ion-icon>
                </button>
            </form>
        </div>
    <?php endforeach; ?>
</section></section>

<?php echo pageFooter(); ?>

