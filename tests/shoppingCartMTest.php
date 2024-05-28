<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../models/shoppingCartM.php';

class ShoppingCartMTest extends TestCase
{
    private $pdo;
    private $shoppingCartM;

    protected function setUp(): void
    {
        // Configura una base de datos SQLite en memoria para las pruebas
        $this->pdo = new PDO('sqlite::memory:');
        $this->pdo->exec("CREATE TABLE cart (id INTEGER PRIMARY KEY, user_id INTEGER, total DECIMAL(10,2))");
        $this->pdo->exec("CREATE TABLE cart_product (id INTEGER PRIMARY KEY, cart_id INTEGER, product_id INTEGER, quantity INTEGER)");

        // Crea una instancia de ShoppingCartM con la conexión PDO configurada
        $this->shoppingCartM = new ShoppingCartM($this->pdo);
    }

    public function testCreateCart()
    {
        // Prueba la creación de un carrito
        $clientId = 1;
        $total = 100.00;
        $cart = $this->shoppingCartM->createCart($clientId, $total);

        // Verifica que el carrito se haya creado correctamente
        $this->assertIsArray($cart);
        $this->assertEquals($clientId, $cart['user_id']);
        $this->assertEquals($total, $cart['total']);
    }

    public function testCreateCartProduct()
    {
        // Crea un carrito para utilizar en la prueba de asociar productos al carrito
        $cartId = $this->createDummyCart();

        // Prueba la asociación de un producto a un carrito
        $productId = 1;
        $quantity = 2;
        $result = $this->shoppingCartM->createCartProduct($cartId, $productId, $quantity);

        // Verifica que el producto se haya asociado correctamente al carrito
        $this->assertTrue($result);
    }

    private function createDummyCart()
    {
        // Crea un carrito de prueba y devuelve su ID
        $clientId = 1;
        $total = 100.00;
        $cart = $this->shoppingCartM->createCart($clientId, $total);
        return $cart['id'];
    }
}
