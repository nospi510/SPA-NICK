
<?php


require_once "../config.php";

session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "utilisateur standard") {
    header("Location: ../client/login.html");
    exit;
}
try {
// Récupérer les publications visibles pour les utilisateurs standards
$stmt = $pdo->prepare("SELECT p.*, u.username FROM publications p LEFT JOIN users u ON p.created_by = u.id WHERE p.created_by IN (SELECT id FROM users WHERE role = 'administrateur' OR role = 'gestionnaire')");
$stmt->execute();
$publications = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // En cas d'erreur, afficher un message d'erreur
    echo "Une erreur est survenue lors de l'ajout de la tâche : " . $e->getMessage();
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/pub.css">
    <title>Publications</title>
</head>
<body>
    <h1>Publications</h1>
    <div class="publications-list">
        <?php foreach ($publications as $publication) : ?>
            <div class="publication">
                <h2><?php echo $publication['title']; ?></h2>
                <p><?php echo $publication['content']; ?></p>
                <p>Auteur : <?php echo $publication['username']; ?></p>
                <p>Date de création : <?php echo $publication['created_at']; ?></p>
            </div>
        <?php endforeach; ?>
    </div>

    
</body>
</html>
