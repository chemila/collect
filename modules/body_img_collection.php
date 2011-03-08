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

		$tp->set_templatefile ('templates/body_img_collection_form.html');
		$tp->assign ($list);
		$tp->assign ('display_markall', 'none');
		$tp->assign ('display_mark_text', 'block');
		$tp->assign ('display_mark_image', 'none');
		$tp->assign ('rulesID', $_GET['rules']);
		$moduleTemplate = $tp->result ();
		$moduleTitle = '文章图片采集';
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
			$mark = intval ($_POST['mark']);
			$markType = trim ($_POST['marktype']);
			$markTextText = trim ($_POST['mark_text_text']);
			$markTextTran = intval ($_POST['mark_text_transition']);
			$markTextFontSize = intval ($_POST['mark_text_fontsize']);
			$markImageTran = intval ($_POST['mark_image_transition']);
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
			$sql .= 'WHERE img_geted = 0 ';
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

			$url = '?module=bodyImgCollection&action=processing&total=' . $total . '&rulesID=' . $rulesID . '&eachTimes=' . $eachTimes . '&savePath=' . rawurlencode ($savePath) . '&start=1&maxsize=' . $maxSize . '&minsize=' . $minSize;
			if ($mark)
			{
				if (!function_exists ('imagecreate'))
				{
					error ('您的服务器不支持GD模块,所以不能使用水印功能,请联系管理员提供支持');
				}

				if ($markType == 'text')
				{
					$url .= '&mark=1&marktype=text&mark_text_text=' . $markTextText . '&mark_text_tran=' . $markTextTran . '&mark_text_fontsize=' . $markTextFontSize;
				}
				else
				{
					if ($markType == 'image')
					{
						$uploadDir = $saveFullPath;
						$fileExtension = strtolower (substr (strrchr ($_FILES['mark_image_image']['name'], '.'), 1));
						$fileName = 'mark_' . time () . '_' . mt_rand () . '.' . $fileExtension;
						$uploadFile = $uploadDir . $fileName;
						if (move_uploaded_file ($_FILES['mark_image_image']['tmp_name'], $uploadFile))
						{
							$url .= '&mark=1&marktype=image&mark_image_image=' . $fileName . '&mark_image_tran=' . $markImageTran;
						}
						else
						{
							error ('水印图片上传失败,请检查配置和目录设置');
						}
					}
				}
			}

			showloading ($url, '文章图片采集开始', '现在开始按照您的设定执行入库任务,请耐心等待.');
			$tpShowBody = false;
		}
		else
		{
			if ($_GET['action'] == 'processing')
			{
				include_once 'includes/class/basic/NEATProcessHttpImg.class.php';
				include_once 'includes/class/basic/NEATGD.class.php';
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

				$NEAT_IMG = new NEAT_ProcessHttpImg ($saveFullPath);
				$NEAT_IMG->setAllowType ();
				$NBS = new NEATBulidSql (TB_DATA);
				if ($rulesID)
				{
					$sqlRules = 'AND rules = ' . $rulesID . ' ';
				}

				$sql = 'SELECT id, link_id, title, body, img_geted ';
				$sql .= 'FROM ' . TB_DATA . ' ';
				$sql .= 'WHERE img_geted = 0 ';
				$sql .= $sqlRules;
				$rs = $db->query ($sql, 0, $eachTimes);
				$allLocalFile = array ();
				while ($rs->next_record ())
				{
					unset ($NEAT_IMG[saved]);
					$bodyContent = $rs->get ('body');
					$NEAT_IMG->setContents ($bodyContent);
					$array = $NEAT_IMG->getImgUrl ();
					$NEAT_IMG->saveImg ($array, $maxSize, $minSize);
					$allLocalFile = array_merge ($allLocalFile, $NEAT_IMG->saved['localFileName']);
					if ($NEAT_IMG->saved['remotePath'])
					{
						foreach ($NEAT_IMG->saved['remotePath'] as $key => $val)
						{
							$newPathReplace = $newPath . $val;
							$bodyContent = str_replace ($key, $newPathReplace, $bodyContent);
						}
					}

					$conditionFids['id'] = $rs->get ('id');
					$datasFids['body'] = addslashes ($bodyContent);
					$datasFids['img_geted'] = 1;
					$sql = $NBS->update ($datasFids, $conditionFids);
					$db->query ($sql);
				}

				if ($_GET['mark'])
				{
					$NEAT_GD = new NEAT_GD ($saveFullPath);
					if ($_GET['marktype'] == 'text')
					{
						$NEAT_GD->font = GD_FONT;
						$NEAT_GD->textBackgroundTransition = $_GET['mark_text_tran'];
						$NEAT_GD->fontSize = $_GET['mark_text_fontsize'];
						$maekText = $_GET['mark_text_text'];
						foreach ($allLocalFile as $val)
						{
							$NEAT_GD->textMark ($saveFullPath . $val, $maekText);
						}
					}
					else
					{
						if ($_GET['marktype'] == 'image')
						{
							$NEAT_GD->imageTransition = $_GET['mark_image_tran'];
							$markImage = $saveFullPath . $_GET['mark_image_image'];
							foreach ($allLocalFile as $val)
							{
								$NEAT_GD->imageMark ($saveFullPath . $val, $markImage);
							}
						}
					}
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
					if (($_GET['mark'] AND $_GET['marktype'] == 'image'))
					{
						@unlink ($markImage);
					}

					$url = '?module=listRules';
					showloading ($url, '文章图片采集任务完成...', '文章图片已经全部采集到本地目录');
					$tpShowBody = false;
				}
				else
				{
					$url = '?module=bodyImgCollection&action=processing&total=' . $total . '&rulesID=' . $rulesID . '&eachTimes=' . $eachTimes . '&savePath=' . $savePath . '&start=' . $nextStart . '&maxsize=' . $maxSize . '&minsize=' . $minSize;
					if ($_GET['mark'])
					{
						if ($_GET['marktype'] == 'text')
						{
							$url .= '&mark=1&marktype=text&mark_text_text=' . $_GET['mark_text_text'] . '&mark_text_tran=' . $_GET['mark_text_tran'] . '&mark_text_fontsize=' . $_GET['mark_text_fontsize'];
						}
						else
						{
							if ($_GET['marktype'] == 'image')
							{
								$url .= '&mark=1&marktype=image&mark_image_image=' . $_GET['mark_image_image'] . '&mark_image_tran=' . $_GET['mark_image_tran'];
							}
						}
					}

					$message = '当前已经采集 : ' . $geted . ' 条,还有 ' . $leaveTotal . ' 条在任务队列中. (一共 ' . $total . ' 条)';
					showloading ($url, '文章图片采集任务进行中...', $message, 1);
					$tpShowBody = false;
				}
			}
		}
	}

?>
