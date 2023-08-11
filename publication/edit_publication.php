<?php
// edit_publication.php

session_start();

// Vérifier si l'utilisateur est connecté et est un administrateur ou gestionnaire
if (!isset($_SESSION["user_id"]) || ($_SESSION["role"] !== "administrateur" && $_SESSION["role"] !== "gestionnaire")) {
    header("Location: ../admin/index.php");
    exit;
}

require_once "../config.php";

// Vérifier si l'ID de la publication est passé en paramètre
if (!isset($_GET["id"]) || empty($_GET["id"])) {
    header("Location: ../admin/admin.php");
    exit;
}

$publication_id = $_GET["id"];

// Récupérer les données de la publication depuis la base de données
$stmt = $pdo->prepare("SELECT * FROM publications WHERE id = ?");
$stmt->execute([$publication_id]);
$publication = $stmt->fetch(PDO::FETCH_ASSOC);

// Vérifier si la publication existe
if (!$publication) {
    header("Location: ../admin/admin.php");
    exit;
}

// Vérifier si le formulaire de modification a été soumis
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Récupérer les données du formulaire de modification
    $title = $_POST["title"];
    $content = $_POST["content"];

    // Mettre à jour la publication dans la base de données
    $stmt = $pdo->prepare("UPDATE publications SET title = ?, content = ? WHERE id = ?");
    $stmt->execute([$title, $content, $publication_id]);

    // Rediriger vers la page d'accueil après la mise à jour de la publication
    header("Location: ../admin/admin.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <title>Modifier la publication</title>
</head>
<body>
    <h1>Modifier la publication</h1>
    <form hx-post="../publication/edit_publication.php?id=<?php echo $publication_id; ?>" hx-target="#content" method="post">
        <label for="title">Titre :</label>
        <input type="text" name="title" value="<?php echo $publication['title']; ?>" required><br>
        <label for="content">Contenu :</label>
        <textarea name="content" required><?php echo $publication['content']; ?></textarea><br>
        <button type="submit">Enregistrer les modifications</button>
    </form>
    <script src="../sourcehtmx/htmx.min.js"></script>

</body>
</html>
