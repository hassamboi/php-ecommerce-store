-- IF THE DATABASE ALREADY EXISTS, DELETE THAT DATABASE AND USE THIS ONE
DROP DATABASE IF EXISTS ecommerce_website;
CREATE DATABASE IF NOT EXISTS ecommerce_website;
USE ecommerce_website;

-- PRODUCTS TABLE CREATION
CREATE TABLE products(
    product_id INT PRIMARY KEY AUTO_INCREMENT,
    product_name VARCHAR(255) NOT NULL,
    product_desc VARCHAR(255) NOT NULL,
    product_price FLOAT(2) NOT NULL,
    product_quantity INT NOT NULL,
    release_date DATE NOT NULL,
    product_img VARCHAR(255) NOT NULL
);

-- CATEGORY TABLE CREATION
CREATE TABLE category(
    category_id INT PRIMARY KEY,
    category_name VARCHAR(255) NOT NULL,
    category_desc VARCHAR(255) NOT NULL
);

-- PRODUCT_CATEGORY TABLE CREATION
CREATE TABLE products_category(
    product_id INT NOT NULL,
    category_id INT NOT NULL,
    FOREIGN KEY(product_id) REFERENCES products(product_id),
    FOREIGN KEY(category_id) REFERENCES category(category_id)
);

-- THE COMBINATION OF PRODUCT_ID AND CATEGORY_ID WILL ALWAYS BE UNIQUE
ALTER TABLE products_category ADD UNIQUE(product_id, category_id);

-- USER TABLE CREATION 
CREATE TABLE user(
    user_id INT PRIMARY KEY AUTO_INCREMENT,
    fname VARCHAR(50) NOT NULL,
    lname VARCHAR(50) NOT NULL,
    email VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    is_admin BOOLEAN NOT NULL
);

ALTER TABLE user ADD UNIQUE(email);

-- ADDRESS TABLE CREATION
CREATE TABLE address(
    street_add VARCHAR(255) NOT NULL,
    city VARCHAR(30) NOT NULL,
    state VARCHAR(30) NOT NULL,
    zip_code INT NOT NULL,
    user_id INT NOT NULL,
    FOREIGN KEY(user_id) REFERENCES user(user_id)
);

-- A USER'S ADDRESS WILL BE UNIQUE (1 ADDRESS FOR EVERY USER - CAN BE UPDATED)
ALTER TABLE address ADD UNIQUE(user_id);

-- BILLING INFORMATION TABLE CREATION
CREATE TABLE billing_info(
    pay_method VARCHAR(50) NOT NULL,
    card_no CHAR(16) NOT NULL,
    exp_date CHAR(5) NOT NULL,
    cvv CHAR(3) NOT NULL,
    user_id INT NOT NULL,
    FOREIGN KEY(user_id) REFERENCES user(user_id)
);

-- A BILLING INFORMATION WILL BE UNIQUE FOR A USER (1 BILLING INFO FOR EVERY USER - CAN BE UPDATED)
ALTER TABLE billing_info ADD UNIQUE(user_id);

-- CART ITEMS TABLE CREATION
CREATE TABLE cart_items(
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    FOREIGN KEY(user_id) REFERENCES user(user_id),
    FOREIGN KEY(product_id) REFERENCES products(product_id)
);

-- THE COMBINATION OF A PRODUCT AND THE USER WILL BE UNIQUE
ALTER TABLE cart_items ADD UNIQUE(user_id, product_id);

-- ORDER DETAILS TABLE CREATION
CREATE TABLE order_details(
    order_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    total_price FLOAT(2) NOT NULL,
    order_notes VARCHAR(255),
    created_at DATETIME NOT NULL,
    modified_at DATETIME,
    FOREIGN KEY(user_id) REFERENCES user(user_id)
);

-- ORDER ITEMS TABLE CREATION
CREATE TABLE order_items(
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    FOREIGN KEY(order_id) REFERENCES order_details(order_id) 
);

-- THE COMBINATION OF ORDER ID AND PRODUCT ID WILL BE UNIQUE FOR EVERY ORDER ITEM
ALTER TABLE order_items ADD UNIQUE(order_id, product_id);


-- CREATING TRIGGERS FOR PRODUCT QUANTITY TO BE DECREASED AND INCREASED ON ORDER CREATION AND DELETION RESPECTIVELY
DELIMITER $$

CREATE TRIGGER decrease_qty_on_order_creation
AFTER INSERT
ON ecommerce_website.order_items FOR EACH ROW
BEGIN
    UPDATE products
    SET product_quantity = product_quantity - new.quantity
    WHERE product_id = new.product_id;
END $$

DELIMITER ;

DELIMITER $$

CREATE TRIGGER increase_qty_on_order_deletion
AFTER DELETE
ON ecommerce_website.order_items FOR EACH ROW
BEGIN
    UPDATE products
    SET product_quantity = product_quantity + old.quantity
    WHERE product_id = old.product_id;
END $$

DELIMITER ;

-- PROCEDURE USED TO INSERT DATA INTO THE CART OF A USER
DELIMITER $$

CREATE PROCEDURE insert_into_cart(
    IN pUserId INT, 
    IN pProductId INT,
    IN pQuantity INT)
BEGIN
    DECLARE available INT DEFAULT 0;

    SELECT product_id 
    INTO available
    FROM cart_items
    WHERE product_id = pProductId
    AND user_id = pUserId;

    IF available > 0 THEN
        UPDATE cart_items
        SET quantity = quantity + pQuantity
        WHERE product_id = pProductId;
    ELSE
        INSERT INTO cart_items(user_id, product_id, quantity)
        VALUES (pUserId, pProductId, pQuantity);
    END IF;
END $$

DELIMITER ;

-- PROCEDURE TO UPDATE USER ADDRESS
DELIMITER $$

CREATE PROCEDURE update_user_address(
    IN pStreetAdd VARCHAR(255), 
    IN pCity VARCHAR(30),
    IN pState VARCHAR(30),
    IN pZipCode INT,
    IN pUserId INT)
BEGIN
    DECLARE available INT DEFAULT 0;

    SELECT user_id 
    INTO available
    FROM address
    WHERE user_id = pUserId;

    IF available > 0 THEN
        UPDATE address SET
        street_add = pStreetAdd, city = pCity, state= pState, zip_code= pZipCode, user_id = pUserId
        WHERE user_id = pUserId;
    ELSE
        INSERT INTO address(street_add, city, state, zip_code, user_id)
        VALUES (pStreetAdd, pCity, pState, pZipCode, pUserId);
    END IF;
END $$

DELIMITER ;

-- PROCEDURE TO UPDATE USER'S BILLING INFO
DELIMITER $$

CREATE PROCEDURE update_user_billing_info(
    IN pPayMethod VARCHAR(50), 
    IN pCardNo CHAR(16),
    IN pExpDate CHAR(5),
    IN pCVV CHAR(3),
    IN pUserId INT)
BEGIN
    DECLARE available INT DEFAULT 0;

    SELECT user_id 
    INTO available
    FROM billing_info
    WHERE user_id = pUserId;

    IF available > 0 THEN
        UPDATE billing_info SET
        pay_method = pPayMethod, card_no = pCardNo, exp_date = pExpDate, cvv = pCVV, user_id = pUserId
        WHERE user_id = pUserId;
    ELSE
        INSERT INTO billing_info(pay_method, card_no, exp_date, cvv, user_id)
        VALUES (pPayMethod, pCardNo, pExpDate, pCVV, pUserId);
    END IF;
END $$

DELIMITER ;

-- ################# INSERTING DUMMY DATA AND AN ADMIN #######################
INSERT INTO products(product_id, product_name, product_desc, product_price, product_quantity, release_date, product_img) VALUES
(1, 'Basic t-shirt', 'product description here', 7.99, 255, NOW(), 'item1c.jpg'),
(2, 'Black glasses', 'product description here', 8.99, 255, NOW(),'item2c-min.jpg'),
(3, 'Denim shorts', 'product description here', 12.99, 255, NOW(),'item3-min.jpg'),
(4, 'Fantasy pants', 'product description here', 15.99, 255, NOW(),'item4c-min.jpg'),
(5, 'Fantasy t-shirt', 'product description here', 9.99, 255, NOW(),'item5c-min.jpg'),
(6, 'Havanna shirt', 'product description here', 14.99, 255, NOW(),'item6c-min.jpg'),
(7, 'Season shoes', 'product description here', 19.99, 255, NOW(),'item7c-min.jpg'),
(8, 'Shoulder bag', 'product description here', 25.99, 255, NOW(),'item8c-min.jpg'),
(9, 'Simple hat', 'product description here', 8.99, 255, NOW(),'item9c-min.jpg'),
(10, 'Striped watch', 'product description here', 8.99, 255, NOW(),'item10c-min.jpg');

INSERT INTO category(category_id, category_name, category_desc) VALUES 
(1,'clothings','You are a clothing category'),
(2,'shirts','You are a shirts category'),
(3,'shorts','You are a shorts category'),
(4,'shoes','You are a shoes category'),
(5,'accessories','You are a accessories category');

INSERT INTO products_category(product_id, category_id) VALUES
(1,1), (1,2), (2,5), (3,1), (3,3), (4,1), (4,3),(5,1),(5,2),(6,1),(6,2),
(7,4), (8,5), (9,5), (10,5);

-- CREATING AN ADMIN USER IN THE BEGINNING
INSERT INTO user(user_id, fname, lname, email, password, is_admin) VALUES
(1,"admin", "admin", "admin@admin.com", "21232f297a57a5a743894a0e4a801fc3", 1);