CREATE TABLE  `memb_adminsessions` (
  `session_id` varchar(255) NOT NULL,
  `session_date` datetime NOT NULL,
  PRIMARY KEY  (`session_id`)
) COMMENT='List of Membership v1.0 admin sessions';


CREATE TABLE  `memb_usersessions` (
  `session_id` varchar(255) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `session_date` datetime NOT NULL,
  PRIMARY KEY  (`session_id`)
) COMMENT='List of Membership v1.0 user sessions';


CREATE TABLE  `memb_config` (
  `config_name` varchar(255) NOT NULL,
  `config_value` text NOT NULL,
  PRIMARY KEY  (`config_name`)
) COMMENT='List of Membership v1.0 config values';


CREATE TABLE  `memb_customfds` (
  `field_id` bigint(20) NOT NULL auto_increment,
  `field_name` varchar(255) NOT NULL,
  `is_required` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`field_id`)
) COMMENT='List of Membership v1.0 custom fields';


CREATE TABLE  `memb_userlist` (
  `user_id` bigint(20) NOT NULL auto_increment,
  `user_email` varchar(255) NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `user_password` varchar(255) NOT NULL,
  `user_status` varchar(50) NOT NULL,
  `register_date` datetime NOT NULL,
  `last_ip` varchar(255) NOT NULL,
  `last_access` datetime NOT NULL,
  `allow_delete` tinyint(1) NOT NULL,
  `user_in_list` tinyint(1) NOT NULL,
  `custom_fields` longtext NOT NULL,
  PRIMARY KEY  (`user_id`)
) COMMENT='List of Membership v1.0 users' ;


INSERT INTO `memb_config` (`config_name`, `config_value`) VALUES
('CF_CAPTHCA', 'TEXT'),
('CF_BACKGROUNDS', 'extras/background_1.png,extras/background_2.png,extras/background_3.png'),
('CF_FONTS', 'extras/COOPBLA.TTF,extras/TIMES.TTF'),
('CF_SIZE', '18'),
('CF_QUESTIONFILE', 'extras/questions.txt'),
('CF_E_REG', '1'),
('CF_USER_LIMIT', '0'),
('CF_E_VER', '1'),
('CF_E_DEL', '1'),
('CF_ENCDEC', 'klJM()*Ulkopi324p'),
('CF_LENGH', '5'),
('CF_ADMINNAME', 'admin'),
('CF_ADMINPASSWORD', 'admin2008'),
('CF_SITENAME', 'MemberShip'),
('CF_SITEEMAIL', 'support@fkeas4lksm.com'),
('CF_REGURL', 'http://localhost/ap/login.php'),
('CF_FDACCESS', '/home/domain.com/Membership/extras/.fdaccess');


INSERT INTO `memb_customfds` (`field_id`, `field_name`, `is_required`) VALUES
(6, 'Gender', 0),
(5, 'Age', 1);
