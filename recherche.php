<?php

require_once 'affichage.php';
require_once 'db.php';

echo pageHeader("Recherche");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['search'])) {
        $search = '%' . htmlspecialchars($_POST['search']) . '%';
        $sqlSearch = 'SELECT pseudo, date_inscription FROM utilisateurs WHERE pseudo LIKE :search;';
        $stmt = db()->prepare($sqlSearch);
        $stmt->bindParam(':search', $search);
        $stmt->execute();
        $results = $stmt->fetchAll();

        foreach ($results as $result) {
            echo '<div><a href="profil.php?pseudo=' . $result['pseudo'] . '">' . $result['pseudo'] . '</a></div>';
        }
    }
}



echo pageFooter();
