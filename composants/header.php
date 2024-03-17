<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <link rel="stylesheet" href="styles/style.css">
</head>

<body>
<header>
    <div>       
        <a href="index.php"><img src="assets/logo.png" style="width: 50px" alt="logo"></a>
    </div>
    <div style="text-align: center" class="middel">
        <form action="recherche.php" method="POST">
            <input type="search" name="search">
            <button type="submit"><ion-icon name="search-outline"></ion-icon></button>
        </form>
    </div>
    <div style="text-align: right"><?php 
    if (empty($_SESSION['isConnect'])) {
        echo '<a href="inscription.php">Inscription</a>';
        echo '<a href="connexion.php">Connexion</a>';
    } else {
        echo '<a href="creer.php">Créer</a>';                  
        echo '<a href="profil.php?pseudo=' . $_SESSION['isConnect'] . '">Profil</a>';                  
        echo '<a href="deconnexion.php">Déconnexion</a>';                  
    }
    ?></div>
</header>

