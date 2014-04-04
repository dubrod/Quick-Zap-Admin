DROP TABLE IF EXISTS `admin`;

CREATE TABLE `admin` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL DEFAULT '',
  `password` varchar(255) NOT NULL DEFAULT '',
  `active` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

LOCK TABLES `admin` WRITE;
/*!40000 ALTER TABLE `admin` DISABLE KEYS */;

INSERT INTO `admin` (`id`, `username`, `password`, `active`)
VALUES
	(1,'admin','MAKE_YOUR_OWN',1);

/*!40000 ALTER TABLE `admin` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table mgr_about
# ------------------------------------------------------------

DROP TABLE IF EXISTS `mgr_about`;

CREATE TABLE `mgr_about` (
  `about_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `about_title` varchar(255) NOT NULL DEFAULT '',
  `about_post` text NOT NULL,
  `about_cached` text,
  PRIMARY KEY (`about_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

LOCK TABLES `mgr_about` WRITE;
/*!40000 ALTER TABLE `mgr_about` DISABLE KEYS */;

INSERT INTO `mgr_about` (`about_id`, `about_title`, `about_post`, `about_cached`)
VALUES
	(1,'About Zap','## This is an H2 ##\n![img-left-align](/uploads/logo.jpg)\n> This is a blockquote with two paragraphs. Lorem ipsum dolor sit amet,\n> consectetuer adipiscing elit. Aliquam hendrerit mi posuere lectus.\n\n\n[This link](http://example.net/) is very simple to make.\n\n\n*emphasis statement that will be in italics*','<h2>This is an H2</h2>\n\n<p><img src=\"/uploads/ics-logo.jpg\" class=\"img-left-align\" /></p>\n\n<blockquote>\n  <p>This is a blockquote with two paragraphs. Lorem ipsum dolor sit amet,\n  consectetuer adipiscing elit. Aliquam hendrerit mi posuere lectus.</p>\n</blockquote>\n\n<p><a href=\"http://example.net/\">This link</a> is very simple to make.</p>\n\n<p><em>emphasis statement that will be in italics</em></p>\n');

/*!40000 ALTER TABLE `mgr_about` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table mgr_blog
# ------------------------------------------------------------

DROP TABLE IF EXISTS `mgr_blog`;

CREATE TABLE `mgr_blog` (
  `blog_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `blog_title` varchar(255) NOT NULL DEFAULT '',
  `blog_date` varchar(255) NOT NULL DEFAULT '',
  `blog_post` text NOT NULL,
  `blog_url` varchar(255) NOT NULL DEFAULT '',
  `blog_cached` text NOT NULL,
  `blog_active` int(11) NOT NULL,
  PRIMARY KEY (`blog_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

LOCK TABLES `mgr_blog` WRITE;
/*!40000 ALTER TABLE `mgr_blog` DISABLE KEYS */;

INSERT INTO `mgr_blog` (`blog_id`, `blog_title`, `blog_date`, `blog_post`, `blog_url`, `blog_cached`, `blog_active`)
VALUES
	(2,'First Blog Post','03/24/2014','**Proin auctor ac justo sed faucibus**. Sed eleifend molestie convallis? Donec mauris mauris, scelerisque a dictum et, posuere vel tortor. Lorem ipsum dolor sit amet, consectetur adipiscing elit. In volutpat risus ut bibendum varius! Morbi a orci eu erat sagittis tincidunt ut eu leo. Morbi iaculis lorem metus; vel tempus risus placerat vitae. Duis dictum mauris augue, dignissim interdum eros euismod pharetra! Duis a est at nunc aliquet scelerisque blandit quis ante. Duis gravida tortor non condimentum rutrum? Duis suscipit semper semper. Integer accumsan velit non laoreet euismod. Aliquam eu posuere sem, nec elementum neque.\n\nNunc euismod odio quis mauris cursus, id tincidunt elit ornare. Nunc id accumsan lacus; a porta velit. Nam laoreet ligula a elit gravida pretium aliquet sit amet massa. Integer vitae dui semper, pellentesque nunc eget, condimentum mi. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Donec molestie neque erat, porta tincidunt odio vestibulum sit amet. Proin egestas leo id erat porttitor, et blandit nunc imperdiet. Ut malesuada a mi ut fringilla. Etiam vitae ligula ac metus bibendum consequat pulvinar sed nibh. Nullam sit amet turpis eget orci auctor faucibus. Quisque eleifend tristique justo, id faucibus nisl lacinia ut. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Mauris interdum, nulla id vehicula porttitor, erat est hendrerit tellus, eget mollis nisi massa quis nunc. Vestibulum dui tortor, placerat non gravida a, rhoncus tempus ligula.\n\nNullam interdum tincidunt neque a consectetur. Cras porta molestie dui, sit amet ultrices sem convallis eu. Morbi imperdiet elit quis justo molestie tristique. Nulla a adipiscing nisi. Fusce tortor ante, fermentum sagittis pharetra scelerisque, auctor at odio. Sed odio purus, pretium vel erat ut, convallis consectetur leo. Integer feugiat laoreet dui; nec blandit arcu sodales id. Ut facilisis metus vulputate; luctus quam eu, ornare mauris. Curabitur eu sapien lacinia, sagittis risus non, ornare nisi. Mauris justo felis, posuere ac aliquam non; ullamcorper sit amet enim. Vestibulum semper vestibulum leo, quis sodales sem dapibus posuere. Duis adipiscing magna vel dolor elementum suscipit eu non dui. Cras volutpat augue sapien, vitae feugiat justo molestie vitae.\n','first-blog-post','<p><strong>Proin auctor ac justo sed faucibus</strong>. Sed eleifend molestie convallis? Donec mauris mauris, scelerisque a dictum et, posuere vel tortor. Lorem ipsum dolor sit amet, consectetur adipiscing elit. In volutpat risus ut bibendum varius! Morbi a orci eu erat sagittis tincidunt ut eu leo. Morbi iaculis lorem metus; vel tempus risus placerat vitae. Duis dictum mauris augue, dignissim interdum eros euismod pharetra! Duis a est at nunc aliquet scelerisque blandit quis ante. Duis gravida tortor non condimentum rutrum? Duis suscipit semper semper. Integer accumsan velit non laoreet euismod. Aliquam eu posuere sem, nec elementum neque.</p>\n\n<p>Nunc euismod odio quis mauris cursus, id tincidunt elit ornare. Nunc id accumsan lacus; a porta velit. Nam laoreet ligula a elit gravida pretium aliquet sit amet massa. Integer vitae dui semper, pellentesque nunc eget, condimentum mi. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Donec molestie neque erat, porta tincidunt odio vestibulum sit amet. Proin egestas leo id erat porttitor, et blandit nunc imperdiet. Ut malesuada a mi ut fringilla. Etiam vitae ligula ac metus bibendum consequat pulvinar sed nibh. Nullam sit amet turpis eget orci auctor faucibus. Quisque eleifend tristique justo, id faucibus nisl lacinia ut. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Mauris interdum, nulla id vehicula porttitor, erat est hendrerit tellus, eget mollis nisi massa quis nunc. Vestibulum dui tortor, placerat non gravida a, rhoncus tempus ligula.</p>\n\n<p>Nullam interdum tincidunt neque a consectetur. Cras porta molestie dui, sit amet ultrices sem convallis eu. Morbi imperdiet elit quis justo molestie tristique. Nulla a adipiscing nisi. Fusce tortor ante, fermentum sagittis pharetra scelerisque, auctor at odio. Sed odio purus, pretium vel erat ut, convallis consectetur leo. Integer feugiat laoreet dui; nec blandit arcu sodales id. Ut facilisis metus vulputate; luctus quam eu, ornare mauris. Curabitur eu sapien lacinia, sagittis risus non, ornare nisi. Mauris justo felis, posuere ac aliquam non; ullamcorper sit amet enim. Vestibulum semper vestibulum leo, quis sodales sem dapibus posuere. Duis adipiscing magna vel dolor elementum suscipit eu non dui. Cras volutpat augue sapien, vitae feugiat justo molestie vitae.</p>\n',1),
	(3,'Second Blog Post Today','03/26/2013','Proin auctor ac justo sed faucibus. Sed eleifend molestie convallis? Donec mauris mauris, scelerisque a dictum et, posuere vel tortor. Lorem ipsum dolor sit amet, consectetur adipiscing elit. In volutpat risus ut bibendum varius! Morbi a orci eu erat sagittis tincidunt ut eu leo. Morbi iaculis lorem metus; vel tempus risus placerat vitae. Duis dictum mauris augue, dignissim interdum eros euismod pharetra! Duis a est at nunc aliquet scelerisque blandit quis ante. Duis gravida tortor non condimentum rutrum? Duis suscipit semper semper. Integer accumsan velit non laoreet euismod. Aliquam eu posuere sem, nec elementum neque.\n\n**Nunc euismod odio quis mauris cursus**, id tincidunt elit ornare. Nunc id accumsan lacus; a porta velit. Nam laoreet ligula a elit gravida pretium aliquet sit amet massa. Integer vitae dui semper, pellentesque nunc eget, condimentum mi. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Donec molestie neque erat, porta tincidunt odio vestibulum sit amet. Proin egestas leo id erat porttitor, et blandit nunc imperdiet. Ut malesuada a mi ut fringilla. Etiam vitae ligula ac metus bibendum consequat pulvinar sed nibh. Nullam sit amet turpis eget orci auctor faucibus. Quisque eleifend tristique justo, id faucibus nisl lacinia ut. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Mauris interdum, nulla id vehicula porttitor, erat est hendrerit tellus, eget mollis nisi massa quis nunc. Vestibulum dui tortor, placerat non gravida a, rhoncus tempus ligula.\n\nNullam interdum tincidunt neque a consectetur. Cras porta molestie dui, sit amet ultrices sem convallis eu. Morbi imperdiet elit quis justo molestie tristique. Nulla a adipiscing nisi. Fusce tortor ante, fermentum sagittis pharetra scelerisque, auctor at odio. Sed odio purus, pretium vel erat ut, convallis consectetur leo. Integer feugiat laoreet dui; nec blandit arcu sodales id. Ut facilisis metus vulputate; luctus quam eu, ornare mauris. Curabitur eu sapien lacinia, sagittis risus non, ornare nisi. Mauris justo felis, posuere ac aliquam non; ullamcorper sit amet enim. Vestibulum semper vestibulum leo, quis sodales sem dapibus posuere. Duis adipiscing magna vel dolor elementum suscipit eu non dui. Cras volutpat augue sapien, vitae feugiat justo molestie vitae.\n','second-blog-post-today','<p>Proin auctor ac justo sed faucibus. Sed eleifend molestie convallis? Donec mauris mauris, scelerisque a dictum et, posuere vel tortor. Lorem ipsum dolor sit amet, consectetur adipiscing elit. In volutpat risus ut bibendum varius! Morbi a orci eu erat sagittis tincidunt ut eu leo. Morbi iaculis lorem metus; vel tempus risus placerat vitae. Duis dictum mauris augue, dignissim interdum eros euismod pharetra! Duis a est at nunc aliquet scelerisque blandit quis ante. Duis gravida tortor non condimentum rutrum? Duis suscipit semper semper. Integer accumsan velit non laoreet euismod. Aliquam eu posuere sem, nec elementum neque.</p>\n\n<p><strong>Nunc euismod odio quis mauris cursus</strong>, id tincidunt elit ornare. Nunc id accumsan lacus; a porta velit. Nam laoreet ligula a elit gravida pretium aliquet sit amet massa. Integer vitae dui semper, pellentesque nunc eget, condimentum mi. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Donec molestie neque erat, porta tincidunt odio vestibulum sit amet. Proin egestas leo id erat porttitor, et blandit nunc imperdiet. Ut malesuada a mi ut fringilla. Etiam vitae ligula ac metus bibendum consequat pulvinar sed nibh. Nullam sit amet turpis eget orci auctor faucibus. Quisque eleifend tristique justo, id faucibus nisl lacinia ut. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Mauris interdum, nulla id vehicula porttitor, erat est hendrerit tellus, eget mollis nisi massa quis nunc. Vestibulum dui tortor, placerat non gravida a, rhoncus tempus ligula.</p>\n\n<p>Nullam interdum tincidunt neque a consectetur. Cras porta molestie dui, sit amet ultrices sem convallis eu. Morbi imperdiet elit quis justo molestie tristique. Nulla a adipiscing nisi. Fusce tortor ante, fermentum sagittis pharetra scelerisque, auctor at odio. Sed odio purus, pretium vel erat ut, convallis consectetur leo. Integer feugiat laoreet dui; nec blandit arcu sodales id. Ut facilisis metus vulputate; luctus quam eu, ornare mauris. Curabitur eu sapien lacinia, sagittis risus non, ornare nisi. Mauris justo felis, posuere ac aliquam non; ullamcorper sit amet enim. Vestibulum semper vestibulum leo, quis sodales sem dapibus posuere. Duis adipiscing magna vel dolor elementum suscipit eu non dui. Cras volutpat augue sapien, vitae feugiat justo molestie vitae.</p>\n',1);

/*!40000 ALTER TABLE `mgr_blog` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table mgr_feedback
# ------------------------------------------------------------

DROP TABLE IF EXISTS `mgr_feedback`;

CREATE TABLE `mgr_feedback` (
  `feedback_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `feedback_title` varchar(255) NOT NULL DEFAULT '',
  `feedback_post` text NOT NULL,
  `feedback_cached` text NOT NULL,
  PRIMARY KEY (`feedback_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

LOCK TABLES `mgr_feedback` WRITE;
/*!40000 ALTER TABLE `mgr_feedback` DISABLE KEYS */;

INSERT INTO `mgr_feedback` (`feedback_id`, `feedback_title`, `feedback_post`, `feedback_cached`)
VALUES
	(1,'We want your Feedback!','# This is an H1 #\n> This is a blockquote with two paragraphs. Lorem ipsum dolor sit amet,\n> consectetuer adipiscing elit. Aliquam hendrerit mi posuere lectus.\n\n[This link](http://example.net/) has no title attribute.\n\n*emphasis statement*','<h1>This is an H1</h1>\n\n<blockquote>\n  <p>This is a blockquote with two paragraphs. Lorem ipsum dolor sit amet,\n  consectetuer adipiscing elit. Aliquam hendrerit mi posuere lectus.</p>\n</blockquote>\n\n<p><a href=\"http://example.net/\">This link</a> has no title attribute.</p>\n\n<p><em>emphasis statement</em></p>\n');

/*!40000 ALTER TABLE `mgr_feedback` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table mgr_helping
# ------------------------------------------------------------

DROP TABLE IF EXISTS `mgr_helping`;

CREATE TABLE `mgr_helping` (
  `helping_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `helping_title` varchar(255) NOT NULL DEFAULT '',
  `helping_post` text NOT NULL,
  `helping_cached` text NOT NULL,
  PRIMARY KEY (`helping_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

LOCK TABLES `mgr_helping` WRITE;
/*!40000 ALTER TABLE `mgr_helping` DISABLE KEYS */;

INSERT INTO `mgr_helping` (`helping_id`, `helping_title`, `helping_post`, `helping_cached`)
VALUES
	(1,'Help Zap','\n# This is an H1 #\n> This is a blockquote with two paragraphs. Lorem ipsum dolor sit amet,\n> consectetuer adipiscing elit. Aliquam hendrerit mi posuere lectus.\n\n[This link](http://example.net/) has no title attribute.\n\n*emphasis statement*','<h1>This is an H1</h1>\n\n<blockquote>\n  <p>This is a blockquote with two paragraphs. Lorem ipsum dolor sit amet,\n  consectetuer adipiscing elit. Aliquam hendrerit mi posuere lectus.</p>\n</blockquote>\n\n<p><a href=\"http://example.net/\">This link</a> has no title attribute.</p>\n\n<p><em>emphasis statement</em></p>\n');

/*!40000 ALTER TABLE `mgr_helping` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table mgr_pages
# ------------------------------------------------------------

DROP TABLE IF EXISTS `mgr_pages`;

CREATE TABLE `mgr_pages` (
  `p_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `db_suffix` varchar(255) NOT NULL DEFAULT '',
  `page_title` varchar(255) NOT NULL DEFAULT '',
  `sort_order` varchar(255) NOT NULL DEFAULT '',
  `url_alias` varchar(255) NOT NULL DEFAULT '',
  `top_nav` int(11) NOT NULL,
  `side_nav` int(11) NOT NULL,
  `main_nav` int(11) NOT NULL,
  `active` int(11) NOT NULL,
  `mobile_nav` int(11) NOT NULL,
  PRIMARY KEY (`p_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

LOCK TABLES `mgr_pages` WRITE;
/*!40000 ALTER TABLE `mgr_pages` DISABLE KEYS */;

INSERT INTO `mgr_pages` (`p_id`, `db_suffix`, `page_title`, `sort_order`, `url_alias`, `top_nav`, `side_nav`, `main_nav`, `active`, `mobile_nav`)
VALUES
	(1,'blog','Blog','0','blog',0,0,1,1,1),
	(3,'feedback','Feedback','2','feedback',1,0,0,1,1),
	(2,'about','About','1','about',1,0,0,1,0),
	(4,'helping','Helping','3','helping',1,0,0,1,0),
	(5,'categories','Categories','4','categories',0,1,0,0,1);

/*!40000 ALTER TABLE `mgr_pages` ENABLE KEYS */;
UNLOCK TABLES;