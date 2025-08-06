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
      background-image: url(bg.png);background-size: cover; background-position: center;;
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
      background-color: #36363686;
    }

    @media (max-width: 768px) {
      .container {
        flex-direction: column;
      }
    }

    .category {
  margin-bottom: 1rem;
}
.category-toggle {
  width: 100%;
  background-color: #444;
  color: white;
  border: none;
  padding: 0.75rem;
  text-align: left;
  font-weight: bold;
  border-radius: 10px;
  cursor: pointer;
  margin-bottom: 0.5rem;
}
.category-toggle:hover {
  background-color: #666;
}
.category-content {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
  gap: 1rem;
}
.social-pill {
  display: inline-block;
  color: white;
  padding: 0.6rem 1.2rem;
  border-radius: 999px;
  font-weight: bold;
  text-decoration: none;
  transition: background-color 0.3s ease;
  margin: 8px;
}

.facebook-pill {
  background-color: #1877F2;
}
.facebook-pill:hover {
  background-color: #145dbf;
}

.email-pill {
  background-color: #b22c2c;
}
.email-pill:hover {
  background-color: #cc3e3e;
}
  </style>
</head>
<body>

<header class="top-bar">
  <div class="top-bar-content">
    <span class="logo"><img src="logo.jpg" alt="Logo" style="height: 30px; vertical-align: middle; margin-right: 8px;">JIMACA GRAPHICS</span>
    <div style="display: flex; gap: 1rem;">
      <a href="Home.html" class="home-link">Home</a>
      <a href="track_order.php" class="home-link">Order Progress</a>
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
      <p id="product-price"><strong>PHP 200.00</strong></p>
      <p id="product-desc">A plain, simple, yet comfort t-shirt made of 100% cotton for quality prints.</p>
      <div id="order-options">    
  <label>Pickup Time:
  <input type="datetime-local" id="pickup-time" required>
  </label>
  <label>Quantity: <input type="number" id="quantity" min="1" value="1"></label>
  <label>Color:
    <select id="color">
      <option>Assorted</option>
      <option>Gold</option>
      <option>Maroon</option>
      <option>Navy Blue</option>
      <option>Soft Orange</option>
      <option>Royal Blue</option>
      <option>Sunkist</option>
      <option>Tangerine</option>
      <option>Strong Red</option>
      <option>Violet</option>
      <option>Army Green</option>
      <option>Canyon Beige</option>
      <option>Khaki</option>
      <option>Mustard Yellow</option>
      <option>Slate Blue</option>
      <option>Teal Green</option>
      <option>Avocado</option>
      <option>C. Yellow</option>
      <option>Coral</option>
      <option>Cream</option>
      <option>Lavender</option>
      <option>LT. Apple Green</option>
      <option>LT. Aqua</option>
      <option>LT. Blue</option>
      <option>LT. Pink</option>
      <option>Peach</option>
      <option>French Lilac</option>
      <option>Mint Green</option>
      <option>Aqua Blue</option>
      <option>Black</option>
      <option>Dark Green</option>
      <option>E. Green</option>
      <option>Fuschia</option>
    </select>
  </label>
  <label>Method:
    <select id="method">
      <option value="DTF">DTF</option>
      <option value="Silkscreen">Silkscreen</option>
    </select>
  </label>
  <label id="size-label">Size:
    <select id="size">
      <option>Kids Small</option>
      <option>Kids Medium</option>
      <option>Kids Large</option>
      <option>Kids XL</option>
      <option>Teen</option>
      <option>XS</option>
      <option>Small</option>
      <option>Medium</option>
      <option>Large</option>
      <option>XL</option>
      <option>XXL</option>
      <option>XXXL</option>
    </select>
  </label>
  <button id="toggleChartBtn" class="size-chart-btn" onclick="toggleChart()">📏 View Size Chart</button>
  <div id="size-chart-box" style="display:none; margin-top: 10px; background: #1a1a1a; padding: 10px; border-radius: 10px;"></div>
       </div>
      <div class="buttons">
        <button type="button" onclick="addToCart()">ADD TO CART</button>
      </div>
    </div>
  </div>

<!-- Right: Product Options -->
<div class="product-options">
<!-- Apparel Category -->
  <div class="category">
    <button class="category-toggle" onclick="toggleCategory(this)">👕 Apparel</button>
    <div class="category-content">
      <div class="product-option" onclick="changeProduct('T-SHIRT')"><p>T-SHIRT</p><img src="tshirt.avif"></div>
      <div class="product-option" onclick="changeProduct('SCHOOL/OFFICE UNIFORMS')"><p>UNIFORMS</p><img src="uniform.jpg"></div>
      <div class="product-option" onclick="changeProduct('BASKETBALL UNIFORMS')"><p>BASKETBALL</p><img src="basket.jpg"></div>
      <div class="product-option" onclick="changeProduct('JOGGING PANTS')"><p>JOGGING</p><img src="jogging.jpg"></div>
      <div class="product-option" onclick="changeProduct('OVERALLS')"><p>OVERALLS</p><img src="over.webp"></div>
      <div class="product-option" onclick="changeProduct('JACKETS')"><p>JACKETS</p><img src="jack.webp"></div>
      <div class="product-option" onclick="changeProduct('LONGSLEEVE')"><p>LONGSLEEVE</p><img src="longsleeve.jpg"></div>
      <div class="product-option" onclick="changeProduct('CAP')"><p>CAPS</p><img src="cap.avif"></div>
      <div class="product-option" onclick="changeProduct('BEDROOM SLIPPERS')"><p>SLIPPERS</p><img src="slip.jpg"></div>
    </div>
  </div>

  <!-- Bags Category -->
  <div class="category">
    <button class="category-toggle" onclick="toggleCategory(this)">🎒 Bags & Pouches</button>
    <div class="category-content">
      <div class="product-option" onclick="changeProduct('CANVAS BAGS & POUCHES')"><p>CANVAS</p><img src="tote.jpg"></div>
      <div class="product-option" onclick="changeProduct('NON-WOVEN BAGS')"><p>WOVEN</p><img src="woven.webp"></div>
      <div class="product-option" onclick="changeProduct('KRAFT PAPER BAGS')"><p>KRAFT</p><img src="kraft.webp"></div>
    </div>
  </div>

  <!-- Household Items -->
  <div class="category">
    <button class="category-toggle" onclick="toggleCategory(this)">🏠 Household</button>
    <div class="category-content">
      <div class="product-option" onclick="changeProduct('THROWPILLOWS')"><p>PILLOW</p><img src="pillow.jpg"></div>
      <div class="product-option" onclick="changeProduct('BEDDINGS')"><p>BEDDING</p><img src="bed.avif"></div>
    </div>
  </div>

  <!-- Tags & Ribbons -->
  <div class="category">
    <button class="category-toggle" onclick="toggleCategory(this)">🏷️ Tags & Ribbons</button>
    <div class="category-content">
      <div class="product-option" onclick="changeProduct('RIBBONS')"><p>RIBBON</p><img src="ribbon.jpg"></div>
      <div class="product-option" onclick="changeProduct('HANG TAGS')"><p>TAG</p><img src="tag.jpg"></div>
    </div>
  </div>

  <!-- Giveaways -->
  <div class="category">
    <button class="category-toggle" onclick="toggleCategory(this)">🎁 Giveaways</button>
    <div class="category-content">
      <div class="product-option" onclick="changeProduct('FOLDABLE FANS')"><p>FAN</p><img src="fan.jpg"></div>
      <div class="product-option" onclick="changeProduct('ID LACE/LANYARDS')"><p>LACE</p><img src="lace.jpg"></div>
      <div class="product-option" onclick="changeProduct('UMBRELLA')"><p>UMBRELLA</p><img src="umbrella.jpg"></div>
     </div>
    </div>
</div>

 <!-- Cart Sidebar -->
 <div id="cart-sidebar">
  <div class="cart-header">
    <strong>🛒 Your Cart</strong>
    <button onclick="toggleCart()">✖</button>
  </div>

  <div id="live-cart" class="cart-container"><p>Loading...</p></div>

  <!-- Add these inputs -->
  <input type="text" id="customer-name" placeholder="Enter your name" required style="margin-top: 1rem; width: 100%; padding: 8px;">
  <input type="email" id="customer-email" placeholder="Enter your email" required style="margin-top: 0.5rem; width: 100%; padding: 8px;">
  <input type="tel" id="customer-phone" placeholder="Enter your phone" required style="margin-top: 0.5rem; width: 100%; padding: 8px;">
  <input type="file" id="design-file" accept=".jpg,.jpeg,.png,.pdf" required style="margin-top: 0.5rem; width: 100%; padding: 8px;">

  <!-- Payment Method Selection -->
  <label for="popup_payment_method" style="margin-top: 0.5rem; display: block;">Select Payment Method:</label>
  <select id="popup_payment_method" required style="width: 100%; padding: 8px;">
  <option value="">-- Choose --</option>
  <option value="gcash">GCash</option>
  <option value="chinabank">China Bank</option>
  </select>

  <!-- Payment Instructions & Upload Box -->
  <div id="payment-upload-box" style="display:none; margin-top: 0.5rem;">
  <div id="gcash-instructions" style="display:none;">
    <p><strong>📱 Scan to pay via GCash:</strong></p>
    <img src="gcash_qr_sample.jpg" alt="GCash QR Code" style="width: 100%; max-width: 200px; display: block; margin: 10px auto; border: 1px solid #ccc; border-radius: 8px;">
  </div>

  <div id="chinabank-instructions" style="display:none;">
    <p><strong>🏦 Transfer to China Bank:</strong></p>
    <p>Account Name: <strong>JIMACA Prints</strong><br>Account Number: <strong>1234-5678-9012</strong></p>
  </div>

  <label for="popup_payment_proof">Upload Payment Proof:</label>
  <input type="file" id="popup_payment_proof" accept="image/*" style="width: 100%; padding: 8px;">
  <img id="payment_preview" src="" alt="Preview" style="margin-top: 8px; width: 100%; max-height: 200px; display: none; border: 1px solid #ccc; border-radius: 6px;">
  </div>

  <div id="cart-total" style="margin-top: 1rem; font-size: 1.2rem;"></div>
  <p id="popup-cart-total" style="font-weight: bold;"></p>
  <input type="hidden" id="final_total" name="final_total">

  <!-- Confirm Order Button -->
  <button type="button" onclick="confirmOrder()" style="margin-top: 1rem; padding: 10px 20px; background-color: green; color: white; border: none; border-radius: 8px; cursor: pointer;">
    ✅ Confirm Order
  </button>
 </div>

</div>
<!-- Footer Section -->
<footer style="color: white; padding: 60px 20px; display: flex; text-align: center; justify-content: center; align-items: flex-start; flex-wrap: wrap;">
  <div style="min-width: 150px; padding: 10px;">    
    <h1 style="font-size: 2.8em;">Contact us</h1>
    <p style="font-size: 1.4em"> 📍 ADDRESS: 8338 Arayat Street, Unit 3 back of Fortuna Bldg, <br>Justina Village, Brgy. San Isidro, Parañaque, Philippines<br>
    📞 09193542867 ☎️ 8715-9506</p>
    <div style="margin-top: 10px;">
      <a href="https://www.facebook.com/SilkcreenPrinting" target="_blank" class="social-pill facebook-pill">
       <i class="fab fa-facebook-f"></i> Visit JIMACA on Facebook
      </a>
      <a href="mailto:jcabales2002@yahoo.com" class="social-pill email-pill">
      ✉️ jcabales2002@yahoo.com
      </a>
    </div>
  </div>
</footer>

<script>
  const products = {
  "T-SHIRT": { title: "T-SHIRT", image: "tshirt.avif", price: "PHP 200.00", desc: "A plain, simple, yet comfort t-shirt made of 100% cotton for quality prints.", sizes: ["Kids Small","Kids Medium","Kids Large", "Kids XL","Teen","XS", "Small", "Medium", "Large", "XL", "XXL","XXXL"]},
  "CAP": { title: "CAP", image: "cap.avif", price: "PHP 120.00", desc: "Classic adjustable cap perfect for embroidery or vinyl print customization.", sizes: ["Child", "Adult"] },
  "UMBRELLA": { title: "UMBRELLA", image: "umbrella.jpg", price: "PHP 275.00", desc: "Strong, waterproof umbrella available in various colors for printing services.", sizes: [] },
  "SASH": { title: "SASH", image: "sash.webp", price: "PHP 75", desc: "Smooth satin sash available for custom print, great for events or awards.", sizes: [] },
  "FAN": { title: "FOLDABLE FAN", image: "fan.jpg", price: "PHP 55.00", desc: "Compact foldable fan for giveaways and promotions with custom logo print.", sizes: [] },
  "SCHOOL/OFFICE UNIFORMS": { title: "SCHOOL/OFFICE UNIFORMS", image: "uniform.jpg", price: "PHP 600.00", desc: "High-quality school and office uniforms with customized print.", sizes: ["Kids Small","Kids Medium","Kids Large", "Kids XL","Teen","XS", "Small", "Medium", "Large", "XL", "XXL","XXXL"]},
  "BASKETBALL UNIFORMS": { title: "BASKETBALL UNIFORMS", image: "basket.jpg", price: "PHP 500.00", desc: "Durable basketball jerseys for teams, clubs, and events.", sizes: ["Kids Small","Kids Medium","Kids Large", "Kids XL","Teen","XS", "Small", "Medium", "Large", "XL", "XXL","XXXL"]},
  "JOGGING PANTS": { title: "JOGGING PANTS", image: "jogging.jpg", price: "PHP 260.00", desc: "Comfortable jogging pants suitable for printing.", sizes: ["Kids Small","Kids Medium","Kids Large", "Kids XL","Teen","XS", "Small", "Medium", "Large", "XL", "XXL","XXXL"]},
  "ID LACE/LANYARDS": { title: "ID LACE/LANYARDS", image: "lace.jpg", price: "PHP 50.00", desc: "Custom-printed ID laces and event lanyards.", sizes: [] },
  "FOLDABLE FANS": { title: "FOLDABLE FANS", image: "fan.jpg", price: "PHP 30.00", desc: "Promotional foldable fans with your logo.", sizes: [] },
  "OVERALLS": { title: "OVERALLS", image: "over.webp", price: "PHP 450.00", desc: "Protective overalls ready for custom branding.", sizes: ["Kids Small","Kids Medium","Kids Large", "Kids XL","Teen","XS", "Small", "Medium", "Large", "XL", "XXL","XXXL"]},
  "JACKETS": { title: "JACKETS", image: "jack.webp", price: "PHP 850.00", desc: "Warm, stylish jackets with full printing support.", sizes: ["Kids Small","Kids Medium","Kids Large", "Kids XL","Teen","XS", "Small", "Medium", "Large", "XL", "XXL","XXXL"]},
  "LONGSLEEVE": { title: "LONGSLEEVE", image: "longsleeve.jpg", price: "PHP 769.00", desc: "A fit for work or casual walk", sizes: ["Kids Small","Kids Medium","Kids Large", "Kids XL","Teen","XS", "Small", "Medium", "Large", "XL", "XXL","XXXL"]},
  "BEDROOM SLIPPERS": { title: "BEDROOM SLIPPERS", image: "slip.jpg", price: "PHP 85.00", desc: "Cozy slippers personalized with your design.", sizes: ["EU36-37", "EU38-39", "EU40-41", "EU42-43", "EU44-45"] },
  "THROWPILLOWS": { title: "THROWPILLOWS", image: "pillow.jpg", price: "PHP 100.00", desc: "Soft throw pillows with custom print options.", sizes: ["Small", "Medium", "Large"] },
  "BEDDINGS": { title: "BEDDINGS", image: "bed.avif", price: "PHP 900.00", desc: "Comfortable printed bedding sets for your room.", sizes: ["Single", "Semi-Single", "Twin", "Full", "Queen", "King"] },
  "RIBBONS": { title: "RIBBONS", image: "ribbon.jpg", price: "PHP 50.00", desc: "Custom ribbons for awards, packaging, or decoration.", sizes: [] },
  "CANVAS BAGS & POUCHES": { title: "CANVAS BAGS & POUCHES", image: "tote.jpg", price: "PHP 150.00", desc: "Eco-friendly canvas bags with printed design.", sizes: ["S", "M", "L"] },
  "NON-WOVEN BAGS": { title: "NON-WOVEN BAGS", image: "woven.webp", price: "PHP 120.00", desc: "Reusable non-woven bags ideal for giveaways.", sizes: ["S", "M", "L"] },
  "KRAFT PAPER BAGS": { title: "KRAFT PAPER BAGS", image: "kraft.webp", price: "PHP 100.00", desc: "Brown kraft bags with business logo printing.", sizes: ["S", "M", "L"] },
  "HANG TAGS": { title: "HANG TAGS", image: "tag.jpg", price: "PHP 25.00", desc: "Custom tags for clothing, packaging, and promos.", sizes: [] }
};

  function toggleCategory(button) {
    const content = button.nextElementSibling;
    content.style.display = content.style.display === "none" ? "grid" : "none";
  }

  // Optional: collapse all categories by default
  document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll(".category-content").forEach(c => c.style.display = "none");
  });

  function changeProduct(key) {
  const product = products[key];

  // Update product info
  document.getElementById('product-title').innerText = product.title;
  document.getElementById('product-img').src = product.image;
  document.getElementById('product-price').innerHTML = `<strong>${product.price}</strong>`;
  document.getElementById('product-desc').innerText = product.desc;

  const sizeLabel = document.getElementById('size-label');
  const sizeSelect = document.getElementById('size');
  const chartBox = document.getElementById("size-chart-box");
  const chartBtn = document.getElementById("toggleChartBtn");

  // Clear old size options
  sizeSelect.innerHTML = "";

  // If sizes are defined, show the dropdown
  if (product.sizes && product.sizes.length > 0) {
    sizeLabel.style.display = "block";
    product.sizes.forEach(size => {
      const option = document.createElement("option");
      option.text = size;
      sizeSelect.add(option);
    });
  } else {
    sizeLabel.style.display = "none"; // Hide if no size needed
  }
  // Update size chart
  if (sizeCharts[key]) {
    chartBox.innerHTML = sizeCharts[key];
    chartBtn.style.display = "inline-block";
  } else {
    chartBox.innerHTML = "";
    chartBtn.style.display = "none";
  }
}


function addToCart() {
  const product = document.getElementById("product-title").innerText;
  const quantity = document.getElementById("quantity").value;
  const color = document.getElementById("color").value;
  const size = document.getElementById("size-label").style.display !== "none"
    ? document.getElementById("size").value : "N/A";
  const customer_name = document.getElementById("customer-name").value || "Guest";
  const customer_phone = document.getElementById("customer-phone").value;
  const customer_email = document.getElementById("customer-email").value;
  const pickup_time = document.getElementById("pickup-time").value;
  const method = document.getElementById("method").value;


  fetch('add_to_cart.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: `product=${encodeURIComponent(product)}&quantity=${quantity}&color=${encodeURIComponent(color)}&size=${encodeURIComponent(size)}&name=${encodeURIComponent(customer_name)}&pickup_time=${encodeURIComponent(pickup_time)}&phone=${encodeURIComponent(customer_phone)}&email=${encodeURIComponent(customer_email)}&method=${encodeURIComponent(method)}`
  })
  .then(res => res.json())
  .then(data => {
    alert(data.success ? "✅ Item added to cart!" : "❌ Failed to add to cart.");
    fetchLiveCart();
  });
}
  function uploadDesign() {
  const fileInput = document.getElementById('design-file');
  if (!fileInput.files.length) {
    alert("❌ No file selected.");
    return;
  }

  const formData = new FormData();
  formData.append('design', fileInput.files[0]);

  fetch('upload_design.php', {
    method: 'POST',
    body: formData
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      alert("✅ Design uploaded successfully!");
    } else {
      alert("❌ Upload failed: " + data.message);
    }
  })
  .catch(err => {
    console.error(err);
    alert("❌ Error uploading design.");
  });
}

  function toggleCart() {
    document.getElementById("cart-sidebar").classList.toggle("open");
  }

  function fetchLiveCart() {
  const name = document.getElementById("customer-name").value;
  const email = document.getElementById("customer-email").value;
  const phone = document.getElementById("customer-phone").value;

  fetch('get_orders.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: new URLSearchParams({ name, email, phone })
  })
  .then(response => response.json())
  .then(data => {
      const container = document.getElementById('live-cart');
      if (data.length === 0) {
        container.innerHTML = "<p>No orders yet.</p>";
        return;
      }

      container.innerHTML = data.map((order, index) => `
  <div class="cart-item">
    <div class="cart-details">
      <h4>${order.product}</h4>
      <div class="cart-meta">
        🧍 ${order.customer_name}<br>
        🎨 Method:
            <select onchange="updateCartItem(${index}, 'method', this.value)">
             <option ${order.method === 'DTF' ? 'selected' : ''}>DTF</option>
             <option ${order.method === 'Silkscreen' ? 'selected' : ''}>Silkscreen</option>
            </select> |
        🔢 <input type="number" value="${order.quantity}" min="1" style="width: 50px;"
          onchange="updateCartItem(${index}, 'quantity', this.value)">
        <br>
        🎨 Color:
        <select onchange="updateCartItem(${index}, 'color', this.value)">
      <option ${order.color === 'Assorted' ? 'selected' : ''}>Assorted</option>
      <option ${order.color === 'Gold' ? 'selected' : ''}>Gold</option>
      <option ${order.color === 'Maroon' ? 'selected' : ''}>Maroon</option>
      <option ${order.color === 'Navy Blue' ? 'selected' : ''}>Navy Blue</option>
      <option ${order.color === 'Soft Orange' ? 'selected' : ''}>Soft Orange</option>
      <option ${order.color === 'Royal BLue' ? 'selected' : ''}>Royal Blue</option>
      <option ${order.color === 'Sunkist' ? 'selected' : ''}>Sunkist</option>
      <option ${order.color === 'Tangerine' ? 'selected' : ''}>Tangerine</option>
      <option ${order.color === 'Strong Red' ? 'selected' : ''}>Strong Red</option>
      <option ${order.color === 'Violet' ? 'selected' : ''}>Violet</option>
      <option ${order.color === 'Army Green' ? 'selected' : ''}>Army Green</option>
      <option ${order.color === 'Canyon Beige' ? 'selected' : ''}>Canyon Beige</option>
      <option ${order.color === 'Khaki' ? 'selected' : ''}>Khaki</option>
      <option ${order.color === 'Mustard Yellow' ? 'selected' : ''}>Mustard Yellow</option>
      <option ${order.color === 'Slate Blue' ? 'selected' : ''}>Slate Blue</option>
      <option ${order.color === 'Teal Green' ? 'selected' : ''}>Teal Green</option>
      <option ${order.color === 'Avocado' ? 'selected' : ''}>Avocado</option>
      <option ${order.color === 'C. Yellow' ? 'selected' : ''}>C. Yellow</option>
      <option ${order.color === 'Coral' ? 'selected' : ''}>Coral</option>
      <option ${order.color === 'Cream' ? 'selected' : ''}>Cream</option>
      <option ${order.color === 'Lavender' ? 'selected' : ''}>Lavender</option>
      <option ${order.color === 'LT. Apple Green' ? 'selected' : ''}>LT. Apple Green</option>
      <option ${order.color === 'LT. Aqua' ? 'selected' : ''}>LT. Aqua</option>
      <option ${order.color === 'LT. Blue' ? 'selected' : ''}>LT. Blue</option>
      <option ${order.color === 'LT. Pink' ? 'selected' : ''}>LT. Pink</option>
      <option ${order.color === 'Peach' ? 'selected' : ''}>Peach</option>
      <option ${order.color === 'French Lilac' ? 'selected' : ''}>French Lilac</option>
      <option ${order.color === 'Mint Green' ? 'selected' : ''}>Mint Green</option>
      <option ${order.color === 'Aqua Blue' ? 'selected' : ''}>Aqua Blue</option>
      <option ${order.color === 'Black' ? 'selected' : ''}>Black</option>
      <option ${order.color === 'Dark Green' ? 'selected' : ''}>Dark Green</option>
      <option ${order.color === 'E. Green' ? 'selected' : ''}>E. Green</option>
      <option ${order.color === 'Fuschia' ? 'selected' : ''}>Fuschia</option>
        </select><br>
       📏 Size:
        <select onchange="updateCartItem(${index}, 'size', this.value)">
          ${
            (products[order.product]?.sizes.length > 0
              ? products[order.product].sizes
              : ['One Size']
              ).map(size => `
             <option value="${size}" ${order.size === size ? 'selected' : ''}>${size}</option>
           `).join('')
         }
      </select>
      </div>
      <span class="badge">⏰ ${order.pickup_time}</span><br>
      <button onclick="removeFromCart(${index})"style="margin-top: 0.5rem; background: red; color: white; border: none; padding: 4px 10px; border-radius: 6px; cursor: pointer;">🗑️ Remove</button>
    </div>
  </div>
`).join('');
let total = 0;
data.forEach(order => {
  const rawPrice = products[order.product]?.price || "PHP 0";
  const priceNum = parseFloat(rawPrice.replace(/[^\d.]/g, ''));
  const quantity = parseInt(order.quantity) || 1;
  total += priceNum * quantity;
});

// Show total in a separate container
const totalContainer = document.getElementById("cart-total");
if (totalContainer) {
  totalContainer.innerHTML = `<strong>Total: PHP ${total.toFixed(2)}</strong>`;
}
    });
}

function updatePopupCartTotal() {
  const name = document.getElementById("customer-name").value.trim();
  const email = document.getElementById("customer-email").value.trim();
  const phone = document.getElementById("customer-phone").value.trim();

  fetch('get_orders.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: new URLSearchParams({ name, email, phone })
  })
  .then(res => res.json())
  .then(data => {
    let total = 0;
    data.forEach(order => {
      const rawPrice = products[order.product]?.price || "PHP 0.00";
      const priceNum = parseFloat(rawPrice.replace(/[^\d.]/g, '')) || 0;
      const quantity = parseInt(order.quantity) || 1;
      total += priceNum * quantity;
    });

    document.getElementById("popup-cart-total").innerText = `💰 Total to Pay: PHP ${total.toFixed(2)}`;
    document.getElementById("final_total").value = total.toFixed(2);
  });
}

function showConfirmPopup() {
  updatePopupCartTotal(); // 🔥 call this first
  document.getElementById("popup").style.display = "block";
}


function confirmOrder() {
  const name = document.getElementById("customer-name").value.trim();
  const email = document.getElementById("customer-email").value.trim();
  const phone = document.getElementById("customer-phone").value.trim();
  const pickupTime = document.getElementById("pickup-time").value;
  const designFile = document.getElementById("design-file").files[0];
  const paymentMethod = document.getElementById("popup_payment_method").value;
  const paymentProof = document.getElementById("popup_payment_proof").files[0];

  // Validate required fields
  if (!name || !email || !phone || !pickupTime || !designFile || !paymentMethod) {
    alert("⚠️ Please complete all required fields.");
    return;
  }

  // Require proof if either payment method is selected
  if ((paymentMethod === "gcash" || paymentMethod === "chinabank") && !paymentProof) {
    alert("⚠️ Please upload payment proof.");
    return;
  }

  const formData = new FormData();
  formData.append("name", name);
  formData.append("email", email);
  formData.append("phone", phone);
  formData.append("pickup_time", pickupTime);
  formData.append("design_file", designFile);
  formData.append("payment_method", paymentMethod);

  // Append proof (optional, but required for gcash/chinabank)
  if (paymentProof) {
    formData.append("gcash_proof", paymentProof);
  }
  
  formData.append("final_total", document.getElementById("final_total").value);

  fetch("submit_order.php", {
    method: "POST",
    body: formData
  })
  .then(response => response.text())
  .then(data => {
    alert(data);
    location.reload(); // clear everything
  })
  .catch(error => {
    console.error("❌ Error:", error);
    alert("❌ Failed to submit order.");
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

function removeFromCart(index) {
  fetch('remove_from_cart.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: `index=${index}`
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      fetchLiveCart(); // Refresh cart
    } else {
      alert("❌ Failed to remove item.");
    }
  });
}

function updateCartItem(index, key, value) {
  fetch('update_cart_item.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: `index=${index}&key=${encodeURIComponent(key)}&value=${encodeURIComponent(value)}`
  })
  .then(res => res.json())
  .then(data => {
    if (!data.success) {
      alert("❌ Failed to update item.");
      console.error(data.message);
    }
  });
}
function toggleChart() {
  const box = document.getElementById("size-chart-box");
  const button = document.getElementById("toggleChartBtn");

  if (box.style.display === "none") {
    box.style.display = "block";
    button.innerText = "📏 Hide Size Chart";
  } else {
    box.style.display = "none";
    button.innerText = "📏 View Size Chart";
  }
}
const sizeCharts = {
  'T-SHIRT': `
<h4 style="margin-top:10px;">T-Shirt Size Chart</h4>
<table style="width:100%; border-collapse: collapse; margin-top: 10px; border-radius: 8px; overflow: hidden;">
  <thead style="background-color: #444; color: #fff;">
    <tr>
      <th style="padding: 10px; border: 1px solid #666;">Size</th>
      <th style="padding: 10px; border: 1px solid #666;">Dimensions (in)</th>
    </tr>
  </thead>
  <tbody>
    <tr><td style="padding:8px; border: 1px solid #555;">Kids Small</td><td style="padding:8px; border: 1px solid #555;">13 x 17</td></tr>
    <tr><td style="padding:8px; border: 1px solid #555;">Kids Medium</td><td style="padding:8px; border: 1px solid #555;">14 x 18</td></tr>
    <tr><td style="padding:8px; border: 1px solid #555;">Kids Large</td><td style="padding:8px; border: 1px solid #555;">15 x 19</td></tr>
    <tr><td style="padding:8px; border: 1px solid #555;">Kids XL</td><td style="padding:8px; border: 1px solid #555;">16 x 20</td></tr>
    <tr><td style="padding:8px; border: 1px solid #555;">Teen</td><td style="padding:8px; border: 1px solid #555;">17 x 22</td></tr>
    <tr><td style="padding:8px; border: 1px solid #555;">XS</td><td style="padding:8px; border: 1px solid #555;">18 x 24</td></tr>
    <tr><td style="padding:8px; border: 1px solid #555;">Small</td><td style="padding:8px; border: 1px solid #555;">19 x 25.5</td></tr>
    <tr><td style="padding:8px; border: 1px solid #555;">Medium</td><td style="padding:8px; border: 1px solid #555;">20 x 26</td></tr>
    <tr><td style="padding:8px; border: 1px solid #555;">Large</td><td style="padding:8px; border: 1px solid #555;">21 x 27</td></tr>
    <tr><td style="padding:8px; border: 1px solid #555;">XL</td><td style="padding:8px; border: 1px solid #555;">22.5 x 28.5</td></tr>
    <tr><td style="padding:8px; border: 1px solid #555;">XXL</td><td style="padding:8px; border: 1px solid #555;">23.5 x 22</td></tr>
    <tr><td style="padding:8px; border: 1px solid #555;">XXXL</td><td style="padding:8px; border: 1px solid #555;">24.5 x 29</td></tr>
  </tbody>
</table>
  `,
  'SCHOOL/OFFICE UNIFORMS': `
<h4 style="margin-top:10px;">Uniforms Size Chart</h4>
<table style="width:100%; border-collapse: collapse; margin-top: 10px; border-radius: 8px; overflow: hidden;">
  <thead style="background-color: #444; color: #fff;">
    <tr>
      <th style="padding: 10px; border: 1px solid #666;">Size</th>
      <th style="padding: 10px; border: 1px solid #666;">Top (in)</th>
      <th style="padding: 10px; border: 1px solid #666;">Pants (in)</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td style="padding:8px; border: 1px solid #555;">Small</td>
      <td style="padding:8px; border: 1px solid #555;">21 x 27</td>
      <td style="padding:8px; border: 1px solid #555;">32 x 40</td>
    </tr>
    <tr>
      <td style="padding:8px; border: 1px solid #555;">Medium</td>
      <td style="padding:8px; border: 1px solid #555;">22 x 28</td>
      <td style="padding:8px; border: 1px solid #555;">34 x 41</td>
    </tr>
    <tr>
      <td style="padding:8px; border: 1px solid #555;">Large</td>
      <td style="padding:8px; border: 1px solid #555;">23 x 29</td>
      <td style="padding:8px; border: 1px solid #555;">36 x 42</td>
    </tr>
    <tr>
      <td style="padding:8px; border: 1px solid #555;">XL</td>
      <td style="padding:8px; border: 1px solid #555;">24 x 30</td>
      <td style="padding:8px; border: 1px solid #555;">38 x 44</td>
    </tr>
    <tr>
      <td style="padding:8px; border: 1px solid #555;">XXL</td>
      <td style="padding:8px; border: 1px solid #555;">25 x 31</td>
      <td style="padding:8px; border: 1px solid #555;">40 x 46</td>
    </tr>
    <tr>
      <td style="padding:8px; border: 1px solid #555;">XXXL</td>
      <td style="padding:8px; border: 1px solid #555;">26 x 32</td>
      <td style="padding:8px; border: 1px solid #555;">42 x 48</td>
    </tr>
  </tbody>
</table>

  `,
'BASKETBALL UNIFORMS': `
<h4 style="margin-top:10px;">Basketball Uniform Size Chart</h4>
<table style="width:100%; border-collapse: collapse; margin-top: 10px; border-radius: 8px; overflow: hidden;">
  <thead style="background-color: #444; color: #fff;">
    <tr>
      <th style="padding: 10px; border: 1px solid #666;">Size</th>
      <th style="padding: 10px; border: 1px solid #666;">Top (in)</th>
      <th style="padding: 10px; border: 1px solid #666;">Shorts (in)</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td style="padding: 8px; border: 1px solid #555;">XS</td>
      <td style="padding: 8px; border: 1px solid #555;">19 x 28</td>
      <td style="padding: 8px; border: 1px solid #555;">40 x 18</td>
    </tr>
    <tr>
      <td style="padding: 8px; border: 1px solid #555;">Small</td>
      <td style="padding: 8px; border: 1px solid #555;">20 x 29</td>
      <td style="padding: 8px; border: 1px solid #555;">42 x 19</td>
    </tr>
    <tr>
      <td style="padding: 8px; border: 1px solid #555;">Medium</td>
      <td style="padding: 8px; border: 1px solid #555;">21 x 30</td>
      <td style="padding: 8px; border: 1px solid #555;">44 x 20</td>
    </tr>
    <tr>
      <td style="padding: 8px; border: 1px solid #555;">Large</td>
      <td style="padding: 8px; border: 1px solid #555;">22 x 31</td>
      <td style="padding: 8px; border: 1px solid #555;">46 x 21</td>
    </tr>
    <tr>
      <td style="padding: 8px; border: 1px solid #555;">XL</td>
      <td style="padding: 8px; border: 1px solid #555;">23 x 32</td>
      <td style="padding: 8px; border: 1px solid #555;">48 x 22</td>
    </tr>
    <tr>
      <td style="padding: 8px; border: 1px solid #555;">XXL</td>
      <td style="padding: 8px; border: 1px solid #555;">24 x 33</td>
      <td style="padding: 8px; border: 1px solid #555;">50 x 23</td>
    </tr>
    <tr>
      <td style="padding: 8px; border: 1px solid #555;">XXXL</td>
      <td style="padding: 8px; border: 1px solid #555;">25 x 34</td>
      <td style="padding: 8px; border: 1px solid #555;">52 x 24</td>
    </tr>
  </tbody>
</table>

`,
  'JOGGING PANTS': `
<h4 style="margin-top:10px;">Jogging Pants Size Chart</h4>
<table style="width:100%; border-collapse: collapse; margin-top: 10px; border-radius: 8px; overflow: hidden;">
  <thead style="background-color: #444; color: #fff;">
    <tr>
      <th style="padding: 10px; border: 1px solid #666;">Size Range</th>
      <th style="padding: 10px; border: 1px solid #666;">Waist (in)</th>
    </tr>
  </thead>
  <tbody>
    <tr><td style="padding:8px; border: 1px solid #555;">Small - Medium</td><td style="padding:8px; border: 1px solid #555;">24 - 44</td></tr>
    <tr><td style="padding:8px; border: 1px solid #555;">Large - XL</td><td style="padding:8px; border: 1px solid #555;">25 - 50</td></tr>
    <tr><td style="padding:8px; border: 1px solid #555;">XXL - XXXL</td><td style="padding:8px; border: 1px solid #555;">28 - 56</td></tr>
  </tbody>
</table>
  `,
  'OVERALLS': `
<h4 style="margin-top:10px;">Overalls Size Chart</h4>
<table style="width:100%; border-collapse: collapse; margin-top: 10px; border-radius: 8px; overflow: hidden;">
  <thead style="background-color: #444; color: #fff;">
    <tr>
      <th style="padding: 10px; border: 1px solid #666;">Size</th>
      <th style="padding: 10px; border: 1px solid #666;">Waist (in)</th>
    </tr>
  </thead>
  <tbody>
    <tr><td style="padding:8px; border: 1px solid #555;">XS</td><td style="padding:8px; border: 1px solid #555;">17</td></tr>
    <tr><td style="padding:8px; border: 1px solid #555;">Small</td><td style="padding:8px; border: 1px solid #555;">18</td></tr>
    <tr><td style="padding:8px; border: 1px solid #555;">Medium</td><td style="padding:8px; border: 1px solid #555;">19</td></tr>
    <tr><td style="padding:8px; border: 1px solid #555;">Large</td><td style="padding:8px; border: 1px solid #555;">20.5</td></tr>
    <tr><td style="padding:8px; border: 1px solid #555;">XL</td><td style="padding:8px; border: 1px solid #555;">22</td></tr>
    <tr><td style="padding:8px; border: 1px solid #555;">XXL</td><td style="padding:8px; border: 1px solid #555;">24</td></tr>
    <tr><td style="padding:8px; border: 1px solid #555;">XXXL</td><td style="padding:8px; border: 1px solid #555;">26</td></tr>
  </tbody>
</table>

  `,
  'JACKETS': `
<h4 style="margin-top:10px;">Jacket Size Chart</h4>
<table style="width:100%; border-collapse: collapse; margin-top: 10px; border-radius: 8px; overflow: hidden;">
  <thead style="background-color: #444; color: #fff;">
    <tr>
      <th style="padding: 10px; border: 1px solid #666;">Size</th>
      <th style="padding: 10px; border: 1px solid #666;">Dimensions (in)</th>
    </tr>
  </thead>
  <tbody>
    <tr><td style="padding:8px; border: 1px solid #555;">Kids Small</td><td style="padding:8px; border: 1px solid #555;">13 x 17</td></tr>
    <tr><td style="padding:8px; border: 1px solid #555;">Kids Medium</td><td style="padding:8px; border: 1px solid #555;">14 x 18</td></tr>
    <tr><td style="padding:8px; border: 1px solid #555;">Kids Large</td><td style="padding:8px; border: 1px solid #555;">15 x 19</td></tr>
    <tr><td style="padding:8px; border: 1px solid #555;">Kids XL</td><td style="padding:8px; border: 1px solid #555;">16 x 20</td></tr>
    <tr><td style="padding:8px; border: 1px solid #555;">Teen</td><td style="padding:8px; border: 1px solid #555;">17 x 22</td></tr>
    <tr><td style="padding:8px; border: 1px solid #555;">XS</td><td style="padding:8px; border: 1px solid #555;">18 x 24</td></tr>
    <tr><td style="padding:8px; border: 1px solid #555;">Small</td><td style="padding:8px; border: 1px solid #555;">19 x 25.5</td></tr>
    <tr><td style="padding:8px; border: 1px solid #555;">Medium</td><td style="padding:8px; border: 1px solid #555;">20 x 26</td></tr>
    <tr><td style="padding:8px; border: 1px solid #555;">Large</td><td style="padding:8px; border: 1px solid #555;">21 x 27</td></tr>
    <tr><td style="padding:8px; border: 1px solid #555;">XL</td><td style="padding:8px; border: 1px solid #555;">22.5 x 28.5</td></tr>
    <tr><td style="padding:8px; border: 1px solid #555;">XXL</td><td style="padding:8px; border: 1px solid #555;">23.5 x 22</td></tr>
    <tr><td style="padding:8px; border: 1px solid #555;">XXXL</td><td style="padding:8px; border: 1px solid #555;">24.5 x 29</td></tr>
  </tbody>
</table>

  `,
  'LONGSLEEVE': `
<h4 style="margin-top:10px;">Longsleeve Size Chart</h4>
<table style="width:100%; border-collapse: collapse; margin-top: 10px; border-radius: 8px; overflow: hidden;">
  <thead style="background-color: #444; color: #fff;">
    <tr>
      <th style="padding: 10px; border: 1px solid #666;">Size</th>
      <th style="padding: 10px; border: 1px solid #666;">Dimensions (in)</th>
    </tr>
  </thead>
  <tbody>
    <tr><td style="padding:8px; border: 1px solid #555;">Kids Small</td><td style="padding:8px; border: 1px solid #555;">13 x 17</td></tr>
    <tr><td style="padding:8px; border: 1px solid #555;">Kids Medium</td><td style="padding:8px; border: 1px solid #555;">14 x 18</td></tr>
    <tr><td style="padding:8px; border: 1px solid #555;">Kids Large</td><td style="padding:8px; border: 1px solid #555;">15 x 19</td></tr>
    <tr><td style="padding:8px; border: 1px solid #555;">Kids XL</td><td style="padding:8px; border: 1px solid #555;">16 x 20</td></tr>
    <tr><td style="padding:8px; border: 1px solid #555;">Teen</td><td style="padding:8px; border: 1px solid #555;">17 x 22</td></tr>
    <tr><td style="padding:8px; border: 1px solid #555;">XS</td><td style="padding:8px; border: 1px solid #555;">18 x 24</td></tr>
    <tr><td style="padding:8px; border: 1px solid #555;">Small</td><td style="padding:8px; border: 1px solid #555;">19 x 25.5</td></tr>
    <tr><td style="padding:8px; border: 1px solid #555;">Medium</td><td style="padding:8px; border: 1px solid #555;">20 x 26</td></tr>
    <tr><td style="padding:8px; border: 1px solid #555;">Large</td><td style="padding:8px; border: 1px solid #555;">21 x 27</td></tr>
    <tr><td style="padding:8px; border: 1px solid #555;">XL</td><td style="padding:8px; border: 1px solid #555;">22.5 x 28.5</td></tr>
    <tr><td style="padding:8px; border: 1px solid #555;">XXL</td><td style="padding:8px; border: 1px solid #555;">23.5 x 22</td></tr>
    <tr><td style="padding:8px; border: 1px solid #555;">XXXL</td><td style="padding:8px; border: 1px solid #555;">24.5 x 29</td></tr>
  </tbody>
</table>

  `,
  'CAP': `
<h4>Cap Size Chart</h4>
<table style="width:100%; border-collapse: collapse; margin-top: 10px;">
  <tr style="background-color:#444; color:#fff;">
    <th style="padding: 10px; border: 1px solid #666;">Size</th>
    <th style="padding: 10px; border: 1px solid #666;">Details</th>
  </tr>
  <tr>
    <td style="padding: 8px; border: 1px solid #555;">One Size</td>
    <td style="padding: 8px; border: 1px solid #555;">Adjustable – fits most</td>
  </tr>
</table>
  `,
  'BEDROOM SLIPPERS': `
<h4>Slippers Size Chart (Euro Sizes)</h4>
<table style="width:100%; border-collapse: collapse; margin-top: 10px;">
  <tr style="background-color:#444; color:#fff;">
    <th style="padding: 10px; border: 1px solid #666;">Euro Size</th>
  </tr>
  <tr><td style="padding: 8px; border: 1px solid #555;">EU 36 - 37</td></tr>
  <tr><td style="padding: 8px; border: 1px solid #555;">EU 38 - 39</td></tr>
  <tr><td style="padding: 8px; border: 1px solid #555;">EU 40 - 41</td></tr>
  <tr><td style="padding: 8px; border: 1px solid #555;">EU 42 - 43</td></tr>
  <tr><td style="padding: 8px; border: 1px solid #555;">EU 44 - 45</td></tr>
</table>

  `,
  'THROWPILLOWS': `
<h4>Throw Pillow Sizes</h4>
<table style="width:100%; border-collapse: collapse; margin-top: 10px;">
  <tr style="background-color:#444; color:#fff;">
    <th style="padding: 10px; border: 1px solid #666;">Size</th>
    <th style="padding: 10px; border: 1px solid #666;">Dimensions</th>
  </tr>
  <tr><td style="padding: 8px; border: 1px solid #555;">Small</td><td style="padding: 8px; border: 1px solid #555;">12in x 12in</td></tr>
  <tr><td style="padding: 8px; border: 1px solid #555;">Medium</td><td style="padding: 8px; border: 1px solid #555;">14in x 14in</td></tr>
  <tr><td style="padding: 8px; border: 1px solid #555;">Large</td><td style="padding: 8px; border: 1px solid #555;">16in x 16in</td></tr>
</table>

  `,
  'BEDDINGS': `
<h4>Bedding Sizes</h4>
<table style="width:100%; border-collapse: collapse; margin-top: 10px;">
  <tr style="background-color:#444; color:#fff;">
    <th style="padding: 10px; border: 1px solid #666;">Type</th>
    <th style="padding: 10px; border: 1px solid #666;">Dimensions</th>
  </tr>
  <tr><td style="padding: 8px; border: 1px solid #555;">Single</td><td style="padding: 8px; border: 1px solid #555;">36in x 75in</td></tr>
  <tr><td style="padding: 8px; border: 1px solid #555;">Semi-single</td><td style="padding: 8px; border: 1px solid #555;">42in x 75in</td></tr>
  <tr><td style="padding: 8px; border: 1px solid #555;">Twin</td><td style="padding: 8px; border: 1px solid #555;">39in x 75in</td></tr>
  <tr><td style="padding: 8px; border: 1px solid #555;">Full</td><td style="padding: 8px; border: 1px solid #555;">54in x 75in</td></tr>
  <tr><td style="padding: 8px; border: 1px solid #555;">Queen</td><td style="padding: 8px; border: 1px solid #555;">60in x 80in</td></tr>
  <tr><td style="padding: 8px; border: 1px solid #555;">King</td><td style="padding: 8px; border: 1px solid #555;">76in x 80in</td></tr>
</table>

  `,
  'CANVAS BAGS & POUCHES': `
<h4>Canvas Bag & Pouch Sizes</h4>
<table style="width:100%; border-collapse: collapse; margin-top: 10px;">
  <tr style="background-color:#444; color:#fff;">
    <th style="padding: 10px; border: 1px solid #666;">Size</th>
    <th style="padding: 10px; border: 1px solid #666;">Dimensions</th>
  </tr>
  <tr><td style="padding: 8px; border: 1px solid #555;">Small</td><td style="padding: 8px; border: 1px solid #555;">10in x 12in</td></tr>
  <tr><td style="padding: 8px; border: 1px solid #555;">Medium</td><td style="padding: 8px; border: 1px solid #555;">12in x 14in</td></tr>
  <tr><td style="padding: 8px; border: 1px solid #555;">Large</td><td style="padding: 8px; border: 1px solid #555;">14in x 16in</td></tr>
</table>

  `,
  'NON-WOVEN BAGS': `
<h4>Non-Woven Bag Sizes</h4>
<table style="width:100%; border-collapse: collapse; margin-top: 10px;">
  <tr style="background-color:#444; color:#fff;">
    <th style="padding: 10px; border: 1px solid #666;">Size</th>
    <th style="padding: 10px; border: 1px solid #666;">Dimensions</th>
  </tr>
  <tr><td style="padding: 8px; border: 1px solid #555;">Small</td><td style="padding: 8px; border: 1px solid #555;">10in x 12in</td></tr>
  <tr><td style="padding: 8px; border: 1px solid #555;">Medium</td><td style="padding: 8px; border: 1px solid #555;">12in x 14in</td></tr>
  <tr><td style="padding: 8px; border: 1px solid #555;">Large</td><td style="padding: 8px; border: 1px solid #555;">14in x 16in</td></tr>
</table>

  `,
  'KRAFT PAPER BAGS': `
<h4>Kraft Paper Bag Sizes</h4>
<table style="width:100%; border-collapse: collapse; margin-top: 10px;">
  <tr style="background-color:#444; color:#fff;">
    <th style="padding: 10px; border: 1px solid #666;">Size</th>
    <th style="padding: 10px; border: 1px solid #666;">Dimensions</th>
  </tr>
  <tr><td style="padding: 8px; border: 1px solid #555;">Small</td><td style="padding: 8px; border: 1px solid #555;">10in x 12in</td></tr>
  <tr><td style="padding: 8px; border: 1px solid #555;">Medium</td><td style="padding: 8px; border: 1px solid #555;">12in x 14in</td></tr>
  <tr><td style="padding: 8px; border: 1px solid #555;">Large</td><td style="padding: 8px; border: 1px solid #555;">14in x 16in</td></tr>
</table>

  `,
  'ID LACE/LANYARDS': `
<h4>Lanyard Size</h4>
<table style="width:100%; border-collapse: collapse; margin-top: 10px;">
  <tr style="background-color:#444; color:#fff;">
    <th style="padding: 10px; border: 1px solid #666;">Type</th>
    <th style="padding: 10px; border: 1px solid #666;">Length</th>
  </tr>
  <tr>
    <td style="padding: 8px; border: 1px solid #555;">Standard</td>
    <td style="padding: 8px; border: 1px solid #555;">18 inches</td>
  </tr>
</table>
  `,
};

  const paymentSelect = document.getElementById("popup_payment_method");
  const gcashBox = document.getElementById("gcash-upload-box");

  paymentSelect.addEventListener("change", function () {
    if (this.value === "gcash" || this.value === "chinabank") {
      gcashBox.style.display = "block";
    } else {
      gcashBox.style.display = "none";
      document.getElementById("popup_payment_proof").value = "";
      document.getElementById("gcash_preview").style.display = "none";
    }
  });

  document.getElementById("popup_payment_proof").addEventListener("change", function () {
    const file = this.files[0];
    const preview = document.getElementById("gcash_preview");
    if (file) {
      const reader = new FileReader();
      reader.onload = function (e) {
        preview.src = e.target.result;
        preview.style.display = "block";
      };
      reader.readAsDataURL(file);
    } else {
      preview.style.display = "none";
    }
  });
document.addEventListener("DOMContentLoaded", function () {
  const paymentMethod = document.getElementById("popup_payment_method");
  const paymentBox = document.getElementById("payment-upload-box");
  const gcashInfo = document.getElementById("gcash-instructions");
  const bankInfo = document.getElementById("chinabank-instructions");
  const proofInput = document.getElementById("popup_payment_proof");
  const preview = document.getElementById("payment_preview");

  paymentMethod.addEventListener("change", function () {
    paymentBox.style.display = "none";
    gcashInfo.style.display = "none";
    bankInfo.style.display = "none";
    preview.style.display = "none";
    proofInput.value = "";

    if (this.value === "gcash") {
      paymentBox.style.display = "block";
      gcashInfo.style.display = "block";
    } else if (this.value === "chinabank") {
      paymentBox.style.display = "block";
      bankInfo.style.display = "block";
    }
  });

  proofInput.addEventListener("change", function () {
    const file = this.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = function (e) {
        preview.src = e.target.result;
        preview.style.display = "block";
      };
      reader.readAsDataURL(file);
    }
  });
});


</script>

</body>
</html>