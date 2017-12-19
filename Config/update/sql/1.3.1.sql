ALTER TABLE `socolissimo_price` ADD COLUMN `price_max` FLOAT AFTER `weight_max`;
ALTER TABLE `socolissimo_price` MODIFY `weight_max` FLOAT NULL DEFAULT NULL;