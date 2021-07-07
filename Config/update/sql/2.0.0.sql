SET FOREIGN_KEY_CHECKS = 0;
-- ---------------------------------------------------------------------
-- socolissimo_area_freeshipping_dom
-- ---------------------------------------------------------------------

ALTER TABLE `socolissimo_area_freeshipping_dom` MODIFY `cart_amount` DECIMAL(18,2) DEFAULT 0.00 NULL;

-- ---------------------------------------------------------------------
-- socolissimo_area_freeshipping_pr
-- ---------------------------------------------------------------------

ALTER TABLE `socolissimo_area_freeshipping_pr` MODIFY `cart_amount` DECIMAL(18,2) DEFAULT 0.00 NULL;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;