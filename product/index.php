<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Product Details</title>
    <link rel="stylesheet" href="product_style.css">
</head>
<body>
    <div class="container">
        <?php
        $servername = "mysql.eecs.ku.edu";
        $username = "447s24_a247l653";
        $password = "eiL3kahf";
        $database = "447s24_a247l653";
        // Database connection setup
        $conn = new mysqli($servername, $username, $password, $database);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Get the product model from the URL parameter
        $model = isset($_GET['model']) ? $_GET['model'] : '';

        // SQL query to select the product
        $stmt = $conn->prepare("SELECT * FROM PRODUCTS WHERE model = ?");
        $stmt->bind_param("s", $model);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $product = $result->fetch_assoc();
            ?>
            <h1><?php echo htmlspecialchars($product['model']); ?></h1>
            <div class="product-detail">
                <img src="../assets/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['model']); ?>">
                <div class="product-info">
                    <p><strong>Price:</strong> $<?php echo number_format($product['price'], 2); ?></p>
                    <p><strong>Description:</strong> <?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
                    <p><strong>Brand:</strong> <?php echo htmlspecialchars($product['brand']); ?></p>
                    <p><strong>Color:</strong> <?php echo htmlspecialchars($product['color']); ?></p>
                    <p><strong>Size:</strong> <?php echo htmlspecialchars($product['shoeSize']); ?></p>
                    <form action="add_to_cart.php" method="POST">
                        <input type="hidden" name="model" value="<?php echo htmlspecialchars($product['model']); ?>">
                        <label for="quantity">Quantity:</label>
                        <input type="number" id="quantity" name="quantity" value="1" min="1" max="<?php echo htmlspecialchars($product['remainingStock']); ?>">
                        <button type="submit">Add to Cart</button>
                    </form>
                </div>
            </div>
            <?php
        } else {
            echo "<p>Product not found.</p>";
        }
        $stmt->close();
        $conn->close();
        ?>
    </div>

</body>
</html>
