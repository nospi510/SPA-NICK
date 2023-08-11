<?php
// edit_task.php

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
    $title = $_POST["title"];
    $description = $_POST["description"];
    $dueDate = $_POST["due_date"];

    try {
        $stmt = $pdo->prepare("UPDATE tasks SET title = :title, description = :description, due_date = :due_date WHERE id = :id");
        $stmt->bindParam(":title", $title, PDO::PARAM_STR);
        $stmt->bindParam(":description", $description, PDO::PARAM_STR);
        $stmt->bindParam(":due_date", $dueDate, PDO::PARAM_STR);
        $stmt->bindParam(":id", $taskId, PDO::PARAM_INT);
        $stmt->execute();

        header("Location: admin.php");
    } catch (PDOException $e) {
        echo "Une erreur est survenue lors de la modification de la tâche : " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <title>Modifier une Tâche</title>
</head>
<body>
    <div class="container">
        <h1>Modifier une Tâche</h1>
        <form hx-post="edit_task.php?id=<?php echo $taskId; ?>" hx-target="#content" method="POST">
        
        <label for="title">Titre :</label>
        <input type="text" id="title" name="title" value="<?php echo $task['title']; ?>" required>

        <label for="description">Description :</label>
        <textarea id="description" name="description" required><?php echo $task['description']; ?></textarea>

        <label for="due_date">Date d'échéance :</label>
        <input type="date" id="due_date" name="due_date" value="<?php echo $task['due_date']; ?>" required>

        <button type="submit">Modifier</button>
        </form>
        <p> <a href="admin.php" hx-get="admin.php" hx-target="#content">Retour</a></p>

    </div>
    <script src="../sourcehtmx/htmx.min.js"></script>
</body>
</html>
