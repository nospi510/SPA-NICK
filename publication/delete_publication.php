<?php
// delete_publication.php

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

$publicationId = $_GET["id"];

// Récupérer les informations de la publication
$stmt = $pdo->prepare("SELECT * FROM publications WHERE id = :id");
$stmt->bindParam(":id", $publicationId, PDO::PARAM_INT);
$stmt->execute();
$publication = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$publication) {
    header("Location: ../admin/admin.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        $stmt = $pdo->prepare("DELETE FROM publications WHERE id = :id");
        $stmt->bindParam(":id", $publicationId, PDO::PARAM_INT);
        $stmt->execute();

        header("Location: ../admin/admin.php");
    } catch (PDOException $e) {
        echo "Une erreur est survenue lors de la suppression de la publication : " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <title>Supprimer une publication</title>
</head>
<body>
<div class="container">
        <h1>Supprimer une publication</h1>
        <p>Êtes-vous sûr de vouloir supprimer cette publication ?</p>
        <div class="publication">
            <h2>Titre: <?php echo $publication['title']; ?></h2>
            <p>Contenu: <?php echo $publication['content']; ?></p>
            <p>Date de création : <?php echo $publication['created_at']; ?></p>
        <form hx-post="../publication/delete_publication.php?id=<?php echo $publicationId; ?>" hx-target="#content" method="POST">
            <button type="submit">Supprimer</button>
        </form>
        </div>

    </div>
    <script src="../sourcehtmx/htmx.min.js"></script>
</body>
</html>
