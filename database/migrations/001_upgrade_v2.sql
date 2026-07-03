USE deukhuri_shop;

-- ==========================
-- ADMINS TABLE
-- ==========================

ALTER TABLE admins
ADD COLUMN full_name VARCHAR(150) NULL AFTER username,
ADD COLUMN email VARCHAR(150) NULL AFTER full_name,
ADD COLUMN role ENUM('Admin','Staff') NOT NULL DEFAULT 'Staff' AFTER password,
ADD COLUMN status ENUM('Active','Inactive') NOT NULL DEFAULT 'Active' AFTER role,
ADD COLUMN last_login DATETIME NULL AFTER status,
ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP AFTER last_login,
ADD COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER created_at;


-- ==========================
-- CATEGORIES TABLE
-- ==========================

ALTER TABLE categories
ADD COLUMN slug VARCHAR(150) NULL AFTER name,
ADD COLUMN image VARCHAR(255) NULL AFTER slug,
ADD COLUMN status ENUM('Active','Inactive') DEFAULT 'Active' AFTER image,
ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP AFTER status,
ADD COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER created_at;


-- ==========================
-- BRANDS TABLE
-- ==========================

CREATE TABLE IF NOT EXISTS brands (

    id INT AUTO_INCREMENT PRIMARY KEY,

    name VARCHAR(120) NOT NULL UNIQUE,

    logo VARCHAR(255) NULL,

    status ENUM('Active','Inactive') DEFAULT 'Active',

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP

);


-- ==========================
-- PRODUCTS TABLE
-- ==========================

ALTER TABLE products

ADD COLUMN brand_id INT NULL AFTER category_id,

ADD COLUMN slug VARCHAR(255) NULL AFTER name,

ADD COLUMN old_price DECIMAL(10,2) NULL AFTER price,

ADD COLUMN featured BOOLEAN DEFAULT FALSE AFTER stock,

ADD COLUMN status ENUM(
'Active',
'Out of Stock',
'Hidden',
'Discontinued'
) DEFAULT 'Active' AFTER featured,

ADD COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
ON UPDATE CURRENT_TIMESTAMP AFTER created_at,

ADD COLUMN created_by INT NULL AFTER updated_at;


ALTER TABLE products
ADD CONSTRAINT fk_brand
FOREIGN KEY (brand_id)
REFERENCES brands(id)
ON DELETE SET NULL;


ALTER TABLE products
ADD CONSTRAINT fk_created_by
FOREIGN KEY (created_by)
REFERENCES admins(id)
ON DELETE SET NULL;



-- ==========================
-- PRODUCT IMAGES
-- ==========================

ALTER TABLE product_images

ADD COLUMN is_primary BOOLEAN DEFAULT FALSE AFTER image_path,

ADD COLUMN sort_order INT DEFAULT 0 AFTER is_primary,

ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP AFTER sort_order;



-- ==========================
-- CONTACT MESSAGES
-- ==========================

CREATE TABLE IF NOT EXISTS contact_messages (

id INT AUTO_INCREMENT PRIMARY KEY,

name VARCHAR(150),

email VARCHAR(150),

phone VARCHAR(30),

subject VARCHAR(200),

message TEXT,

status ENUM(
'Unread',
'Read',
'Replied'
) DEFAULT 'Unread',

created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP

);



-- ==========================
-- SETTINGS
-- ==========================

CREATE TABLE IF NOT EXISTS settings (

id INT AUTO_INCREMENT PRIMARY KEY,

shop_name VARCHAR(150),

email VARCHAR(150),

phone VARCHAR(50),

facebook VARCHAR(255),

instagram VARCHAR(255),

whatsapp VARCHAR(50),

address TEXT,

google_map TEXT,

created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
ON UPDATE CURRENT_TIMESTAMP

);



-- ==========================
-- ACTIVITY LOGS
-- ==========================

CREATE TABLE IF NOT EXISTS activity_logs (

id INT AUTO_INCREMENT PRIMARY KEY,

admin_id INT,

action VARCHAR(255),

description TEXT,

created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

FOREIGN KEY (admin_id)
REFERENCES admins(id)
ON DELETE SET NULL

);