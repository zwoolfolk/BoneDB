CREATE TABLE `bone` (
	`bone_id` int(11) AUTO_INCREMENT,
	`bone_number` int(11),
	`side` varchar(10),
	`sex` varchar(10),
	CONSTRAINT `UC_bone` UNIQUE (`bone_number`),
	PRIMARY KEY(`bone_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE `type` (
	`type_id` int(11) AUTO_INCREMENT,
	`bone_type` varchar(100) NOT NULL,
	CONSTRAINT `UC_type` UNIQUE (`bone_type`),
	PRIMARY KEY(`type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE `bag` (
	`bag_id` int(11) AUTO_INCREMENT,
	`bag_number` int(11) NOT NULL,
	`bag_provenance` varchar(255),
	CONSTRAINT `UC_bag` UNIQUE (`bag_number`),
	PRIMARY KEY(`bag_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE `box` (
	`box_id` int(11) AUTO_INCREMENT,
	`box_number` int(11) NOT NULL,
	CONSTRAINT `UC_box` UNIQUE (`box_number`),
	PRIMARY KEY(`box_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE `picture` (
	`picture_id` int(11) AUTO_INCREMENT,
	`picture_number` int(11) NOT NULL,
	CONSTRAINT `UC_picture` UNIQUE (`picture_number`),
	PRIMARY KEY(`picture_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE `ancestry` (
	`ancestry_id` int(11) AUTO_INCREMENT,
	`ancestry_type` varchar(255) NOT NULL,
	CONSTRAINT `UC_ancestry` UNIQUE (`ancestry_type`),
	PRIMARY KEY(`ancestry_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE `individual` (
	`individual_id` int(11) AUTO_INCREMENT,
	PRIMARY KEY(`individual_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE `sample` (
	`sample_id` int(11) AUTO_INCREMENT,
	`sample_type` varchar(255) NOT NULL,
	CONSTRAINT `UC_sample` UNIQUE (`sample_type`),
	PRIMARY KEY(`sample_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE `age` (
	`age_id` int(11) AUTO_INCREMENT,
	`age_type` varchar(255),
	`age_range` varchar(255),
	CONSTRAINT `UC_age` UNIQUE (`age_type`, `age_range`),
	PRIMARY KEY(`age_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;






CREATE TABLE `bone_bag` (
	`bone_id` int(11) NOT NULL,
	`bag_id` int(11) NOT NULL,
	CONSTRAINT `bone_bag_fk1` FOREIGN KEY (`bone_id`) REFERENCES `bone` (`bone_id`),
	CONSTRAINT `bone_bag_fk2` FOREIGN KEY (`bag_id`) REFERENCES `bag` (`bag_id`),
	CONSTRAINT `UC_bone_bag` UNIQUE (`bone_id`),
	PRIMARY KEY(`bone_id`, `bag_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE `bone_type` (
	`bone_id` int(11) NOT NULL,
	`type_id` int(11) NOT NULL,
	CONSTRAINT `bone_type_fk1` FOREIGN KEY (`bone_id`) REFERENCES `bone` (`bone_id`),
	CONSTRAINT `bone_type_fk2` FOREIGN KEY (`type_id`) REFERENCES `type` (`type_id`),
	PRIMARY KEY(`bone_id`, `type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE `bag_box` (
	`bag_id` int(11) NOT NULL,
	`box_id` int(11) NOT NULL,
	CONSTRAINT `bag_box_fk1` FOREIGN KEY (`bag_id`) REFERENCES `bag` (`bag_id`),
	CONSTRAINT `bag_box_fk2` FOREIGN KEY (`box_id`) REFERENCES `box` (`box_id`),
	CONSTRAINT `UC_bag_box` UNIQUE (`bag_id`),
	PRIMARY KEY(`bag_id`, `box_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE `bone_picture` (
	`bone_id` int(11) NOT NULL,
	`picture_id` int(11) NOT NULL,
	CONSTRAINT `bone_picture_fk1` FOREIGN KEY (`bone_id`) REFERENCES `bone` (`bone_id`),
	CONSTRAINT `bone_picture_fk2` FOREIGN KEY (`picture_id`) REFERENCES `picture` (`picture_id`),
	CONSTRAINT `UC_bone_picture` UNIQUE (`picture_id`),
	PRIMARY KEY(`bone_id`, `picture_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE `bone_ancestry` (
	`join_id` int(11) AUTO_INCREMENT,
	`bone_id` int(11) NOT NULL,
	`ancestry_id` int(11) NOT NULL,
	`ancestry_notes` text,
	CONSTRAINT `bone_ancestry_fk1` FOREIGN KEY (`bone_id`) REFERENCES `bone` (`bone_id`),
	CONSTRAINT `bone_ancestry_fk2` FOREIGN KEY (`ancestry_id`) REFERENCES `ancestry` (`ancestry_id`),
	CONSTRAINT `UC_bone_ancestry` UNIQUE (`bone_id`, `ancestry_id`),
	PRIMARY KEY(`join_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE `bone_sample` (
	`bone_id` int(11) NOT NULL,
	`sample_id` int(11) NOT NULL,
	CONSTRAINT `bone_sample_fk1` FOREIGN KEY (`bone_id`) REFERENCES `bone` (`bone_id`),
	CONSTRAINT `bone_sample_fk2` FOREIGN KEY (`sample_id`) REFERENCES `sample` (`sample_id`),
	CONSTRAINT `UC_bone_sample` UNIQUE (`sample_id`),
	PRIMARY KEY(`bone_id`, `sample_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE `bone_individual` (
	`bone_id` int(11) NOT NULL,
	`individual_id` int(11) NOT NULL,
	CONSTRAINT `bone_individual_fk1` FOREIGN KEY (`bone_id`) REFERENCES `bone` (`bone_id`),
	CONSTRAINT `bone_individual_fk2` FOREIGN KEY (`individual_id`) REFERENCES `individual` (`individual_id`),
	CONSTRAINT `UC_bone_individual` UNIQUE (`bone_id`),
	PRIMARY KEY(`bone_id`, `individual_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE `bone_age` (
	`bone_id` int(11) NOT NULL,
	`age_id` int(11) NOT NULL,
	CONSTRAINT `bone_age_fk1` FOREIGN KEY (`bone_id`) REFERENCES `bone` (`bone_id`),
	CONSTRAINT `bone_age_fk2` FOREIGN KEY (`age_id`) REFERENCES `age` (`age_id`),
	PRIMARY KEY(`bone_id`, `age_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;





INSERT INTO `age` (age_type, age_range) VALUES 
('sutures', 'under 20'),
('teeths', 'over 9000'),
('ear bonez', '3');

INSERT INTO `ancestry` (ancestry_type) VALUES 
('beagle'),
('like white on rice'),
('orange');

INSERT INTO `bag` (bag_number, bag_provenance) VALUES 
(90210, 'back yard'),
(20193, 'pig'),
(11111, 'tree');

INSERT INTO `bone` (bone_number, side, sex) VALUES 
(999, 'Left', 'M'),
(1094, 'Right', 'F'),
(1990, 'Left', 'M');

INSERT INTO `type` (bone_type) VALUES 
('Femur'),
('ear bone'),
('head bone');

INSERT INTO `box` (box_number) VALUES
(4),
(5),
(6);

INSERT INTO `individual` (individual_id) VALUES
(null),
(null),
(null);

INSERT INTO `picture` (picture_number) VALUES
(167),
(168),
(169);

INSERT INTO `sample` (sample_type) VALUES
('isotope'),
('DNA');




INSERT INTO `bag_box` (bag_id, box_id) VALUES
(1, 1),
(2, 2),
(3, 3);

INSERT INTO `bone_type` (bone_id, type_id) VALUES
(1, 1),
(2, 2),
(3, 3);

INSERT INTO `bone_age` (bone_id, age_id) VALUES
(1, 3),
(2, 2),
(1, 1);

INSERT INTO `bone_ancestry` (bone_id, ancestry_id) VALUES 
(1, 1),
(2, 2),
(2, 3),
(3, 3);

INSERT INTO `bone_bag` (bone_id, bag_id) VALUES 
(1, 1),
(2, 2),
(3, 3);

INSERT INTO `bone_individual` (bone_id, individual_id) VALUES 
(1, 1),
(2, 2),
(3, 3);

INSERT INTO `bone_picture` (bone_id, picture_id) VALUES 
(1, 1),
(2, 2),
(3, 3);

INSERT INTO `bone_sample` (bone_id, sample_id) VALUES 
(1, 1),
(3, 2);
