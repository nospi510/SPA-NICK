<?php


require_once "../config.php";

session_start();

// Vérifier si l'administrateur est déjà connecté, le rediriger vers la page d'administration s'il l'est
if (isset($_SESSION["user_id"]) && ($_SESSION["role"] !== "administrateur" && $_SESSION["role"] !== "gestionnaire")) {
    header("Location: admin.php");
    exit;
}

// Vérifier si le formulaire de connexion a été soumis
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Vérifier les informations de connexion de l'administrateur
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username AND role IN ('administrateur', 'gestionnaire')");
    $stmt->bindParam(":username", $username, PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user["password"])) {
        // Authentification réussie, enregistrer les informations de l'administrateur dans la session
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["username"] = $user["username"];
        $_SESSION["role"] = $user["role"];

        // Rediriger vers la page d'administration
        header("Location: admin.php");
        exit;
    } else {
        // Identifiants incorrects, afficher un message d'erreur
        $error_message = "Nom d'utilisateur ou mot de passe incorrect.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <title>Connexion Administrateur</title>
</head>
<body>
    <div class="container">
    <h1>Plateforme de gestion des tâches</h1>
        <h1>Connexion Administrateur</h1>
     
       
       <form class="login_form" hx-post="index.php"  hx-target="#content" method="POST">
   
        <?php if (isset($error_message)) : ?>
            <p class="error"><?php echo $error_message; ?></p>
        <?php endif; ?>

            <label for="username">Nom d'utilisateur :</label>
            <input type="text" id="username" name="username" required>
            <label for="password">Mot de passe :</label>
            <input type="password" id="password" name="password" required>
            <button type="submit">Se connecter</button>
        </form>
    </div>
    <script src="../sourcehtmx/htmx.min.js"></script>
</body>
</html>
