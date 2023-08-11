<?php


require_once "../config.php";

session_start();

if (!isset($_SESSION["user_id"]) || ($_SESSION["role"] !== "administrateur" && $_SESSION["role"] !== "gestionnaire")) {
    header("Location: index.php");
    exit;
}


// Récupérer les utilisateurs
$stmtUsers = $pdo->query("SELECT * FROM users");
$users = $stmtUsers->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les tâches
$stmtTasks = $pdo->query("SELECT t.*, u.username FROM tasks t LEFT JOIN users u ON t.assigned_to = u.id");
$tasks = $stmtTasks->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les publications visibles pour les utilisateurs standards
$stmt = $pdo->prepare("SELECT p.*, u.username FROM publications p LEFT JOIN users u ON p.created_by = u.id WHERE p.created_by IN (SELECT id FROM users WHERE role = 'administrateur' OR role = 'gestionnaire')");
$stmt->execute();
$publications = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupérer tous les messages
$stmt = $pdo->query("SELECT m.*, u.username AS sender, u2.username AS receiver FROM messages m INNER JOIN users u ON m.sender_id = u.id INNER JOIN users u2 ON m.receiver_id = u2.id");
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style1.css">
    <title>Interface d'Administration</title>
</head>
<body>
    <div class="container">
        <h1>Interface d'Administration</h1>
        <h1>Bienvenue, <?php echo $_SESSION["role"]; ?> <?php echo $_SESSION["username"]; ?> !</h1>

    </br>
        <a href="logout.php" hx-get="logout.php" hx-target="#content">Se déconnecter</a>
        
        </br></br>
    
        <h2>Liste des Utilisateurs</h2>
        <table>
            <thead>
                <tr>
                    <th>Nom d'utilisateur</th>
                    <th>Adresse e-mail</th>
                    <th>Rôle</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user) : ?>
                    <tr>
                        <td><?php echo $user['username']; ?></td>
                        <td><?php echo $user['email']; ?></td>
                        <td><?php echo ucfirst($user['role']); ?></td>
                        <td>
                    
                            <a href="edit_user.php?id=<?php echo $user['id']; ?>" hx-get="edit_user.php?id=<?php echo $user['id']; ?>" hx-target="#content">Modifier</a>
                            <a href="delete_user.php?id=<?php echo $user['id']; ?>" hx-get="delete_user.php?id=<?php echo $user['id']; ?>"hx-target="#content">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        </br></br>

        <h2>Liste des Tâches</h2>
    <table>
        <thead>
         <tr>
            <th>Titre</th>
            <th>Description</th>
            <th>Date d'échéance</th>
            <th>Statut</th>
            <th>Attribuée à</th>
            <th>Actions</th>
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

                    <a href="edit_task.php?id=<?php echo $task['id']; ?>" hx-get="edit_task.php?id=<?php echo $task['id']; ?>" hx-target="#content">Modifier</a>
                    <a href="delete_task.php?id=<?php echo $task['id']; ?>" hx-get="delete_task.php?id=<?php echo $task['id']; ?>" hx-target="#content">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    </br></br>

<h2>Liste des Publications</h2>

<table>
    <thead>
        <tr>
            <th>Titre</th>
            <th>Contenu</th>
            <th>Auteur</th>
            <th>Date de création</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($publications as $publication) : ?>
            <tr>
                <td><?php echo $publication['title']; ?></td>
                <td><?php echo $publication['content']; ?></td>
                <td><?php echo $publication['username']; ?></td>
                <td><?php echo $publication['created_at']; ?></td>
                <td>
                    <a href="../publication/edit_publication.php?id=<?php echo $publication['id']; ?>" hx-get="../publication/edit_publication.php?id=<?php echo $publication['id']; ?>" hx-target="#content">Modifier</a>
                    <a href="../publication/delete_publication.php?id=<?php echo $publication['id']; ?>" hx-get="../publication/delete_publication.php?id=<?php echo $publication['id']; ?>" hx-target="#content">Supprimer</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
</br></br>


<h2>Messagerie</h2>
<table>
            <thead>
                <tr>
                    <th>Expéditeur</th>
                    <th>Destinataire</th>
                    <th>Message</th>
                    <th>Date d'envoi</th>
                    <th>Lu</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($messages as $message) : ?>
                    <tr>
                        <td><?php echo $message['sender']; ?></td>
                        <td><?php echo $message['receiver']; ?></td>
                        <td><?php echo $message['content']; ?></td>
                        <td><?php echo $message['sent_at']; ?></td>
                        <td><?php echo ($message['is_read'] == 1) ? 'Lu' : 'Non Lu'; ?></td>
                        <td>
                            <a href="../messagerie/delete_message.php?id=<?php echo $message['id']; ?>" hx-get="../messagerie/delete_message.php?id=<?php echo $message['id']; ?>" hx-target="#content">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>




    </div>
    <script src="../sourcehtmx/htmx.min.js"></script>
</body>
</html>
