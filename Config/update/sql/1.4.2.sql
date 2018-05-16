SET FOREIGN_KEY_CHECKS = 0;
-- ---------------------------------------------------------------------
-- socolissimo_area_freeshipping_dom
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `socolissimo_area_freeshipping_dom`;

CREATE TABLE `socolissimo_area_freeshipping_dom`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `area_id` INTEGER NOT NULL,
    `delivery_mode_id` INTEGER NOT NULL,
    `cart_amount` DECIMAL(16,6) DEFAULT 0.000000 NOT NULL,
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
    `cart_amount` DECIMAL(16,6) DEFAULT 0.000000 NOT NULL,
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