<?

	include_once 'includes/class/basic/NEATCategory.class.php';
	include_once 'includes/class/basic/NEATCache.class.php';
	$db = new MySQL (DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE);
	$NBS = new NEATBulidSql (TB_CATE);
	$NC = new NEAT_CATEGORY ();
	$NCA = new NEAT_CACHE ();
	$NCA->setCachePath ('tmp/');
	$NCA->setCacheFile ('categoryCache');
	$NC->setTable (TB_CATE);
	$catData['id'] = 'id';
	$catData['pid'] = 'pid';
	$catData['orderid'] = 'orderid';
	$NC->setField ($catData);
	$NC->setNDB ($db);
	$NC->setNBS ($NBS);
	$NC->setNCA ($NCA);
	$getarray = $NC->readCategoryCache ();
	if (!is_array ($getarray))
	{
		$getarray = array ();
	}

	foreach ($getarray as $k => $v)
	{
		(!$i[$v['pid']] ? $i[$v['pid']] = 1 : $i[$v['pid']]++);
		$categoryPostion[$v['pid']][$v['id']] = $i[$v['pid']];
	}

	$last = '<img src=images/tree_ico/join.gif>&nbsp;&nbsp;';
	$i = 0;
	foreach ($getarray as $key => $val)
	{
		$num = count ($categoryPostion[$val['pid']]);
		if (($categoryPostion[$val['pid']][$val['id']] == 1 AND $categoryPostion[$val['pid']][$val['id']] != $num))
		{
			$imgUp = 'none';
			$imgDown = '';
		}
		else
		{
			if (($categoryPostion[$val['pid']][$val['id']] != 1 AND $categoryPostion[$val['pid']][$val['id']] == $num))
			{
				$imgUp = '';
				$imgDown = 'none';
			}
			else
			{
				if (($categoryPostion[$val['pid']][$val['id']] != 1 AND $categoryPostion[$val['pid']][$val['id']] != $num))
				{
					$imgUp = '';
					$imgDown = '';
				}
				else
				{
					$imgUp = 'none';
					$imgDown = 'none';
				}
			}
		}

		$list['row'][$i]['imgup'] = $imgUp;
		$list['row'][$i]['imgdown'] = $imgDown;
		$list['row'][$i]['id'] = $val['id'];
		$list['row'][$i]['title'] = $val['title'];
		$list['row'][$i]['list'] = str_repeat ('<img src=images/tree_ico/line.gif>', $val['deep']) . $last . $val['title'];
		$list['row'][$i]['pname'] = $getarray[$val['pid']]['title'];
		$list['row'][$i]['num'] = $val['num'];
		$list['row'][$i]['orderid'] = $val['orderid'];
		++$i;
	}

	$tp->set_templatefile ('templates/category_list.html');
	$tp->assign ($list);
	$moduleTemplate = $tp->result ();
	$moduleTitle = '采集器分类列表';
	$db->disconnect ();
?>
