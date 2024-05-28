<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../models/productM.php';

class ProductMTest extends TestCase
{
    private $pdo;
    private $productM;

    protected function setUp(): void
    {
        // Crea un mock del objeto PDO
        $this->pdo = $this->createMock(PDO::class);

        // Crea una instancia de ProductM con el mock de PDO
        $this->productM = new ProductM($this->pdo);
    }

    public function testCreateProductSuccess()
    {
        $name = 'Producto 1';
        $price = 100;
        $stock = 10;

        // Crea un mock de PDOStatement para la consulta de verificación
        $stmtCheck = $this->createMock(PDOStatement::class);
        $stmtCheck->expects($this->once())
            ->method('execute')
            ->willReturn(true);
        $stmtCheck->expects($this->once())
            ->method('rowCount')
            ->willReturn(0);

        // Crea un mock de PDOStatement para la consulta de inserción
        $stmtInsert = $this->createMock(PDOStatement::class);
        $stmtInsert->expects($this->once())
            ->method('execute')
            ->willReturn(true);
        $stmtInsert->expects($this->once())
            ->method('rowCount')
            ->willReturn(1);

        // Configura el mock de PDO para devolver el mock de PDOStatement
        $this->pdo->expects($this->exactly(2))
            ->method('prepare')
            ->willReturnOnConsecutiveCalls($stmtCheck, $stmtInsert);

        // Llama al método a probar
        $result = $this->productM->create($name, $price, $stock);

        // Verifica el resultado y proporciona un mensaje
        $this->assertTrue($result, "El producto debe crearse correctamente cuando no existe previamente.");
    }

    public function testCreateProductFailure()
    {
        $name = 'Producto 1';
        $price = 100;
        $stock = 10;

        // Crea un mock de PDOStatement para la consulta de verificación
        $stmtCheck = $this->createMock(PDOStatement::class);
        $stmtCheck->expects($this->once())
            ->method('execute')
            ->willReturn(true);
        $stmtCheck->expects($this->once())
            ->method('rowCount')
            ->willReturn(1);

        // Configura el mock de PDO para devolver el mock de PDOStatement
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->willReturn($stmtCheck);

        // Llama al método a probar
        $result = $this->productM->create($name, $price, $stock);

        // Verifica el resultado y proporciona un mensaje
        $this->assertFalse($result, "El producto no debe crearse si ya existe un producto con el mismo nombre.");
    }

    public function testReadProducts()
    {
        $expectedProducts = [
            ['id' => 1, 'name' => 'Producto 1', 'price' => 100, 'stock' => 10],
            ['id' => 2, 'name' => 'Producto 2', 'price' => 200, 'stock' => 20],
        ];

        // Crea un mock de PDOStatement para la consulta de lectura
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);
        $stmt->expects($this->any())
            ->method('fetch')
            ->willReturnOnConsecutiveCalls(...array_merge($expectedProducts, [false]));


        // Configura el mock de PDO para devolver el mock de PDOStatement
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->willReturn($stmt);

        // Llama al método a probar
        $result = $this->productM->read();

        // Verifica el resultado y proporciona un mensaje
        $this->assertEquals($expectedProducts, $result, "La lectura de productos debe devolver el resultado esperado.");
    }

    public function testFindByIdSuccess()
    {
        $id = 1;
        $expectedProduct = ['id' => $id, 'name' => 'Producto 1', 'price' => 100, 'stock' => 10];

        // Crea un mock de PDOStatement para la consulta de búsqueda por ID
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);
        $stmt->expects($this->once())
            ->method('fetch')
            ->willReturn($expectedProduct);

        // Configura el mock de PDO para devolver el mock de PDOStatement
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->willReturn($stmt);

        // Llama al método a probar
        $result = $this->productM->findById($id);

        // Verifica el resultado y proporciona un mensaje
        $this->assertEquals($expectedProduct, $result, "La búsqueda de producto por ID debe devolver el producto esperado.");
    }

    public function testFindByIdFailure()
    {
        $id = 1;

        // Crea un mock de PDOStatement para la consulta de búsqueda por ID
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);
        $stmt->expects($this->once())
            ->method('fetch')
            ->willReturn(false);

        // Configura el mock de PDO para devolver el mock de PDOStatement
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->willReturn($stmt);

        // Llama al método a probar
        $result = $this->productM->findById($id);

        // Verifica el resultado y proporciona un mensaje
        $this->assertFalse($result, "La búsqueda de producto por ID debe devolver false si no se encuentra el producto.");
    }

    public function testUpdateProductSuccess()
    {
        $id = 1;
        $name = 'Producto Actualizado';
        $price = 150;
        $stock = 15;

        // Crea un mock de PDOStatement para la consulta de verificación
        $stmtCheck = $this->createMock(PDOStatement::class);
        $stmtCheck->expects($this->once())
            ->method('execute')
            ->willReturn(true);
        $stmtCheck->expects($this->once())
            ->method('rowCount')
            ->willReturn(1);

        // Crea un mock de PDOStatement para la consulta de actualización
        $stmtUpdate = $this->createMock(PDOStatement::class);
        $stmtUpdate->expects($this->once())
            ->method('execute')
            ->willReturn(true);
        $stmtUpdate->expects($this->once())
            ->method('rowCount')
            ->willReturn(1);

        // Configura el mock de PDO para devolver los mocks de PDOStatement
        $this->pdo->expects($this->exactly(2))
            ->method('prepare')
            ->willReturnOnConsecutiveCalls($stmtCheck, $stmtUpdate);

        // Llama al método a probar
        $result = $this->productM->update($name, $price, $stock, $id);

        // Verifica el resultado y proporciona un mensaje
        $this->assertTrue($result, "La actualización del producto debe realizarse correctamente si el producto existe.");
    }

    public function testUpdateProductFailure()
    {
        $id = 1;
        $name = 'Producto Actualizado';
        $price = 150;
        $stock = 15;

        // Crea un mock de PDOStatement para la consulta de verificación
        $stmtCheck = $this->createMock(PDOStatement::class);
        $stmtCheck->expects($this->once())
            ->method('execute')
            ->willReturn(true);
        $stmtCheck->expects($this->once())
            ->method('rowCount')
            ->willReturn(0);

        // Configura el mock de PDO para devolver el mock de PDOStatement
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->willReturn($stmtCheck);

        // Llama al método a probar
        $result = $this->productM->update($name, $price, $stock, $id);

        // Verifica el resultado y proporciona un mensaje
        $this->assertFalse($result, "La actualización del producto debe fallar si el producto no existe.");
    }

    public function testDeleteProductSuccess()
    {
        $id = 1;

        // Crea un mock de PDOStatement para la consulta de verificación
        $stmtCheck = $this->createMock(PDOStatement::class);
        $stmtCheck->expects($this->once())
            ->method('execute')
            ->willReturn(true);
        $stmtCheck->expects($this->once())
            ->method('rowCount')
            ->willReturn(1);

        // Crea un mock de PDOStatement para la consulta de eliminación
        $stmtDelete = $this->createMock(PDOStatement::class);
        $stmtDelete->expects($this->once())
            ->method('execute')
            ->willReturn(true);
        $stmtDelete->expects($this->once())
            ->method('rowCount')
            ->willReturn(1);

        // Configura el mock de PDO para devolver los mocks de PDOStatement
        $this->pdo->expects($this->exactly(2))
            ->method('prepare')
            ->willReturnOnConsecutiveCalls($stmtCheck, $stmtDelete);

        // Llama al método a probar
        $result = $this->productM->delete($id);

        // Verifica el resultado y proporciona un mensaje
        $this->assertTrue($result['success'], "La eliminación del producto debe ser exitosa.");
        $this->assertEquals('Producto eliminado correctamente', $result['message'], "El mensaje debe indicar que el producto se eliminó correctamente.");
    }

    public function testDeleteProductFailure()
    {
        $id = 1;

        // Crea un mock de PDOStatement para la consulta de verificación
        $stmtCheck = $this->createMock(PDOStatement::class);
        $stmtCheck->expects($this->once())
            ->method('execute')
            ->willReturn(true);
        $stmtCheck->expects($this->once())
            ->method('rowCount')
            ->willReturn(0);

        // Configura el mock de PDO para devolver el mock de PDOStatement
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->willReturn($stmtCheck);

        // Llama al método a probar
        $result = $this->productM->delete($id);

        // Verifica el resultado y proporciona un mensaje
        $this->assertFalse($result['success'], "La eliminación del producto debe fallar si el producto no existe.");
        $this->assertEquals('El producto no existe', $result['message'], "El mensaje debe indicar que el producto no existe.");
    }
}
