CREATE TABLE orders (
    order_id INT(11) NOT NULL AUTO_INCREMENT,
    user_id INT(11) NOT NULL,
    created datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
    primary key(order_id)
);

CREATE TABLE order_products (
    order_id INT(11) NOT NULL,
    item_id INT(11) NOT NULL,
    price INT(11) NOT NULL,
    primary key(item_id)
);

CREATE TABLE order_details (
    order_id INT(11) NOT NULL,
    item_id INT(11) NOT NULL,
    amount INT(11) NOT NULL,
    primary key(order_id)
);