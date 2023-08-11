<?php
// update_status.php

require_once "../config.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Récupérer l'ID de la tâche et le nouveau statut depuis le formulaire soumis
    $taskId = $_POST["task_id"];
    $newStatus = $_POST["newStatus"];

    // Associer la couleur du statut en fonction du nouveau statut sélectionné
    $statusColors = [
        "en attente" => "orange",
        "en cours" => "green",
        "terminée" => "red"
    ];
    $newStatusColor = $statusColors[$newStatus];

    // Mettre à jour le statut de la tâche dans la base de données en utilisant PDO.
    try {
        // Requête SQL pour mettre à jour le statut et la couleur du statut de la tâche dans la table "tasks"
        $stmt = $pdo->prepare("UPDATE tasks SET status = :new_status, status_color = :new_status_color WHERE id = :task_id");
        $stmt->bindParam(":new_status", $newStatus, PDO::PARAM_STR);
        $stmt->bindParam(":new_status_color", $newStatusColor, PDO::PARAM_STR);
        $stmt->bindParam(":task_id", $taskId, PDO::PARAM_INT);
        $stmt->execute();
        
        // Rediriger l'utilisateur vers la page tasks.php après la mise à jour
        header("Location: tasks.php");
    } catch (PDOException $e) {
        // En cas d'erreur, afficher un message d'erreur
        echo "Une erreur est survenue lors de la mise à jour du statut de la tâche : " . $e->getMessage();
    }
}
?>
