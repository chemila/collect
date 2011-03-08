-- phpMyAdmin SQL Dump
-- version 3.1.3.1
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2011 年 03 月 08 日 11:35
-- 服务器版本: 5.1.33
-- PHP 版本: 5.2.9

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- 数据库: `seo`
--

-- --------------------------------------------------------

--
-- 表的结构 `collect_category`
--

CREATE TABLE IF NOT EXISTS `collect_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) NOT NULL DEFAULT '0',
  `orderid` int(10) NOT NULL DEFAULT '0',
  `title` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=23 ;

--
-- 导出表中的数据 `collect_category`
--

INSERT INTO `collect_category` (`id`, `pid`, `orderid`, `title`) VALUES
(1, 0, 3, '默认分类'),
(22, 0, 6, '家庭影院'),
(4, 0, 1, '笔记本'),
(19, 0, 4, '洗衣机/干洗机'),
(12, 0, 1, '卡片相机');





-- phpMyAdmin SQL Dump
-- version 3.1.3.1
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2011 年 03 月 08 日 11:35
-- 服务器版本: 5.1.33
-- PHP 版本: 5.2.9

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- 数据库: `seo`
--

-- --------------------------------------------------------

--
-- 表的结构 `collect_datas`
--

CREATE TABLE IF NOT EXISTS `collect_datas` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `link_id` int(10) unsigned NOT NULL DEFAULT '0',
  `rules` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(500) NOT NULL,
  `body` text NOT NULL,
  `author` varchar(200) NOT NULL DEFAULT '',
  `data_from` varchar(200) NOT NULL DEFAULT '',
  `intro` text NOT NULL,
  `url` varchar(500) NOT NULL,
  `date` int(10) unsigned NOT NULL DEFAULT '0',
  `img_geted` int(1) NOT NULL DEFAULT '0',
  `swf_geted` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`,`link_id`),
  KEY `rules` (`rules`,`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=22726 ;




-- phpMyAdmin SQL Dump
-- version 3.1.3.1
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2011 年 03 月 08 日 11:36
-- 服务器版本: 5.1.33
-- PHP 版本: 5.2.9

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- 数据库: `seo`
--

-- --------------------------------------------------------

--
-- 表的结构 `collect_filter`
--

CREATE TABLE IF NOT EXISTS `collect_filter` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rule_id` int(10) unsigned NOT NULL DEFAULT '0',
  `filter_name` varchar(50) NOT NULL DEFAULT '',
  `filter_rule` text NOT NULL,
  `filter_multi` int(1) NOT NULL DEFAULT '0',
  `filter_enter` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;



-- phpMyAdmin SQL Dump
-- version 3.1.3.1
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2011 年 03 月 08 日 11:36
-- 服务器版本: 5.1.33
-- PHP 版本: 5.2.9

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- 数据库: `seo`
--

-- --------------------------------------------------------

--
-- 表的结构 `collect_links`
--

CREATE TABLE IF NOT EXISTS `collect_links` (
  `id` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL DEFAULT '',
  `url` varchar(200) NOT NULL DEFAULT '',
  `rules` int(8) unsigned NOT NULL DEFAULT '0',
  `date` int(10) unsigned NOT NULL DEFAULT '0',
  `adopt` int(1) unsigned NOT NULL DEFAULT '1',
  `import` int(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `url` (`url`),
  KEY `adopt` (`adopt`,`import`),
  KEY `rules` (`rules`,`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=17806 ;



-- phpMyAdmin SQL Dump
-- version 3.1.3.1
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2011 年 03 月 08 日 11:37
-- 服务器版本: 5.1.33
-- PHP 版本: 5.2.9

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- 数据库: `seo`
--

-- --------------------------------------------------------

--
-- 表的结构 `collect_rules`
--

CREATE TABLE IF NOT EXISTS `collect_rules` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cid` int(10) NOT NULL DEFAULT '0',
  `name` varchar(100) NOT NULL DEFAULT '',
  `index_type` int(1) NOT NULL DEFAULT '1',
  `url` text NOT NULL,
  `replaceRNT` varchar(9) NOT NULL DEFAULT '0,0,0,0,0',
  `method` int(1) NOT NULL DEFAULT '0',
  `posts` text NOT NULL,
  `cookies` text NOT NULL,
  `referer` varchar(100) NOT NULL DEFAULT '',
  `useragent` varchar(100) NOT NULL DEFAULT '',
  `page_start` int(11) NOT NULL DEFAULT '0',
  `page_end` int(11) NOT NULL DEFAULT '0',
  `page_rules` varchar(30) NOT NULL DEFAULT ',,',
  `filter` text NOT NULL,
  `area_link` text NOT NULL,
  `area_title` text NOT NULL,
  `area_body` text NOT NULL,
  `area_body_page` text NOT NULL,
  `area_body_page_link` text NOT NULL,
  `area_author` text NOT NULL,
  `area_from` text NOT NULL,
  `area_intro` text NOT NULL,
  `multi_link` int(1) unsigned NOT NULL DEFAULT '1',
  `multi_title` int(1) unsigned NOT NULL DEFAULT '1',
  `multi_body` int(1) unsigned NOT NULL DEFAULT '1',
  `multi_body_page` int(1) NOT NULL DEFAULT '0',
  `multi_body_page_link` int(1) NOT NULL DEFAULT '0',
  `multi_author` int(1) NOT NULL DEFAULT '0',
  `multi_from` int(1) NOT NULL DEFAULT '0',
  `multi_intro` int(1) NOT NULL DEFAULT '0',
  `enter_link` int(1) unsigned NOT NULL DEFAULT '0',
  `enter_title` int(1) unsigned NOT NULL DEFAULT '0',
  `enter_body` int(1) unsigned NOT NULL DEFAULT '0',
  `enter_body_page` int(1) NOT NULL DEFAULT '0',
  `enter_body_page_link` int(1) NOT NULL DEFAULT '0',
  `enter_author` int(1) NOT NULL DEFAULT '0',
  `enter_from` int(1) NOT NULL DEFAULT '0',
  `enter_intro` int(1) NOT NULL DEFAULT '0',
  `link_num` int(10) unsigned NOT NULL DEFAULT '0',
  `import_num` int(10) unsigned NOT NULL DEFAULT '0',
  `date` int(10) unsigned NOT NULL DEFAULT '0',
  `body_page_type` int(1) NOT NULL DEFAULT '0',
  `link_replace` varchar(200) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  KEY `cid` (`cid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=52 ;

--
-- 导出表中的数据 `collect_rules`
--

INSERT INTO `collect_rules` (`id`, `cid`, `name`, `index_type`, `url`, `replaceRNT`, `method`, `posts`, `cookies`, `referer`, `useragent`, `page_start`, `page_end`, `page_rules`, `filter`, `area_link`, `area_title`, `area_body`, `area_body_page`, `area_body_page_link`, `area_author`, `area_from`, `area_intro`, `multi_link`, `multi_title`, `multi_body`, `multi_body_page`, `multi_body_page_link`, `multi_author`, `multi_from`, `multi_intro`, `enter_link`, `enter_title`, `enter_body`, `enter_body_page`, `enter_body_page_link`, `enter_author`, `enter_from`, `enter_intro`, `link_num`, `import_num`, `date`, `body_page_type`, `link_replace`) VALUES
(1, 1, 'office商铺', 3, 'http://office.soufun.com/SearchOffice/%b1%b1%be%a9______%d0%b4%d7%d6%c2%a5__________[分页].htm', '1,1,1,1', 1, '', '', '', '', 1, 10, ',,', '', '<div class="info"><ul><li class="s1"><div class="name"><a href="[连接]" target="_blank">[标题]</a></div>', '<a href="/house/%b1%b1%be%a9_1010668157.htm" target="_blank">[标题]</a></div>', 'target="_blank">[内容]</a></div>', '<a href="[变数]">[分页区域]</a>', '', '', '', '', 1, 1, 1, 1, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1276128000, 0, ''),
(13, 4, '笔记本:newegg', 3, 'http://www.newegg.com.cn/SubCategory/970-[分页].htm', ',,,', 1, '', '', '', '', 1, 35, ',,', '', '<li class="name productName"><h4><a href="[连接]" title="[标题]">[变数]<font class=''description''>', '<div class="proHeader"><h1>[标题]</h1><p class="promoText">', '</td></tr><tr><th>型号</th><td>[内容]</td></tr><tr>', '', '', '<tr><th>品牌</th><td>[作者]</td></tr><tr><th>', '', '', 1, 1, 1, 1, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 290, 290, 1292515200, 0, ''),
(14, 4, '笔记本:360buy', 3, 'http://www.360buy.com/products/670-671-672-0-0-0-0-0-0-0-1-1-[分页].html', ',,,', 1, '', '', '', 'Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.04 (lucid) Firefox/3.6', 1, 26, ',,', '', '<div class=''p-name''><a target=''_blank'' href=''[连接]''>[标题]<font', '<div id="name">\r\n				<h1>[标题]<font', '<tr><td class="tdTitle">型号</td><td>[内容]</td></tr>', '', '', '<tr><td class="tdTitle">品牌</td><td>[作者]</td></tr>', '', '', 1, 1, 1, 1, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 802, 802, 1292515200, 0, ''),
(12, 4, '笔记本:coo8', 3, 'http://www.coo8.com/shop/list_10001_600_0_0_0_0_[分页]_0_0_0_0_0_0_0_0.htm', ',,,', 1, '', '', '', '', 1, 15, ',,', '', '<div class="Zi"><h1><a href="[连接]" target="_blank" title="[标题]">[变数]</a>', '<a id="hyGoodsName" href="[变数]">[标题]</a>\r\n', '<TD width="29%" height=25 bgColor=#FFFFFF class=tab align="left">型号</TD><TD width="51%" colspan="3" align="left" bgColor=#FFFFFF class=tab>[内容] </TD>', '', '', ' <a id="hyBrandName" href="[变数]">[作者]</a>', '', '', 1, 1, 1, 1, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 346, 346, 1292515200, 0, ''),
(15, 4, '笔记本:tmall淘宝商城', 3, 'http://list.3c.tmall.com/l/28-[分页]-_sq---50035768.htm#item-list', ',,,,', 1, '', 't=eb3c87a7ffa7c401a4e2ffca4f113914; uc1=x; passtime=1292603950858; cookie2=06f641d71f9ba3bfe487e93d044a79e1', 'http://list.3c.taobao.com/', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; zh-CN; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13', 1, 84, ',,', '', '<dd class="attribute"> \r\n		<h4>\r\n			<a href="[连接]" target="_blank">[标题]<span class="info">', '<div class="detail-hd">\r\n	<h3>\r\n		[标题]\r\n	</h3>\r\n', '<li title="产品名称">产品名称：[内容]</li>', '', '', '<li title="品牌">品牌:\r\n																																															[作者]\r\n																																			</li>\r\n', '', '', 1, 1, 1, 1, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 2326, 1880, 1292515200, 0, ''),
(16, 4, '笔记本:chinadrtv', 2, 'http://www.chinadrtv.com/shumayingyin/bijibendiannao/\r\nhttp://www.chinadrtv.com/shumayingyin/bijibendiannao/index1.shtm\r\nhttp://www.chinadrtv.com/shumayingyin/bijibendiannao/index2.shtm', '1,1,1,', 1, '', '', '', '', 0, 2, ',,', '', '<TD class=produce vAlign=top width=172                                 height=50><A                                 href=[连接] target=_blank>[标题]<font color=red>[变数]</font><SPAN                                 class=red_color></SPAN>', '笔记本电脑品牌分类</A>&nbsp;<IMG height=6 src="/images/arrow-orange.gif" width=4 align=absMiddle>&nbsp; <a href=[变数]>[变数]</a>&nbsp;&gt;[标题]</TD>', '', '', '', '笔记本电脑品牌分类</A>&nbsp;<IMG height=6 src="/images/arrow-orange.gif" width=4 align=absMiddle>&nbsp; <a href=[变数]>[作者]</a>&nbsp;', '', '', 1, 1, 1, 1, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 24, 24, 1292515200, 0, ''),
(17, 4, '笔记本:亚马逊', 3, 'http://www.amazon.cn/s/qid=1292588292/ref=sr_pg_2?ie=UTF8&rh=n:888465051,n:888483051&page=[分页]', '1,1,1,,1', 1, '', '', '', 'Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.04 (lucid) Firefox/3.6', 1, 21, ',,', '', '<div id="srProductTitle_[变数]" class="productTitle"><a href="[连接]"><img src="[变数]" class="" border="0" alt="[变数]"  width="160" height="160"/><br clear="all" />[标题]</a></div>', '<span id="btAsinTitle">[标题]</span>', '', '', '', '</h1>  品牌: <a href="[变数]">[作者]</a>', '', '', 1, 1, 1, 1, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 625, 625, 1292515200, 0, ''),
(20, 4, '笔记本:zol（详情页无法抓取）', 3, 'http://www.zol.com/list/2_16,[分页].html', ',,,,', 1, '', '', '', '', 1, 100, ',,', '', '<td class="txtListImg" valign="middle" align="center" ><a href="[连接]" title="[标题]"  target="_blank">', '<p class="big_title">[标题]</p>\r\n', '', '', '', '笔记本电脑</a> &gt; <a href="[变数]">[作者]</a>', '', '', 1, 1, 1, 1, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 228, 0, 1292515200, 0, ''),
(18, 4, '笔记本:dangdang', 3, 'http://category.dangdang.com/list?ps=39&cat=4001075&sort=5&p=[分页]', ',,,,', 1, '', '', '', '', 1, 76, ',,', '', '<div class="name" name="__name"><a href="[连接]" title="[标题]" target="_blank">', '<title>[标题] - 电脑办公', '<div class="right_content_0"><div class="right_title_1">型号</div><div class="right_content_1">[内容]</div></div>', '', '', '品牌：[作者]</a>', '', '', 1, 1, 1, 1, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 2887, 2887, 1292515200, 0, ''),
(19, 4, '笔记本:paipai', 3, 'http://d1.shop.qq.com/json.php?Callback=$vipshop.search.showResult.init&mod=search&act=warekeyword&jsontype=str&sf=88&Property=256&ac=1&cluster=1&PageSize=24&PageNum=[分页]&sClassid=231748', '1,1,1,,', 1, '', 'pgv_pvid=3783043238; pgv_flv=10.0 r45; pgv_r_cookie=10979488383; pt2gguin=o0000628553; ptcz=56029c1af4c2e874ffbec785a42c6e2a3468d1d1b5608d357bbabfb0b8943333; o_cookie=628553; aq_displaybubble=628555_37028; pvid=3783043238; qqshop_search_showway=img_mode', 'http://shop.qq.com/search/index.html?classId=23174', 'Mozilla/5.0 (X11; U; Linux i686; zh-CN; rv:1.9.2.10) Gecko/20100915 Ubuntu/10.04 (lucid) Firefox/3.6', 1, 36, ',,', '', 'title:"[标题]",    link:"[连接]",commId', 'commodityTitle:"[标题]",', '<span>[变数]型号：</span><span class="attrValue">[内容]</span>', '', '', '<span>品牌：</span><span class="attrValue">[作者]</span></li>', '', '', 1, 1, 1, 1, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 253, 252, 1292515200, 0, ''),
(21, 4, '笔记本:luobao365', 3, 'http://www.luobao365.com/category.php?id=297&price_min=0&price_max=0&filter_attr=&page=[分页]&sort=last_update&order=DESC', '1,1,1,,1', 1, '', '', '', '', 1, 3, ',,', '', '<p class="name">        <a href="[连接]" title="">[标题]</a>', '<div id="itemInfoList">          <h2>[标题]</h2>', '', '', '', '<title>[变数]_[作者]_电脑基地_罗宝网', '', '', 1, 1, 1, 1, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 25, 25, 1292515200, 0, ''),
(22, 4, '笔记本:woye', 1, 'http://browse.woye.com/Notebook-computer/', '1,1,1,,', 1, '', '', '', '', 0, 0, ',,', '', '<h1><a href="[连接]" title="[标题]" target="_blank"><strong>[变数]<span style="color:red;">[变数]</span></strong></a></h1>', '<h1><strong><span class="black">[标题]</span>', '型号：</td><td class="left">[内容]</td>', '', '', '品牌：</td><td class="left">[作者]</td>', '', '', 1, 1, 1, 1, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 4, 4, 1292515200, 0, ''),
(24, 12, '相机:360buy', 3, 'http://www.360buy.com/products/652-654-831-0-0-0-0-0-0-0-1-1-[分页].html', ',,,', 1, '', '', '', 'Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.04 (lucid) Firefox/3.6', 1, 15, ',,', '', '<div class=''p-name''><a target=''_blank'' href=''[连接]''>[标题]<font', '<div id="name">\r\n				<h1>[标题]<font', '<tr><td class="tdTitle">型号</td><td>[内容]</td></tr>', '', '', '<tr><td class="tdTitle">品牌</td><td>[作者]</td></tr>', '', '', 1, 1, 1, 1, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 462, 462, 1292515200, 0, ''),
(23, 12, '相机: newegg', 3, 'http://www.newegg.com.cn/SubCategory/655-[分页].htm', ',,,', 1, '', '', '', '', 1, 27, ',,', '', '<li class="name productName"><h4><a href="[连接]" title="[标题]">[变数]<font class=''description''>', '<div class="proHeader"><h1>[标题]</h1><p class="promoText">', '</td></tr><tr><th>型号</th><td>[内容]</td></tr><tr>', '', '', '<tr><th>品牌</th><td>[作者]</td></tr><tr><th>', '', '', 1, 1, 1, 1, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 203, 203, 1292515200, 0, ''),
(25, 12, '相机:tmall淘宝商城', 3, 'http://list.3c.tmall.com/l/28-[分页]-_sq---50035596.htm#item-list', ',,,,', 1, '', 't=eb3c87a7ffa7c401a4e2ffca4f113914; uc1=x; passtime=1292599663668; cookie2=06f641d71f9ba3bfe487e93d044a79e1', 'http://list.3c.taobao.com/', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; zh-CN; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13', 1, 14, ',,', '', '<dd class="attribute"> \r\n		<h4>\r\n			<a href="[连接]" target="_blank">[标题]<span class="info">', '<div class="detail-hd">\r\n	<h3>\r\n		[标题]\r\n	</h3>', '<li title="产品名称">产品名称：[内容]</li>', '', '', '<li title="品牌">品牌:\r\n																																															[作者]\r\n																																			</li>\r\n', '', '', 1, 1, 1, 1, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 389, 389, 1292515200, 0, ''),
(26, 12, '相机:amazon', 3, 'http://www.amazon.cn/s/qid=1292594343/ref=sr_pg_2?ie=UTF8&rh=n:755653051,n:755654051&page=[分页]', '1,1,1,,1', 1, '', '', '', 'Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.04 (lucid) Firefox/3.6', 1, 19, ',,', '', '<div id="srProductTitle_[变数]" class="productTitle"><a href="[连接]"><img src="[变数]" class="" border="0" alt="[变数]"  width="160" height="160"/><br clear="all" />[标题]</a></div>', '<span id="btAsinTitle">[标题]</span>', '', '', '', '</h1>  品牌: <a href="[变数]">[作者]</a>', '', '', 1, 1, 1, 1, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 399, 399, 1292515200, 0, ''),
(27, 12, '相机:dangdang', 3, 'http://category.dangdang.com/list?ps=39&cat=4001132&sort=5&p=[分页]', '0,0,0,0,0', 1, '', '', '', '', 1, 116, ',,', '', '<div class="name" name="__name"><a href="[连接]" title="[标题]" target="_blank">', '<title>[标题] - 手机数码', '', '', '', '', '', '', 1, 1, 1, 1, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 4472, 4472, 1292515200, 0, ''),
(28, 12, '相机:zol(详情页没有)', 3, 'http://www.zol.com/list/35_15_0__s1996_2_7___0_0_0_0_0_0_0_0_0_0_0_0,[分页].html', ',,,', 1, '', '', '', '', 1, 100, ',,', '', '<td class="txtListImg" valign="middle" align="center" ><a href="[连接]" title="[标题]"  target="_blank">', '<p class="big_title">[标题]</p>', '数码相机</a> &gt; <a href="[变数]">[作者]</a>', '', '', '', '', '', 1, 1, 1, 1, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 21, 0, 1292515200, 0, ''),
(29, 12, '相机:chinadrtv', 2, 'http://www.chinadrtv.com/shumayingyin/shumayingxiang/smyxxnfl/kapianji/index.shtml\r\nhttp://www.chinadrtv.com/shumayingyin/shumayingxiang/smyxxnfl/kapianji/index1.shtml', ',,,,', 1, '', '', '', 'Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.04 (lucid) Firefox/3.6', 0, 0, ',,', '', '<TD class=produce vAlign=top width=172 \r\n                                height=50><A \r\n                                href=[连接] target=_blank>[标题]<font color=red>', '<TD class=biaotizuti align=left \r\n                        height=35>[标题]<SPAN \r\n                        class=baitizengpin></SPAN> \r\n', '<TD bgColor=#efefef height=25><B \r\n      style="FONT-SIZE: 14px; COLOR: #282828">　◆品牌型号:</B></TD></TR>\r\n  <TR>\r\n    <TD>\r\n      <P>[内容]</P>', '', '', '数码影像品牌分类</A>&nbsp;<IMG height=6 src="/images/arrow-orange.gif" width=4 align=absMiddle>&nbsp; <a href=[变数]>[作者]</a>', '', '', 1, 1, 1, 1, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 31, 31, 1292515200, 0, ''),
(33, 22, '家庭影院:亚马逊', 3, 'http://www.amazon.cn/s/qid=1292641873/ref=sr_pg_1?ie=UTF8&keywords=%E5%AE%B6%E5%BA%AD%E5%BD%B1%E9%99%A2&bbn=874259051&rh=k:%E5%AE%B6%E5%BA%AD%E5%BD%B1%E9%99%A2,n:874259051,n:874268051&page=[分页]', '1,1,1,,1', 1, '', '', '', '', 1, 3, ',,', '', '<div id="title_[变数]"><a href="[连接]"><span class="srTitle">[标题]</span></a>', '<span id="btAsinTitle">[标题]</span>', '', '', '', '</h1>  品牌: <a href="[变数]">[作者]</a>', '', '', 1, 1, 1, 1, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 68, 68, 1292601600, 0, ''),
(31, 12, '相机:xiangji', 2, 'http://xiangji.net/list.asp?id=1235\r\nhttp://xiangji.net/list.asp?id=44\r\nhttp://xiangji.net/list.asp?id=523\r\nhttp://xiangji.net/list.asp?id=43\r\nhttp://xiangji.net/list.asp?id=1213\r\nhttp://xiangji.net/list.asp?id=39\r\nhttp://xiangji.net/list.asp?id=41\r\nhttp://xiangji.net/list.asp?id=38&Page=1\r\nhttp://xiangji.net/list.asp?id=38&Page=2\r\nhttp://xiangji.net/list.asp?id=37&Page=1\r\nhttp://xiangji.net/list.asp?id=37&Page=2\r\nhttp://xiangji.net/list.asp?id=36&Page=1\r\nhttp://xiangji.net/list.asp?id=36&Page=2\r\nhttp://xiangji.net/list.asp?id=48&Page=1\r\nhttp://xiangji.net/list.asp?id=48&Page=2\r\nhttp://xiangji.net/list.asp?id=47\r\nhttp://xiangji.net/list.asp?id=45\r\nhttp://xiangji.net/list.asp?id=46&Page=1\r\nhttp://xiangji.net/list.asp?id=46&Page=2\r\nhttp://xiangji.net/list.asp?id=40&Page=1\r\nhttp://xiangji.net/list.asp?id=40&Page=2', ',,,,', 1, '', '', '', '', 0, 0, ',,', '', '<tr>\r\n          <td width="80" align="center"><a href="[连接]"><img src="[变数]" border="0"></a></td>\r\n          <td width="200">[标题]<BR>', '<font color=#ff0000>[标题]</font></td>', '', '', '', '', '', '', 1, 1, 1, 1, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 147, 147, 1292515200, 0, ''),
(32, 12, '相机:paipai', 3, 'http://d1.shop.qq.com/json.php?Callback=$vipshop.search.showResult.init&mod=search&act=warekeyword&jsontype=str&sf=88&Property=256&ac=1&cluster=1&PageSize=24&PageNum=[分页]&sClassid=203900', '1,1,1,,', 1, '', 'pgv_pvid=3783043238; pgv_flv=10.0 r45; pgv_r_cookie=10979488383; pt2gguin=o0000628553; ptcz=56029c1af4c2e874ffbec785a42c6e2a3468d1d1b5608d357bbabfb0b8943333; o_cookie=628553; aq_displaybubble=628555_37028; pvid=3783043238; qqshop_search_showway=img_mode', '', '', 1, 36, ',,', '', 'title:"[标题]",    link:"[连接]",commId', 'commodityTitle:"[标题]",', '<span>[变数]型号：</span><span class="attrValue">[内容]</span>', '', '', '<span>品牌：</span><span class="attrValue">[作者]</span></li>', '', '', 1, 1, 1, 1, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 851, 841, 1292515200, 0, ''),
(34, 22, '家庭影院:coo8', 3, 'http://www.coo8.com/shop/list_10001_201_0_0_0_0_[分页]_0_0_0_0_0_0_0_0.htm', '0,0,0,0,0', 1, '', '', '', '', 1, 3, ',,', '', '<div class="Zi"><h1><a href="[连接]" target="_blank" title="[标题]">[变数]</a>', '<a id="hyGoodsName" href="[变数]">[标题]</a>', '<TD width="29%" height=25 bgColor=#FFFFFF class=tab align="left">型号</TD><TD width="51%" colspan="3" align="left" bgColor=#FFFFFF class=tab>[内容] </TD>', '', '', ' <a id="hyBrandName" href="[变数]">[作者]</a>', '', '', 1, 1, 1, 1, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 57, 57, 1292601600, 0, ''),
(35, 22, '家庭影院:360buy', 3, 'http://www.360buy.com/products/737-794-823-0-0-0-0-0-0-0-1-1-[分页].html', '1,1,1,,', 1, '', '', '', '', 1, 3, ',,', '', '<div class=''p-name''><a target=''_blank'' href=''[连接]''>[标题]<font', '<div id="name"><h1>[标题]<font ', '', '', '', '<li>生产厂家：<a target="_blank" href="[变数]">[作者]</a></li>', '', '', 1, 1, 1, 1, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 71, 70, 1292601600, 0, ''),
(37, 22, '家庭影院:woye', 1, 'http://browse.woye.com/Home-theatre/', '1,1,1,,', 1, '', '', '', '', 0, 0, ',,', '', '<h1><a href="[连接]" title="[标题]" target="_blank"><strong>', '<h1><strong><span class="black">[标题]</span>', '型号：</td><td class="left">[内容]</td>', '', '', '<dt class="w41 right">制造厂商：<dt>[变数]<dd class="w37"><span class="black">[作者]</span>', '', '', 1, 1, 1, 1, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 4, 4, 1292601600, 0, ''),
(36, 22, '家庭影院:paipai', 1, 'http://d1.shop.qq.com/json.php?Callback=$vipshop.search.showResult.init&mod=search&act=warekeyword&jsontype=str&sf=88&Property=256&ac=1&cluster=1&PageSize=24&PageNum=1&sClassid=213860', '1,1,1,,', 1, '', 'pgv_pvid=3783043238; pgv_flv=10.0 r45; pgv_r_cookie=10979488383; pt2gguin=o0000628553; ptcz=56029c1af4c2e874ffbec785a42c6e2a3468d1d1b5608d357bbabfb0b8943333; o_cookie=628553; aq_displaybubble=628555_37028; pvid=3783043238; qqshop_search_showway=img_mode; PPRD_P=IA.20051.3.15', '', '', 0, 0, ',,', '', 'title:"[标题]",    link:"[连接]",commId', 'commodityTitle:"[标题]",', '<span>[变数]型号：</span><span class="attrValue">[内容]</span>', '', '', '<span>品牌：</span><span class="attrValue">[作者]</span></li>', '', '', 1, 1, 1, 1, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 11, 11, 1292601600, 0, ''),
(38, 22, '家庭影院:zol', 3, 'http://detail.zol.com.cn/home-theater_index/subcate446_list_[分页].html', '1,1,1,,', 1, '', '', '', 'Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.04 (lucid) Firefox/3.6', 1, 15, ',,', '', '<div class="intro">  <a href="[连接]" class="title" id="proName_[变数]">[标题]</a>', '<div id="ptitle">  <h1>[标题]</h1>', '', '', '', '品牌：<a href="[变数]">[作者]</a>', '', '', 1, 1, 1, 1, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 370, 370, 1292601600, 0, ''),
(39, 22, '家庭影院:tmall', 3, 'http://list.3c.tmall.com/l/28-[分页]-_sq---50035672.htm#item-list', ',,,,', 1, '', 'uc1=x; t=7a0f903ad76c1786591fa4fb38ab0bfe; cookie2=ffa87505defbbc923e6230d8f28558eb; passtime=1292644339190', 'http://list.3c.taobao.com/', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; zh-CN; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13', 1, 6, ',,', '', '<dd class="attribute"> \r\n		<h4>\r\n			<a href="[连接]" target="_blank">[标题]<span class="info">', '<div class="detail-hd">\r\n	<h3>\r\n		[标题]\r\n	</h3>', '<li title="产品名称">产品名称：[内容]</li>', '', '', '<li title="品牌">品牌:\r\n																																															[作者]\r\n																																			</li>\r\n', '', '', 1, 1, 1, 1, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 168, 168, 1292601600, 0, ''),
(40, 19, '洗衣机:newegg', 3, 'http://www.newegg.com.cn/SubCategory/1025-[分页].htm', '0,0,0,0,0', 1, '', '', '', '', 1, 4, ',,', '', '<li class="name productName"><h4><a href="[连接]" title="[标题]">[变数]<font class=''description''>', '<div class="proHeader"><h1>[标题]</h1><p class="promoText">', '</td></tr><tr><th>型号</th><td>[内容]</td></tr><tr>', '', '', '<tr><th>品牌</th><td>[作者]</td></tr><tr><th>', '', '', 1, 1, 1, 1, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 25, 25, 1292601600, 0, ''),
(41, 19, '洗衣机:tmall', 3, 'http://list.3c.tmall.com/l/28-[分页]-_sq---50035634.htm#item-list', ',,,,', 1, '', 'uc1=x; t=7a0f903ad76c1786591fa4fb38ab0bfe; cookie2=ffa87505defbbc923e6230d8f28558eb; passtime=1292647180559', '', '', 1, 10, ',,', '', '<dd class="attribute"> \r\n		<h4>\r\n			<a href="[连接]" target="_blank">[标题]<span class="info">', '<div class="detail-hd">\r\n	<h3>\r\n		[标题]\r\n	</h3>', '<li title="产品名称">产品名称：[内容]</li>', '', '', '<li title="[变数]品牌">[变数]品牌:\r\n																																															[作者]\r\n																																			</li>\r\n', '', '', 1, 1, 1, 1, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 270, 270, 1292601600, 0, ''),
(42, 19, '洗衣机:amazon', 3, 'http://www.amazon.cn/s/qid=1292647205/ref=sr_pg_2?ie=UTF8&bbn=874259051&rh=n%3A874259051%2Cn%3A2121147051&page=[分页]', '1,1,1,,1', 1, '', '', '', 'Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.04 (lucid) Firefox/3.6', 1, 7, ',,', '', '<div id="srProductTitle_[变数]" class="productTitle"><a href="[连接]"><img src="[变数]" class="" border="0" alt="产品详细信息"  width="160" height="160"/><br clear="all" />[标题]</a>', '<span id="btAsinTitle">[标题]</span>', '', '', '', '</h1>  品牌: <a href="[变数]">[作者]</a>', '', '', 1, 1, 1, 1, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 132, 132, 1292601600, 0, ''),
(44, 19, '洗衣机:360buy', 3, 'http://www.360buy.com/products/737-794-880-0-0-0-0-0-0-0-1-1-[分页].html', '1,1,1,,', 1, '', '', '', '', 1, 7, ',,', '', '<div class=''p-name''><a target=''_blank'' href=''[连接]''>[标题]<font', '<div id="name"><h1>[标题]<font ', '', '', '', '<li>生产厂家：<a target="_blank" href="[变数]">[作者]</a></li>', '', '', 1, 1, 1, 1, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 217, 217, 1292601600, 0, ''),
(43, 19, '干洗机:tmall', 3, 'http://list.3c.tmall.com/l/28-[分页]-_sq---50035988.htm#item-list', '0,0,0,0,0', 1, '', 'uc1=x; t=7a0f903ad76c1786591fa4fb38ab0bfe; cookie2=ffa87505defbbc923e6230d8f28558eb; passtime=1292647180559', '', '', 1, 2, ',,', '', '<dd class="attribute"> \r\n		<h4>\r\n			<a href="[连接]" target="_blank">[标题]<span class="info">', '<div class="detail-hd">\r\n	<h3>\r\n		[标题]\r\n	</h3>', '<li title="产品名称">产品名称：[内容]</li>', '', '', '<li title="[变数]品牌">[变数]品牌:\r\n																																															[作者]\r\n																																			</li>\r\n', '', '', 1, 1, 1, 1, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 49, 49, 1292601600, 0, ''),
(45, 19, '洗衣机:dangdang', 3, 'http://category.dangdang.com/list?ps=39&cat=4001016&sort=5&p=[分页]', '0,0,0,0,0', 1, '', '', '', '', 1, 6, ',,', '', '<div class="name" name="__name"><a href="[连接]" title="[标题]" target="_blank">', '<title>[标题]- 家电', '', '', '', '', '', '', 1, 1, 1, 1, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 214, 214, 1292601600, 0, ''),
(46, 19, '干洗机:360buy', 1, 'http://www.360buy.com/products/737-738-1283-0-0-0-0-0-0-0-1-1-1.html', '1,1,1,,', 1, '', '', '', '', 0, 0, ',,', '', '<div class=''p-name''><a target=''_blank'' href=''[连接]''>[标题]<font', '<div id="name"><h1>[标题]<font ', '', '', '', '<li>生产厂家：<a target="_blank" href="[变数]">[作者]</a></li>', '', '', 1, 1, 1, 1, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 18, 18, 1292601600, 0, ''),
(47, 19, '洗衣机:zol', 3, 'http://detail.zol.com.cn/washer_index/subcate372_list_[分页].html', '1,1,1,,', 1, '', '', '', 'Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.04 (lucid) Firefox/3.6', 1, 37, ',,', '', '<div class="intro">  <a href="[连接]" class="title" id="proName_[变数]">[标题]</a>', '<div id="ptitle">  <h1>[标题]</h1>', '', '', '', '品牌：<a href="[变数]">[作者]</a>', '', '', 1, 1, 1, 1, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 921, 920, 1292601600, 0, ''),
(48, 19, '洗衣机:woye', 1, 'http://browse.woye.com/Washing-Machine/', '1,1,1,,', 1, '', '', '', 'Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9.2.13) Gecko/20101206 Ubuntu/10.04 (lucid) Firefox/3.6', 0, 0, ',,', '', '<h1><a href="[连接]" title="[标题]" target="_blank"><strong>', '<strong><span class="black">[标题]</span>', '', '', '', '', '', '', 1, 1, 1, 1, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 6, 6, 1292601600, 0, ''),
(49, 19, '洗衣机:paipai', 3, 'http://d1.shop.qq.com/json.php?Callback=$vipshop.search.showResult.init&mod=search&act=warekeyword&jsontype=str&sf=88&Property=256&ac=1&cluster=1&PageSize=24&PageNum=[分页]&sClassid=218880', '1,1,1,,', 1, '', 'pgv_pvid=3783043238; pgv_flv=10.0 r45; pgv_r_cookie=10979488383; pt2gguin=o0000628553; ptcz=56029c1af4c2e874ffbec785a42c6e2a3468d1d1b5608d357bbabfb0b8943333; o_cookie=628553; aq_displaybubble=628555_37028; pvid=3783043238; qqshop_search_showway=img_mode', '', '', 1, 2, ',,', '', 'title:"[标题]",    link:"[连接]",commId', 'commodityTitle:"[标题]",', '<span>[变数]型号：</span><span class="attrValue">[内容]</span>', '', '', '<span>品牌：</span><span class="attrValue">[作者]</span></li>', '', '', 1, 1, 1, 1, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 46, 46, 1292601600, 0, ''),
(50, 19, '干洗机:coo8', 1, 'http://www.coo8.com/shop/list_10001_281_0_0_0_0_0_0_0_0_0_0_0_0_0.htm', '1,1,1,,', 1, '', '', '', '', 0, 0, ',,', '', '<div class="Zi"><h1><a href="[连接]" target="_blank" title="[变数]">[标题]<span', '<div class="ProName"><h1><strong>[标题]</strong>', '商品型号】：[内容]</p>', '', '', '<p>　　【商品品牌】：[作者] 【', '', '', 1, 1, 1, 1, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 7, 7, 1292601600, 0, ''),
(51, 19, '洗衣机:coo8', 3, 'http://www.coo8.com/shop/list_10001_260_0_0_0_0_[分页]_0_0_0_0_0_0_0_0.htm', ',,,,', 1, '', '', '', '', 1, 6, ',,', '', '<div class="Zi"><h1><a href="[连接]" target="_blank" title="[标题]">[变数]</a>', '<a id="hyGoodsName" href="[变数]">[标题]</a>\r\n', '<TD width="29%" height=25 bgColor=#FFFFFF class=tab align="left">型号</TD><TD width="51%" colspan="3" align="left" bgColor=#FFFFFF class=tab>[内容] </TD>', '', '', ' <a id="hyBrandName" href="[变数]">[作者]</a>', '', '', 1, 1, 1, 1, 1, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 130, 130, 1292601600, 0, '');

