<?php
require '../config/config.php';
require_once '../models/shoppingCartM.php';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

$shoppingCartM = new shoppingCartM($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['cart'])) {

        $clientId = $_POST['clientId'];
        $total = $_POST['total'];
        $result = $shoppingCartM->createCart($clientId, $total);

        if ($result) {
            echo json_encode($result);
        } else {
            echo json_encode(['success' => false, 'error' => 'No se pudo actualizar el carrito']);
        }

    } else if(isset($_POST['cartProduct'])){

        $cartId = $_POST['cartId'];
        $productID = $_POST['productId'];
        $quantity = $_POST['quantity'];
        $cartProduct = $shoppingCartM->createCartProduct($cartId, $productID, $quantity);
        echo $cartProduct;

    }
}