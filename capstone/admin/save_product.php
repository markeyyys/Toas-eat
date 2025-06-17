<?php
$targetDir = "uploads/";
if (!is_dir($targetDir)) {
    mkdir($targetDir, 0777, true);
}

$name = $_POST['name'];
$qty = $_POST['quantity'];
$price = $_POST['price'];
$imageName = basename($_FILES["image"]["name"]);
$targetFile = $targetDir . $imageName;

if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
    // Database
    $conn = new mysqli("localhost", "root", "", "toas-eat");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("INSERT INTO product (name, quantity, price, image) VALUES (?, ?, ?, ?)");
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("sids", $name, $qty, $price, $targetFile);

    if ($stmt->execute()) {
        echo "Success";
    } else {
        echo "Execute failed: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Error uploading file.";
}
?>
