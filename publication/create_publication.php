<?php
// create_publication.php

require_once "../config.php";
session_start();

// Vérifier si l'utilisateur est connecté et est un administrateur ou gestionnaire
if (!isset($_SESSION["user_id"]) || ($_SESSION["role"] !== "administrateur" && $_SESSION["role"] !== "gestionnaire")) {
    header("Location: ../admin/index.php");
    exit;
}

// Récupérer les données du formulaire
$title = $_POST["title"];
$content = $_POST["content"];
$created_by = $_SESSION["user_id"];

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Insérer la nouvelle publication dans la base de données
    $stmt = $pdo->prepare("INSERT INTO publications (title, content, created_by) VALUES (?, ?, ?)");
    $stmt->execute([$title, $content, $created_by]);
}

?>


<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../css/style.css">
        <title>creer une publication</title>
    </head>
    <body>
        <div class="container">

        <?php
            // Afficher un message de succès si le formulaire a été soumis
            if ($_SERVER["REQUEST_METHOD"] === "POST") {
                echo '<div>
                <h2>Publication ajoutée avec succès !</h2>
                <p>Titre : ' . $title . '</p>
                <p>Contenu : ' . $content . '</p>
                </br>
                </div>';
                }
            ?>
            
            <h1> Creer une publication </h1>
            <form hx-post="../publication/create_publication.php"  hx-target="#content"  method="POST">
            
                <label for="title">Titre :</label>
                <input type="text" name="title" required><br>
                <label for="content">Contenu :</label>
                <textarea name="content" required></textarea><br>
                <button type="submit">Publier</button>
    
            </form>
            <p> <a href="../admin/admin.php" hx-get="../admin/admin.php" hx-target="#content">Retour</a></p>

            
            
        </div>



        <script src="../sourcehtmx/htmx.min.js"></script>
    </body>
</html>






