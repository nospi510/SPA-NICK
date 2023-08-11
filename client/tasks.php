<?php
// tasks.php


require_once "../config.php";

session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "utilisateur standard") {
    header("Location: login.html");
    exit;
}

// Récupérer les tâches de l'utilisateur connecté
$stmt = $pdo->prepare("SELECT t.*, u.username FROM tasks t LEFT JOIN users u ON t.assigned_to = u.id");
$stmt->execute();
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);


?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style1.css">
    <title>Plateforme de gestion des tâches</title>
</head>
<body>
    
<div class="container">
        <h1>Bienvenue, <?php echo $_SESSION["username"]; ?> !</h1>        
        </br>

        <a href="logout.php" hx-get="logout.php" hx-target="#content">Se déconnecter</a>
        
        <h2>Liste de vos tâches</h2>
        <table>
            <thead>
                <tr>
                    <th>Titre</th>
                    <th>Description</th>
                    <th>Date d'échéance</th>
                    <th>Statut</th>
                    <th>Attribuée à</th>

                </tr>
            </thead>
            <tbody>
        <?php foreach ($tasks as $task) : ?>
            <tr>
                <td><?php echo $task['title']; ?></td>
                <td><?php echo $task['description']; ?></td>
                <td><?php echo $task['due_date']; ?></td>
                <td class="status" data-task-id="<?php echo $task['id']; ?>" data-status="<?php echo $task['status']; ?>">
                <?php echo ucfirst($task['status']); ?>
            </td>
            <td><?php echo $task['username']; ?></td>
            <td>
                <?php if ($task['assigned_to'] === $_SESSION["user_id"]) : ?>
                    <form class="status-form" hx-post="update_status.php"  hx-target="#content">
                        <!-- Champ task_id inclus dans le formulaire -->
                        <input type="hidden" name="task_id" value="<?php echo $task['id']; ?>">
                        <select name="newStatus">
                            <option value="en attente" <?php if ($task['status'] === 'en attente') echo 'selected'; ?>>En attente</option>
                            <option value="en cours" <?php if ($task['status'] === 'en cours') echo 'selected'; ?>>En cours</option>
                            <option value="terminée" <?php if ($task['status'] === 'terminée') echo 'selected'; ?>>Terminée</option>
                        </select>
                        <button type="submit">OK</button>
                    </form>
                <?php endif; ?>
            </td>
                
    
            </tr>
        <?php endforeach; ?>
    
    </tbody>
        </table>
    </div>
    <script src="../sourcehtmx/htmx.min.js"></script>
    <script src="tasks.js"></script>
</body>
</html>
