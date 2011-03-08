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
	$getarray = $NC->readCategoryCache ();
	$NC->changeOrderID ($getarray, $_GET['CID'], $_GET['type']);
	unset ($getarray);
	$catearray = $NC->getCategory ();
	$getarray = $NC->getTree ($catearray, 0, 0, 'category');
	$NC->doCategoryCache ($getarray);
	header ('location:?module=listCategory');
?>
