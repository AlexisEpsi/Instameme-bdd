<?php

session_start();
require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_SESSION['isConnect'])) {
        $id_contenu = $_POST['id_contenu'];

        $sqlIsLiked = 'SELECT COUNT(*) AS count_likes FROM likes JOIN utilisateurs ON likes.id_utilisateur = utilisateurs.id WHERE utilisateurs.pseudo = :pseudo AND likes.id_contenu = :id_contenu;';
        $stmtIsLiked = db()->prepare($sqlIsLiked);
        $stmtIsLiked->bindParam(':id_contenu', $id_contenu);
        $stmtIsLiked->bindParam(':pseudo', $_SESSION['isConnect']);
        $stmtIsLiked->execute();
        $IsLiked = $stmtIsLiked->fetch();

        if ($IsLiked['count_likes'] == 0) {
            $sql = 'INSERT INTO likes (id_contenu, id_utilisateur) SELECT :id_contenu, id FROM utilisateurs WHERE pseudo = :pseudo';
            $stmt = db()->prepare($sql);
            $stmt->bindParam(':id_contenu', $id_contenu);
            $stmt->bindParam(':pseudo', $_SESSION['isConnect']);
            $stmt->execute();
        } else {
            $sqlDelete = 'DELETE likes FROM likes JOIN utilisateurs ON likes.id_utilisateur = utilisateurs.id WHERE likes.id_contenu = :id_contenu AND utilisateurs.pseudo = :pseudo';
            $stmtDelete = db()->prepare($sqlDelete);
            $stmtDelete->bindParam(':id_contenu', $id_contenu);
            $stmtDelete->bindParam(':pseudo', $_SESSION['isConnect']);
            $stmtDelete->execute();
        }

        header('Location: ' . $_SERVER['HTTP_REFERER']);
    } else {
        header('location:connexion.php');
    }
}
