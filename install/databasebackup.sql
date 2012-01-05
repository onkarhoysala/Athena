SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';


-- -----------------------------------------------------
-- Table `type`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `type` (
  `tyid` INT(11) NOT NULL AUTO_INCREMENT ,
  `description` VARCHAR(255) NOT NULL ,
  PRIMARY KEY (`tyid`) )
ENGINE = InnoDB
AUTO_INCREMENT = 5
DEFAULT CHARACTER SET = latin1
COMMENT = 'Table to store type of resource';


-- -----------------------------------------------------
-- Table `resource`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `resource` (
  `eid` INT(11) NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(255) NOT NULL ,
  `tyid` INT(11) NOT NULL ,
  `date_of_index` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP ,
  `uid` INT(11) NULL DEFAULT NULL ,
  `location` VARCHAR(255) NOT NULL ,
  `owner` VARCHAR(255) NULL DEFAULT NULL ,
  `lost` TINYINT(1) NULL DEFAULT NULL ,
  `rating` INT(11) NULL DEFAULT NULL ,
  `digital` TINYINT(1) NULL DEFAULT NULL ,
  PRIMARY KEY (`eid`) ,
  INDEX `tyid` (`tyid` ASC) ,
  INDEX `owner` (`uid` ASC) ,
  INDEX `name_index` USING BTREE (`name` ASC) ,
  INDEX `fk_resource_1` (`tyid` ASC) ,
  CONSTRAINT `fk_resource_1`
    FOREIGN KEY (`tyid` )
    REFERENCES `type` (`tyid` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
AUTO_INCREMENT = 26588
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `Book`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `Book` (
  `eid` INT(11) NOT NULL DEFAULT '0' ,
  `isbn` VARCHAR(255) NULL DEFAULT NULL ,
  `author` VARCHAR(255) NULL DEFAULT NULL ,
  `publisher` VARCHAR(255) NULL DEFAULT NULL ,
  PRIMARY KEY (`eid`) ,
  INDEX `eid` (`eid` ASC) ,
  INDEX `eid_book` (`eid` ASC) ,
  CONSTRAINT `eid_book`
    FOREIGN KEY (`eid` )
    REFERENCES `resource` (`eid` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `Journal`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `Journal` (
  `eid` INT(11) NOT NULL DEFAULT '0' ,
  `Volume` VARCHAR(255) NULL DEFAULT NULL ,
  `Issue` VARCHAR(255) NULL DEFAULT NULL ,
  `Publication` VARCHAR(255) NULL DEFAULT NULL ,
  `Date` VARCHAR(255) NULL DEFAULT NULL ,
  `DOI` VARCHAR(255) NULL DEFAULT NULL ,
  PRIMARY KEY (`eid`) ,
  INDEX `eid` (`eid` ASC) ,
  INDEX `eid_journal` (`eid` ASC) ,
  CONSTRAINT `eid_journal`
    FOREIGN KEY (`eid` )
    REFERENCES `resource` (`eid` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `Report`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `Report` (
  `eid` INT(11) NOT NULL DEFAULT '0' ,
  `author` VARCHAR(255) NULL DEFAULT NULL ,
  `publisher` VARCHAR(255) NULL DEFAULT NULL ,
  `year` VARCHAR(255) NULL DEFAULT NULL ,
  `institution` VARCHAR(255) NULL DEFAULT NULL ,
  PRIMARY KEY (`eid`) ,
  INDEX `eid` (`eid` ASC) ,
  INDEX `eid_report` (`eid` ASC) ,
  CONSTRAINT `eid_report`
    FOREIGN KEY (`eid` )
    REFERENCES `resource` (`eid` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `Software`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `Software` (
  `eid` INT(11) NULL DEFAULT NULL ,
  `Version` VARCHAR(255) NULL DEFAULT NULL ,
  `Platform` VARCHAR(255) NULL DEFAULT NULL ,
  `License` VARCHAR(255) NULL DEFAULT NULL ,
  INDEX `eid` (`eid` ASC) ,
  INDEX `eid_software` (`eid` ASC) ,
  CONSTRAINT `eid_software`
    FOREIGN KEY (`eid` )
    REFERENCES `resource` (`eid` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `user`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `user` (
  `uid` INT(11) NOT NULL AUTO_INCREMENT ,
  `email` VARCHAR(255) NOT NULL ,
  `fname` VARCHAR(255) NOT NULL ,
  `sname` VARCHAR(255) NOT NULL ,
  `phone` VARCHAR(45) NULL DEFAULT NULL ,
  `designation` VARCHAR(255) NULL DEFAULT NULL ,
  `password` VARCHAR(255) NOT NULL ,
  `question` TEXT NULL DEFAULT NULL ,
  `answer` TEXT NULL DEFAULT NULL ,
  PRIMARY KEY (`uid`) ,
  UNIQUE INDEX `uni` (`email` ASC) )
ENGINE = InnoDB
AUTO_INCREMENT = 34
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `borrowed`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `borrowed` (
  `eid` INT(11) NOT NULL DEFAULT '0' ,
  `uid` INT(11) NOT NULL ,
  `date_borrowed` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP ,
  PRIMARY KEY (`eid`, `uid`, `date_borrowed`) ,
  INDEX `eid` (`eid` ASC) ,
  INDEX `uid` (`uid` ASC, `eid` ASC) ,
  INDEX `eid_bor` (`eid` ASC) ,
  INDEX `uid_bor` (`uid` ASC) ,
  CONSTRAINT `eid_bor`
    FOREIGN KEY (`eid` )
    REFERENCES `resource` (`eid` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `uid_bor`
    FOREIGN KEY (`uid` )
    REFERENCES `user` (`uid` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `comment_table`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `comment_table` (
  `cid` INT(11) NOT NULL AUTO_INCREMENT ,
  `eid` INT(11) NULL DEFAULT NULL ,
  `comments` TEXT NULL DEFAULT NULL ,
  `uid` INT(11) NULL DEFAULT NULL ,
  `step` INT(11) NULL DEFAULT NULL ,
  PRIMARY KEY (`cid`) ,
  INDEX `eid_comment` (`eid` ASC) ,
  INDEX `fk_comment_table_1` (`uid` ASC) ,
  CONSTRAINT `eid_comment`
    FOREIGN KEY (`eid` )
    REFERENCES `resource` (`eid` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_comment_table_1`
    FOREIGN KEY (`uid` )
    REFERENCES `user` (`uid` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
AUTO_INCREMENT = 37
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `favourites`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `favourites` (
  `eid` INT(11) NOT NULL DEFAULT '0' ,
  `uid` INT(11) NOT NULL DEFAULT '0' ,
  PRIMARY KEY (`eid`, `uid`) ,
  INDEX `uid` (`uid` ASC) ,
  INDEX `eid_fav` (`eid` ASC) ,
  INDEX `uid_fav` (`uid` ASC) ,
  CONSTRAINT `eid_fav`
    FOREIGN KEY (`eid` )
    REFERENCES `resource` (`eid` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `uid_fav`
    FOREIGN KEY (`uid` )
    REFERENCES `user` (`uid` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `feedback`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `feedback` (
  `fid` INT(11) NOT NULL AUTO_INCREMENT ,
  `email` VARCHAR(255) NULL DEFAULT NULL ,
  `msg` TEXT NULL DEFAULT NULL ,
  `type` VARCHAR(255) NULL DEFAULT NULL ,
  `time_entry` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP ,
  `step` INT(11) NULL DEFAULT NULL ,
  PRIMARY KEY (`fid`) )
ENGINE = InnoDB
AUTO_INCREMENT = 56
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `images`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `images` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `eid` INT(11) NULL DEFAULT NULL ,
  `url` TEXT NULL DEFAULT NULL ,
  `uid` INT(11) NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `eid` (`eid` ASC) ,
  INDEX `fk_images_1` (`eid` ASC) ,
  INDEX `fk_images_2` (`uid` ASC) ,
  CONSTRAINT `fk_images_1`
    FOREIGN KEY (`eid` )
    REFERENCES `resource` (`eid` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_images_2`
    FOREIGN KEY (`uid` )
    REFERENCES `user` (`uid` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
AUTO_INCREMENT = 5
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `log`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `log` (
  `time_entry` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP ,
  `activity` VARCHAR(255) NULL DEFAULT NULL ,
  `uid` INT(11) NULL DEFAULT NULL ,
  `eid` INT(11) NULL DEFAULT NULL ,
  `comments` TEXT NULL DEFAULT NULL ,
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  PRIMARY KEY (`id`) ,
  INDEX `eid` (`eid` ASC) ,
  INDEX `fk_log_1` (`uid` ASC) ,
  INDEX `fk_log_2` (`eid` ASC) ,
  CONSTRAINT `fk_log_1`
    FOREIGN KEY (`uid` )
    REFERENCES `user` (`uid` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_log_2`
    FOREIGN KEY (`eid` )
    REFERENCES `resource` (`eid` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
AUTO_INCREMENT = 26710
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `message`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `message` (
  `from_uid` INT(11) NULL DEFAULT NULL ,
  `to_uid` INT(11) NULL DEFAULT NULL ,
  `date_sent` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP ,
  `sub` TEXT NULL DEFAULT NULL ,
  `message` TEXT NULL DEFAULT NULL ,
  `delete_flag` TINYINT(1) NULL DEFAULT NULL ,
  `read_flag` TINYINT(1) NULL DEFAULT NULL ,
  `mid` INT(11) NOT NULL AUTO_INCREMENT ,
  PRIMARY KEY (`mid`) ,
  INDEX `fk_message_1` (`to_uid` ASC) ,
  INDEX `fk_message_2` (`from_uid` ASC) )
ENGINE = MyISAM
AUTO_INCREMENT = 79
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `module`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `module` (
  `mod_name` VARCHAR(255) NOT NULL DEFAULT '' ,
  `mod_loc` VARCHAR(255) NOT NULL DEFAULT '' ,
  PRIMARY KEY (`mod_name`, `mod_loc`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `online`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `online` (
  `uid` INT(11) NULL DEFAULT NULL ,
  `time_entry` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP ,
  INDEX `fk_online_1` (`uid` ASC) )
ENGINE = MyISAM
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `ratings`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `ratings` (
  `eid` INT(11) NULL DEFAULT NULL ,
  `uid` INT(11) NULL DEFAULT NULL ,
  `rating` INT(11) NULL DEFAULT NULL ,
  INDEX `fk_ratings_1` (`eid` ASC) ,
  INDEX `fk_ratings_2` (`uid` ASC) ,
  CONSTRAINT `fk_ratings_1`
    FOREIGN KEY (`eid` )
    REFERENCES `resource` (`eid` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_ratings_2`
    FOREIGN KEY (`uid` )
    REFERENCES `user` (`uid` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `reco_dislike`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `reco_dislike` (
  `uid` INT(11) NULL DEFAULT NULL ,
  `eid` INT(11) NULL DEFAULT NULL ,
  INDEX `fk_reco_dislike_1` (`eid` ASC) ,
  INDEX `fk_reco_dislike_2` (`uid` ASC) ,
  CONSTRAINT `fk_reco_dislike_1`
    FOREIGN KEY (`eid` )
    REFERENCES `resource` (`eid` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_reco_dislike_2`
    FOREIGN KEY (`uid` )
    REFERENCES `user` (`uid` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `reco_log`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `reco_log` (
  `rid` INT(11) NOT NULL AUTO_INCREMENT ,
  `uid` INT(11) NULL DEFAULT NULL ,
  `activity` TEXT NULL DEFAULT NULL ,
  `time_entry` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP ,
  PRIMARY KEY (`rid`) ,
  INDEX `fk_reco_log_1` (`uid` ASC) ,
  CONSTRAINT `fk_reco_log_1`
    FOREIGN KEY (`uid` )
    REFERENCES `user` (`uid` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
AUTO_INCREMENT = 4368
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `resource_attrib`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `resource_attrib` (
  `tyid` INT(11) NULL DEFAULT NULL ,
  `attrib` VARCHAR(255) NULL DEFAULT NULL ,
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_resource_attrib_1` (`tyid` ASC) ,
  CONSTRAINT `fk_resource_attrib_1`
    FOREIGN KEY (`tyid` )
    REFERENCES `type` (`tyid` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `resource_tag`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `resource_tag` (
  `tagname` VARCHAR(255) NOT NULL ,
  `eid` INT(11) NOT NULL ,
  `uid` INT(11) NOT NULL DEFAULT '0' ,
  PRIMARY KEY (`tagname`, `eid`, `uid`) ,
  INDEX `eidindex` (`eid` ASC) ,
  INDEX `fk_resource_tag_1` (`eid` ASC) ,
  INDEX `fk_resource_tag_2` (`uid` ASC) ,
  CONSTRAINT `fk_resource_tag_1`
    FOREIGN KEY (`eid` )
    REFERENCES `resource` (`eid` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_resource_tag_2`
    FOREIGN KEY (`uid` )
    REFERENCES `user` (`uid` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `uploaded`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `uploaded` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `eid` INT(11) NULL DEFAULT NULL ,
  `fname` VARCHAR(255) NULL DEFAULT NULL ,
  `uid` INT(11) NULL ,
  `request_download` TINYINT(1)  NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_uploaded_1` (`eid` ASC) ,
  INDEX `fk_uploaded_2` (`uid` ASC) ,
  CONSTRAINT `fk_uploaded_1`
    FOREIGN KEY (`eid` )
    REFERENCES `resource` (`eid` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_uploaded_2`
    FOREIGN KEY (`uid` )
    REFERENCES `user` (`uid` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
AUTO_INCREMENT = 11
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `wishlist`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `wishlist` (
  `wid` INT(11) NOT NULL AUTO_INCREMENT ,
  `uid` INT(11) NULL DEFAULT NULL ,
  `tyid` INT(11) NULL DEFAULT NULL ,
  `name` VARCHAR(255) NULL DEFAULT NULL ,
  `comments` VARCHAR(255) NULL DEFAULT NULL ,
  PRIMARY KEY (`wid`) ,
  INDEX `fk_wishlist_1` (`uid` ASC) ,
  CONSTRAINT `fk_wishlist_1`
    FOREIGN KEY (`uid` )
    REFERENCES `user` (`uid` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `download_request`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `download_request` (
  `download_id` INT NOT NULL AUTO_INCREMENT ,
  `id` INT(11) NULL ,
  `eid` INT(11) NULL ,
  `uid` INT(11) NULL ,
  `status` VARCHAR(45) NULL ,
  PRIMARY KEY (`download_id`) ,
  INDEX `fk_download_request_1` (`uid` ASC) ,
  INDEX `fk_download_request_2` (`eid` ASC) ,
  INDEX `fk_download_request_3` (`id` ASC) ,
  CONSTRAINT `fk_download_request_1`
    FOREIGN KEY (`uid` )
    REFERENCES `user` (`uid` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_download_request_2`
    FOREIGN KEY (`eid` )
    REFERENCES `resource` (`eid` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_download_request_3`
    FOREIGN KEY (`id` )
    REFERENCES `uploaded` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Placeholder table for view `Bookdetails`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Bookdetails` (`uid` INT, `eid` INT, `name` INT, `tyid` INT, `date_of_index` INT, `location` INT, `owner` INT, `lost` INT, `rating` INT, `digital` INT, `isbn` INT, `author` INT, `publisher` INT, `email` INT, `fname` INT, `sname` INT, `phone` INT, `designation` INT, `password` INT, `question` INT, `answer` INT);

-- -----------------------------------------------------
-- Placeholder table for view `Journaldetails`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Journaldetails` (`uid` INT, `eid` INT, `name` INT, `tyid` INT, `date_of_index` INT, `location` INT, `owner` INT, `lost` INT, `rating` INT, `digital` INT, `Volume` INT, `Issue` INT, `Publication` INT, `Date` INT, `DOI` INT, `email` INT, `fname` INT, `sname` INT, `phone` INT, `designation` INT, `password` INT, `question` INT, `answer` INT);

-- -----------------------------------------------------
-- Placeholder table for view `Reportdetails`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Reportdetails` (`uid` INT, `eid` INT, `name` INT, `tyid` INT, `date_of_index` INT, `location` INT, `owner` INT, `lost` INT, `rating` INT, `digital` INT, `author` INT, `publisher` INT, `year` INT, `institution` INT, `email` INT, `fname` INT, `sname` INT, `phone` INT, `designation` INT, `password` INT, `question` INT, `answer` INT);

-- -----------------------------------------------------
-- Placeholder table for view `Softwaredetails`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Softwaredetails` (`uid` INT, `eid` INT, `name` INT, `tyid` INT, `date_of_index` INT, `location` INT, `owner` INT, `lost` INT, `rating` INT, `digital` INT, `Version` INT, `Platform` INT, `License` INT, `email` INT, `fname` INT, `sname` INT, `phone` INT, `designation` INT, `password` INT, `question` INT, `answer` INT);

-- -----------------------------------------------------
-- View `Bookdetails`
-- -----------------------------------------------------



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
