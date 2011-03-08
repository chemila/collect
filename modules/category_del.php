<?

	include_once 'includes/class/basic/NEATCategory.class.php';
	include_once 'includes/class/basic/NEATCache.class.php';
	$NC = new NEAT_CATEGORY ();
	$NBS = new NEATBulidSql (TB_CATE);
	$NCA = new NEAT_CACHE ();
	$NDB = new MySQL (DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE);
	$NCA->setCachePath ('tmp/');
	$NCA->setCacheFile ('categoryCache');
	$NC->setTable (TB_CATE);
	$catData['id'] = 'id';
	$catData['pid'] = 'pid';
	$catData['orderid'] = 'orderid';
	$NC->setField ($catData);
	$NC->setNDB ($NDB);
	$NC->setNBS ($NBS);
	$NC->setNCA ($NCA);
	if (!$_GET['CID'])
	{
		error ('您要删除什么分类?');
	}

	$getarray = $NC->readCategoryCache ();
	$delCIDList = $NC->delCategory ($getarray, $_GET['CID']);
	$catearray = $NC->getCategory ();
	$getarray = $NC->getTree ($catearray, 0, 0, 'category');
	$NC->doCategoryCache ($getarray);
	showloading ('?module=listCategory', '分类删除', '分类删除完毕,现在返回分类列表', 1);
	$tpShowBody = false;
?>
