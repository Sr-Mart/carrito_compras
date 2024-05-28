<?php

class userM
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function create($user, $password)
    {
        try {
            $query = "SELECT user FROM user WHERE user = :user";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':user', $user);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                return false;
            } else {

                $insertQuery = "INSERT INTO user (user, password) VALUES (:user, :password)";
                $insertStmt = $this->pdo->prepare($insertQuery);
                $insertStmt->bindParam(':user', $user);
                $insertStmt->bindParam(':password', $password);
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
            $query = "SELECT * FROM user";
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
        $stmt = $this->pdo->prepare("SELECT * FROM user WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($user, $password, $id)
    {
        try {

            $query = "SELECT * FROM user WHERE id = :id";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            if ($stmt->rowCount() == 0) {
                return false;
            } else {

                $query = "UPDATE user SET user = :user, password = :password WHERE id = :id";
                $stmt = $this->pdo->prepare($query);
                $stmt->bindParam(':user', $user);
                $stmt->bindParam(':password', $password);
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
            $query = "SELECT * FROM user WHERE id = :id";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            if ($stmt->rowCount() == 0) {
                return ['success' => false, 'message' => 'El usuario no existe'];
            } else {
                $deleteQuery = "DELETE FROM user WHERE id = :id";
                $deleteStmt = $this->pdo->prepare($deleteQuery);
                $deleteStmt->bindParam(':id', $id);
                $deleteStmt->execute();

                if ($deleteStmt->rowCount() > 0) {
                    return ['success' => true, 'message' => 'Usuario eliminado correctamente'];
                } else {
                    return ['success' => false, 'message' => 'Error al eliminar al usuario'];
                }
            }
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Error en la ejecución de la consulta SQL: ' . $e->getMessage()];
        }
    }
}
