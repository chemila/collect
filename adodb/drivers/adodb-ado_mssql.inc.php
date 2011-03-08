<?


	if (!defined ('ADODB_DIR'))
	{
		exit ();
	}

	include ADODB_DIR . '/drivers/adodb-ado.inc.php';
	class adodb_ado_mssql extends adodb_ado
	{
		var $databaseType = 'ado_mssql';
		var $hasTop = 'top';
		var $hasInsertID = true;
		var $sysDate = 'convert(datetime,convert(char,GetDate(),102),102)';
		var $sysTimeStamp = 'GetDate()';
		var $leftOuter = '*=';
		var $rightOuter = '=*';
		var $ansiOuter = true;
		var $substr = 'substring';
		var $length = 'len';
		function adodb_ado_mssql ()
		{
			$this->ADODB_ado ();
		}

		function _insertid ()
		{
			return $this->GetOne ('select @@identity');
		}

		function _affectedrows ()
		{
			return $this->GetOne ('select @@rowcount');
		}

		function metacolumns ($table)
		{
			$table = strtoupper ($table);
			$arr = array ();
			$dbc = $this->_connectionID;
			$osoptions = array ();
			$osoptions[0] = null;
			$osoptions[1] = null;
			$osoptions[2] = $table;
			$osoptions[3] = null;
			$adors = @$dbc->OpenSchema (4, $osoptions);
			if ($adors)
			{
				while (!$adors->EOF)
				{
					$fld = new ADOFieldObject ();
					$c = $adors->Fields (3);
					$fld->name = $c->Value;
					$fld->type = 'CHAR';
					$fld->max_length = -1;
					$arr[strtoupper ($fld->name)] = $fld;
					$adors->MoveNext ();
				}

				$adors->Close ();
			}

			$false = false;
			return (empty ($arr) ? $false : $arr);
		}
	}

	class adorecordset_ado_mssql extends adorecordset_ado
	{
		var $databaseType = 'ado_mssql';
		function adorecordset_ado_mssql ($id, $mode = false)
		{
			return $this->ADORecordSet_ado ($id, $mode);
		}
	}

?>
