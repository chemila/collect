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
	if (!$_GET['action'])
	{
		if (!$_GET['CID'])
		{
			error ('您要编辑哪个分类?');
		}

		$array = $NC->getCategory ($_GET['CID']);
		$pid = $array['pid'];
		$pArray = $NC->getCategory ($pid);
		$getarray = $NC->readCategoryCache ();
		if (!is_array ($getarray))
		{
			$getarray = array ();
		}

		$last = '├─';
		$option = '<option value=0>根目录</option>';
		foreach ($getarray as $key => $val)
		{
			$itemTemp = str_repeat ('│', $val['deep']);
			($pid == $val['id'] ? $selected = ' selected' : $selected = '');
			$option .= '<option' . $selected . ' value="' . $val['id'] . '">' . $itemTemp . $last . $val['title'] . '</option>
';
		}

		$tp->set_templatefile ('templates/category_edit.html');
		$tp->assign ('id', $array['id']);
		$tp->assign ('title', $array['title']);
		$tp->assign ('option', $option);
		$tp->assign ('orderid', $array['orderid']);
		$tp->assign ('editorid', $array['editorid']);
		$tp->assign ('num', $array['num']);
		$moduleTemplate = $tp->result ();
		$moduleTitle = '编辑采集器分类';
	}
	else
	{
		if (!$_GET['CID'])
		{
			$error ('您要编辑什么分类?');
		}

		if (!$_POST['title'])
		{
			$error ('请您填写分类名称!');
		}

		if (strlen ($_POST['pid']) == 0)
		{
			$error ('请您选择上级分类!');
		}

		$data['title'] = $_POST['title'];
		$data['pid'] = intval ($_POST['pid']);
		$data['orderid'] = intval ($_POST['orderid']);
		$dataCon['id'] = intval ($_GET['CID']);
		if (!$NC->updateCategory ($data, $dataCon))
		{
			error ('不能把父级分类设置在该分类的子分类下!');
		}

		$catearray = $NC->getCategory ();
		$getarray = $NC->getTree ($catearray, 0, 0, 'category');
		$NC->doCategoryCache ($getarray);
		showloading ('?module=listCategory', '分类编辑', '分类编辑完毕,现在返回分类列表', 1);
		$tpShowBody = false;
	}

?>
