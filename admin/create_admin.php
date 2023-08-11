<?php
// create_admin.php

require_once "../config.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $role = "administrateur"; // Par défaut, l'utilisateur créé sera un administrateur

    try {
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role) VALUES (:username, :email, :password, :role)");
        $stmt->bindParam(":username", $username, PDO::PARAM_STR);
        $stmt->bindParam(":email", $email, PDO::PARAM_STR);
        $stmt->bindParam(":password", $password, PDO::PARAM_STR);
        $stmt->bindParam(":role", $role, PDO::PARAM_STR);
        $stmt->execute();

        echo "Administrateur créé avec succès !";
    } catch (PDOException $e) {
        echo "Une erreur est survenue lors de la création de l'administrateur : " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <title>Créer un Administrateur</title>
</head>
<body>
    <div class="container">
        <h1>Créer un Administrateur</h1>
        <form action="create_admin.php" method="POST">
            <label for="username">Nom d'utilisateur :</label>
            <input type="text" id="username" name="username" required>
            <label for="email">Adresse e-mail :</label>
            <input type="email" id="email" name="email" required>
            <label for="password">Mot de passe :</label>
            <input type="password" id="password" name="password" required>
            <button type="submit">Créer l'Administrateur</button>
        </form>
        <p><a href="../admin">Retour </a></p>
    </div>
    <script src="../sourcehtmx/htmx.min.js"></script>
</body>
</html>

<form class="login_form" hx-post="index.php"  hx-target="#content">
