<?php

class shoppingCartM
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function createCart($clientId, $total)
    {
        try {

            $insertQuery = "INSERT INTO cart (user_id, total) VALUES (:clientId, :total)";
            $insertStmt = $this->pdo->prepare($insertQuery);
            $insertStmt->bindParam(':clientId', $clientId);
            $insertStmt->bindParam(':total', $total);
            $insertStmt->execute();

            $cartId = $this->pdo->lastInsertId();

            $selectQuery = "SELECT * FROM cart WHERE id = :cartId";
            $selectStmt = $this->pdo->prepare($selectQuery);
            $selectStmt->bindParam(':cartId', $cartId);
            $selectStmt->execute();

            $cart = $selectStmt->fetch(PDO::FETCH_ASSOC);

            return $cart;
        } catch (PDOException $e) {
            echo "Error en la ejecución de la consulta SQL: " . $e->getMessage();
            return false;
        }
    }

    public function createCartProduct($cartId, $productId, $quantity)
    {
        try {

            $insertQuery = "INSERT INTO cart_product (cart_id, product_id, quantity) VALUES (:cartId, :productId, :quantity)";
            $insertStmt = $this->pdo->prepare($insertQuery);
            $insertStmt->bindParam(':cartId', $cartId);
            $insertStmt->bindParam(':productId', $productId);
            $insertStmt->bindParam(':quantity', $quantity);
            $insertStmt->execute();

            if ($insertStmt->rowCount() > 1) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            echo "Error en la ejecución de la consulta SQL: " . $e->getMessage();
            return false;
        }
    }
}
