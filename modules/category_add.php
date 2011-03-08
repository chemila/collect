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
			($_GET['CID'] == $val['id'] ? $selected = ' selected' : $selected = '');
			$option .= '<option' . $selected . ' value="' . $val['id'] . '">' . $itemTemp . $last . $val['title'] . '</option>
';
			$tree .= 'var item' . $val['id'] . ' =new treeItem("' . $val['title'] . '", \'?module=addCategory&CID=' . $val['id'] . '\');
';
			if ($val['pid'] == 0)
			{
				$root .= 'root.add(item' . $val['id'] . ');
';
				continue;
			}
			else
			{
				$item .= 'item' . $val['pid'] . '.add(item' . $val['id'] . ');
';
				continue;
			}
		}

		$tp->set_templatefile ('templates/category_add.html');
		$tp->assign ('tree', $tree);
		$tp->assign ('root', $root);
		$tp->assign ('item', $item);
		$tp->assign ('option', $option);
		$moduleTemplate = $tp->result ();
		$moduleTitle = '添加采集器分类';
	}
	else
	{
		if (!$_POST['title'])
		{
			error ('分类名称不能为空!');
		}

		(!$_POST['orderid'] ? $maxOrderID = $NC->getNodeMaxOrderID ($_POST['pid']) : $maxOrderID = $_POST['orderid']);
		$data['id'] = '';
		$data['pid'] = $_POST['pid'];
		$data['title'] = $_POST['title'];
		$data['orderid'] = $maxOrderID;
		$NC->addCategory ($data);
		$catearray = $NC->getCategory ();
		$getarray = $NC->getTree ($catearray, 0, 0, 'category');
		$NC->doCategoryCache ($getarray);
		showloading ('?module=listCategory', '分类添加', '分类添加完毕,现在返回分类列表', 1);
		$tpShowBody = false;
	}

?>
