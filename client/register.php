<?php
// register.php

require_once "../config.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $role = "utilisateur standard"; // Par défaut, les nouveaux utilisateurs ont le rôle d'utilisateur standard.

    $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->execute([$username, $email, $password, $role]);

    header("Location: index.html");
    exit;
}
?>



