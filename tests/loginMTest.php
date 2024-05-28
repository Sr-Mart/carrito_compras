<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../models/LoginM.php';

class LoginMTest extends TestCase
{
    private $pdo;
    private $loginM;

    protected function setUp(): void
    {
        // Crea un mock del objeto PDO
        $this->pdo = $this->createMock(PDO::class);

        // Crea una instancia de LoginM con el mock de PDO
        $this->loginM = new LoginM($this->pdo);
    }

    public function testValidarCredencialesValidas()
    {
        $user = 'usuario';
        $password = 'contraseña';

        // Prepara el resultado esperado de la consulta
        $expectedResult = ['user' => $user, 'password' => $password];

        // Crea un mock de PDOStatement
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $stmt->expects($this->once())
            ->method('rowCount')
            ->willReturn(1);

        $stmt->expects($this->once())
            ->method('fetch')
            ->willReturn($expectedResult);

        // Configura el mock de PDO para devolver el mock de PDOStatement
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->willReturn($stmt);

        // Llama al método a probar
        $result = $this->loginM->validarCredenciales($user, $password);

        // Verifica el resultado y proporciona un mensaje
        $this->assertEquals($expectedResult, $result, "Las credenciales válidas deben devolver el resultado esperado.");
    }

    public function testValidarCredencialesInvalidas()
    {
        $user = 'usuario';
        $password = 'contraseña_incorrecta';

        // Crea un mock de PDOStatement
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $stmt->expects($this->once())
            ->method('rowCount')
            ->willReturn(0);

        // Configura el mock de PDO para devolver el mock de PDOStatement
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->willReturn($stmt);

        // Llama al método a probar
        $result = $this->loginM->validarCredenciales($user, $password);

        // Verifica el resultado y proporciona un mensaje
        $this->assertFalse($result, "Las credenciales inválidas deben devolver false.");
    }
}