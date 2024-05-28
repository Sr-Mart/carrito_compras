<?php

class LoginM
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function validarCredenciales($user, $password)
    {
        try {
            $query = "SELECT user, password FROM user WHERE user = :user AND password = :password";
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(':user', $user);
            $stmt->bindParam(':password', $password);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                return $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
                return false;
            }
        } catch (PDOException $e) {
            echo "Error en la ejecución de la consulta SQL: " . $e->getMessage();
            return false;
        }
    }
}
