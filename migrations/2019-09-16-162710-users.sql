CREATE TABLE IF NOT EXISTS `users` (
  `user_uuid` VARCHAR(45) NOT NULL,
  `name` VARCHAR(45) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `password` VARCHAR(32) NOT NULL,
  `cpf` VARCHAR(45) NOT NULL,
  `create_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `company_uuid` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`user_uuid`),
  INDEX `fk_users_company_uuid_idx` (`company_uuid` ASC),
  UNIQUE INDEX `cpf_UNIQUE` (`cpf` ASC),
  CONSTRAINT `fk_users_company_uuid`
    FOREIGN KEY (`company_uuid`)
    REFERENCES `companies` (`company_uuid`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;