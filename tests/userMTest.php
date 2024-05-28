<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../models/userM.php';

class UserMTest extends TestCase
{
    private $pdo;
    private $userM;

    protected function setUp(): void
    {
        // Configura una base de datos SQLite en memoria para las pruebas
        $this->pdo = new PDO('sqlite::memory:');
        $this->pdo->exec("CREATE TABLE user (id INTEGER PRIMARY KEY, user TEXT, password TEXT)");

        // Crea una instancia de UserM con la conexión PDO configurada
        $this->userM = new userM($this->pdo);
    }

    public function testCreateUserSuccess()
    {
        // Prueba la creación exitosa de un usuario
        $result = $this->userM->create('testuser', 'testpass');
        $this->assertTrue($result);

        // Verifica que el usuario se haya creado correctamente en la base de datos
        $stmt = $this->pdo->query("SELECT * FROM user WHERE user = 'testuser'");
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->assertEquals('testuser', $user['user']);
        $this->assertEquals('testpass', $user['password']);
    }

    public function testCreateUserFailure()
    {
        // Prueba la creación fallida de un usuario con el mismo nombre de usuario
        $this->userM->create('testuser', 'testpass');
        $result = $this->userM->create('testuser', 'testpass');
        $this->assertFalse($result);
    }

    public function testReadUsers()
    {
        // Prueba la lectura de usuarios
        $this->userM->create('testuser1', 'testpass1');
        $this->userM->create('testuser2', 'testpass2');
        $users = $this->userM->read();
        $this->assertCount(2, $users);
    }

    public function testFindById()
    {
        // Prueba la búsqueda de un usuario por ID
        $this->userM->create('testuser', 'testpass');
        $user = $this->pdo->query("SELECT * FROM user WHERE user = 'testuser'")->fetch(PDO::FETCH_ASSOC);
        $foundUser = $this->userM->findById($user['id']);
        $this->assertEquals($user['user'], $foundUser['user']);
    }

    public function testUpdateUser()
    {
        // Prueba la actualización de un usuario
        $this->userM->create('testuser', 'testpass');
        $user = $this->pdo->query("SELECT * FROM user WHERE user = 'testuser'")->fetch(PDO::FETCH_ASSOC);
        $result = $this->userM->update('updateduser', 'updatedpass', $user['id']);
        $this->assertTrue($result);

        // Verifica que el usuario se haya actualizado correctamente
        $updatedUser = $this->userM->findById($user['id']);
        $this->assertEquals('updateduser', $updatedUser['user']);
        $this->assertEquals('updatedpass', $updatedUser['password']);
    }

    public function testDeleteUser()
    {
        // Prueba la eliminación de un usuario
        $this->userM->create('testuser', 'testpass');
        $user = $this->pdo->query("SELECT * FROM user WHERE user = 'testuser'")->fetch(PDO::FETCH_ASSOC);
        $result = $this->userM->delete($user['id']);

        // Verifica que el usuario se haya eliminado correctamente
        $this->assertEquals(['success' => true, 'message' => 'Usuario eliminado correctamente'], $result);

        // Verifica que el usuario realmente se haya eliminado de la base de datos
        $stmt = $this->pdo->query("SELECT * FROM user WHERE id = {$user['id']}");
        $this->assertFalse($stmt->fetch(PDO::FETCH_ASSOC));
    }
}