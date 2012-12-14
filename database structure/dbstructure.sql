
--DDL

 CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `created` int(11) NOT NULL,
  `modified` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `headshot` varchar(255) NOT NULL,
  `dateofbirth` date NOT NULL,
  `status` varchar(30) NOT NULL DEFAULT 'new',
  PRIMARY KEY (`user_id`)
) ENGINE=innodb  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


 CREATE TABLE IF NOT EXISTS `types` (
  `type_id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(30) NOT NULL,
  `total` int(11),
  PRIMARY KEY (`type_id`)
) ENGINE=innodb  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

INSERT INTO `types` (`type_id`, `type`) VALUES
(1, 'general'),
(2, 'modeling & simulation'),
(3, 'micro + nano fabrications'),
(4, 'knowledge bank'),
(5, 'hobbies');


CREATE TABLE IF NOT EXISTS `threads` (
  `thread_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(233) NOT NULL,
  `type` int(11) NOT NULL,
  `date` int(11) NOT NULL,
  `starter` int(11) NOT NULL,
  PRIMARY KEY (`thread_id`),
  FOREIGN KEY (`type`) references `types`(`type_id`) on update cascade on delete cascade,
  FOREIGN KEY (`starter`) references `users`(`user_id`) on update cascade on delete cascade
) ENGINE=innodb DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `posts` (
  `thread_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `time_stamp` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` longtext NOT NULL,
  FOREIGN KEY (`thread_id`) references `threads`(`thread_id`) on update cascade on delete cascade,
  FOREIGN KEY (`user_id`) references `users`(`user_id`) on update cascade on delete cascade,
  PRIMARY KEY (`thread_id`,`post_id`)
 ) ENGINE=innodb DEFAULT CHARSET=latin1;

--relationship tables

CREATE TABLE IF NOT EXISTS `type_of_relationship` (
  `relation_id` int(11) NOT NULL AUTO_INCREMENT,
  `relation_type` varchar(233) NOT NULL,
  PRIMARY KEY (`relation_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

INSERT INTO `type_of_relationship` (`relation_type`) VALUES
('friend'),
('friendship requesting'),
('following');
 
 CREATE TABLE IF NOT EXISTS `relationships` (
  `me` int(11) NOT NULL,
  `you` int(11) NOT NULL,
  `kind` int(11) NOT NULL,
  FOREIGN KEY (`me`) references `users`(`user_id`) on update cascade on delete cascade,
  FOREIGN KEY (`you`) references `users`(`user_id`) on update cascade on delete cascade,
  FOREIGN KEY (`kind`) references `type_of_relationship`(`relation_id`) on update cascade on delete cascade, 
  PRIMARY KEY (`me`, `you`),
  UNIQUE KEY `ure`(`me`, `you`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;
 
-- user's wall ** using a ac number for pk
CREATE TABLE IF NOT EXISTS `walls`(
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`owner` int(11) NOT NULL,
	`poster` int(11) NOT NULL,
	`time_stamp` int(11) NOT NULL,
	`content` varchar(255) NOT NULL,
	`shared` int(1) NOT NULL DEFAULT 1,
	FOREIGN KEY (`owner`) references `users`(`user_id`) on update cascade on delete cascade,
	FOREIGN KEY (`poster`) references `users`(`user_id`) on update cascade on delete cascade,
	PRIMARY KEY(`id`, `owner`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;
 
 
 -- ****************************************
 -- need to re-design the below tables
 -- ****************************************
 
 CREATE TABLE IF NOT EXISTS `images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `thread_id` int(11) DEFAULT NULL,
  `post_id` int(11) DEFAULT NULL,
  `poster` int(11) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `cottons` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `data` longtext NOT NULL,
  `user` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user` (`user`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
 
 
 --*************************************************************************
 -- Triggers
 --*************************************************************************
 -- keep tracks of types of thread being insert or deleting from the database
DELIMITER $$
 CREATE TRIGGER addThreadtotal AFTER INSERT ON `threads`
	FOR EACH ROW
	
	BEGIN
	DECLARE foo INT default 0;
	SET foo = (select `total` from `types` where `type_id` = NEW.type);
	SET foo = foo + 1;
	update `types` set `total` = foo where `type_id` = NEW.type;
 END;
 $$
DELIMITER ; 
 
DELIMITER $$
 CREATE TRIGGER subThreadtotal AFTER DELETE ON `threads`
	FOR EACH ROW
	
	BEGIN
	DECLARE foo INT default 0;
	SET foo = (select `total` from `types` where `type_id` = OLD.type);
	SET foo = foo - 1;
	update `types` set `total` = foo where `type_id` = OLD.type;
 END;
  $$
DELIMITER ; 


-- keep track of the number of replies of a THREAD
DELIMITER $$
 CREATE TRIGGER addPosttotal AFTER INSERT ON `posts`
	FOR EACH ROW
	
	BEGIN
	DECLARE foo INT default 0;
	SET foo = (select `total` from `threads` where `thread_id` = NEW.thread_id);
	SET foo = foo + 1;
	update `threads` set `total` = foo where `thread_id` = NEW.thread_id;
 END;
 $$
DELIMITER ; 

DELIMITER $$
 CREATE TRIGGER subPosttotal AFTER DELETE ON `posts`
	FOR EACH ROW
	
	BEGIN
	DECLARE foo INT default 0;
	SET foo = (select `total` from `threads` where `thread_id` = OLD.thread_id);
	SET foo = foo - 1;
	update `threads` set `total` = foo where `thread_id` = OLD.thread_id;
 END;
 $$
DELIMITER ; 

-- to keep track of the composite key of post table 
 DELIMITER $$
 CREATE TRIGGER postsCPK BEFORE INSERT ON `posts`
	FOR EACH ROW
	
	BEGIN
	DECLARE foo INT default 0;
	SET foo = ( select count(*) from `posts` where `thread_id` = NEW.thread_id);
	SET NEW.post_id = foo + 1;
 END;
 $$
DELIMITER ; 


