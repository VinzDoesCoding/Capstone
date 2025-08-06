DROP TABLE IF EXISTS orders;

CREATE TABLE orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_id VARCHAR(20),
  customer_name VARCHAR(100),
  items TEXT,
  method ENUM('DTF', 'Silkscreen'),
  quantity INT DEFAULT 1,
  order_time DATETIME,
  pickup_time DATETIME,
  status VARCHAR(50),
  priority INT DEFAULT 0,
  customer_id VARCHAR(50)
);

INSERT INTO orders (order_id, customer_name, items, method, quantity, order_time, pickup_time, status, customer_id)
VALUES 