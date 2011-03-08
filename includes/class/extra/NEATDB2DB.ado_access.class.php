<?

	class neat_db2db_ado_access
	{
		function testconnect ()
		{
			if (!$this->_selectDB ())
			{
				return 2;
			}
			else
			{
				if (!$this->_connect ())
				{
					return 1;
				}
				else
				{
					return 3;
				}
			}

		}

		function _connect ()
		{
			$this->conn = new COM ('ADODB.Connection');
			$u = 'UID';
			$p = 'PWD';
			$argHostname = 'PROVIDER=Microsoft.Jet.OLEDB.4.0;DATA SOURCE=' . realpath ($_POST['ADO_ACCESS_TARGET_DB_HOST']);
			if ($_POST['ADO_ACCESS_TARGET_DB_USER'])
			{
				$argHostname .= '' . ';' . $u . '=' . $_POST['ADO_ACCESS_TARGET_DB_USER'];
			}

			if ($_POST['ADO_ACCESS_TARGET_DB_PASS'])
			{
				$argHostname .= '' . ';' . $p . '=' . $_POST['ADO_ACCESS_TARGET_DB_PASS'];
			}

			@$this->conn->Open ((string)$argHostname);
			return $this->conn->State;
		}

		function _selectdb ()
		{
			return file_exists (realpath ($_POST['ADO_ACCESS_TARGET_DB_HOST']));
		}

		function _closeconnect ()
		{
			$this->conn->Close ();
		}

		function setsession ()
		{
			$u = 'UID';
			$p = 'PWD';
			$argHostname = 'PROVIDER=Microsoft.Jet.OLEDB.4.0;DATA SOURCE=' . realpath ($_POST['ADO_ACCESS_TARGET_DB_HOST']);
			if ($_POST['ADO_ACCESS_TARGET_DB_USER'])
			{
				$argHostname .= '' . ';' . $u . '=' . $_POST['ADO_ACCESS_TARGET_DB_USER'];
			}

			if ($_POST['ADO_ACCESS_TARGET_DB_PASS'])
			{
				$argHostname .= '' . ';' . $p . '=' . $_POST['ADO_ACCESS_TARGET_DB_PASS'];
			}

			$_SESSION['TARGET_DB_HOST'] = $_POST['ADO_ACCESS_TARGET_DB_HOST'];
			$_SESSION['TARGET_DB_CONN'] = $argHostname;
			$_SESSION['TARGET_DB_USER'] = $_POST['ADO_ACCESS_TARGET_DB_USER'];
			$_SESSION['TARGET_DB_PASS'] = $_POST['ADO_ACCESS_TARGET_DB_PASS'];
			$_SESSION['TARGET_DB_NAME'] = '';
		}

		function setadodb (&$obj)
		{
			$this->adodb = &$obj;
		}

		function adodbconnect ()
		{
			$this->adodb->Connect ($_SESSION['TARGET_DB_CONN'], '', '', '');
		}

		function gettables ($name)
		{
			$this->ADODBConnect ();
			$rs = $this->adodb->MetaTables ();
			$i = 0;
			foreach ($rs as $k => $v)
			{
				$tableList[$i]['name'] = $v;
				$sql = 'SELECT COUNT(*) ';
				$sql .= 'FROM ' . $v;
				$rows = array ();
				$rsTotal = $this->adodb->Execute ($sql);
				if ($rsTotal)
				{
					$rows = $rsTotal->FetchRow ();
				}
				else
				{
					$rows[0] = '--';
				}

				$tableList[$i]['rows'] = $rows[0];
				$tableList[$i]['type'] = '--';
				++$i;
			}

			$this->adodb->Close ();
			return $tableList;
		}

		function getfields ($name)
		{
			$this->ADODBConnect ();
			$rs = $this->adodb->MetaColumns ($name);
			$i = 0;
			foreach ($rs as $k => $v)
			{
				$default = 'NULL';
				($v->default_value != null ? $default = $v->default_value : null);
				$fieldList[$i]['article_table_id'] = $name;
				$fieldList[$i]['name'] = $v->name;
				$fieldList[$i]['type'] = $v->type;
				$fieldList[$i]['key'] = '--';
				$fieldList[$i]['default'] = $default;
				++$i;
			}

			$this->adodb->Close ();
			return $fieldList;
		}
	}


?>
