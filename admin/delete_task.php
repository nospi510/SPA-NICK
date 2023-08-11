<?php
// delete_task.php

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

$taskId = $_GET["id"];

// Récupérer les informations de la tâche
$stmt = $pdo->prepare("SELECT * FROM tasks WHERE id = :id");
$stmt->bindParam(":id", $taskId, PDO::PARAM_INT);
$stmt->execute();
$task = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$task) {
    header("Location: admin.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        $stmt = $pdo->prepare("DELETE FROM tasks WHERE id = :id");
        $stmt->bindParam(":id", $taskId, PDO::PARAM_INT);
        $stmt->execute();

        header("Location: admin.php");
    } catch (PDOException $e) {
        echo "Une erreur est survenue lors de la suppression de la tâche : " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <title>Supprimer une Tâche</title>
</head>
<body>
    <div class="container">
        <h1>Supprimer une Tâche</h1>
        <p>Êtes-vous sûr de vouloir supprimer la tâche "<?php echo $task['title']; ?>" ?</p>

        <form hx-post="delete_task.php?id=<?php echo $taskId; ?>" hx-target="#content" method="POST">

        <button type="submit">Supprimer</button>
    </form>
        <p> <a href="admin.php" hx-get="admin.php" hx-target="#content">Retour</a></p>
    </div>
    <script src="../sourcehtmx/htmx.min.js"></script>
</body>
</html>
