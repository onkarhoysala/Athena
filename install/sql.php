<?php
	$type="CREATE  TABLE IF NOT EXISTS `type` (
		`tyid` INT(11) NOT NULL AUTO_INCREMENT ,
		`description` VARCHAR(255) NOT NULL ,
		PRIMARY KEY (`tyid`) )
		ENGINE = InnoDB
		AUTO_INCREMENT = 1
		DEFAULT CHARACTER SET = latin1
		COMMENT = 'Table to store type of resource';";
	
		
	$resource="	CREATE  TABLE IF NOT EXISTS `resource` (
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
		AUTO_INCREMENT = 1
		DEFAULT CHARACTER SET = latin1;";
		
	$user="   CREATE  TABLE IF NOT EXISTS `users` (
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
		AUTO_INCREMENT = 1
		DEFAULT CHARACTER SET = latin1;";
		
	$borrowed=" CREATE  TABLE IF NOT EXISTS `borrowed` (
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
		    REFERENCES `users` (`uid` )
		    ON DELETE CASCADE
		    ON UPDATE CASCADE)
		ENGINE = InnoDB
		DEFAULT CHARACTER SET = latin1;";
		
	$comment=" CREATE  TABLE IF NOT EXISTS `comment_table` (
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
		REFERENCES `users` (`uid` )
		ON DELETE CASCADE
		ON UPDATE CASCADE)
		ENGINE = InnoDB
		AUTO_INCREMENT = 1
		DEFAULT CHARACTER SET = latin1;";
		
	$fav=" CREATE  TABLE IF NOT EXISTS `favourites` (
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
		    REFERENCES `users` (`uid` )
		    ON DELETE CASCADE
		    ON UPDATE CASCADE)
		ENGINE = InnoDB
		DEFAULT CHARACTER SET = latin1;";
		
	$feedback="CREATE  TABLE IF NOT EXISTS `feedback` (
		  `fid` INT(11) NOT NULL AUTO_INCREMENT ,
		  `email` VARCHAR(255) NULL DEFAULT NULL ,
		  `msg` TEXT NULL DEFAULT NULL ,
		  `type` VARCHAR(255) NULL DEFAULT NULL ,
		  `time_entry` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP ,
		  `step` INT(11) NULL DEFAULT NULL ,
		  PRIMARY KEY (`fid`) )
		ENGINE = InnoDB
		AUTO_INCREMENT = 1
		DEFAULT CHARACTER SET = latin1;";
		
	$images="CREATE  TABLE IF NOT EXISTS `images` (
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
	    REFERENCES `users` (`uid` )
	    ON DELETE CASCADE
	    ON UPDATE CASCADE)
	ENGINE = InnoDB
	AUTO_INCREMENT = 1
	DEFAULT CHARACTER SET = latin1;";


	$log="CREATE  TABLE IF NOT EXISTS `log` (
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
	    REFERENCES `users` (`uid` )
	    ON DELETE CASCADE
	    ON UPDATE CASCADE,
	  CONSTRAINT `fk_log_2`
	    FOREIGN KEY (`eid` )
	    REFERENCES `resource` (`eid` )
	    ON DELETE CASCADE
	    ON UPDATE CASCADE)
	ENGINE = InnoDB
	AUTO_INCREMENT = 1
	DEFAULT CHARACTER SET = latin1;";


	$message="CREATE  TABLE IF NOT EXISTS `message` (
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
	AUTO_INCREMENT = 1
	DEFAULT CHARACTER SET = latin1;";


	$module="CREATE  TABLE IF NOT EXISTS `module` (
	  `mod_name` VARCHAR(255) NOT NULL DEFAULT '' ,
	  `mod_loc` VARCHAR(255) NOT NULL DEFAULT '' ,
	  PRIMARY KEY (`mod_name`, `mod_loc`) )
	ENGINE = InnoDB
	DEFAULT CHARACTER SET = latin1;";
	
	$online="CREATE  TABLE IF NOT EXISTS `online` (
	  `uid` INT(11) NULL DEFAULT NULL ,
	  `time_entry` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP ,
	  INDEX `fk_online_1` (`uid` ASC) )
	ENGINE = InnoDB
	DEFAULT CHARACTER SET = latin1;";


	$ratings="CREATE  TABLE IF NOT EXISTS `ratings` (
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
	    REFERENCES `users` (`uid` )
	    ON DELETE CASCADE
	    ON UPDATE CASCADE)
	ENGINE = InnoDB
	DEFAULT CHARACTER SET = latin1;";


	$reco_log="CREATE  TABLE IF NOT EXISTS `reco_log` (
	  `rid` INT(11) NOT NULL AUTO_INCREMENT ,
	  `uid` INT(11) NULL DEFAULT NULL ,
	  `activity` TEXT NULL DEFAULT NULL ,
	  `time_entry` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP ,
	  PRIMARY KEY (`rid`) ,
	  INDEX `fk_reco_log_1` (`uid` ASC) ,
	  CONSTRAINT `fk_reco_log_1`
	    FOREIGN KEY (`uid` )
	    REFERENCES `users` (`uid` )
	    ON DELETE CASCADE
	    ON UPDATE CASCADE)
	ENGINE = InnoDB
	AUTO_INCREMENT = 1
	DEFAULT CHARACTER SET = latin1;";


	$res_attr="CREATE  TABLE IF NOT EXISTS `resource_attrib` (
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
	DEFAULT CHARACTER SET = latin1;";


	$res_tag="CREATE  TABLE IF NOT EXISTS `resource_tag` (
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
	    REFERENCES `users` (`uid` )
	    ON DELETE CASCADE
	    ON UPDATE CASCADE)
	ENGINE = InnoDB
	DEFAULT CHARACTER SET = latin1;";


	$uploaded="CREATE  TABLE IF NOT EXISTS `uploaded` (
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
	    REFERENCES `users` (`uid` )
	    ON DELETE CASCADE
	    ON UPDATE CASCADE)
	ENGINE = InnoDB
	AUTO_INCREMENT = 1
	DEFAULT CHARACTER SET = latin1;";



	$download_req="CREATE  TABLE IF NOT EXISTS `download_request` (
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
	    REFERENCES `users` (`uid` )
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
	ENGINE = InnoDB;";

	$ins_mod="CREATE TABLE IF NOT EXISTS `installed_modules` (
	   `mod_id` INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
	   `module_name` VARCHAR(255) NOT NULL	
	) 
	ENGINE = InnoDB;";
	
	$api="create table api_register(id int(11) primary key auto_increment,hash varchar(255) unique,uid int(11) references user(uid),time_of_login timestamp);";
	$settings_table="create table settings(option_id int(11) primary key auto_increment,option_name varchar(255),option_value varchar(255));";
	$wishlist="create table wishlist(wid int(11) primary key auto_increment, wname varchar(255), tyid int(11) references type.tyid on delete cascade on update cascade, date_of_entry timestamp default now(),uid int(11) references users.uid on delete cascade on update cascade, digital bool)";
	$insert="INSERT INTO user values('-1','automatic','automatic','automatic','automatic','automatic','automatic','automatic','automatic');";
	
?>
