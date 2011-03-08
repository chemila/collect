<?

	$db = new MySQL (DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE);
	if (!trim ($_GET['action']))
	{
		$sql = 'SELECT id, name ';
		$sql .= 'FROM ' . TB_RULES . ' ';
		$sql .= 'ORDER BY id DESC';
		$rs = $db->query ($sql);
		$i = 0;
		while ($rs->next_record ())
		{
			$list['option'][$i]['rulesID'] = $rs->get ('id');
			$list['option'][$i]['rulesName'] = $rs->get ('name');
			($rs->get ('id') == $_GET['rules'] ? $list['option'][$i]['rulesSelected'] = ' selected' : $list['option'][$i]['rulesSelected'] = '');
			++$i;
		}

		$tp->set_templatefile ('templates/body_swf_collection_form.html');
		$tp->assign ($list);
		$tp->assign ('display_markall', 'none');
		$tp->assign ('display_mark_text', 'block');
		$tp->assign ('display_mark_image', 'none');
		$tp->assign ('rulesID', $_GET['rules']);
		$moduleTemplate = $tp->result ();
		$moduleTitle = '文章中FLASH采集';
	}
	else
	{
		if ($_GET['action'] == 'getReady')
		{
			if (!$_POST['eachTimes'])
			{
				error ('请您输入每次采集的条数');
			}

			if (!is_numeric ($_POST['eachTimes']))
			{
				error ('每次采集条数只能是数字');
			}

			if ($_POST['eachTimes'] < 0)
			{
				error ('每次采集条数不能是负数');
			}

			if (!trim ($_POST['savePath']))
			{
				error ('保存目录不能为空');
			}

			$rulesID = intval ($_POST['rulesID']);
			$eachTimes = intval ($_POST['eachTimes']);
			$totalGet = intval ($_POST['total']);
			$savePath = trim (str_replace ('\\', '/', stripslashes ($_POST['savePath'])));
			$maxSize = intval ($_POST['maxsize']);
			$minSize = intval ($_POST['minsize']);
			if (!ereg ('/$', $_SERVER['DOCUMENT_ROOT']))
			{
				$rootPath = $_SERVER['DOCUMENT_ROOT'] . '/';
			}
			else
			{
				$rootPath = $_SERVER['DOCUMENT_ROOT'];
			}

			$saveFullPath = $rootPath . $savePath;
			if (!ereg ('/$', $saveFullPath))
			{
				$saveFullPath = $saveFullPath . '/';
			}

			if (!is_writable ($saveFullPath))
			{
				$message = '设置的保存目录"' . $saveFullPath . '"不可写入或者不存在.请确认目录的存在并设置目录属性为777.';
				error ($message);
			}

			if ($rulesID)
			{
				$sqlRules = 'AND rules = ' . $rulesID . ' ';
			}

			$sql = 'SELECT COUNT(*) AS total ';
			$sql .= 'FROM ' . TB_DATA . ' ';
			$sql .= 'WHERE swf_geted = 0 ';
			$sql .= $sqlRules;
			$rs = $db->query ($sql);
			$rs->next_record ();
			$total = $rs->get ('total');
			if ($total < 1)
			{
				showloading ('?module=listRules', '取消任务...', '目前待处理的队列为空,返回采集器列表.');
				$tpShowBody = false;
				exit ();
			}

			if ($totalGet)
			{
				($total < $totalGet ? $total = $total : $total = $totalGet);
			}

			if ($total < $eachTimes)
			{
				$eachTimes = $total;
			}

			$url = '?module=bodySwfCollection&action=processing&total=' . $total . '&rulesID=' . $rulesID . '&eachTimes=' . $eachTimes . '&savePath=' . rawurlencode ($savePath) . '&start=1&maxsize=' . $maxSize . '&minsize=' . $minSize;
			showloading ($url, '文章FLASH采集开始', '现在开始按照您的设定执行入库任务,请耐心等待.');
			$tpShowBody = false;
		}
		else
		{
			if ($_GET['action'] == 'processing')
			{
				include_once 'includes/class/basic/NEAT_ProcessHttpSwf.php';
				$total = intval ($_GET['total']);
				$eachTimes = intval ($_GET['eachTimes']);
				$start = intval ($_GET['start']);
				$rulesID = intval ($_GET['rulesID']);
				$maxSize = intval ($_GET['maxsize']);
				$minSize = intval ($_GET['minsize']);
				$savePath = trim ($_GET['savePath']);
				if (!ereg ('/$', $_SERVER['DOCUMENT_ROOT']))
				{
					$rootPath = $_SERVER['DOCUMENT_ROOT'] . '/';
				}
				else
				{
					$rootPath = $_SERVER['DOCUMENT_ROOT'];
				}

				$saveFullPath = $rootPath . $savePath;
				if (!ereg ('/$', $saveFullPath))
				{
					$saveFullPath = $saveFullPath . '/';
				}

				if (!ereg ('/$', $savePath))
				{
					$newPath = '/' . $savePath . '/';
				}
				else
				{
					$newPath = '/' . $savePath;
				}

				$NEAT_SWF = new NEAT_ProcessHttpSwf ($saveFullPath);
				$NEAT_SWF->setAllowType ();
				$NBS = new NEATBulidSql (TB_DATA);
				if ($rulesID)
				{
					$sqlRules = 'AND rules = ' . $rulesID . ' ';
				}

				$sql = 'SELECT id, link_id, title, body, swf_geted ';
				$sql .= 'FROM ' . TB_DATA . ' ';
				$sql .= 'WHERE swf_geted = 0 ';
				$sql .= $sqlRules;
				$rs = $db->query ($sql, 0, $eachTimes);
				$allLocalFile = array ();
				while ($rs->next_record ())
				{
					unset ($NEAT_IMG[saved]);
					$bodyContent = $rs->get ('body');
					$NEAT_SWF->setContents ($bodyContent);
					$array = $NEAT_SWF->getSWFUrl ();
					$NEAT_SWF->saveSwf ($array, $maxSize, $minSize);
					$allLocalFile = array_merge ($allLocalFile, $NEAT_SWF->saved['localFileName']);
					if ($NEAT_SWF->saved['remotePath'])
					{
						foreach ($NEAT_SWF->saved['remotePath'] as $key => $val)
						{
							$newPathReplace = $newPath . $val;
							$bodyContent = str_replace ($key, $newPathReplace, $bodyContent);
						}
					}

					$conditionFids['id'] = $rs->get ('id');
					$datasFids['body'] = addslashes ($bodyContent);
					$datasFids['swf_geted'] = 1;
					$sql = $NBS->update ($datasFids, $conditionFids);
					$db->query ($sql);
				}

				$nextStart = $start + $eachTimes;
				$geted = $nextStart - 1;
				$leaveTotal = $total - $geted;
				if ($leaveTotal < $eachTimes)
				{
					$eachTimes = $leaveTotal;
				}

				if ($total < $nextStart)
				{
					$url = '?module=listRules';
					showloading ($url, '文章FLASH采集任务完成...', '文章FLASH已经全部采集到本地目录');
					$tpShowBody = false;
				}
				else
				{
					$url = '?module=bodySwfCollection&action=processing&total=' . $total . '&rulesID=' . $rulesID . '&eachTimes=' . $eachTimes . '&savePath=' . $savePath . '&start=' . $nextStart . '&maxsize=' . $maxSize . '&minsize=' . $minSize;
					$message = '当前已经采集 : ' . $geted . ' 条,还有 ' . $leaveTotal . ' 条在任务队列中. (一共 ' . $total . ' 条)';
					showloading ($url, '文章FLASH采集任务进行中...', $message, 10);
					$tpShowBody = false;
				}
			}
		}
	}

?>
