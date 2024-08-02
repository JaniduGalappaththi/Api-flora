<?php

use PHPUnit\Framework\TestCase;

class RegistrationTest extends TestCase {
    private $conn;
    private $registration;

    protected function setUp(): void {
        // Mock the database connection
        $this->conn = $this->createMock(mysqli::class);

        // Instantiate the Registration class with the mocked connection
        $this->registration = new Registration($this->conn);
    }

    public function testUserAlreadyExists() {
        $email = 'user@example.com';
        $password = 'password';
        $confirmPassword = 'password';

        // Mock query result
        $queryResult = $this->createMock(mysqli_result::class);
        $queryResult->method('num_rows')->willReturn(1);

        // Mock the connection behavior for the SELECT query
        $this->conn->method('prepare')->willReturn($this->createMock(mysqli_stmt::class));
        $stmt = $this->conn->prepare('SELECT * FROM `users` WHERE email = ?');
        $stmt->method('execute');
        $stmt->method('get_result')->willReturn($queryResult);

        // Perform the registration
        $message = $this->registration->register('John Doe', $email, $password, $confirmPassword);

        // Assert the result
        $this->assertEquals('user already exists!', $message);
    }

    public function testPasswordMismatch() {
        $email = 'user@example.com';
        $password = 'password';
        $confirmPassword = 'differentpassword';

        // Mock query result
        $queryResult = $this->createMock(mysqli_result::class);
        $queryResult->method('num_rows')->willReturn(0);

        // Mock the connection behavior for the SELECT query
        $this->conn->method('prepare')->willReturn($this->createMock(mysqli_stmt::class));
        $stmt = $this->conn->prepare('SELECT * FROM `users` WHERE email = ?');
        $stmt->method('execute');
        $stmt->method('get_result')->willReturn($queryResult);

        // Perform the registration
        $message = $this->registration->register('John Doe', $email, $password, $confirmPassword);

        // Assert the result
        $this->assertEquals('confirm password not matched!', $message);
    }

    public function testSuccessfulRegistration() {
        $email = 'user@example.com';
        $password = 'password';
        $confirmPassword = 'password';

        // Mock query result
        $queryResult = $this->createMock(mysqli_result::class);
        $queryResult->method('num_rows')->willReturn(0);

        // Mock the connection behavior for the SELECT query
        $this->conn->method('prepare')->willReturn($this->createMock(mysqli_stmt::class));
        $stmt = $this->conn->prepare('SELECT * FROM `users` WHERE email = ?');
        $stmt->method('execute');
        $stmt->method('get_result')->willReturn($queryResult);

        // Mock the connection behavior for the INSERT query
        $this->conn->method('query')->willReturn(true);

        // Perform the registration
        $message = $this->registration->register('John Doe', $email, $password, $confirmPassword);

        // Assert the result
        $this->assertEquals('registered successfully!', $message);
    }
}
