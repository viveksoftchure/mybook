ALTER TABLE `md_payment` ADD `id_user` INT(11) NULL DEFAULT NULL AFTER `id_payment`;
UPDATE `md_payment` SET `id_user`='1'