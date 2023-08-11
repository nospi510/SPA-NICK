<?php

require_once "../config.php";

session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "administrateur") {
    header("Location: index.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $assignedTo = $_POST["assigned_to"];
    $title = $_POST["title"];
    $description = $_POST["description"];
    $dueDate = $_POST["due_date"];
    $status = "en attente";

    try {
        $stmt = $pdo->prepare("INSERT INTO tasks (title, description, due_date, status, assigned_to) VALUES (:title, :description, :due_date, :status, :assigned_to)");
        $stmt->bindParam(":title", $title, PDO::PARAM_STR);
        $stmt->bindParam(":description", $description, PDO::PARAM_STR);
        $stmt->bindParam(":due_date", $dueDate, PDO::PARAM_STR);
        $stmt->bindParam(":status", $status, PDO::PARAM_STR);
        $stmt->bindParam(":assigned_to", $assignedTo, PDO::PARAM_INT);
        $stmt->execute();

        header("Location: admin.php");
    } catch (PDOException $e) {
        echo "Une erreur est survenue lors de l'ajout de la tâche : " . $e->getMessage();
    }
}

// Récupérer la liste des utilisateurs pour l'attribution de la tâche
$stmtUsers = $pdo->query("SELECT * FROM users");
$users = $stmtUsers->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../style.css">
        <title>Assigner une Tâche</title>
    </head>
    <body>
        <div class="container">
            <h1>Assigner une Tâche</h1>

            <form hx-post="assign_task.php" hx-target="#content" method="POST">
                <label for="assigned_to">Attribuer à :</label>
                <select id="assigned_to" name="assigned_to">
                    <option value="" disabled selected>Sélectionner un utilisateur</option>
                    <?php foreach ($users as $user) : ?>
                        <option value="<?php echo $user['id']; ?>"><?php echo $user['username']; ?></option>
                    <?php endforeach; ?>
                </select>
                <label for="title">Titre :</label>
                <input type="text" id="title" name="title" required>
                <label for="description">Description :</label>
                <textarea id="description" name="description" required></textarea>
                <label for="due_date">Date d'échéance :</label>
                <input type="date" id="due_date" name="due_date" required>
                <button type="submit">Assigner</button>
            </form>

            <p> <a href="admin.php" hx-get="admin.php" hx-target="#content">Retour</a></p>
        </div>
        <script src="../sourcehtmx/htmx.min.js"></script>

    </body>
</html>
