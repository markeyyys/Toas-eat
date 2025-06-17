<?php 
session_start();
if (!isset($_SESSION['email'])) {
  header("Location: login.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../css/admin.css">
  <title>Admin</title>

</head>
<body class="Admin-section">

  <div class="sidebar">
    <a onclick="showContent('option')">Admin</a>
    <a onclick="showContent('inventory')">Inventory</a>
    <a onclick="showContent('payment')">Payment History</a>
  </div>

  <div class="content" id="main-content">
    
  </div>

  <script>
    const inventory = [];

    function showContent(section) {
      const content = document.getElementById("main-content");

      if (section === "option") {
        content.innerHTML = "<h1>Option</h1><p>This is the option section.</p>";
      } else if (section === "inventory") {
        content.innerHTML = `
          <h1>Inventory</h1>
          <div>
            <input type="text" id="productName" placeholder="Product Name" />
            <input type="number" id="productQty" placeholder="Quantity" />
            <input type="number" id="productPrice" placeholder="Enter price" step="0.01" min="0" />
            <input type="file" id="productImage" accept="image/*" />
            <button type="button" onclick="addProduct()">Add Product</button>
          </div>
          <table id="inventoryTable">
            <thead>
              <tr>
                <th>Image</th>
                <th>Name</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        `;
        updateInventory(); 
      } else if (section === "payment") {
        content.innerHTML = "<h1>Payment History</h1><p>This is the payment history section.</p>";
      }
    }

    function addProduct() {
  const name = document.getElementById('productName').value.trim();
  const qty = parseInt(document.getElementById('productQty').value);
  const price = parseFloat(document.getElementById('productPrice').value);
  const imgInput = document.getElementById('productImage');

  if (!name || isNaN(qty) || qty <= 0 || isNaN(price) || price < 0 || imgInput.files.length === 0) {
    alert("Please enter valid product details.");
    return;
  }

  const formData = new FormData();
  formData.append('name', name);
  formData.append('quantity', qty);
  formData.append('price', price);
  formData.append('image', imgInput.files[0]);

  fetch('http://localhost/CAPSTONE/admin/save_product.php', {

    method: 'POST',
    body: formData
  })
  .then(response => response.text())
  .then(result => {
    if (result === 'Success') {
      alert("Product saved!");
      clearForm();
      // Optionally update your inventory table
    } else {
      alert("Error saving product: " + result);
    }
  })
  .catch(error => {
    alert("Fetch error: " + error);
  });
}

  </script>

</body>
</html>