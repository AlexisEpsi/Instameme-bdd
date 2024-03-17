<?php

session_start();
require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_SESSION['isConnect']) == true && !empty($_POST['message']) && !empty($_GET['id_contenu'])) {
        $id_contenu = $_GET['id_contenu'];
        $message = $_POST['message'];
        $date_publication = date('Y-m-d H:i:s');

        $sql = 'INSERT INTO commentaires (id_contenu, id_utilisateur, message, date_publication) SELECT :id_contenu, id, :message, :date_publication FROM utilisateurs WHERE pseudo = :pseudo';
        $stmt = db()->prepare($sql);
        $stmt->bindParam(':id_contenu', $id_contenu);
        $stmt->bindParam(':message', $message);
        $stmt->bindParam(':pseudo', $_SESSION['isConnect']);
        $stmt->bindParam(':date_publication', $date_publication);
        $stmt->execute();

        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }
}