SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- socolissimo_area_freeshipping
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `socolissimo_area_freeshipping`;

CREATE TABLE `socolissimo_area_freeshipping`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `area_id` INTEGER NOT NULL,
    `delivery_mode_id` INTEGER NOT NULL,
    `cart_amount` FLOAT NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `FI_socolissimo_area_freeshipping_area_id` (`area_id`),
    INDEX `FI_socolissimo_area_freeshipping_delivery_mode_id` (`delivery_mode_id`),
    CONSTRAINT `fk_socolissimo_area_freeshipping_area_id`
        FOREIGN KEY (`area_id`)
        REFERENCES `area` (`id`)
        ON UPDATE RESTRICT
        ON DELETE RESTRICT,
    CONSTRAINT `fk_socolissimo_area_freeshipping_delivery_mode_id`
        FOREIGN KEY (`delivery_mode_id`)
        REFERENCES `socolissimo_delivery_mode` (`id`)
        ON UPDATE RESTRICT
        ON DELETE RESTRICT
) ENGINE=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;