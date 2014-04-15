
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- socolissimo_freeshipping
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `socolissimo_freeshipping`;

CREATE TABLE `socolissimo_freeshipping`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `active` TINYINT(1) NOT NULL,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

INSERT INTO `socolissimo_freeshipping`(`active`, `created_at`, `updated_at`) VALUES (0, NOW(), NOW());


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
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_order_address_socolissimo_order_address_id`
        FOREIGN KEY (`id`)
        REFERENCES `order_address` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE
) ENGINE=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;

