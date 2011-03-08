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
	if (!is_array ($getarray))
	{
		$getarray = array ();
	}

	foreach ($getarray as $key => $val)
	{
		$tree .= 'var item' . $val['id'] . ' =new treeItem("' . $val['title'] . '", \'?module=listRules&CID=' . $val['id'] . '\');
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

	if ($_GET['CID'])
	{
		$sqlWhere = 'WHERE r.cid = \'' . intval ($_GET['CID']) . '\' ';
	}

	$db = new MySQL (DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE);
	include_once 'includes/class/basic/opb.class.php';
	$sqlTotal = 'SELECT COUNT(*) AS total ';
	$sqlTotal .= 'FROM ' . TB_RULES . ' AS r ';
	$sqlTotal .= $sqlWhere;
	$rs = $db->query ($sqlTotal);
	$rs->next_record ();
	$total = $rs->get ('total');
	$onepage = NUM_RULES_ONEPAGE;
	($_GET['page'] ? $page = $_GET['page'] : $page = 1);
	$offset = $page * $onepage - $onepage;
	$pb = new OPB ($total, $onepage, 'searchkey');
	$pagebar = $pb->whole_bar ();
	$sql = 'SELECT r.id, r.name, r.link_num, r.import_num, r.cid, c.title AS cateName ';
	$sql .= 'FROM ' . TB_RULES . ' AS r ';
	$sql .= 'LEFT JOIN ' . TB_CATE . ' AS c ';
	$sql .= 'ON r.cid = c.id ';
	$sql .= $sqlWhere;
	$sql .= 'ORDER BY r.id DESC';
	$rs = $db->query ($sql, $offset, $onepage);
	$db->disconnect ();
	$i = 0;
	while ($rs->next_record ())
	{
		$list['list'][$i]['id'] = $rs->get ('id');
		$list['list'][$i]['name'] = $rs->get ('name');
		$list['list'][$i]['link_num'] = $rs->get ('link_num');
		$list['list'][$i]['import_num'] = $rs->get ('import_num');
		$list['list'][$i]['cateName'] = $rs->get ('cateName');
		++$i;
	}

	$tp->set_templatefile ('templates/rules_list.html');
	$tp->assign ($list);
	$tp->assign ('tree', $tree);
	$tp->assign ('root', $root);
	$tp->assign ('item', $item);
	$tp->assign ('pagebar', $pagebar);
	$moduleTemplate = $tp->result ();
	$moduleTitle = '采集器列表';
?>
