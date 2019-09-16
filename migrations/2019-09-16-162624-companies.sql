CREATE TABLE IF NOT EXISTS `companies` (
  `company_uuid` VARCHAR(45) NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `cnpj` VARCHAR(32) NOT NULL,
  `create_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`company_uuid`),
  UNIQUE INDEX `cnpj_unique` (`cnpj` ASC));