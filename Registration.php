<?php

class Registration {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function register($name, $email, $password, $confirmPassword) {
        $filter_name = filter_var($name, FILTER_SANITIZE_STRING);
        $name = mysqli_real_escape_string($this->conn, $filter_name);

        $filter_email = filter_var($email, FILTER_SANITIZE_EMAIL);
        $email = mysqli_real_escape_string($this->conn, $filter_email);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return 'Invalid email format!';
        }

        if ($password !== $confirmPassword) {
            return 'Confirm password does not match!';
        }

        // Prepare the SQL statement
        $stmt = $this->conn->prepare("SELECT * FROM `users` WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $stmt->close();
            return 'User already exists!';
        }

        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Insert the new user
        $stmt = $this->conn->prepare("INSERT INTO `users` (name, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $hashedPassword);

        if ($stmt->execute()) {
            $stmt->close();
            return 'Registered successfully!';
        } else {
            $stmt->close();
            return 'Registration failed!';
        }
    }
}

