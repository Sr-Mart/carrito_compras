<?php
require '../config/config.php';
require_once '../models/loginM.php';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $user = $_POST['user'];
    $password = $_POST['password'];

    $loginM = new LoginM($pdo);

    $login = $loginM->validarCredenciales($user, $password);

    if ($login) {

        $hashedPassword = $login['password'];
        
        if ($password == $hashedPassword) {

            session_start();
            $_SESSION["user"] = $login["user"];
            echo json_encode(array('success' => true));
            exit();
        } else {
            echo json_encode(array('success' => false));
        }
    } else {
        echo json_encode(array('success' => false));
    }
}
