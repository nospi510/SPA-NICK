<?php
// delete_user.php

require_once "../config.php";

session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "administrateur") {
    header("Location: index.php");
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
    try {
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = :id");
        $stmt->bindParam(":id", $userId, PDO::PARAM_INT);
        $stmt->execute();

        header("Location: admin.php");
    } catch (PDOException $e) {
        echo "Une erreur est survenue lors de la suppression de l'utilisateur : " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <title>Supprimer un Utilisateur</title>
</head>
<body>
    <div class="container">
        <h1>Supprimer un Utilisateur</h1>
        <p>Êtes-vous sûr de vouloir supprimer l'utilisateur "<?php echo $user['username']; ?>" ?</p>

        <form hx-post="delete_user.php?id=<?php echo $userId; ?>" hx-target="#content" method="POST">
        <button type="submit">Supprimer</button>
    </form>

        <p> <a href="admin.php" hx-get="admin.php" hx-target="#content">Retour</a></p>
    </div>
    <script src="../sourcehtmx/htmx.min.js"></script>
</body>
</html>
