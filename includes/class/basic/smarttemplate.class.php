<?
	class smarttemplate
	{
		var $reuse_code = true;
		var $temp_dir = './tmp/';
		var $cache_dir = './tmp/';
		var $cache_lifetime = 600;
		var $cache_filename = null;
		var $tpl_path = '';
		var $tpl_file = null;
		var $cpl_file = null;
		var $data = array ();
		var $parser = null;
		var $debugger = null;
		var $loadTplTimes = 0;
		function smarttemplate ($template_filename = '')
		{
			global $_CONFIG;
			if (!empty ($_CONFIG['smarttemplate_compiled']))
			{
				$this->temp_dir = $_CONFIG['smarttemplate_compiled'];
			}

			if (!empty ($_CONFIG['smarttemplate_cache']))
			{
				$this->cache_dir = $_CONFIG['smarttemplate_cache'];
			}

			if (is_file ($_CONFIG['smarttemplate_templates'] . $template_filename))
			{
				$this->tpl_path = $_CONFIG['smarttemplate_templates'];
			}

			$this->tpl_file = $template_filename;
		}

		function set_templatefile ($template_filename)
		{
			++$this->loadTplTimes;
			$this->tpl_file = $template_filename;
		}

		function getloadtpltimes ()
		{
			return $this->loadTplTimes;
		}

		function add_value ($name, $value)
		{
			$this->assign ($name, $value);
		}

		function add_array ($name, $value)
		{
			$this->append ($name, $value);
		}

		function assign ($name, $value = '')
		{
			if (is_array ($name))
			{
				foreach ($name as $k => $v)
				{
					$this->data[$k] = $v;
				}
			}
			else
			{
				$this->data[$name] = $value;
			}

		}

		function append ($name, $value)
		{
			if (is_array ($value))
			{
				$this->data[$name][] = $value;
			}
			else
			{
				if (!is_array ($this->data[$name]))
				{
					$this->data[$name] .= $value;
				}
			}

		}

		function result ($_top = '')
		{
			ob_start ();
			$this->output ($_top);
			$result = ob_get_contents ();
			ob_end_clean ();
			return $result;
		}

		function output ($_top = '')
		{
			global $_top;
			if (!is_array ($_top))
			{
				if (strlen ($_top))
				{
					$this->tpl_file = $_top;
				}

				$_top = $this->data;
			}

			$_obj = &$_top;
			$_stack_cnt = 0;
			$_stack[$_stack_cnt++] = $_obj;
			$this->cpl_file = $this->temp_dir . str_replace ('.', '_', str_replace ('/', '_', $this->tpl_file)) . '.php';
			$compile_template = true;
			if ($this->reuse_code)
			{
				if (is_file ($this->cpl_file))
				{
					if ($this->mtime ($this->tpl_path . $this->tpl_file) < $this->mtime ($this->cpl_file))
					{
						$compile_template = false;
					}
				}
			}

			if ($compile_template)
			{
				include_once 'smarttemplateparser.class.php';
				$this->parser = new SmartTemplateParser ($this->tpl_path . $this->tpl_file);
				if (!$this->parser->compile ($this->cpl_file))
				{
					exit ('SmartTemplate Compiler Error: ' . $this->parser->error);
				}
			}

			include $this->cpl_file;
			unset ($GLOBALS[_top]);
		}

		function debug ($_top = '')
		{
			if (!$_top)
			{
				$_top = $this->data;
			}

			include_once 'class.smarttemplatedebugger.php';
			$this->debugger = new SmartTemplateDebugger ($this->tpl_path . $this->tpl_file);
			$this->debugger->start ($_top);
		}

		function use_cache ($key = '')
		{
			if (empty ($_POST))
			{
				$this->cache_filename = $this->cache_dir . 'cache_' . md5 ($_SERVER['REQUEST_URI'] . serialize ($key)) . '.ser';
				if ((($_SERVER['HTTP_CACHE_CONTROL'] != 'no-cache' AND $_SERVER['HTTP_PRAGMA'] != 'no-cache') AND @is_file ($this->cache_filename)))
				{
					if (time () - filemtime ($this->cache_filename) < $this->cache_lifetime)
					{
						readfile ($this->cache_filename);
						exit ();
					}
				}

				ob_start (array ($this, 'cache_callback'));
			}

		}

		function cache_callback ($output)
		{
			if ($hd = @fopen ($this->cache_filename, 'w'))
			{
				fputs ($hd, $output);
				fclose ($hd);
			}

			return $output;
		}

		function mtime ($filename)
		{
			if (@is_file ($filename))
			{
				return filemtime ($filename);
			}

		}
	}

?>
