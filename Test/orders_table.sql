CREATE TABLE IF NOT EXISTS orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_id VARCHAR(20),
  customer_name VARCHAR(100),
  items TEXT,
  method ENUM('DTF', 'Silkscreen'),
  quantity INT DEFAULT 1,
  order_time DATETIME,
  pickup_time DATETIME,
  status VARCHAR(50),
  priority INT DEFAULT 0
);

INSERT INTO orders (order_id, customer_name, items, method, quantity, order_time, pickup_time, status)
VALUES 
('ORD001', 'Vinze Valino', 'Shirt - White, Large', 'Silkscreen', 15, NOW(), DATE_ADD(NOW(), INTERVAL 2 HOUR), 'In Queue'),
('ORD002', 'Charlene Sagun', 'Cap - Black', 'DTF', 5, NOW(), DATE_ADD(NOW(), INTERVAL 1 HOUR), 'In Queue'),
('ORD003', 'Jim Joseph', 'Sash - Blue', 'DTF', 10, NOW(), DATE_ADD(NOW(), INTERVAL 3 HOUR), 'In Queue');