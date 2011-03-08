<?

	class neat_db2db_mysql
	{
		function testconnect ()
		{
			if (!$this->_connect ())
			{
				return 1;
			}
			else
			{
				if (!$this->_selectDB ())
				{
					$this->_closeConnect ();
					return 2;
				}
				else
				{
					$this->_closeConnect ();
					return 3;
				}
			}

		}

		function _connect ()
		{
			$this->conn = @mysql_connect ($_POST['MYSQL_TARGET_DB_HOST'], $_POST['MYSQL_TARGET_DB_USER'], $_POST['MYSQL_TARGET_DB_PASS']);
			return $this->conn;
		}

		function _selectdb ()
		{
			return @mysql_select_db ($_POST['MYSQL_TARGET_DB_NAME'], $this->conn);
		}

		function _closeconnect ()
		{
			mysql_close ($this->conn);
		}

		function setsession ()
		{
			$_SESSION['TARGET_DB_HOST'] = $_POST['MYSQL_TARGET_DB_HOST'];
			$_SESSION['TARGET_DB_USER'] = $_POST['MYSQL_TARGET_DB_USER'];
			$_SESSION['TARGET_DB_PASS'] = $_POST['MYSQL_TARGET_DB_PASS'];
			$_SESSION['TARGET_DB_NAME'] = $_POST['MYSQL_TARGET_DB_NAME'];
		}

		function setadodb (&$obj)
		{
			$this->adodb = &$obj;
		}

		function adodbconnect ()
		{
			$this->adodb->Connect ($_SESSION['TARGET_DB_HOST'], $_SESSION['TARGET_DB_USER'], $_SESSION['TARGET_DB_PASS'], $_SESSION['TARGET_DB_NAME']);
		}

		function gettables ($name)
		{
			$this->ADODBConnect ();
			$sql = 'SHOW TABLE STATUS ';
			$sql .= 'FROM ' . $name;
			$rs = $this->adodb->Execute ($sql);
			$i = 0;
			while (!$rs->EOF)
			{
				$tableList[$i]['name'] = $rs->fields['Name'];
				$tableList[$i]['rows'] = $rs->fields['Rows'];
				$tableList[$i]['type'] = $rs->fields['Type'];
				$tableList[$i]['length'] = $rs->fields['Data_length'];
				++$i;
				$rs->MoveNext ();
			}

			$rs->Close ();
			$this->adodb->Close ();
			return $tableList;
		}

		function getfields ($name)
		{
			$this->ADODBConnect ();
			$sql = 'SHOW FIELDS ';
			$sql .= 'FROM ' . $name;
			$rs = $this->adodb->Execute ($sql);
			$i = 0;
			while (!$rs->EOF)
			{
				$default = 'NULL';
				($rs->fields['Default'] != null ? $default = $rs->fields['Default'] : null);
				$fieldList[$i]['article_table_id'] = $name;
				$fieldList[$i]['name'] = $rs->fields['Field'];
				$fieldList[$i]['type'] = $rs->fields['Type'];
				$fieldList[$i]['key'] = $rs->fields['Key'];
				$fieldList[$i]['default'] = $default;
				++$i;
				$rs->MoveNext ();
			}

			$rs->Close ();
			$this->adodb->Close ();
			return $fieldList;
		}
	}

?>
