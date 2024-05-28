<?php
require '../config/config.php';
require_once '../models/productM.php';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

$productM = new productM($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id'])) {
        $name = $_POST['name'];
        $price = $_POST['price'];
        $stock = $_POST['stock'];
        $id = $_POST['id'];
        $result = $productM->update($name, $price, $stock, $id);

        if ($result) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'No se pudo actualizar el producto']);
        }
    } else {
        $name = $_POST['name'];
        $price = $_POST['price'];
        $stock = $_POST['stock'];
        $user = $productM->create($name, $price, $stock);
        echo $user;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $user = $productM->findById($id);
        echo json_encode($user);
    } else {
        $data = $productM->read();
        echo json_encode($data);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    try {

        $inputJSON = file_get_contents('php://input');
        $input = json_decode($inputJSON, TRUE);

        if (isset($input['id'])) {

            $id = $input['id'];

            $result = $productM->delete($id);

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
