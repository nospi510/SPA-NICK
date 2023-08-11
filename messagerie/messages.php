<?php
// messages.php

require_once "../config.php";

session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: index.php");
    exit;
}

$userId = $_SESSION["user_id"];

// Récupérer les messages reçus par l'utilisateur
$stmt = $pdo->prepare("SELECT m.*, u.username FROM messages m INNER JOIN users u ON m.sender_id = u.id WHERE m.receiver_id = :receiver_id ORDER BY m.sent_at DESC");
$stmt->bindParam(":receiver_id", $userId, PDO::PARAM_INT);
$stmt->execute();
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- messages.php -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/mess.css">
    <title>Messagerie</title>
</head>
<body>
    <div class="container">
        <h1>Messagerie</h1>
        <ul class="message-list">
            <?php foreach ($messages as $message) : ?>
                <li class="message <?php echo $message['is_read'] ? 'read' : 'unread'; ?>">
                    <div class="message-bubble">
                        <div class="sender"><?php echo $message['username']; ?>:</div>
                        <div class="content"><?php echo $message['content']; ?></div>
                        <div class="timestamp"><?php echo $message['sent_at']; ?></div>
                        <a href="../messagerie/mark_read.php?id=<?php echo $message['id']; ?>" hx-get="../messagerie/mark_read.php?id=<?php echo $message['id']; ?>"  hx-target="#content">Marquer comme lu</a>

                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
        <p> <a href="../client/tasks.php" hx-get="../client/tasks.php" hx-target="#content">Retour</a></p>
    </div>

    <script src="../sourcehtmx/htmx.min.js"></script>

</body>
</html>

