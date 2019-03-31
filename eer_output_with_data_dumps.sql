SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

CREATE SCHEMA IF NOT EXISTS `portal` DEFAULT CHARACTER SET latin1 ;
USE `portal` ;

-- -----------------------------------------------------
-- Table `portal`.`user_types`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `portal`.`user_types` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `type_name` VARCHAR(255) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
AUTO_INCREMENT = 3
DEFAULT CHARACTER SET = latin1;

--
-- Dumping data for table `user_types`
--

INSERT INTO `user_types` (`id`, `type_name`) VALUES
(1, 'employee'),
(2, 'admin');

-- -----------------------------------------------------
-- Table `portal4`.`users`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `portal`.`users` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `email` VARCHAR(255) NOT NULL ,
  `password` VARCHAR(255) NOT NULL ,
  `firstname` VARCHAR(255) NULL ,
  `lastname` VARCHAR(255) NULL DEFAULT NULL ,
  `user_type` INT(11) NULL ,
  `supervisor_id` INT(11) NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `user_type_idx` (`user_type` ASC) ,
  INDEX `supervisor_id_idx` (`supervisor_id` ASC) ,
  CONSTRAINT `user_type`
    FOREIGN KEY (`user_type` )
    REFERENCES `portal`.`user_types` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `supervisor_id`
    FOREIGN KEY (`supervisor_id` )
    REFERENCES `portal`.`users` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 30
DEFAULT CHARACTER SET = latin1;
--
-- Dumping data for table `users`
--

-- User  password = 1234
INSERT INTO `users` (`id`, `email`, `password`, `firstname`, `lastname`, `user_type`, `supervisor_id`) VALUES
(30, 'master@test.test', '$2y$10$KJx1jrf.NqJ/GCse8SG8qussLnYUiPff0BAMdy8BdUUsqrL7LJhIe', 'master', 'master', 2, NULL);

-- --------------------------------------------------------

-- -----------------------------------------------------
-- Table `portal`.`applications`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `portal`.`applications` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `user_id` INT(11) NOT NULL ,
  `submitted_date` DATETIME NOT NULL ,
  `dateFrom` DATE NOT NULL ,
  `dateTo` DATE NOT NULL ,
  `reason` TEXT NOT NULL ,
  `status` VARCHAR(20) NOT NULL DEFAULT 'pending' ,
  `processed` TINYINT(4) NOT NULL DEFAULT '0' ,
  `procession_code` VARCHAR(30) NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `user_id_idx` (`user_id` ASC) ,
  CONSTRAINT `user_id`
    FOREIGN KEY (`user_id` )
    REFERENCES `portal`.`users` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 173
DEFAULT CHARACTER SET = latin1;

USE `portal` ;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
