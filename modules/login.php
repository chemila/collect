<?

	if ($_POST['islogin'])
	{
		$_POST['username'] = htmlspecialchars (trim ($_POST['username']));
		$_POST['password'] = trim ($_POST['password']);
		$_POST['savetime'] = trim ($_POST['savetime']);
		switch ($_POST['savetime'])
		{
			case 'no':
			{
				$savetime = 0;
				break;
			}

			case '1day':
			{
				$savetime = 86400;
				break;
			}

			case '1week':
			{
				$savetime = 604800;
				break;
			}

			case '1year':
			{
				$savetime = 31536000;
				break;
			}

			default:
			{
				$savetime = 0;
			}
		}

		$cookieSaveTime = (!$savetime ? 0 : $savetime + time ());
		if (($_POST['username'] == NEAT_USERNAME AND $_POST['password'] == NEAT_PASSWORD))
		{
			setcookie (COOKIE_PREFIX . 'neatusername', $_POST['username'], $cookieSaveTime, COOKIE_PATH, COOKIE_DOMAIN);
			setcookie (COOKIE_PREFIX . 'neatpassword', md5 ($_POST['password']), $cookieSaveTime, COOKIE_PATH, COOKIE_DOMAIN);
			showloading ('index.php', '登陆成功', '登陆成功,现在返回首页');
			$tpShowBody = false;
		}
		else
		{
			error ('用户或密码错误,请返回重试!');
		}
	}
	else
	{
		if ($_GET['logout'])
		{
			setcookie (COOKIE_PREFIX . 'neatusername', time () - 31536000, $cookieSaveTime, COOKIE_PATH, COOKIE_DOMAIN);
			setcookie (COOKIE_PREFIX . 'neatpassword', time () - 31536000, $cookieSaveTime, COOKIE_PATH, COOKIE_DOMAIN);
			showloading ('index.php', '退出成功', '退出成功,现在返回首页');
			$tpShowBody = false;
		}
		else
		{
			$tpShowBody = false;
			$tp->set_templatefile ('templates/login.html');
			$tp->output ();
		}
	}

?>
