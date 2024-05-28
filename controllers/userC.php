<?php
require '../config/config.php';
require_once '../models/userM.php';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

$userM = new userM($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id'])) {
        $user = $_POST['user'];
        $password = $_POST['password'];
        $id = $_POST['id'];
        $result = $userM->update($user, $password, $id);

        if ($result) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'No se pudo actualizar el usuario']);
        }
    } else {
        $user = $_POST['user'];
        $password = $_POST['password'];
        $user = $userM->create($user, $password);
        echo $user;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $user = $userM->findById($id);
        echo json_encode($user);
    } else {
        $data = $userM->read();
        echo json_encode($data);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    try {

        $inputJSON = file_get_contents('php://input');
        $input = json_decode($inputJSON, TRUE);

        if (isset($input['id'])) {
            $id = $input['id'];

            $result = $userM->delete($id);

            if ($result) {
                echo "true";
            } else {
                echo "false";
            }
        } else {
            echo json_encode(['success' => false, 'error' => 'ID no proporcionado en la solicitud']);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => 'Error SQL: ' . $e->getMessage()]);
    }
}
