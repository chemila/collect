<?

	if (!defined ('ADODB_DIR'))
	{
		exit ();
	}

	if (!defined ('_ADODB_ADO_LAYER'))
	{
		if (5 <= PHP_VERSION)
		{
			include ADODB_DIR . '/drivers/adodb-ado5.inc.php';
		}
		else
		{
			include ADODB_DIR . '/drivers/adodb-ado.inc.php';
		}
	}

	class adodb_ado_access extends adodb_ado
	{
		var $databaseType = 'ado_access';
		var $hasTop = 'top';
		var $fmtDate = '#Y-m-d#';
		var $fmtTimeStamp = '#Y-m-d h:i:sA#';
		var $sysDate = 'FORMAT(NOW,\'yyyy-mm-dd\')';
		var $sysTimeStamp = 'NOW';
		var $hasTransactions = false;
		function adodb_ado_access ()
		{
			$this->ADODB_ado ();
		}

		function begintrans ()
		{
			return false;
		}

		function committrans ()
		{
			return false;
		}

		function rollbacktrans ()
		{
			return false;
		}
	}

	class adorecordset_ado_access extends adorecordset_ado
	{
		var $databaseType = 'ado_access';
		function adorecordset_ado_access ($id, $mode = false)
		{
			return $this->ADORecordSet_ado ($id, $mode);
		}
	}

?>
