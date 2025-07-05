<?php
session_start();
if (!isset($_SESSION['customer_id'])) {
  $_SESSION['customer_id'] = uniqid("guest_");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Order - JIMACA Prints</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    body {
      margin: 0;
      font-family: 'Arial', sans-serif;
      background-color: #1c1c1c;
      color: white;
    }
    .top-bar {
      background-color: #111;
      padding: 1rem 2rem;
      position: sticky;
      top: 0;
      z-index: 1000;
      box-shadow: 0 2px 5px rgba(0,0,0,0.5);
    }
    .top-bar-content {
      display: flex;
      justify-content: space-between;
      align-items: center;
      max-width: 1200px;
      margin: auto;
      color: white;
    }
    .logo {
      font-size: 1.2rem;
      font-weight: bold;
    }
    .home-link {
      color: white;
      text-decoration: none;
      font-weight: bold;
      background-color: #333;
      padding: 0.5rem 1rem;
      border-radius: 10px;
      transition: background-color 0.3s ease;
    }
    .home-link:hover {
      background-color: #555;
    }

    .container {
      display: flex;
      padding: 2rem;
      max-width: 1200px;
      margin: auto;
      gap: 2rem;
    }

    .product-preview {
      flex: 2;
      background-color: #2b2b2b;
      padding: 1rem;
      border-radius: 20px;
    }
    .product-preview img {
      width: 100%;
      border-radius: 20px;
    }
    .details h2 { margin: 0.5rem 0; }
    .stars { color: gold; }

    #order-options {
      margin: 1rem 0;
    }
    #order-options label {
      display: block;
      margin-bottom: 0.5rem;
    }
    select {
      padding: 0.5rem;
      border-radius: 10px;
      border: none;
      width: 70%;
    }

    .buttons {
      margin-top: 1.5rem;
      display: flex;
      gap: 1rem;
    }
    .buttons button {
      padding: 0.8rem 1.5rem;
      border: none;
      border-radius: 10px;
      cursor: pointer;
      font-weight: bold;
      background-color: white;
      color: black;
      transition: background-color 0.3s ease;
    }
    .buttons button:hover {
      background-color: #ddd;
    }

    .product-options {
      flex: 1;
      display: flex;
      flex-direction: column;
      gap: 1rem;
    }
    .product-option {
      background-color: #3b3b3b;
      padding: 0.5rem;
      border-radius: 15px;
      text-align: center;
      cursor: pointer;
      transition: background 0.3s;
    }
    .product-option:hover {
      background-color: #555;
    }
    .product-option img {
      width: 100%;
      border-radius: 10px;
    }

    #cart-sidebar {
      position: fixed;
      top: 0;
      right: -400px;
      width: 350px;
      height: 100%;
      background-color: #2b2b2b;
      color: white;
      box-shadow: -2px 0 10px rgba(0, 0, 0, 0.7);
      transition: right 0.4s ease;
      z-index: 1001;
      padding: 1rem;
      overflow-y: auto;
    }
    #cart-sidebar.open {
      right: 0;
    }
    .cart-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 1rem;
    }
    .cart-header button {
      background: none;
      border: none;
      color: white;
      font-size: 1.2rem;
      cursor: pointer;
    }
    .cart-container {
      display: flex;
      flex-direction: column;
      gap: 1rem;
    }
    .cart-item {
      background-color: #3a3a3a;
      padding: 0.8rem;
      border-radius: 10px;
      border: 1px solid #444;
    }
    .cart-details h4 {
      margin: 0.3rem 0;
    }
    .cart-meta {
      font-size: 0.85rem;
      color: #ccc;
    }
    .badge {
      background: #0099ff;
      padding: 2px 8px;
      border-radius: 10px;
      font-size: 0.75rem;
      margin-top: 4px;
      display: inline-block;
    }

    footer {
      background-color: #111;
      color: white;
      padding: 60px 20px;
      display: flex;
      justify-content: center;
      align-items: flex-start;
      flex-wrap: wrap;
    }
    footer div {
      flex: 1;
      min-width: 250px;
      padding: 20px;
    }

    @media (max-width: 768px) {
      .container {
        flex-direction: column;
      }
    }
  </style>
</head>
<body>

<header class="top-bar">
  <div class="top-bar-content">
    <span class="logo">🖨️ JIMACA Prints</span>
    <div style="display: flex; gap: 1rem;">
      <a href="Home.html" class="home-link">Home</a>
      <a href="#" class="home-link" onclick="toggleCart()">🛒 Cart</a>
    </div>
  </div>
</header>

<div class="container">
  <!-- Left: Product and Order Form -->
  <div class="product-preview">
    <img id="product-img" src="tshirt.avif" alt="T-Shirt">
    <div class="details">
      <h2 id="product-title">T-SHIRT</h2>
      <p class="stars">★★★★★ 5/5 stars</p>
      <p id="product-price"><strong>PHP 150.00 - 250.00</strong></p>
      <p id="product-desc">A plain, simple, yet comfort t-shirt made of 100% cotton for quality prints.</p>
      <div id="order-options">
        <label>Quantity: <input type="number" id="quantity" min="1" value="1"></label>
        <label>Color:
          <select id="color">
            <option>Black</option>
            <option>White</option>
            <option>Red</option>
            <option>Blue</option>
            <option>Green</option>
          </select>
        </label>
        <label id="size-label">Size:
          <select id="size">
            <option>Small</option>
            <option>Medium</option>
            <option>Large</option>
            <option>XL</option>
            <option>XXL</option>
          </select>
        </label>
      </div>
      <div class="buttons">
        <button type="button" onclick="submitOrder()">ADD TO CART</button>
        <button type="button" onclick="uploadDesign()">SEND DESIGN</button>
      </div>
    </div>
  </div>

  <!-- Right: Product Options -->
  <div class="product-options">
    <div class="product-option" onclick="changeProduct('T-SHIRT')"><p>T-SHIRT</p><img src="tshirt.avif"></div>
    <div class="product-option" onclick="changeProduct('CAP')"><p>CAP</p><img src="cap.avif"></div>
    <div class="product-option" onclick="changeProduct('UMBRELLA')"><p>UMBRELLA</p><img src="umbrella.jpg"></div>
    <div class="product-option" onclick="changeProduct('SASH')"><p>SASH</p><img src="sash.webp"></div>
    <div class="product-option" onclick="changeProduct('FAN')"><p>FAN</p><img src="fan.jpg"></div>
  </div>
</div>

<!-- Cart Sidebar -->
<div id="cart-sidebar">
  <div class="cart-header">
    <strong>🛒 Your Cart</strong>
    <button onclick="toggleCart()">✖</button>
  </div>
  <div id="live-cart" class="cart-container"><p>Loading...</p></div>
  <button onclick="confirmOrder()" style="margin-top: 1rem; padding: 10px 20px; background-color: green; color: white; border: none; border-radius: 8px; cursor: pointer;">
    ✅ Confirm Order
  </button>
</div>

<footer>
  <div>
    <h2>JIMACA<br>Graphics</h2>
    <p>Ready to provide quality prints for your professional brand.</p>
  </div>
  <div>
    <h2>Contact us</h2>
    <p>Somewhere in the Philippines<br>0000-000-0000<br>example@gmail.com</p>
  </div>
</footer>

<script>
  const products = {
    "T-SHIRT": { title: "T-SHIRT", image: "tshirt.avif", price: "PHP 150.00 - 250.00", desc: "A plain, simple, yet comfort t-shirt made of 100% cotton for quality prints." },
    "CAP": { title: "CAP", image: "cap.avif", price: "PHP 80.00 - 150.00", desc: "Classic adjustable cap perfect for embroidery or vinyl print customization." },
    "UMBRELLA": { title: "UMBRELLA", image: "umbrella.jpg", price: "PHP 200.00 - 350.00", desc: "Strong, waterproof umbrella available in various colors for printing services." },
    "SASH": { title: "SASH", image: "sash.webp", price: "PHP 50.00 - 100.00", desc: "Smooth satin sash available for custom print, great for events or awards." },
    "FAN": { title: "FOLDABLE FAN", image: "fan.jpg", price: "PHP 30.00 - 80.00", desc: "Compact foldable fan for giveaways and promotions with custom logo print." }
  };

  function changeProduct(key) {
    const product = products[key];
    document.getElementById('product-title').innerText = product.title;
    document.getElementById('product-img').src = product.image;
    document.getElementById('product-price').innerHTML = `<strong>${product.price}</strong>`;
    document.getElementById('product-desc').innerText = product.desc;
    document.getElementById('size-label').style.display = (key === "T-SHIRT" || key === "CAP") ? "block" : "none";
  }

  function submitOrder() {
    const product = document.getElementById("product-title").innerText;
    const quantity = document.getElementById("quantity").value;
    const color = document.getElementById("color").value;
    const size = document.getElementById("size-label").style.display !== "none"
      ? document.getElementById("size").value : "N/A";

    fetch('add_to_cart.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: `product=${encodeURIComponent(product)}&quantity=${quantity}&color=${encodeURIComponent(color)}&size=${encodeURIComponent(size)}`
    })
    .then(res => res.json())
    .then(data => {
      alert(data.success ? "✅ Item added to cart!" : "❌ Failed to add to cart.");
      fetchLiveCart();
    });
  }

  function uploadDesign() {
    alert("📤 Please send your design to example@gmail.com or upload in the next version.");
  }

  function toggleCart() {
    document.getElementById("cart-sidebar").classList.toggle("open");
  }

  function fetchLiveCart() {
    fetch('get_orders.php')
    .then(response => response.json())
    .then(data => {
      const container = document.getElementById('live-cart');
      container.innerHTML = data.length === 0
        ? "<p>No orders yet.</p>"
        : data.map(order => `
          <div class="cart-item">
            <div class="cart-details">
              <h4>${order.items}</h4>
              <div class="cart-meta">
                🧍 ${order.customer_name}<br>
                🎨 ${order.method} | 🔢 ${order.quantity}
              </div>
              <span class="badge">⏰ ${order.pickup_time}</span>
            </div>
          </div>
        `).join('');
    });
  }

  function confirmOrder() {
  // Step 1: Submit cart to DB
  fetch('submit_order.php')
    .then(response => {
      if (!response.ok) throw new Error("Failed to submit order");
      return fetch('confirm_order.php', { method: 'POST' }); // Step 2: Confirm the order
    })
    .then(res => res.text())
    .then(response => {
      alert(response);      // Step 3: Show confirmation message
      fetchLiveCart();      // Step 4: Refresh the cart display
    })
    .catch(err => {
      alert("❌ Something went wrong.");
      console.error(err);
    });
}


  document.addEventListener("DOMContentLoaded", () => {
    changeProduct("T-SHIRT");
    document.getElementById("quantity").addEventListener("input", e => {
      if (e.target.value < 1) e.target.value = 1;
    });
    fetchLiveCart();
    setInterval(fetchLiveCart, 5000);
  });
</script>

</body>
</html>