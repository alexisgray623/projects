<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Products</title>
    <link rel="stylesheet" href="products_style.css">
</head>

<body>
    <h1 class="header">Products</h1>

    <div class="container">
        <div class="sidebar" id="sidebar">
            <h2>Filters</h2>
            <form method="GET">
                <div class="row">
                    <label for="brand">Brand:</label>
                    <select name="brand" id="brand">
                        <option value="">Select Brand</option>
                        <?php
                        // Assuming connection variables are defined
                        $servername = "mysql.eecs.ku.edu";
                        $username = "447s24_a247l653";
                        $password = "eiL3kahf";
                        $database = "447s24_a247l653";

                        $conn = new mysqli($servername, $username, $password, $database);
                        if ($conn->connect_error) {
                            die("Connection failed: " . $conn->connect_error);
                        }
                        $result = $conn->query("SELECT DISTINCT brand FROM PRODUCTS ORDER BY brand");
                        while ($row = $result->fetch_assoc()) {
                            echo '<option value="'.htmlspecialchars($row['brand']).'">'.htmlspecialchars($row['brand']).'</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="row">
                    <label for="color">Color:</label>
                    <select name="color" id="color">
                        <option value="">Select Color</option>
                        <?php
                        $result = $conn->query("SELECT DISTINCT color FROM PRODUCTS ORDER BY color");
                        while ($row = $result->fetch_assoc()) {
                            echo '<option value="'.htmlspecialchars($row['color']).'">'.htmlspecialchars($row['color']).'</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="row">
                    <label for="shoeSize">Size:</label>
                    <select name="shoeSize" id="shoeSize">
                        <option value="">Select Size</option>
                        <?php
                        $result = $conn->query("SELECT DISTINCT shoeSize FROM PRODUCTS ORDER BY shoeSize");
                        while ($row = $result->fetch_assoc()) {
                            echo '<option value="'.htmlspecialchars($row['shoeSize']).'">'.htmlspecialchars($row['shoeSize']).'</option>';
                        }
                        ?>
                    </select>
                </div>
                <button type="submit">Apply Filters</button>
            </form>
        </div>

        <div class="content">
            <?php
            $query = "SELECT * FROM PRODUCTS";
            $conditions = [];
            $params = [];
            
            if (!empty($_GET['brand'])) {
                $conditions[] = "brand = ?";
                $params[] = $_GET['brand'];
            }
            if (!empty($_GET['color'])) {
                $conditions[] = "color = ?";
                $params[] = $_GET['color'];
            }
            if (!empty($_GET['shoeSize'])) {
                $conditions[] = "shoeSize = ?";
                $params[] = $_GET['shoeSize'];
            }
            if (!empty($conditions)) {
                $query .= " WHERE " . implode(" AND ", $conditions);
            }
            
            $stmt = $conn->prepare($query);
            if ($stmt) {
                $stmt->bind_param(str_repeat("s", count($params)), ...$params);
                $stmt->execute();
                $result = $stmt->get_result();

                while ($product = $result->fetch_assoc()) {
                    echo '<div class="product-box" onclick="navigateToProduct(\'product.php?model='.$product['model'].'\')">';
                    echo '<img src="../assets/'.htmlspecialchars($product['image']).'" alt="'.htmlspecialchars($product['model']).'">';
                    echo '<h3>'.htmlspecialchars($product['model']).'</h3>';
                    echo '<p>$'.number_format($product['price'], 2).'</p>';
                    echo '</div>';
                }
                $stmt->close();
            }
            $conn->close();
            ?>
        </div>
    </div>

    <div class="filter-toggle" onclick="toggleSidebar()">Filters</div>

    <script src="products.js"></script>

</body>
</html>
