<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Track Order</title>
  <style>
    body {
      margin: 0;
      font-family: 'Arial', sans-serif;
      background-image: url(bg.png);background-size: cover; background-position: center;;
      color: white;
    }

main {
  flex: 1;
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 40px 0;
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

.track-wrapper {
  background-color: rgba(0, 0, 0, 0.7);
  padding: 30px;
  border-radius: 10px;
  width: 90%;
  max-width: 500px;
  box-shadow: 0 0 15px rgba(0,0,0,0.3);
  text-align: center;
  margin-top: 120px; /* Push content down below header */
}


    h2 {
      margin-bottom: 20px;
    }

    form {
      margin-bottom: 20px;
    }

    input[type="text"] {
      padding: 10px;
      width: 70%;
      border-radius: 6px;
      border: none;
      margin-right: 10px;
    }

    button {
      padding: 10px 16px;
      border: none;
      border-radius: 6px;
      background-color: #28a745;
      color: white;
      cursor: pointer;
    }

    .receipt-container {
      background: #fff;
      color: #000;
      max-width: 400px;
      margin: 0 auto;
      padding: 20px 30px;
      border-radius: 10px;
      box-shadow: 0 0 15px rgba(0,0,0,0.3);
      font-family: 'Courier New', Courier, monospace;
      line-height: 1.5;
      display: none;
    }

    .receipt-container h3 {
      margin-top: 0;
      color: #444;
      text-align: center;
      border-bottom: 2px dashed #999;
      padding-bottom: 10px;
      margin-bottom: 20px;
    }

    .receipt-container p {
      margin: 6px 0;
    }

    .receipt-container .label {
      font-weight: bold;
    }

    .receipt-container a {
      color: #007bff;
      text-decoration: none;
    }

    .receipt-container a:hover {
      text-decoration: underline;
    }
    
  </style>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
</head>
<body>

<header class="top-bar">
  <div class="top-bar-content">
    <span class="logo"><img src="logo.jpg" alt="Logo" style="height: 30px; vertical-align: middle; margin-right: 8px;">JIMACA GRAPHICS</span>
    <div style="display: flex; gap: 1rem;">
      <a href="Home.html" class="home-link">Home</a>
      <a href="ordersite.php" class="home-link">Shop</a>
      <a href="about.html" class="home-link">About us!</a>
    </div>
  </div>
</header>
<main>
  <div class="track-wrapper">
    <h2>📦 Track Your Order</h2>
    <form id="track-form">
      <input type="text" id="track-id" placeholder="Enter your Order ID..." required>
      <button type="submit">Track</button>
    </form>

    <div id="order-status" class="receipt-container"></div>
  </div>
</main>
  <script>
    document.getElementById("track-form").addEventListener("submit", function (e) {
      e.preventDefault();
      const orderId = document.getElementById("track-id").value.trim();

      fetch("get_order_status.php?order_id=" + encodeURIComponent(orderId))
        .then(res => res.json())
        .then(data => {
          const statusBox = document.getElementById("order-status");

          if (data.error) {
            statusBox.style.display = "block";
            statusBox.innerHTML = `<p style="color:red;">${data.error}</p>`;
            return;
          }

          statusBox.style.display = "block";
          statusBox.innerHTML = `
            <h3>🧾 Order Receipt</h3>
            <p><span class="label">Order ID:</span> ${data.order_id}</p>
            <p><span class="label">Status:</span> ${data.status}</p>
            <p><span class="label">Customer:</span> ${data.customer_name}</p>
            <p><span class="label">Items:</span> ${data.items}</p>
            <p><span class="label">Pickup Time:</span> ${data.pickup_time}</p>
            <p><span class="label">Payment Method:</span> ${data.payment_method.toUpperCase()}</p>
            <p><span class="label">Order Date:</span> ${data.order_time}</p>
            ${
              data.design_file
                ? `<p><span class="label">Design File:</span> <a href="${data.design_file}" target="_blank">📎 View File</a></p>`
                : `<p><span class="label">Design File:</span> None</p>`
            }
            <div style="text-align:center; margin-top: 20px;">
            <button onclick="window.print()" style="margin-right: 10px;">🖨️ Print</button>
            <button onclick="downloadReceipt()">📥 Download PDF</button>
            </div>
          `;
        })
        .catch(() => {
          document.getElementById("order-status").style.display = "block";
          document.getElementById("order-status").innerHTML = `<p style="color:red;">Unable to fetch order.</p>`;
        });
    });
    async function downloadReceipt() {
    const { jsPDF } = window.jspdf;
    const receipt = document.querySelector("#order-status");
    const pdf = new jsPDF();

    // Get the text content
    let lines = receipt.innerText.split("\n").filter(line => line.trim() !== "");

    let y = 10;
    lines.forEach((line) => {
      pdf.text(line.trim(), 10, y);
      y += 10;
    });

    pdf.save("order_receipt.pdf");
  }
  </script>

</body>
</html>
