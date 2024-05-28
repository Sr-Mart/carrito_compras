<?php

class productM
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function create($name, $price, $stock)
    {
        try {
            $query = "SELECT name FROM product WHERE name = :name";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':name', $name);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                return false;
            } else {

                $insertQuery = "INSERT INTO product (name, price, stock) VALUES (:name, :price, :stock)";
                $insertStmt = $this->pdo->prepare($insertQuery);
                $insertStmt->bindParam(':name', $name);
                $insertStmt->bindParam(':price', $price);
                $insertStmt->bindParam(':stock', $stock);
                $insertStmt->execute();

                if ($insertStmt->rowCount() > 1) {
                    return true;
                } else {
                    return false;
                }
            }
        } catch (PDOException $e) {
            echo "Error en la ejecución de la consulta SQL: " . $e->getMessage();
            return false;
        }
    }

    public function read()
    {
        try {
            $query = "SELECT * FROM product";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute();

            $users = array();

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $users[] = $row;
            }

            return $users;
        } catch (PDOException $e) {
            echo "Error en la ejecución de la consulta SQL: " . $e->getMessage();
            return false;
        }
    }

    public function findById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM product WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($name, $price, $stock, $id)
    {
        try {

            $query = "SELECT * FROM product WHERE id = :id";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            if ($stmt->rowCount() == 0) {
                return false;
            } else {

                $query = "UPDATE product SET name = :name, price = :price, stock = :stock WHERE id = :id";
                $stmt = $this->pdo->prepare($query);
                $stmt->bindParam(':name', $name);
                $stmt->bindParam(':price', $price);
                $stmt->bindParam(':stock', $stock);
                $stmt->bindParam(':id', $id);
                $stmt->execute();

                if ($stmt->rowCount() > 0) {
                    return true;
                } else {
                    return false;
                }
            }
        } catch (PDOException $e) {
            echo "Error en la ejecución de la consulta SQL: " . $e->getMessage();
            return false;
        }
    }

    public function delete($id)
    {
        try {
            $query = "SELECT * FROM product WHERE id = :id";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            if ($stmt->rowCount() == 0) {
                return ['success' => false, 'message' => 'El producto no existe'];
            } else {
                $deleteQuery = "DELETE FROM product WHERE id = :id";
                $deleteStmt = $this->pdo->prepare($deleteQuery);
                $deleteStmt->bindParam(':id', $id);
                $deleteStmt->execute();

                if ($deleteStmt->rowCount() > 0) {
                    return ['success' => true, 'message' => 'Producto eliminado correctamente'];
                } else {
                    return ['success' => false, 'message' => 'Error al eliminar el producto'];
                }
            }
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Error en la ejecución de la consulta SQL: ' . $e->getMessage()];
        }
    }
}
