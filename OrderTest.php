<?php

use PHPUnit\Framework\TestCase;

class OrderTest extends TestCase
{
    private $conn;

    protected function setUp(): void
    {
        // Setup database connection
        $this->conn = new mysqli('localhost', 'username', 'password', 'database');

        // Create a test user
        $this->conn->query("INSERT INTO users (id, name) VALUES (1, 'Test User')");

        // Create test orders
        $this->conn->query("INSERT INTO orders (user_id, placed_on, name, number, email, address, method, total_products, total_price, payment_status) VALUES
            (1, '2024-08-02', 'Test User', '1234567890', 'test@example.com', '123 Test Street', 'credit card', 'Product 1, Product 2', 100, 'completed')");
    }

    protected function tearDown(): void
    {
        // Clean up database
        $this->conn->query("DELETE FROM orders WHERE user_id = 1");
        $this->conn->query("DELETE FROM users WHERE id = 1");

        // Close the database connection
        $this->conn->close();
    }

    public function testFetchOrders()
    {
        $user_id = 1;
        $result = $this->conn->query("SELECT * FROM `orders` WHERE user_id = '$user_id'");
        $this->assertTrue($result->num_rows > 0);

        while ($order = $result->fetch_assoc()) {
            $this->assertEquals('Test User', $order['name']);
            $this->assertEquals('test@example.com', $order['email']);
            $this->assertEquals('123 Test Street', $order['address']);
            $this->assertEquals('credit card', $order['method']);
            $this->assertEquals('Product 1, Product 2', $order['total_products']);
            $this->assertEquals(100, $order['total_price']);
            $this->assertEquals('completed', $order['payment_status']);
        }
    }
}
