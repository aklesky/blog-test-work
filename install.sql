# ************************************************************
# Sequel Pro SQL dump
# Version 4135
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Host: localhost (MySQL 5.5.38)
# Database: blogs
# Generation Time: 2014-12-07 17:08:19 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table blog_comments
# ------------------------------------------------------------

DROP TABLE IF EXISTS `blog_comments`;

CREATE TABLE `blog_comments` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `post_id` int(12) DEFAULT NULL,
  `commentator` varchar(125) DEFAULT NULL,
  `comment` text,
  `comment_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `post_id` (`post_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

LOCK TABLES `blog_comments` WRITE;
/*!40000 ALTER TABLE `blog_comments` DISABLE KEYS */;

INSERT INTO `blog_comments` (`id`, `post_id`, `commentator`, `comment`, `comment_date`)
VALUES
	(1,1,'Service Comment','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam lobortis urna in porta molestie. Aliquam sit amet ipsum dui. Cras ac est in tortor malesuada facilisis. Nunc feugiat blandit ante, in feugiat est. Morbi sollicitudin lorem in ultricies lobortis. Nunc sem leo, tincidunt nec interdum nec, mattis sed dolor. ','2014-12-07 14:44:19'),
	(2,1,'Aklesky Comment','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam lobortis urna in porta molestie. Aliquam sit amet ipsum dui. Cras ac est in tortor malesuada facilisis. Nunc feugiat blandit ante, in feugiat est. Morbi sollicitudin lorem in ultricies lobortis. Nunc sem leo, tincidunt nec interdum nec, mattis sed dolor. ','2014-12-07 14:44:36'),
	(3,2,'Service Comment','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam lobortis urna in porta molestie. Aliquam sit amet ipsum dui. Cras ac est in tortor malesuada facilisis. Nunc feugiat blandit ante, in feugiat est. Morbi sollicitudin lorem in ultricies lobortis. Nunc sem leo, tincidunt nec interdum nec, mattis sed dolor. ','2014-07-12 15:53:26'),
	(4,2,'Aklesky Comment','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam lobortis urna in porta molestie. Aliquam sit amet ipsum dui. Cras ac est in tortor malesuada facilisis. Nunc feugiat blandit ante, in feugiat est. Morbi sollicitudin lorem in ultricies lobortis. Nunc sem leo, tincidunt nec interdum nec, mattis sed dolor. ','2014-07-12 16:24:33');

/*!40000 ALTER TABLE `blog_comments` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table blog_posts
# ------------------------------------------------------------

DROP TABLE IF EXISTS `blog_posts`;

CREATE TABLE `blog_posts` (
  `id` int(15) unsigned NOT NULL AUTO_INCREMENT,
  `blog_id` int(12) DEFAULT NULL,
  `user_id` int(12) DEFAULT NULL,
  `post_slug_tag` varchar(125) DEFAULT NULL,
  `post_title` varchar(125) DEFAULT '',
  `post_picture` varchar(255) DEFAULT NULL,
  `post_url` varchar(125) DEFAULT NULL,
  `post_text` text,
  `post_date` datetime DEFAULT '0000-00-00 00:00:00',
  `post_schedule` date DEFAULT NULL,
  `post_modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `blog_id` (`blog_id`),
  KEY `user_id` (`user_id`),
  KEY `post_slug_tag` (`post_slug_tag`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

LOCK TABLES `blog_posts` WRITE;
/*!40000 ALTER TABLE `blog_posts` DISABLE KEYS */;

INSERT INTO `blog_posts` (`id`, `blog_id`, `user_id`, `post_slug_tag`, `post_title`, `post_picture`, `post_url`, `post_text`, `post_date`, `post_schedule`, `post_modified`)
VALUES
	(1,1,1,'php-storm-plugin','PHPStorm Plugin','c7acc6c51df8b617419c95e4e2feacafPluginScreen.png','http://blog.local/blog/view/php-storm-plugin','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam lobortis urna in porta molestie. Aliquam sit amet ipsum dui. Cras ac est in tortor malesuada facilisis. Nunc feugiat blandit ante, in feugiat est. Morbi sollicitudin lorem in ultricies lobortis. Nunc sem leo, tincidunt nec interdum nec, mattis sed dolor. Etiam vulputate justo vitae dolor sodales, vitae pulvinar ligula dignissim. Suspendisse molestie at leo sit amet blandit. Ut lobortis congue lacus vitae semper. Curabitur maximus orci ut neque suscipit tristique. Fusce sit amet tincidunt eros. Suspendisse in feugiat augue. Donec sit amet nisl elit.</p>\r\n<p>Aenean eget magna sem. Nullam efficitur consectetur rutrum. Suspendisse faucibus, lectus quis aliquam fringilla, odio nisl faucibus magna, vel convallis urna ipsum non dolor. Ut dapibus et mi eget interdum. Praesent luctus magna eu rhoncus blandit. Cras justo augue, tempus a erat eu, varius mollis dolor. In viverra pellentesque lacus. Fusce ultrices luctus elit, eu rutrum sem accumsan vitae. Nullam in congue sem. Maecenas vitae nisl feugiat tortor fringilla suscipit. Nunc porta nisl tincidunt ligula cursus pulvinar. Donec eu condimentum diam. Morbi felis ipsum, rhoncus at magna quis, gravida ultrices mauris. Proin vulputate lobortis leo ut eleifend. Vestibulum fringilla, dui id finibus vehicula, orci sapien fermentum lorem, quis pharetra velit velit vel nulla.</p>','2014-07-12 17:57:44','2014-12-07','2014-12-07 19:05:08'),
	(2,1,1,'future-post','Future Post','c7c080c4ccd14fb0c6fedcd6cae1d37c6.jpg','','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam lobortis urna in porta molestie. Aliquam sit amet ipsum dui. Cras ac est in tortor malesuada facilisis. Nunc feugiat blandit ante, in feugiat est. Morbi sollicitudin lorem in ultricies lobortis. Nunc sem leo, tincidunt nec interdum nec, mattis sed dolor. Etiam vulputate justo vitae dolor sodales, vitae pulvinar ligula dignissim. Suspendisse molestie at leo sit amet blandit. Ut lobortis congue lacus vitae semper. Curabitur maximus orci ut neque suscipit tristique. Fusce sit amet tincidunt eros. Suspendisse in feugiat augue. Donec sit amet nisl elit.</p>\r\n<p>Aenean eget magna sem. Nullam efficitur consectetur rutrum. Suspendisse faucibus, lectus quis aliquam fringilla, odio nisl faucibus magna, vel convallis urna ipsum non dolor. Ut dapibus et mi eget interdum. Praesent luctus magna eu rhoncus blandit. Cras justo augue, tempus a erat eu, varius mollis dolor. In viverra pellentesque lacus. Fusce ultrices luctus elit, eu rutrum sem accumsan vitae. Nullam in congue sem. Maecenas vitae nisl feugiat tortor fringilla suscipit. Nunc porta nisl tincidunt ligula cursus pulvinar. Donec eu condimentum diam. Morbi felis ipsum, rhoncus at magna quis, gravida ultrices mauris. Proin vulputate lobortis leo ut eleifend. Vestibulum fringilla, dui id finibus vehicula, orci sapien fermentum lorem, quis pharetra velit velit vel nulla.</p>','2014-07-12 16:15:37','2014-12-08','2014-12-07 19:05:09'),
	(3,1,1,'service-post','Service Post','c498021132efc67b58dde4cc256f133b6-pack-secrets-revealed-main_0.jpg','','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam lobortis urna in porta molestie. Aliquam sit amet ipsum dui. Cras ac est in tortor malesuada facilisis. Nunc feugiat blandit ante, in feugiat est. Morbi sollicitudin lorem in ultricies lobortis. Nunc sem leo, tincidunt nec interdum nec, mattis sed dolor. Etiam vulputate justo vitae dolor sodales, vitae pulvinar ligula dignissim. Suspendisse molestie at leo sit amet blandit. Ut lobortis congue lacus vitae semper. Curabitur maximus orci ut neque suscipit tristique. Fusce sit amet tincidunt eros. Suspendisse in feugiat augue. Donec sit amet nisl elit.</p>\r\n<p>Aenean eget magna sem. Nullam efficitur consectetur rutrum. Suspendisse faucibus, lectus quis aliquam fringilla, odio nisl faucibus magna, vel convallis urna ipsum non dolor. Ut dapibus et mi eget interdum. Praesent luctus magna eu rhoncus blandit. Cras justo augue, tempus a erat eu, varius mollis dolor. In viverra pellentesque lacus. Fusce ultrices luctus elit, eu rutrum sem accumsan vitae. Nullam in congue sem. Maecenas vitae nisl feugiat tortor fringilla suscipit. Nunc porta nisl tincidunt ligula cursus pulvinar. Donec eu condimentum diam. Morbi felis ipsum, rhoncus at magna quis, gravida ultrices mauris. Proin vulputate lobortis leo ut eleifend. Vestibulum fringilla, dui id finibus vehicula, orci sapien fermentum lorem, quis pharetra velit velit vel nulla.</p>','2014-07-12 17:50:53','2014-12-07','2014-12-07 19:05:10');

/*!40000 ALTER TABLE `blog_posts` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table blog
# ------------------------------------------------------------

DROP TABLE IF EXISTS `blog`;

CREATE TABLE `blog` (
  `id` int(15) unsigned NOT NULL AUTO_INCREMENT,
  `blog_title` varchar(125) NOT NULL DEFAULT '',
  `blog_description` text,
  `blog_post_limit` tinyint(2) DEFAULT NULL,
  `blog_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `blog_created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

LOCK TABLES `blog` WRITE;
/*!40000 ALTER TABLE `blog` DISABLE KEYS */;

INSERT INTO `blog` (`id`, `blog_title`, `blog_description`, `blog_post_limit`, `blog_modified`, `blog_created`)
VALUES
	(1,'Aklesky\'s Blog Service','The official example template of creating a blog with Bootstrap.',5,'2014-12-07 19:04:53','2014-07-12 00:05:22');

/*!40000 ALTER TABLE `blog` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(125) NOT NULL DEFAULT '',
  `password` varchar(125) NOT NULL DEFAULT '',
  `firstname` varchar(225) NOT NULL DEFAULT '',
  `lastname` varchar(255) DEFAULT '',
  `email` varchar(125) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `username` (`username`),
  KEY `password` (`password`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;

INSERT INTO `users` (`id`, `username`, `password`, `firstname`, `lastname`, `email`)
VALUES
	(1,'Service','sha256:1000:smUUHEy4dshu1PZrb0uqgt/gjpVTkDzj:qdXMuaiiro9NytnXGgDZgCWixCQl1m/K','Service','Test-Work','admin@admin.mt');

/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
