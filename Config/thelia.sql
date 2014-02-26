
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

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
