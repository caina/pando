-- added 14/07

alter table company add upload_path varchar(255) null;
alter table module add menu_id int(255) null 

CREATE TABLE `menu` (
  `id` int(250) NOT NULL AUTO_INCREMENT,
  `company_id` int(11) NOT NULL,
  `label` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;
