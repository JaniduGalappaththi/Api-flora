// Wishlist.php
class Wishlist
{
    private $conn;
    private $user_id;

    public function __construct($conn, $user_id)
    {
        $this->conn = $conn;
        $this->user_id = $user_id;
    }

    public function addToCart($product_id, $product_name, $product_price, $product_image)
    {
        $product_quantity = 1;

        $check_cart_numbers = mysqli_query($this->conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$this->user_id'") or die('query failed');

        if (mysqli_num_rows($check_cart_numbers) > 0) {
            mysqli_query($this->conn, "UPDATE `cart` SET quantity = quantity + '$product_quantity' WHERE user_id = '$this->user_id' AND pid = '$product_id'") or die('query failed');
        } else {
            $check_wishlist_numbers = mysqli_query($this->conn, "SELECT * FROM `wishlist` WHERE name = '$product_name' AND user_id = '$this->user_id'") or die('query failed');

            if (mysqli_num_rows($check_wishlist_numbers) > 0) {
                mysqli_query($this->conn, "DELETE FROM `wishlist` WHERE name = '$product_name' AND user_id = '$this->user_id'") or die('query failed');
            }

            mysqli_query($this->conn, "INSERT INTO `cart`(user_id, pid, name, price, quantity, image) VALUES('$this->user_id', '$product_id', '$product_name', '$product_price', '$product_quantity', '$product_image')") or die('query failed');
        }
    }

    public function deleteFromWishlist($delete_id)
    {
        mysqli_query($this->conn, "DELETE FROM `wishlist` WHERE id = '$delete_id'") or die('query failed');
    }

    public function deleteAllFromWishlist()
    {
        mysqli_query($this->conn, "DELETE FROM `wishlist` WHERE user_id = '$this->user_id'") or die('query failed');
    }

    public function getWishlistItems()
    {
        return mysqli_query($this->conn, "SELECT * FROM `wishlist` WHERE user_id = '$this->user_id'") or die('query failed');
    }
}
