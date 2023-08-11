<?php
// delete_message.php

require_once "../config.php";

session_start();

if (!isset($_SESSION["user_id"]) || ($_SESSION["role"] !== "administrateur" && $_SESSION["role"] !== "gestionnaire")) {
    header("Location: ../admin/index.php");
    exit;
}

if (!isset($_GET["id"])) {
    header("Location: ../admin/admin.php");
    exit;
}

$messageId = $_GET["id"];

// Récupérer les informations du message
$stmt = $pdo->prepare("SELECT * FROM messages WHERE id = :id");
$stmt->bindParam(":id", $messageId, PDO::PARAM_INT);
$stmt->execute();
$message = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$message) {
    header("Location: ../admin/admin.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        $stmt = $pdo->prepare("DELETE FROM messages WHERE id = :id");
        $stmt->bindParam(":id", $messageId, PDO::PARAM_INT);
        $stmt->execute();

        header("Location: ../admin/admin.php");
    } catch (PDOException $e) {
        echo "Une erreur est survenue lors de la suppression du message : " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Supprimer un Message</title>
</head>
<body>
    <div class="container">
        <h1>Supprimer un Message</h1>
        <p>Êtes-vous sûr de vouloir supprimer ce message ?</p>

        <form hx-post="../messagerie/delete_message.php?id=<?php echo $messageId; ?>" hx-target="#content" method="POST">
            <button type="submit">Supprimer</button>
        </form>

        <p><a href="../admin/admin.php" hx-get="../admin/admin.php" hx-target="#content">Retour</a></p>
    </div>
    <script src="../sourcehtmx/htmx.min.js"></script>
</body>
</html>
