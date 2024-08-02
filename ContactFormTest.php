// tests/ContactFormTest.php
use PHPUnit\Framework\TestCase;

class ContactFormTest extends TestCase
{
    private $conn;

    protected function setUp(): void
    {
        // Setup database connection
        $this->conn = new mysqli('localhost', 'username', 'password', 'database');

        // Ensure the connection is successful
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }

        // Clean up before each test
        $this->conn->query("DELETE FROM message WHERE email = 'test@example.com'");
    }

    protected function tearDown(): void
    {
        // Clean up database
        $this->conn->query("DELETE FROM message WHERE email = 'test@example.com'");

        // Close the database connection
        $this->conn->close();
    }

    public function testSendMessage()
    {
        // Simulate form submission
        $_POST['name'] = 'Test User';
        $_POST['email'] = 'test@example.com';
        $_POST['phone_number'] = '1234567890';
        $_POST['message'] = 'This is a test message';
        $_POST['send'] = true;

        // Include the script that handles the form submission
        include 'contact.php';

        // Verify that the message was inserted into the database
        $result = $this->conn->query("SELECT * FROM message WHERE email = 'test@example.com'");
        $this->assertEquals(1, $result->num_rows);
    }

    public function testPreventDuplicateMessage()
    {
        // Insert a message first
        $this->conn->query("INSERT INTO message (user_id, name, email, phone_number, message) VALUES (1, 'Test User', 'test@example.com', '1234567890', 'This is a test message')");

        // Simulate duplicate form submission
        $_POST['name'] = 'Test User';
        $_POST['email'] = 'test@example.com';
        $_POST['phone_number'] = '1234567890';
        $_POST['message'] = 'This is a test message';
        $_POST['send'] = true;

        // Include the script that handles the form submission
        include 'contact.php';

        // Verify that the duplicate message was not inserted
        $result = $this->conn->query("SELECT * FROM message WHERE email = 'test@example.com'");
        $this->assertEquals(1, $result->num_rows);
    }
}
