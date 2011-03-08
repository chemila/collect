-- 
-- 表的结构 `category`
-- 

DROP TABLE IF EXISTS `NEAT_category`;
CREATE TABLE `NEAT_category` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `pid` int(10) NOT NULL default '0',
  `orderid` int(10) NOT NULL default '0',
  `title` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM DEFAULT CHARSET=utf8 ;

INSERT INTO `NEAT_category` VALUES (1, 0, 1, '默认分类');

-- --------------------------------------------------------

-- 
-- 表的结构 `datas`
-- 

DROP TABLE IF EXISTS `NEAT_datas`;
CREATE TABLE `NEAT_datas` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `link_id` int(10) unsigned NOT NULL default '0',
  `rules` int(10) unsigned NOT NULL default '0',
  `title` varchar(200) NOT NULL default '',
  `body` text NOT NULL,
  `author` varchar(200) NOT NULL default '',
  `data_from` varchar(200) NOT NULL default '',
  `intro` text NOT NULL,
  `url` varchar(200) NOT NULL default '',
  `date` int(10) unsigned NOT NULL default '0',
  `img_geted` int(1) NOT NULL default '0',
  `swf_geted` int(1) NOT NULL default '0',
  PRIMARY KEY  (`id`,`link_id`),
  KEY `rules` (`rules`,`id`)
) TYPE=MyISAM DEFAULT CHARSET=utf8 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `export`
-- 

DROP TABLE IF EXISTS `NEAT_export`;
CREATE TABLE `NEAT_export` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `db_type` varchar(200) NOT NULL default '',
  `rules` text NOT NULL,
  `name` varchar(200) NOT NULL default '',
  `host` varchar(200) NOT NULL default '',
  `user` varchar(200) NOT NULL default '',
  `password` varchar(200) NOT NULL default '',
  `db_name` varchar(200) NOT NULL default '',
  `article_table` varchar(200) NOT NULL default '',
  `field_list` tinytext NOT NULL,
  `value_list` tinytext NOT NULL,
  `recount_fields_list` tinytext NOT NULL,
  `recount_fields_value_list` tinytext NOT NULL,
  `recount_rules_list` tinytext NOT NULL,
  `recount_rules_value_list` tinytext NOT NULL,
  `date` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM DEFAULT CHARSET=utf8 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `filter`
-- 

DROP TABLE IF EXISTS `NEAT_filter`;
CREATE TABLE `NEAT_filter` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `rule_id` int(10) unsigned NOT NULL default '0',
  `filter_name` varchar(50) NOT NULL default '',
  `filter_rule` text NOT NULL,
  `filter_multi` int(1) NOT NULL default '0',
  `filter_enter` int(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM DEFAULT CHARSET=utf8 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `links`
-- 

DROP TABLE IF EXISTS `NEAT_links`;
CREATE TABLE `NEAT_links` (
  `id` int(8) unsigned NOT NULL auto_increment,
  `title` varchar(200) NOT NULL default '',
  `url` varchar(200) NOT NULL default '',
  `rules` int(8) unsigned NOT NULL default '0',
  `date` int(10) unsigned NOT NULL default '0',
  `adopt` int(1) unsigned NOT NULL default '1',
  `import` int(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `url` (`url`),
  KEY `adopt` (`adopt`,`import`),
  KEY `rules` (`rules`,`id`)
) TYPE=MyISAM DEFAULT CHARSET=utf8 ;

-- --------------------------------------------------------

-- 
-- 表的结构 `rules`
-- 

DROP TABLE IF EXISTS `NEAT_rules`;
CREATE TABLE `rules` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `cid` int(10) NOT NULL default '0',
  `name` varchar(100) NOT NULL default '',
  `index_type` int(1) NOT NULL default '1',
  `url` text NOT NULL,
  `replaceRNT` varchar(7) NOT NULL default '0,0,0,0',
  `method` int(1) NOT NULL default '0',
  `posts` text NOT NULL,
  `cookies` text NOT NULL,
  `referer` varchar(100) NOT NULL default '',
  `useragent` varchar(100) NOT NULL default '',
  `page_start` int(11) NOT NULL default '0',
  `page_end` int(11) NOT NULL default '0',
  `page_rules` varchar(30) NOT NULL default ',,',
  `filter` text NOT NULL,
  `area_link` text NOT NULL,
  `area_title` text NOT NULL,
  `area_body` text NOT NULL,
  `area_body_page` text NOT NULL,
  `area_body_page_link` text NOT NULL,
  `area_author` text NOT NULL,
  `area_from` text NOT NULL,
  `area_intro` text NOT NULL,
  `multi_link` int(1) unsigned NOT NULL default '1',
  `multi_title` int(1) unsigned NOT NULL default '1',
  `multi_body` int(1) unsigned NOT NULL default '1',
  `multi_body_page` int(1) NOT NULL default '0',
  `multi_body_page_link` int(1) NOT NULL default '0',
  `multi_author` int(1) NOT NULL default '0',
  `multi_from` int(1) NOT NULL default '0',
  `multi_intro` int(1) NOT NULL default '0',
  `enter_link` int(1) unsigned NOT NULL default '0',
  `enter_title` int(1) unsigned NOT NULL default '0',
  `enter_body` int(1) unsigned NOT NULL default '0',
  `enter_body_page` int(1) NOT NULL default '0',
  `enter_body_page_link` int(1) NOT NULL default '0',
  `enter_author` int(1) NOT NULL default '0',
  `enter_from` int(1) NOT NULL default '0',
  `enter_intro` int(1) NOT NULL default '0',
  `link_num` int(10) unsigned NOT NULL default '0',
  `import_num` int(10) unsigned NOT NULL default '0',
  `date` int(10) unsigned NOT NULL default '0',
  `body_page_type` int(1) NOT NULL default '0',
  `link_replace` varchar(200) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `name` (`name`),
  KEY `cid` (`cid`)
) TYPE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

