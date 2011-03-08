<?

	if ($NeatReged == 'yes')
	{
		$regName = REG_NAME;
		$regDomain = $serverINFO['HTTP_HOST'];
		switch (REG_TYPE)
		{
			case 2:
			{
				$regType = '个人版';
				break;
			}

			case 3:
			{
				$regType = '组织版';
				break;
			}

			case 4:
			{
				$regType = '企业版';
			}
		}
	}
	else
	{
		$regName = REG_NAME;
		$regDomain = '本地或任意';
		$regType = '免费版';
	}

	if ($NeatLocalReged == 'yes')
	{
		$regType = ' 本地版';
		$regDomain = 'localhost';
	}

	$tp->set_templatefile ('templates/license_view.html');
	$tp->assign ('reg_name', $regName);
	$tp->assign ('reg_domain', $regDomain);
	$tp->assign ('reg_type', $regType);
	$moduleTemplate = $tp->result ();
	$moduleTitle = '查看注册信息';
?>
