<?php
// save_task.php

require_once "../config.php";

session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "utilisateur standard") {
    header("Location: login.html");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $taskTitle = $_POST["taskTitle"];
    $taskDescription = $_POST["taskDescription"];
    $dueDate = $_POST["dueDate"];

    // Vérifier si l'utilisateur est connecté
    if (!isset($_SESSION["user_id"])) {
        echo "Veuillez vous connecter pour ajouter une tâche.";
        exit;
    }

    // Récupérer l'ID de l'utilisateur connecté
    $userId = $_SESSION["user_id"];

    // Vous pouvez insérer les données dans la base de données en utilisant PDO ici.
    try {
        
        // Configuration pour que PDO génère des exceptions en cas d'erreur
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Requête SQL pour insérer la tâche dans la table "tasks"
        $stmt = $pdo->prepare("INSERT INTO tasks (title, description, due_date, status, assigned_to) VALUES (:title, :description, :due_date, :status, :assigned_to)");
        
        // Par défaut, nous pouvons initialiser le statut de la tâche à "en attente" et l'ID de l'utilisateur attribué à l'ID de l'utilisateur connecté.
        $status = "en attente";
        $assignedTo = $userId;
        
        $stmt->bindParam(":title", $taskTitle, PDO::PARAM_STR);
        $stmt->bindParam(":description", $taskDescription, PDO::PARAM_STR);
        $stmt->bindParam(":due_date", $dueDate, PDO::PARAM_STR);
        $stmt->bindParam(":status", $status, PDO::PARAM_STR);
        $stmt->bindParam(":assigned_to", $assignedTo, PDO::PARAM_INT);
        $stmt->execute();
        
        // Afficher un message de confirmation
        echo '<div>
                <h2>Tâche ajoutée avec succès !</h2>
                <p>Titre : '.$taskTitle.'</p>
                <p>Description : '.$taskDescription.'</p>
                <p>Date d\'échéance : '.$dueDate.'</p>
                </br>
                <p> <a href="tasks.php" hx-get="tasks.php" hx-target="#content">Retour</a></p>

            </div>';

    } catch (PDOException $e) {
        // En cas d'erreur, afficher un message d'erreur
        echo "Une erreur est survenue lors de l'ajout de la tâche : " . $e->getMessage();
    }
}
?>



<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une tâche</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="container">
        <h1>Ajouter une tâche</h1>
        <form id="addTaskForm" hx-post="add_task.php" hx-target="#content">
            <label for="taskTitle">Titre de la tâche :</label>
            <input type="text" id="taskTitle" name="taskTitle" required>
            <label for="taskDescription">Description de la tâche :</label>
            <textarea id="taskDescription" name="taskDescription" required></textarea>
            <label for="dueDate">Date d'échéance :</label>
            <input type="date" id="dueDate" name="dueDate" required>
            <button type="submit">Ajouter</button>
        </form>

        <p> <a href="tasks.php" hx-get="tasks.php" hx-target="#content">Retour</a></p>
    </div>
    <script src="../sourcehtmx/htmx.min.js"></script>
</body>
</html>
