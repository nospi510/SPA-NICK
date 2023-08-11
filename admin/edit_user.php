<?php

require_once "../config.php";

session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "administrateur") {
    header("Location: admin.php");
    exit;
}

if (!isset($_GET["id"])) {
    header("Location: admin.php");
    exit;
}

$userId = $_GET["id"];

// Récupérer les informations de l'utilisateur
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
$stmt->bindParam(":id", $userId, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    header("Location: admin.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST["username"];
    $email = $_POST["email"];
    $role = $_POST["role"];

    try {
        $stmt = $pdo->prepare("UPDATE users SET username = :username, email = :email, role = :role WHERE id = :id");
        $stmt->bindParam(":username", $username, PDO::PARAM_STR);
        $stmt->bindParam(":email", $email, PDO::PARAM_STR);
        $stmt->bindParam(":role", $role, PDO::PARAM_STR);
        $stmt->bindParam(":id", $userId, PDO::PARAM_INT);
        $stmt->execute();

        header("Location: admin.php");
    } catch (PDOException $e) {
        echo "Une erreur est survenue lors de la mise à jour de l'utilisateur : " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <title>Modifier un Utilisateur</title>
</head>
<body>
    <div class="container">
        <h1>Modifier un Utilisateur</h1>

        <form hx-post="edit_user.php?id=<?php echo $userId; ?>" hx-target="#content" method="POST">
        <label for="username">Nom d'utilisateur :</label>
        <input type="text" id="username" name="username" value="<?php echo $user['username']; ?>" required>
        <label for="email">Adresse e-mail :</label>
        <input type="email" id="email" name="email" value="<?php echo $user['email']; ?>" required>
        <label for="role">Rôle :</label>
        <select id="role" name="role">
            <option value="administrateur" <?php if ($user['role'] === 'administrateur') echo 'selected'; ?>>Administrateur</option>
            <option value="gestionnaire" <?php if ($user['role'] === 'gestionnaire') echo 'selected'; ?>>Gestionnaire</option>
            <option value="utilisateur standard" <?php if ($user['role'] === 'utilisateur standard') echo 'selected'; ?>>Utilisateur Standard</option>
        </select>
        <button type="submit">Modifier</button>
    </form>

        <p> <a href="admin.php" hx-get="admin.php" hx-target="#content">Retour</a></p>
    </div>
    <script src="../sourcehtmx/htmx.min.js"></script>
</body>
</html>
