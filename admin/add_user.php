<?php

require_once "../config.php";

session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "administrateur") {
    header("Location: index.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $role = $_POST["role"];

    try {
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role) VALUES (:username, :email, :password, :role)");
        $stmt->bindParam(":username", $username, PDO::PARAM_STR);
        $stmt->bindParam(":email", $email, PDO::PARAM_STR);
        $stmt->bindParam(":password", $password, PDO::PARAM_STR);
        $stmt->bindParam(":role", $role, PDO::PARAM_STR);
        $stmt->execute();

        header("Location: admin.php");
    } catch (PDOException $e) {
        echo "Une erreur est survenue lors de l'ajout de l'utilisateur : " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <title>Ajouter un Utilisateur</title>
</head>
<body>
    <div class="container">
        <h1>Ajouter un Utilisateur</h1>
        <form class="add-form" hx-post="add_user.php"  hx-target="#content" >
            <label for="username">Nom d'utilisateur :</label>
            <input type="text" id="username" name="username" required>
            <label for="email">Adresse e-mail :</label>
            <input type="email" id="email" name="email" required>
            <label for="password">Mot de passe :</label>
            <input type="password" id="password" name="password" required>
            <label for="role">Rôle :</label>
            <select id="role" name="role">
                <option value="administrateur">Administrateur</option>
                <option value="gestionnaire">Gestionnaire</option>
                <option value="utilisateur standard">Utilisateur Standard</option>
            </select>
            <button type="submit">Ajouter</button>
        </form>
        <p> <a href="admin.php" hx-get="admin.php" hx-target="#content">Retour</a></p>
    </div>
    
    <script src="../sourcehtmx/htmx.min.js"></script>

</body>
</html>
