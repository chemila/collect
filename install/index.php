<?
	error_reporting (0);
	include './include/config.inc.php';
	include './include/function.inc.php';
	if (file_exists ('install.lock'))
	{
		exit ('发现install.lock文件存在，如果需要安装，请先删除该文件!');
	}

	if (!$_GET['step'])
	{
		$contents = getcontent (cfgFilePath . cfgReadme);
		$contents = str_replace ('cfgVersion', cfgVersion, $contents);
		showform ('index.php?step=showLisence', '安装说明', '在开始安装程序前，请您仔细阅读下面说明。', $contents);
	}

	if ($_GET['step'] == 'showLisence')
	{
		$contents = '<pre>';
		$contents .= getcontent (cfgFilePath . cfgLicense);
		$contents .= '</pre>';
		$acceptContent = '<INPUT TYPE="radio" NAME="neatAccept" checked onclick="document.neatinstall.nextButton.disabled=true" />不接受协议&nbsp;&nbsp;';
		$acceptContent .= '<INPUT TYPE="radio" NAME="neatAccept" onclick="document.neatinstall.nextButton.disabled=false" />明白了,我接受&nbsp;&nbsp;&nbsp;&nbsp;';
		$disabled = 'disabled';
		showform ('index.php?step=list', '软件产品最终用户许可协议', '请您在安装本程序前，务必仔细阅读下面的许可协议。', $contents, $acceptContent, $disabled);
	}

	if ($_GET['step'] == 'list')
	{
		if (!is_writable (cfgTmpDir))
		{
			$dirTmpCheck = 0;
			$errorInfo = 1;
		}

		if (!is_writable (cfgIncludeDir))
		{
			$dirIncCheck = 0;
			$errorInfo = 1;
		}

		if (PHP_VERSION < '4.1')
		{
			$PHPVERSIONCheck = 0;
			$errorInfo = 1;
		}

		if (!getphpcfg ('allow_url_fopen'))
		{
			$allowUrlFopenCheck = 0;
			$errorInfo = 1;
		}

		if (getphpcfg2 ('safe_mode'))
		{
			$safeModeCheck = 0;
			$errorInfo = 1;
		}

		$safeMode = getphpcfg2 ('safe_mode', 2);
		$urlFopen = getphpcfg ('allow_url_fopen', 2);
		$imgLine = getfun ('imageline', 2);
		$geticonv = getfun ('iconv', 2);
		$dirTmpCheck = getwriteable ($dirTmpCheck);
		$dirIncCheck = getwriteable ($dirIncCheck);
		$contents = getcontent (cfgFilePath . cfgSetting);
		$contents = str_replace ('phpversion', PHP_VERSION, $contents);
		$contents = str_replace ('safe_mode', $safeMode, $contents);
		$contents = str_replace ('allow_url_fopen', $urlFopen, $contents);
		$contents = str_replace ('imageline', $imgLine, $contents);
		$contents = str_replace ('geticonv', $geticonv, $contents);
		$contents = str_replace ('dirTmpCheck', $dirTmpCheck, $contents);
		$contents = str_replace ('dirIncCheck', $dirIncCheck, $contents);
		$contents = str_replace ('ERRORINFO', $errorInfo, $contents);
		showform ('index.php?step=checksetting', '程序安装环境', '下面的表格给出了程序的推荐、基本和当前环境的对比。', $contents);
	}

	if ($_GET['step'] == 'checksetting')
	{
		$writeable = $version = $url_fopen = $safemode = '';
		if ((!is_writable (cfgTmpDir) OR !is_writable (cfgIncludeDir)))
		{
			$writeable = '请将 <font color="red">tmp</font> 和 <font color="red">include</font> 目录的属性设置为 <font color="red">777</font>';
		}

		if (PHP_VERSION < '4.1')
		{
			$version = '<font color="red">您的服务器PHP版本太低</font> 系统需要最低版本为: <font color="red">4.1.0</font> 您当前版本为: ' . PHP_VERSION;
		}

		if (!getphpcfg ('allow_url_fopen'))
		{
			$url_fopen = '您的服务器PHP配置关闭了<font color="red"> allow_url_fopen 项目</font>	这样程序将无法正常运行';
		}

		if (getphpcfg ('safe_mode'))
		{
			$safemode = '您的服务器处于<font color="red">安全模式</font> 这样程序将无法正常运行,请修改 php.ini 文件';
		}

		$contents = getcontent (cfgFilePath . cfgSetError);
		$contents = str_replace ('TMP_INCLUDE', $writeable, $contents);
		$contents = str_replace ('PHPVERSION', $version, $contents);
		$contents = str_replace ('URLFOPEN', $url_fopen, $contents);
		$contents = str_replace ('SAFEMODE', $safemode, $contents);
		if ($_POST['error'] == 1)
		{
			$title = '<b>检查目录和文件是否可写发生错误!</b><br><br>';
			$contents = str_replace ('TITLE', $title, $contents);
			showform ('', '安装环境有误', '请您仔细对照出错内容进行修改。', $contents, '', 'disabled');
		}
		else
		{
			$title = '<b>您的程序配置完全符合要求,如需安装请按 [下一步] </b><br><br>';
			$contents = str_replace ('TITLE', $title, $contents);
			showform ('index.php?step=installsetting', '安装环境正确', '检查当前环境和正常要求。', $contents);
		}
	}

	if ($_GET['step'] == 'installsetting')
	{
		$contents = getcontent (cfgFilePath . cfgInstallConfig);
		if (file_exists (cfgIncludeDir . 'config.inc.php'))
		{
			@include cfgIncludeDir . 'config.inc.php';
			$contents = str_replace ('DBHOST', DB_SERVER, $contents);
			$contents = str_replace ('DBUSER', DB_USER, $contents);
			$contents = str_replace ('DBPASS', DB_PASSWORD, $contents);
			$contents = str_replace ('DBNAME', DB_DATABASE, $contents);
			$contents = str_replace ('DBPRE', DB_TB_PRE, $contents);
			$contents = str_replace ('FONT', GD_FONT, $contents);
			$contents = str_replace ('ADMINNAME', NEAT_USERNAME, $contents);
			$contents = str_replace ('ADMINPASS', NEAT_PASSWORD, $contents);
		}
		else
		{
			$contents = str_replace ('DBHOST', 'localhost', $contents);
			$contents = str_replace ('DBUSER', 'root', $contents);
			$contents = str_replace ('DBPASS', 'root', $contents);
			$contents = str_replace ('DBNAME', 'neatcollector', $contents);
			$contents = str_replace ('DBPRE', 'NC_', $contents);
			$contents = str_replace ('FONT', 'simsun.ttc', $contents);
			$contents = str_replace ('ADMINNAME', 'admin', $contents);
			$contents = str_replace ('ADMINPASS', '', $contents);
		}

		showform ('index.php?step=install', '参数配置', '默认是您原来的配置,如果您原来没有配置,则取默认', $contents, '', '');
	}

	if ($_GET['step'] == 'install')
	{
		if ((!$_POST['username'] OR !$_POST['password']))
		{
			$error = 1;
			$errorInfo .= '用户名或密码不能为空<br>';
		}

		if ($_POST['password'] != $_POST['passwordconfirm'])
		{
			$error = 1;
			$errorInfo .= '两次输入得密码不一致<br>';
		}

		if (!@mysql_connect ($_POST['databaseserver'], $_POST['databaseuser'], $_POST['databasepassword']))
		{
			$error = 1;
			$errorInfo .= '数据库连接错误!<br>';
		}

		if (!@mysql_select_db ($_POST['database']))
		{
			if (!@mysql_create_db ($_POST['database']))
			{
				$error = 1;
				$errorInfo .= '所选择的数据库不存在或者不能被创建.!<br>';
			}
			else
			{
				@mysql_select_db ($_POST['database']);
			}
		}

		$configContent = getcontent (cfgFilePath . cfgDefaultConfig);
		$configContent = str_replace ('DATABASE_SERVER', $_POST['databaseserver'], $configContent);
		$configContent = str_replace ('DATABASE_USER', $_POST['databaseuser'], $configContent);
		$configContent = str_replace ('DATABASE_PASS', $_POST['databasepassword'], $configContent);
		$configContent = str_replace ('DATABASE_NAME', $_POST['database'], $configContent);
		$configContent = str_replace ('DATABASE_PRE', $_POST['tablepre'], $configContent);
		$configContent = str_replace ('GDFONT', $_POST['gdfont'], $configContent);
		$configContent = str_replace ('ADMINNAME', $_POST['username'], $configContent);
		$configContent = str_replace ('ADMINPASS', $_POST['password'], $configContent);
		if (!$error)
		{
			$fp = fopen (cfgIncludeDir . 'config.inc.php', 'w');
			fwrite ($fp, trim ($configContent));
			fclose ($fp);
			$sqlData = getcontent (cfgFilePath . cfgSqlData);
			installdb ($sqlData, $_POST['deldb']);
			if ($error)
			{
				$contents = '<center><br><BR>TITLE<BR></center>';
				$title = '<b>' . $errorInfo . '</b><br><br>';
				$contents = str_replace ('TITLE', $title, $contents);
				$finish = 'style="display:none" >&nbsp;&nbsp;<input type="submit" class="button" value="&nbsp;关	闭&nbsp;" ';
				showform ('index.php?step=finish', '安装过程发生错误', '请根据信息检查错误。', $contents, '', $finish);
				exit ();
			}

			$fp = fopen ('install.lock', 'w+');
			fclose ($fp);
			$contents = '&nbsp;&nbsp;<b>安装完成</b><br><br>';
			$contents .= '&nbsp;&nbsp;<b><font color="red">请检查 install 目录是否存在.<br>';
			$contents .= '&nbsp;&nbsp;如果存在请立即删除,以防别人利用此程序获得您的相关资料.</font></b><br></p>';
			$finish = 'style="display:none" >&nbsp;&nbsp;<input type="submit" class="button" value="&nbsp;关	闭&nbsp;" ';
			showform ('index.php?step=finish', '安装完成', '请检查相关信息', $contents, '', $finish);
		}
		else
		{
			$contents = '<center><br><BR>TITLE<BR></center>';
			$title = '<b>' . $errorInfo . '</b><br><br>';
			$contents = str_replace ('TITLE', $title, $contents);
			showform ('index.php?step=installsetting', '安装过程发生错误', '请根据信息检查错误。', $contents, '', 'disabled');
		}
	}

	if ($_GET['step'] == 'finish')
	{
		header ('location:../index.php');
		echo '<script language="javascript">';
		echo 'window.close();';
		echo '</script>';
	}

	echo '</body>
</html>';
?>
