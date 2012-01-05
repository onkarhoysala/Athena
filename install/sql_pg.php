<?php
	$type="CREATE  TABLE  type (
		tyid SERIAL ,
		description VARCHAR(255) NOT NULL ,
		PRIMARY KEY (tyid) )
		;";
	
		
	$resource="	CREATE  TABLE  resource (
		  eid SERIAL ,
		  name VARCHAR(255) NOT NULL ,
		  tyid INTEGER NOT NULL ,
		  date_of_index TIMESTAMP NOT NULL ,
		  uid INTEGER NULL DEFAULT NULL ,
		  location VARCHAR(255) NOT NULL ,
		  owner VARCHAR(255) NULL DEFAULT NULL ,
		  lost BOOL NULL DEFAULT NULL ,
		  rating INTEGER NULL DEFAULT NULL ,
		  digital BOOL NULL DEFAULT NULL ,
		  PRIMARY KEY (eid) ,
		  CONSTRAINT fk_resource_1
		    FOREIGN KEY (tyid )
		    REFERENCES type (tyid )
		    ON DELETE CASCADE
		    ON UPDATE CASCADE)
		;";
		
	$user="   CREATE TABLE users(
		  uid SERIAL ,
		  email VARCHAR(255) NOT NULL ,
		  fname VARCHAR(255) NOT NULL ,
		  sname VARCHAR(255) NOT NULL ,
		  phone VARCHAR(45) NULL DEFAULT NULL ,
		  designation VARCHAR(255) NULL DEFAULT NULL ,
		  password VARCHAR(255) NOT NULL ,
		  question TEXT NULL DEFAULT NULL ,
		  answer TEXT NULL DEFAULT NULL ,
		  PRIMARY KEY (uid) )
		 ;";
		
	$borrowed=" CREATE  TABLE  borrowed (
		  eid INTEGER NOT NULL DEFAULT '0' ,
		  uid INTEGER NOT NULL ,
		  date_borrowed TIMESTAMP NOT NULL  ,
		  PRIMARY KEY (eid, uid, date_borrowed) ,
		  CONSTRAINT eid_bor
		    FOREIGN KEY (eid )
		    REFERENCES resource (eid )
		    ON DELETE CASCADE
		    ON UPDATE CASCADE,
		  CONSTRAINT uid_bor
		    FOREIGN KEY (uid )
		    REFERENCES users (uid )
		    ON DELETE CASCADE
		    ON UPDATE CASCADE);";
		
	$comment=" CREATE  TABLE  comment_table (
		cid SERIAL ,
		eid INTEGER NULL DEFAULT NULL ,
		comments TEXT NULL DEFAULT NULL ,
		uid INTEGER NULL DEFAULT NULL ,
		step INTEGER NULL DEFAULT NULL ,
		PRIMARY KEY (cid) ,
		CONSTRAINT eid_comment
		FOREIGN KEY (eid )
		REFERENCES resource (eid )
		ON DELETE CASCADE
		ON UPDATE CASCADE,
		CONSTRAINT fk_comment_table_1
		FOREIGN KEY (uid )
		REFERENCES users (uid )
		ON DELETE CASCADE
		ON UPDATE CASCADE);";
		
	$fav=" CREATE  TABLE  favourites (
		  eid INTEGER NOT NULL DEFAULT '0' ,
		  uid INTEGER NOT NULL DEFAULT '0' ,
		  PRIMARY KEY (eid, uid) ,
		  CONSTRAINT eid_fav
		    FOREIGN KEY (eid )
		    REFERENCES resource (eid )
		    ON DELETE CASCADE
		    ON UPDATE CASCADE,
		  CONSTRAINT uid_fav
		    FOREIGN KEY (uid )
		    REFERENCES users (uid )
		    ON DELETE CASCADE
		    ON UPDATE CASCADE);";
		
	$feedback="CREATE  TABLE  feedback (
		  fid SERIAL ,
		  email VARCHAR(255) NULL DEFAULT NULL ,
		  msg TEXT NULL DEFAULT NULL ,
		  type VARCHAR(255) NULL DEFAULT NULL ,
		  time_entry TIMESTAMP NOT NULL ,
		  step INTEGER NULL DEFAULT NULL ,
		  PRIMARY KEY (fid) );";
		
	$images="CREATE  TABLE  images (
	  id SERIAL ,
	  eid INTEGER NULL DEFAULT NULL ,
	  url TEXT NULL DEFAULT NULL ,
	  uid INTEGER NULL DEFAULT NULL ,
	  PRIMARY KEY (id) ,
	  CONSTRAINT fk_images_1
	    FOREIGN KEY (eid )
	    REFERENCES resource (eid )
	    ON DELETE CASCADE
	    ON UPDATE CASCADE,
	  CONSTRAINT fk_images_2
	    FOREIGN KEY (uid )
	    REFERENCES users (uid )
	    ON DELETE CASCADE
	    ON UPDATE CASCADE);";


	$log="CREATE  TABLE  log (
	  time_entry TIMESTAMP NOT NULL ,
	  activity VARCHAR(255) NULL DEFAULT NULL ,
	  uid INTEGER NULL DEFAULT NULL ,
	  eid INTEGER NULL DEFAULT NULL ,
	  comments TEXT NULL DEFAULT NULL ,
	  id SERIAL ,
	  PRIMARY KEY (id) ,
	  CONSTRAINT fk_log_1
	    FOREIGN KEY (uid )
	    REFERENCES users (uid )
	    ON DELETE CASCADE
	    ON UPDATE CASCADE,
	  CONSTRAINT fk_log_2
	    FOREIGN KEY (eid )
	    REFERENCES resource (eid )
	    ON DELETE CASCADE
	    ON UPDATE CASCADE);";


	$message="CREATE  TABLE  message (
	  from_uid INTEGER NULL DEFAULT NULL ,
	  to_uid INTEGER NULL DEFAULT NULL ,
	  date_sent TIMESTAMP NOT NULL,
	  sub TEXT NULL DEFAULT NULL ,
	  message TEXT NULL DEFAULT NULL ,
	  delete_flag BOOL NULL DEFAULT NULL ,
	  read_flag BOOL NULL DEFAULT NULL ,
	  mid SERIAL ,
	  PRIMARY KEY (mid));";


	$module="CREATE  TABLE  module (
	  mod_name VARCHAR(255) NOT NULL DEFAULT '' ,
	  mod_loc VARCHAR(255) NOT NULL DEFAULT '' ,
	  PRIMARY KEY (mod_name, mod_loc) );";
	
	$online="CREATE  TABLE  online (
	  uid INTEGER NULL DEFAULT NULL ,
	  time_entry TIMESTAMP NOT NULL);";


	$ratings="CREATE  TABLE  ratings (
	  eid INTEGER NULL DEFAULT NULL ,
	  uid INTEGER NULL DEFAULT NULL ,
	  rating INTEGER NULL DEFAULT NULL ,
	  CONSTRAINT fk_ratings_1
	    FOREIGN KEY (eid )
	    REFERENCES resource (eid )
	    ON DELETE CASCADE
	    ON UPDATE CASCADE,
	  CONSTRAINT fk_ratings_2
	    FOREIGN KEY (uid )
	    REFERENCES users (uid )
	    ON DELETE CASCADE
	    ON UPDATE CASCADE);";


	$reco_log="CREATE  TABLE  reco_log (
	  rid SERIAL ,
	  uid INTEGER NULL DEFAULT NULL ,
	  activity TEXT NULL DEFAULT NULL ,
	  time_entry TIMESTAMP NOT NULL ,
	  PRIMARY KEY (rid) ,
	  CONSTRAINT fk_reco_log_1
	    FOREIGN KEY (uid )
	    REFERENCES users (uid )
	    ON DELETE CASCADE
	    ON UPDATE CASCADE);";


	$res_attr="CREATE  TABLE  resource_attrib (
	  tyid INTEGER NULL DEFAULT NULL ,
	  attrib VARCHAR(255) NULL DEFAULT NULL ,
	  id SERIAL ,
	  PRIMARY KEY (id) ,
	  CONSTRAINT fk_resource_attrib_1
	    FOREIGN KEY (tyid )
	    REFERENCES type (tyid )
	    ON DELETE CASCADE
	    ON UPDATE CASCADE);";


	$res_tag="CREATE  TABLE  resource_tag (
	  tagname VARCHAR(255) NOT NULL ,
	  eid INTEGER NOT NULL ,
	  uid INTEGER NOT NULL DEFAULT '0' ,
	  PRIMARY KEY (tagname, eid, uid) ,
	  CONSTRAINT fk_resource_tag_1
	    FOREIGN KEY (eid )
	    REFERENCES resource (eid )
	    ON DELETE CASCADE
	    ON UPDATE CASCADE,
	  CONSTRAINT fk_resource_tag_2
	    FOREIGN KEY (uid )
	    REFERENCES users (uid )
	    ON DELETE CASCADE
	    ON UPDATE CASCADE);";


	$uploaded="CREATE  TABLE  uploaded (
	  id SERIAL ,
	  eid INTEGER NULL DEFAULT NULL ,
	  fname VARCHAR(255) NULL DEFAULT NULL ,
	  uid INTEGER NULL ,
	  request_download BOOL  NULL ,
	  PRIMARY KEY (id) ,
	  CONSTRAINT fk_uploaded_1
	    FOREIGN KEY (eid )
	    REFERENCES resource (eid )
	    ON DELETE CASCADE
	    ON UPDATE CASCADE,
	  CONSTRAINT fk_uploaded_2
	    FOREIGN KEY (uid )
	    REFERENCES users (uid )
	    ON DELETE CASCADE
	    ON UPDATE CASCADE);";



	$download_req="CREATE  TABLE  download_request (
	  download_id SERIAL ,
	  id INTEGER NULL ,
	  eid INTEGER NULL ,
	  uid INTEGER NULL ,
	  status VARCHAR(45) NULL ,
	  PRIMARY KEY (download_id) ,
	  CONSTRAINT fk_download_request_1
	    FOREIGN KEY (uid )
	    REFERENCES users (uid )
	    ON DELETE CASCADE
	    ON UPDATE CASCADE,
	  CONSTRAINT fk_download_request_2
	    FOREIGN KEY (eid )
	    REFERENCES resource (eid )
	    ON DELETE CASCADE
	    ON UPDATE CASCADE,
	  CONSTRAINT fk_download_request_3
	    FOREIGN KEY (id )
	    REFERENCES uploaded (id )
	    ON DELETE CASCADE
	    ON UPDATE CASCADE)
	 ;";

	$ins_mod="CREATE TABLE  installed_modules (
	   mod_id SERIAL,
	   module_name VARCHAR(255) NOT NULL	
	) 
	 ;";

	$insert="INSERT INTO users values('-1','automatic','automatic','automatic','automatic','automatic','automatic','automatic','automatic');";
	
?>
