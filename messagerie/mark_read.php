<?php
// mark_read.php

require_once "../config.php";

session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: index.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["id"])) {
    $messageId = $_GET["id"];
    $userId = $_SESSION["user_id"];

    try {
        $stmt = $pdo->prepare("UPDATE messages SET is_read = 1 WHERE id = :id AND receiver_id = :receiver_id");
        $stmt->bindParam(":id", $messageId, PDO::PARAM_INT);
        $stmt->bindParam(":receiver_id", $userId, PDO::PARAM_INT);
        $stmt->execute();

        // Rediriger vers la page des messages
        header("Location: messages.php");
    } catch (PDOException $e) {
        echo "Une erreur est survenue lors de la mise à jour du statut de lecture : " . $e->getMessage();
    }
}
?>
