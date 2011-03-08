<?


	function arrayorder ($array, $key)
	{
		$orderFun = create_function ('$a,$b', '$k	=	' . $key . '; if($a[$k]	==	$b[$k])	return	0; return	$a[$k]>$b[$k]?1:-1;');
		usort ($array, $orderFun);
		return $array;
	}

	header ('Cache-Control: no-cache, must-revalidate');
	header ('Pragma: no-cache');
	include_once '../includes/config.inc.php';
	include_once '../includes/class/basic/smarttemplate.class.php';
	$NEATIsLogin = 'no';
	$NEATUsername = $_COOKIE[COOKIE_PREFIX . 'neatusername'];
	$NEATPassword = $_COOKIE[COOKIE_PREFIX . 'neatpassword'];
	if (($NEATUsername == NEAT_USERNAME AND $NEATPassword == md5 (NEAT_PASSWORD)))
	{
		$NEATIsLogin = 'yes';
	}
	else
	{
		exit ();
	}

	$baseRoot = realpath ('../');
	if (!ereg ('/$', $_SERVER['DOCUMENT_ROOT']))
	{
		$rootPath = $_SERVER['DOCUMENT_ROOT'] . '/';
	}
	else
	{
		$rootPath = $_SERVER['DOCUMENT_ROOT'];
	}

	if ($_GET['dir'])
	{
		$fullPath = $_GET['currentdir'] . '/' . $_GET['dir'];
		$currentDir = realpath ($fullPath);
	}
	else
	{
		$currentDir = realpath ($rootPath);
	}

	if (strlen ($currentDir) < strlen ($rootPath))
	{
		$currentDir = realpath ($rootPath);
	}

	chdir ($currentDir);
	$handle = opendir ($currentDir);
	while (false !== $file = readdir ($handle))
	{
		if (($file !== '.' AND $file !== '..'))
		{
			if (is_dir ($file))
			{
				$FolderInfo['file'] = strtolower ($file);
				$FolderInfo['sub'] = '<a href=?dir=' . $file . '&&currentdir=' . rawurlencode ($currentDir) . '><img src="../images/folder.gif" align="absmiddle" border="0">' . $file . '</a>';
				$FolderInfo['date'] = date ('Y-n-d H:i:s', filemtime ($file));
				$FolderInfo['mode'] = substr (base_convert (fileperms ($file), 10, 8), -4);
				$folderList[] = $FolderInfo;
				continue;
			}
			else
			{
				if (is_file ($file))
				{
					$fileInfo['file'] = strtolower ($file);
					$fileInfo['sub'] = '<img src="../images/page.gif" align="absmiddle" border="0">' . $file;
					$fileInfo['size'] = number_format (filesize ($file) / 1024, 3) . ' KB';
					$fileInfo['date'] = date ('Y-n-d H:i:s', filectime ($file));
					$fileInfo['mode'] = substr (base_convert (fileperms ($file), 10, 8), -4);
					$fileList[] = $fileInfo;
					continue;
				}

				continue;
			}

			continue;
		}
	}

	closedir ($handle);
	if (is_array ($folderList))
	{
		$folderList = arrayorder ($folderList, 'file');
	}

	if (is_array ($fileList))
	{
		$fileList = arrayorder ($fileList, 'file');
	}

	$allList = array_merge ($folderList, $fileList);
	$childDir = str_replace (realpath ($rootPath), '', $currentDir);
	if (($childDir[0] == '/' OR $childDir[0] == '\\'))
	{
		$childDir = substr ($childDir, 1);
	}

	$i = 0;
	foreach ($allList as $val)
	{
		$list['list'][$i]['sub'] = $val['sub'];
		$list['list'][$i]['date'] = $val['date'];
		$list['list'][$i]['size'] = $val['size'];
		$list['list'][$i]['mode'] = $val['mode'];
		++$i;
	}

	if ($childDir == '')
	{
		$childDir = '根目录';
	}

	chdir ($baseRoot);
	$tp = new SmartTemplate ();
	$tp->set_templatefile ('templates/dir_list.html');
	$tp->assign ($list);
	$tp->assign ('path', rawurlencode ($currentDir));
	$tp->assign ('childDir', addslashes ($childDir));
	$tp->output ();
?>
