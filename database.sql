CREATE TABLE users (
 id INT AUTO_INCREMENT PRIMARY KEY,
 name VARCHAR(100),
 email VARCHAR(100) UNIQUE,
 password VARCHAR(255),
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE admins (
 id INT AUTO_INCREMENT PRIMARY KEY,
 username VARCHAR(50),
 password VARCHAR(255)
);

CREATE TABLE categories (
 id INT AUTO_INCREMENT PRIMARY KEY,
 name VARCHAR(100)
);

CREATE TABLE products (
 id INT AUTO_INCREMENT PRIMARY KEY,
 title VARCHAR(255),
 description TEXT,
 price DECIMAL(10,2),
 image VARCHAR(255),
 demo_link VARCHAR(255),
 category_id INT,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
);

CREATE TABLE cart (
 id INT AUTO_INCREMENT PRIMARY KEY,
 user_id INT,
 product_id INT,
 quantity INT,
 FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
 FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

CREATE TABLE orders (
 id INT AUTO_INCREMENT PRIMARY KEY,
 user_id INT,
 total DECIMAL(10,2),
 status VARCHAR(50),
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE order_items (
 id INT AUTO_INCREMENT PRIMARY KEY,
 order_id INT,
 product_id INT,
 quantity INT,
 price DECIMAL(10,2),
 FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
 FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

CREATE TABLE contact_messages (
 id INT AUTO_INCREMENT PRIMARY KEY,
 name VARCHAR(100),
 email VARCHAR(100),
 message TEXT,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE reviews (
 id INT AUTO_INCREMENT PRIMARY KEY,
 user_id INT,
 product_id INT,
 rating INT,
 comment TEXT,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
 FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

INSERT INTO admins (username, password) VALUES ('kashan', '$2y$10$1z8G1FB9FQHZXD9ciV06f..5s7/0Qv6rh2r7d9MIkAbx86jnK9Jf2');
INSERT INTO categories (name) VALUES ('Business'), ('Portfolio'), ('Store'), ('Blog');
INSERT INTO products (title, description, price, image, demo_link, category_id) VALUES
('Business Pro Site', 'Modern business website template with service pages and contact forms.', 199.00, 'images/business.jpg', 'https://example.com/demo/business', 1),
('Freelancer Portfolio', 'Personal portfolio for designers and developers.', 89.00, 'images/portfolio.jpg', 'https://example.com/demo/portfolio', 2),
('Ecommerce Starter', 'Fast-loading online store with product grids and checkout UI.', 349.00, 'images/store.jpg', 'https://example.com/demo/store', 3),
('SaaS Landing Page', 'Conversion-focused startup landing page with animations.', 129.00, 'images/landing.jpg', 'https://example.com/demo/landing', 1);
