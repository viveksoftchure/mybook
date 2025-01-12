ALTER TABLE `md_payment` ADD `payment_type` VARCHAR(255) NULL DEFAULT NULL AFTER `payment_method`;
ALTER TABLE `md_payment` 
ADD `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `items`, 
ADD `updated_at` DATETIME NULL DEFAULT NULL AFTER `created_at`;

UPDATE `md_payment` SET `payment_type`='cr';