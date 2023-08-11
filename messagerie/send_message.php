<?php
// send_message.php

require_once "../config.php";

session_start();

if (!isset($_SESSION["user_id"]) || ($_SESSION["role"] !== "administrateur" && $_SESSION["role"] !== "gestionnaire")) {
    header("Location: ../admin/index.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $senderId = $_SESSION["user_id"];
    $receiverId = $_POST["receiver_id"];
    $messageContent = $_POST["message"];

    try {
        $stmt = $pdo->prepare("INSERT INTO messages (sender_id, receiver_id, content) VALUES (:sender_id, :receiver_id, :content)");
        $stmt->bindParam(":sender_id", $senderId, PDO::PARAM_INT);
        $stmt->bindParam(":receiver_id", $receiverId, PDO::PARAM_INT);
        $stmt->bindParam(":content", $messageContent, PDO::PARAM_STR);
        $stmt->execute();

        $messageSent = true;
    } catch (PDOException $e) {
        echo "Une erreur est survenue lors de l'envoi du message : " . $e->getMessage();
    }
}

$stmt = $pdo->prepare("SELECT id, username, role FROM users WHERE role = 'utilisateur standard'");
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="fr">
    <head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../css/style.css">
        <title>Envoyer un Message</title>
    </head>
    <body>
        <div class="container">
            <h1>Envoyer un Message</h1>
            <?php if (isset($messageSent) && $messageSent) : ?>
                <p>Message envoyé avec succès.</p>
            <?php endif; ?>

            <form hx-post="../messagerie/send_message.php"  hx-target="#content"  method="POST">
                <label for="receiver_id">Destinataire :</label>
                <select name="receiver_id" id="receiver_id">
                    <?php foreach ($users as $user) : ?>
                        <option value="<?php echo $user['id']; ?>"><?php echo $user['username']; ?></option>
                    <?php endforeach; ?>
                </select>
            
                <label for="message">Message :</label>
                <textarea name="message" id="message" rows="4" required></textarea>
            
                <button type="submit">Envoyer</button>
            </form>

            <p> <a href="../admin/admin.php" hx-get="../admin/admin.php" hx-target="#content">Retour</a></p>
        </div>
        <script src="../sourcehtmx/htmx.min.js"></script>

</body>
</html>
