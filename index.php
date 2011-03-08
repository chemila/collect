<?
	set_time_limit (0);
	include_once 'includes/config.inc.php';
	include_once 'includes/class/basic/NEATCollector.class.php';
	include_once 'includes/class/basic/smarttemplate.class.php';
	include_once 'includes/class/basic/smarttemplateparser.class.php';
	include_once 'includes/class/basic/mysql.class.php';
	include_once 'includes/class/basic/NEATBuildSql.class.php';
	include_once 'includes/class/extra/NEATImportArticle.class.php';
	include_once 'includes/function.inc.php';
	include_once 'includes/modulesMap.inc.php';
	if (!get_magic_quotes_gpc ())
	{
		$_POST = stripslashes_array ($_POST);
		$_GET = stripslashes_array ($_GET);
		$_REQUEST = stripslashes_array ($_REQUEST);
		$_COOKIE = stripslashes_array ($_COOKIE);
	}


	header('Content-Type: text/html; charset=UTF-8');
	$NEATIsLogin = 'no';
	$NEATUsername = $_COOKIE[COOKIE_PREFIX . 'neatusername'];
	$NEATPassword = $_COOKIE[COOKIE_PREFIX . 'neatpassword'];
	if (($NEATUsername == NEAT_USERNAME AND $NEATPassword == md5 (NEAT_PASSWORD)))
	{
		$NEATIsLogin = 'yes';
	}

	if (($NEATIsLogin != 'yes' AND trim ($_GET['module']) != 'login'))
	{
		header ('Location: ?module=login');
	}

	if (DEBUG == 0)
	{
		error_reporting (0);
	}

	$startTimes = utime ();
	$tp = new SmartTemplate ();
	$MetaSetting = '<meta asdfasdf><META HTTP-EQUIV="content-type" CONTENT="text/html; CHARSET=utf-8" />';

	($_GET['module'] ? $moduleName = $_GET['module'] : $moduleName = 'listRules');
	if ($modulesMap[$moduleName])
	{
		$tpShowBody = true;
		include_once 'modules/' . $modulesMap[$moduleName] . '.php';
		if ($tpShowBody)
		{
			$tp->set_templatefile ('templates/body.html');
			$tp->assign ('body_title', $moduleTitle);
			$tp->assign ('body_contents', $moduleTemplate);
			$tp->assign ('meta_setting', $MetaSetting);
			$tp->output ();
		}
	}
	else
	{
		header ('Location: index.php');
		exit ();
	}

//	$endTimes = utime ();
//	$spendTimes = sprintf ('%0.4f', $endTimes - $startTimes);
	$tp->set_templatefile ('templates/footer.html');
//	$tp->assign ('version', NEAT_COLLECTOR_VERSION . ' BETA');
//	$tp->assign ('times', $spendTimes);
//	$tp->assign ('openTplTimes', $tp->getLoadTplTimes ());
	$tp->output ();
?>
