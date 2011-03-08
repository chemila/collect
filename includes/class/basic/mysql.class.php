<?
class mysql
{
	var $selectQueries = 0;
	var $updateQueries = 0;
	var $timeOffset = 8;
	var $charset = 'utf8';
	function mysql ($server, $user, $password, $database, $pConnect = 0, $autoRun = 1)
	{
		$this->pConnect = $pConnect;
		if ($autoRun == 1)
		{
			$this->connect ($server, $user, $password, $database);
		}
		

	}

	function connect ($server, $user, $password, $database)
	{
		$connectType = ($this->pConnect ? 'mysql_pconnect' : 'mysql_connect');
		$this->conn = $connectType ($server, $user, $password);
		if (!$this->conn)
		{
			$this->error ('Fail to connect to MySQL server');
			return false;
		}

		if ($database)
		{
			if (!mysql_select_db ($database, $this->conn))
			{
				$this->error ('Cannot use database : ' . $database);
				return false;
			}
			$this->query("SET NAMES '$this->charset'");
		}

	}

	function disconnect ()
	{
		return mysql_close ($this->conn);
	}

	function getselectqueries ()
	{
		return $this->selectQueries;
	}

	function getupdatequeries ()
	{
		return $this->updateQueries;
	}

	function query ($queryString, $beginRow = 0, $limit = 0)
	{
		if ($limit)
		{
			$queryString .= ' LIMIT ' . $beginRow . ' , ' . $limit;
		}
		$queryid = mysql_query ($queryString, $this->conn);
		++$this->selectQueries;
		if (!$queryid)
		{
			$this->error ('Invalid SQL : ' . $queryString);
		}

		return new Result ($queryid);
	}

	function update ($queryString)
	{
		$queryid = mysql_query ($queryString, $this->conn);
		++$this->updateQueries;
		if (!$queryid)
		{
			$this->error ('Invalid SQL : ' . $queryString);
		}

		return $queryid;
	}

	function lastid ()
	{
		return mysql_insert_id ($this->conn);
	}

	function error ($msg)
	{
		$errorTime = gmdate ('Y-n-j g:i a', time () + $this->timeOffset * 3600);
		$mysql_error = @mysql_error ($this->conn);
		$mysql_errno = @mysql_errno ($this->conn);
		printf ('<br><br>MySQL error message : <br><br><textarea rows="10" cols="60">
time : %s
--------------------------------
%s
--------------------------------
mysql error : %s
mysql error no. : %s</textarea>', $errorTime, $msg, $mysql_error, $mysql_errno);
		exit ();
	}
}

class result
{
	var $resultid = 0;
	var $Rows = 0;
	var $record = array ();
	function result ($resultid)
	{
		$this->resultid = $resultid;
	}

	function next_record ()
	{
		$this->record = mysql_fetch_array ($this->resultid, MYSQL_ASSOC);
		++$this->Rows;
		$status = is_array ($this->record);
		return $status;
	}

	function get ($Name)
	{
		return $this->record[$Name];
	}

	function getarray ()
	{
		return $this->record;
	}

	function rows ()
	{
		return mysql_num_rows ($this->resultid);
	}

	function free ()
	{
		mysql_free_result ($this->resultid);
		$this->resultid = 0;
	}
}
?>
