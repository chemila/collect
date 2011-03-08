<?

	if (!$_GET['action'])
	{
		$tp->set_templatefile ('templates/license_input.html');
		$tp->assign ('user', REG_NAME);
		$moduleTemplate = $tp->result ();
		$moduleTitle = '填写注册信息';
	}
	else
	{
		if ($_GET['action'] == 'generate')
		{
			if (!$_POST['user'])
			{
				error ('请您输入注册名字');
			}

			if (!$_POST['type'])
			{
				error ('请选择要生成的授权码类型');
			}

			if ($_POST['type'] == 'server')
			{
				$serverINFO = array ();
				$serverSoftTemp = getenv ('SERVER_SOFTWARE');
				if ($serverSoftTemp == '')
				{
					$serverSoftTemp = $_SERVER['SERVER_SOFTWARE'];
				}

				$websoft = strtolower (substr ($serverSoftTemp, 0, 3));
				$serverINFO['NEAT_COLLECTOR_VERSION'] = NEAT_COLLECTOR_VERSION;
				$serverINFO['USER'] = $_POST['user'];
				$serverINFO['HTTP_HOST'] = getenv ('HTTP_HOST');
				if ($serverINFO['HTTP_HOST'] == '')
				{
					$serverINFO['HTTP_HOST'] = $_SERVER['HTTP_HOST'];
				}

				if ($websoft == 'apa')
				{
					$serverADDR = getenv ('SERVER_ADDR');
					if ($serverINFO['SERVER_ADDR'] == '')
					{
						$serverINFO['SERVER_ADDR'] = $_SERVER['SERVER_ADDR'];
					}
				}
				else
				{
					if ($websoft == 'zeu')
					{
						$serverADDR = gethostbyname ($serverINFO['HTTP_HOST']);
					}
					else
					{
						$websoft = 'IIS';
						$serverADDR = getenv ('LOCAL_ADDR');
						if ($serverADDR == '')
						{
							$serverADDR = $_SEVER['LOCAL_ADDR'];
						}

						if ($serverADDR == '')
						{
							$serverADDR = gethostbyname ($serverINFO['HTTP_HOST']);
						}
					}
				}

				$serverINFO['SERVER_SOFTWARE'] = $serverSoftTemp;
				$serverINFO['SERVER_ADDR'] = $serverADDR;
				$encrypted_string = $SNobeject_12s3ds->serverInfoEncode ($serverINFO);
				if ($_POST['download'])
				{
					ob_end_clean ();
					header ('Last-Modified: ' . gmdate ('D, d M Y H:i:s') . ' GMT');
					header ('Content-Encoding: none');
					header ('Cache-Control: private');
					header ('Content-Length: ' . strlen ($encrypted_string));
					header ('Content-Disposition: inline;filename= ' . trim ($_POST['user']) . '_NC_SERVER_SN.txt');
					header ('Content-Type: txt');
					echo $encrypted_string;
					exit ();
				}
				else
				{
					$tp->set_templatefile ('templates/license_generate.html');
					$tp->assign ('code', $encrypted_string);
					$moduleTemplate = $tp->result ();
					$moduleTitle = '生成授权码';
				}
			}
			else
			{
				$get = new getLocalInfo ();
				if (!$get->checkFun ('serialize'))
				{
					error ('您系统不符合本地版使用标准');
				}

				if (!$get->checkFun ('base64_encode'))
				{
					error ('您系统不符合本地版使用标准');
				}

				$serverInfo = array ();
				$serverInfo['USER']['NAME'] = trim ($_POST['user']);
				$serverInfo['SYSTEM']['OS_VERSION'] = $get->getOSVersion ();
				$serverInfo['SYSTEM']['MAC_ADDRESS'] = $get->getMacAddress ();
				$serverInfo['SYSTEM']['WINDOWS_SYSTEM_PATH'] = $get->getWindowsSystemPath ();
				$serverInfo['SYSTEM']['HOST_IP'] = $get->getHostIP ();
				$serverInfo['SYSTEM']['SAM_CREAT_TIME'] = $get->getSAMCTime ();
				$serverInfo['PHP']['PHP_OS'] = $get->getPHPOS ();
				$serverInfo['PHP']['PHP_VERSION'] = $get->getPHPVersion ();
				$serverInfo['PHP']['SERVER_API'] = $get->getServerAPI ();
				$serverInfo['PHP']['SERVER_SOFTWARE'] = $get->getServerSoftware ();
				$serverInfo['PHP']['EXTENSION'] = $get->getExtensionInfo ();
				$serverInfo['FILE'] = $get->getAllFileCTime ('modules/');
				$encrypted_string = $SNobeject_12s3ds->localInfoEncode ($serverInfo);
				if ($_POST['download'])
				{
					ob_end_clean ();
					header ('Last-Modified: ' . gmdate ('D, d M Y H:i:s') . ' GMT');
					header ('Content-Encoding: none');
					header ('Cache-Control: private');
					header ('Content-Length: ' . strlen ($encrypted_string));
					header ('Content-Disposition: inline;filename= ' . trim ($_POST['user']) . '_NC_LOCAL_SN.txt');
					header ('Content-Type: txt');
					echo $encrypted_string;
					exit ();
				}
				else
				{
					$tp->set_templatefile ('templates/license_generate.html');
					$tp->assign ('code', $encrypted_string);
					$moduleTemplate = $tp->result ();
					$moduleTitle = '生成授权码';
				}
			}
		}
	}

?>
