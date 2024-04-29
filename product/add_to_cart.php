<?php
session_start();  // Start or continue a session

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

$email = $_SESSION['email'] ?? null;  // Retrieve the email from the session
$model = $_POST['model'];
$quantity = intval($_POST['quantity']);

// Check if email and model are provided
if (!$email || !$model) {
    echo "Error: Missing user or product information.";
    exit;
}

// Find the session ID for the user's current cart
$stmt = $conn->prepare("SELECT sessionID FROM CARTS WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $sessionID = $row['sessionID'];

    // SQL to insert into CARTITEMS
    $insertStmt = $conn->prepare("INSERT INTO CARTITEMS (sessionID, model, quantity) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE quantity = quantity + ?");
    $insertStmt->bind_param("ssii", $sessionID, $model, $quantity, $quantity);
    $insertResult = $insertStmt->execute();

    if ($insertResult) {
        echo "Product added to cart successfully!";
    } else {
        echo "Error adding product to cart: " . $conn->error;
    }
    $insertStmt->close();
} else {
    echo "No cart found for this user. Please ensure the user has a cart initiated.";
}

$stmt->close();
$conn->close();
?>
