// tests/WishlistTest.php
use PHPUnit\Framework\TestCase;

class WishlistTest extends TestCase
{
    private $conn;
    private $user_id;

    protected function setUp(): void
    {
        // Setup database connection
        $this->conn = new mysqli('localhost', 'username', 'password', 'database');

        // Ensure the connection is successful
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }

        // Setup user_id
        $this->user_id = 1;

        // Ensure the user exists
        $this->conn->query("INSERT IGNORE INTO users (id, name, email) VALUES ($this->user_id, 'Test User', 'test@example.com')");
    }

    protected function tearDown(): void
    {
        // Clean up database
        $this->conn->query("DELETE FROM wishlist WHERE user_id = $this->user_id");        
        $this->conn->query("DELETE FROM cart WHERE user_id = $this->user_id");

        // Close the database connection
        $this->conn->close();
    }

    public function testAddToCart()
    {
        $wishlist = new Wishlist($this->conn, $this->user_id);
        $wishlist->addToCart(1, 'Test Product', 100, 'test.png');

        $result = $this->conn->query("SELECT * FROM cart WHERE user_id = $this->user_id AND name = 'Test Product'");
        $this->assertEquals(1, $result->num_rows);
    }

    public function testDeleteFromWishlist()
    {
        $wishlist = new Wishlist($this->conn, $this->user_id);

        // Insert a test item
        $this->conn->query("INSERT INTO wishlist (user_id, name) VALUES ($this->user_id, 'Test Product')");

        // Get the inserted item ID
        $result = $this->conn->query("SELECT id FROM wishlist WHERE user_id = $this->user_id AND name = 'Test Product'");
        $item = $result->fetch_assoc();
        $item_id = $item['id'];

        $wishlist->deleteFromWishlist($item_id);

        $result = $this->conn->query("SELECT * FROM wishlist WHERE user_id = $this->user_id AND name = 'Test Product'");
        $this->assertEquals(0, $result->num_rows);
    }

    public function testDeleteAllFromWishlist()
    {
        $wishlist = new Wishlist($this->conn, $this->user_id);

        // Insert test items
        $this->conn->query("INSERT INTO wishlist (user_id, name) VALUES ($this->user_id, 'Test Product 1')");
        $this->conn->query("INSERT INTO wishlist (user_id, name) VALUES ($this->user_id, 'Test Product 2')");

        $wishlist->deleteAllFromWishlist();

        $result = $this->conn->query("SELECT * FROM wishlist WHERE user_id = $this->user_id");
        $this->assertEquals(0, $result->num_rows);
    }
}

