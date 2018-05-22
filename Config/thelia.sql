
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- address_socolissimo
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `address_socolissimo`;

CREATE TABLE `address_socolissimo`
(
    `id` INTEGER NOT NULL,
    `title_id` INTEGER NOT NULL,
    `company` VARCHAR(255),
    `firstname` VARCHAR(255) NOT NULL,
    `lastname` VARCHAR(255) NOT NULL,
    `address1` VARCHAR(255) NOT NULL,
    `address2` VARCHAR(255) NOT NULL,
    `address3` VARCHAR(255) NOT NULL,
    `zipcode` VARCHAR(10) NOT NULL,
    `city` VARCHAR(255) NOT NULL,
    `country_id` INTEGER NOT NULL,
    `code` VARCHAR(10) NOT NULL,
    `type` VARCHAR(10) NOT NULL,
    `cellphone` VARCHAR(20),
    PRIMARY KEY (`id`),
    INDEX `FI_address_socolissimo_customer_title_id` (`title_id`),
    INDEX `FI_address_socolissimo_country_id` (`country_id`),
    CONSTRAINT `fk_address_socolissimo_customer_title_id`
        FOREIGN KEY (`title_id`)
        REFERENCES `customer_title` (`id`)
        ON UPDATE RESTRICT
        ON DELETE RESTRICT,
    CONSTRAINT `fk_address_socolissimo_country_id`
        FOREIGN KEY (`country_id`)
        REFERENCES `country` (`id`)
        ON UPDATE RESTRICT
        ON DELETE RESTRICT
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- order_address_socolissimo
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `order_address_socolissimo`;

CREATE TABLE `order_address_socolissimo`
(
    `id` INTEGER NOT NULL,
    `code` VARCHAR(10) NOT NULL,
    `type` VARCHAR(10) NOT NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_order_address_socolissimo_order_address_id`
        FOREIGN KEY (`id`)
        REFERENCES `order_address` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- socolissimo_delivery_mode
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `socolissimo_delivery_mode`;

CREATE TABLE `socolissimo_delivery_mode`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `title` VARCHAR(255),
    `code` VARCHAR(55) NOT NULL,
    `freeshipping_active` TINYINT(1),
    `freeshipping_from` FLOAT,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- socolissimo_price
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `socolissimo_price`;

CREATE TABLE `socolissimo_price`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `area_id` INTEGER NOT NULL,
    `delivery_mode_id` INTEGER NOT NULL,
    `weight_max` FLOAT,
    `price_max` FLOAT,
    `franco_min_price` FLOAT,
    `price` FLOAT NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `FI_socolissimo_price_area_id` (`area_id`),
    INDEX `FI_socolissimo_price_delivery_mode_id` (`delivery_mode_id`),
    CONSTRAINT `fk_socolissimo_price_area_id`
        FOREIGN KEY (`area_id`)
        REFERENCES `area` (`id`)
        ON UPDATE RESTRICT
        ON DELETE RESTRICT,
    CONSTRAINT `fk_socolissimo_price_delivery_mode_id`
        FOREIGN KEY (`delivery_mode_id`)
        REFERENCES `socolissimo_delivery_mode` (`id`)
        ON UPDATE RESTRICT
        ON DELETE RESTRICT
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- socolissimo_area_freeshipping_dom
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `socolissimo_area_freeshipping_dom`;

CREATE TABLE `socolissimo_area_freeshipping_dom`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `area_id` INTEGER NOT NULL,
    `delivery_mode_id` INTEGER NOT NULL,
    `cart_amount` DECIMAL(16,6) DEFAULT 0.000000,
    PRIMARY KEY (`id`),
    INDEX `FI_socolissimo_area_freeshipping_dom_area_id` (`area_id`),
    INDEX `FI_socolissimo_area_freeshipping_dom_delivery_mode_id` (`delivery_mode_id`),
    CONSTRAINT `fk_socolissimo_area_freeshipping_dom_area_id`
        FOREIGN KEY (`area_id`)
        REFERENCES `area` (`id`)
        ON UPDATE RESTRICT
        ON DELETE RESTRICT,
    CONSTRAINT `fk_socolissimo_area_freeshipping_dom_delivery_mode_id`
        FOREIGN KEY (`delivery_mode_id`)
        REFERENCES `socolissimo_delivery_mode` (`id`)
        ON UPDATE RESTRICT
        ON DELETE RESTRICT
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- socolissimo_area_freeshipping_pr
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `socolissimo_area_freeshipping_pr`;

CREATE TABLE `socolissimo_area_freeshipping_pr`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `area_id` INTEGER NOT NULL,
    `delivery_mode_id` INTEGER NOT NULL,
    `cart_amount` DECIMAL(16,6) DEFAULT 0.000000,
    PRIMARY KEY (`id`),
    INDEX `FI_socolissimo_area_freeshipping_pr_area_id` (`area_id`),
    INDEX `FI_socolissimo_area_freeshipping_pr_delivery_mode_id` (`delivery_mode_id`),
    CONSTRAINT `fk_socolissimo_area_freeshipping_pr_area_id`
        FOREIGN KEY (`area_id`)
        REFERENCES `area` (`id`)
        ON UPDATE RESTRICT
        ON DELETE RESTRICT,
    CONSTRAINT `fk_socolissimo_area_freeshipping_pr_delivery_mode_id`
        FOREIGN KEY (`delivery_mode_id`)
        REFERENCES `socolissimo_delivery_mode` (`id`)
        ON UPDATE RESTRICT
        ON DELETE RESTRICT
) ENGINE=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
