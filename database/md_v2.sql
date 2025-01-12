UPDATE `md_payment` SET `payment_type`='move_to_cr' where `payment_type` = 'dr';
UPDATE `md_payment` SET `payment_type`='dr' where `payment_type` = 'cr';
UPDATE `md_payment` SET `payment_type`='cr' where `payment_type` = 'move_to_cr';

ALTER TABLE `md_payment` ADD `paid_for` INT(11) NOT NULL AFTER `payment_type`;