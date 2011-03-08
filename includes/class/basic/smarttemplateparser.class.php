<?

	class smarttemplateparser
	{
		var $template = null;
		var $extension_tagged = array ();
		var $error = null;
		function smarttemplateparser ($template_filename)
		{
			if ($hd = @fopen ($template_filename, 'r'))
			{
				$this->template = fread ($hd, filesize ($template_filename));
				fclose ($hd);
			}
			else
			{
				$this->template = '' . 'File not found: \'' . $template_filename . '\'';
			}

		}

		function compile ($compiled_template_filename = '')
		{
			if (empty ($this->template))
			{
				return null;
			}

			$page = preg_replace ('/<!-- ENDIF.+?-->/', '<?php
}
?>', $this->template);
			$page = preg_replace ('/<!-- END[ a-zA-Z0-9_.]* -->/', '<?php
}
$_obj=$_stack[--$_stack_cnt];}
?>', $page);
			$page = str_replace ('<!-- ELSE -->', '<?php
} else {
?>', $page);
			if (preg_match_all ('/<!-- BEGIN ([a-zA-Z0-9_.]+) -->/', $page, $var))
			{
				foreach ($var[1] as $tag)
				{
					list ($parent, $block) = $this->var_name ($tag);
					$code = '<?php
' . ('' . 'if (!empty($' . $parent) . ('' . '[\'' . $block . '\'])){
') . ('' . 'if (!is_array($' . $parent) . ('' . '[\'' . $block . '\']))
') . (('' . '$') . $parent) . ('' . '[\'' . $block . '\']=array(array(\'' . $block . '\'=>$' . $parent) . ('' . '[\'' . $block . '\']));
') . ('' . '$_tmp_arr_keys=array_keys($' . $parent) . ('' . '[\'' . $block . '\']);
') . 'if ($_tmp_arr_keys[0]!=\'0\')
' . (('' . '$') . $parent) . ('' . '[\'' . $block . '\']=array(0=>$' . $parent) . ('' . '[\'' . $block . '\']);
') . '$_stack[$_stack_cnt++]=$_obj;
' . ('' . 'foreach ($' . $parent) . ('' . '[\'' . $block . '\'] as $rowcnt=>$' . $block . ') {
') . (('' . '$') . $block) . '[\'ROWCNT\']=$rowcnt;
' . (('' . '$') . $block) . '[\'ALTROW\']=$rowcnt%2;
' . (('' . '$') . $block) . '[\'ROWBIT\']=$rowcnt%2;
' . ('' . '$_obj=&$' . $block . ';
?>');
					$page = str_replace ('' . '<!-- BEGIN ' . $tag . ' -->', $code, $page);
				}
			}

			if (preg_match_all ('/<!-- (ELSE)?IF ([a-zA-Z0-9_.]+)([!=<>]+)"([^"]*)" -->/', $page, $var))
			{
				foreach ($var[2] as $cnt => $tag)
				{
					list ($parent, $block) = $this->var_name ($tag);
					$cmp = $var[3][$cnt];
					$val = $var[4][$cnt];
					$else = ($var[1][$cnt] == 'ELSE' ? '} else' : '');
					if ($cmp == '=')
					{
						$cmp = '==';
					}

					$code = '' . '<?php
' . $else . ('' . 'if ($' . $parent) . ('' . '[\'' . $block . '\'] ' . $cmp . ' "' . $val . '"){
?>');
					$page = str_replace ($var[0][$cnt], $code, $page);
				}
			}

			if (preg_match_all ('/<!-- (ELSE)?IF ([a-zA-Z0-9_.]+) -->/', $page, $var))
			{
				foreach ($var[2] as $cnt => $tag)
				{
					$else = ($var[1][$cnt] == 'ELSE' ? '} else' : '');
					list ($parent, $block) = $this->var_name ($tag);
					$code = '' . '<?php
' . $else . ('' . 'if (!empty($' . $parent) . ('' . '[\'' . $block . '\'])){
?>');
					$page = str_replace ($var[0][$cnt], $code, $page);
				}
			}

			if (preg_match_all ('/{([a-zA-Z0-9_. >]+)}/', $page, $var))
			{
				foreach ($var[1] as $fulltag)
				{
					list ($cmd, $tag) = $this->cmd_name ($fulltag);
					list ($block, $skalar) = $this->var_name ($tag);
					$code = '' . '<?php
' . $cmd . ' $' . $block . ('' . '[\'' . $skalar . '\'];
?>
');
					$page = str_replace ('{' . $fulltag . '}', $code, $page);
				}
			}

			if (preg_match_all ('/<"([a-zA-Z0-9_.]+)">/', $page, $var))
			{
				foreach ($var[1] as $tag)
				{
					list ($block, $skalar) = $this->var_name ($tag);
					$code = '' . '<?php
echo gettext(\'' . $skalar . '\');
?>
';
					$page = str_replace ('<"' . $tag . '">', $code, $page);
				}
			}

			if (preg_match_all ('/{([a-zA-Z0-9_]+):([^}]*)}/', $page, $var))
			{
				foreach ($var[2] as $cnt => $tag)
				{
					list ($cmd, $tag) = $this->cmd_name ($tag);
					$extension = $var[1][$cnt];
					if (!$this->extension_tagged[$extension])
					{
						$header .= '' . 'include_once "smarttemplate_extensions/smarttemplate_extension_' . $extension . '.php";
';
						$this->extension_tagged[$extension] = true;
					}

					if (!strlen ($tag))
					{
						$code = '' . '<?php
' . $cmd . ' smarttemplate_extension_' . $extension . '();
?>
';
					}
					else
					{
						if (substr ($tag, 0, 1) == '"')
						{
							$code = '' . '<?php
' . $cmd . ' smarttemplate_extension_' . $extension . '(' . $tag . ');
?>
';
						}
						else
						{
							if (strpos ($tag, ','))
							{
								list ($tag, $addparam) = explode (',', $tag, 2);
								list ($block, $skalar) = $this->var_name ($tag);
								if (preg_match ('/^([a-zA-Z_]+)/', $addparam, $match))
								{
									$nexttag = $match[1];
									list ($nextblock, $nextskalar) = $this->var_name ($nexttag);
									$addparam = substr ($addparam, strlen ($nexttag));
									$code = '' . '<?php
' . $cmd . ' smarttemplate_extension_' . $extension . '($' . $block . ('' . '[\'' . $skalar . '\'],$' . $nextblock) . ('' . '[\'' . $nextskalar . '\']') . ('' . $addparam . ');
?>
');
								}
								else
								{
									$code = '' . '<?php
' . $cmd . ' smarttemplate_extension_' . $extension . '($' . $block . ('' . '[\'' . $skalar . '\'],' . $addparam . ');
?>
');
								}
							}
							else
							{
								list ($block, $skalar) = $this->var_name ($tag);
								$code = '' . '<?php
' . $cmd . ' smarttemplate_extension_' . $extension . '($' . $block . ('' . '[\'' . $skalar . '\']);
?>
');
							}
						}
					}

					$page = str_replace ($var[0][$cnt], $code, $page);
				}
			}

			if ($header)
			{
				$page = '' . '<?php
' . $header . '
?>' . $page;
			}

			if (strlen ($compiled_template_filename))
			{
				if ($hd = fopen ($compiled_template_filename, 'w'))
				{
					fwrite ($hd, $page);
					fclose ($hd);
					return true;
				}
				else
				{
					$this->error = 'Could not write compiled file.';
					return false;
				}
			}
			else
			{
				return $page;
			}

		}

		function var_name ($tag)
		{
			$parent_level = 0;
			while (substr ($tag, 0, 7) == 'parent.')
			{
				$tag = substr ($tag, 7);
				++$parent_level;
			}

			if (substr ($tag, 0, 4) == 'top.')
			{
				$obj = '_stack[0]';
				$tag = substr ($tag, 4);
			}
			else
			{
				if ($parent_level)
				{
					$obj = '_stack[$_stack_cnt-' . $parent_level . ']';
				}
				else
				{
					$obj = '_obj';
				}
			}

			while (is_int (strpos ($tag, '.')))
			{
				list ($parent, $tag) = explode ('.', $tag, 2);
				if (is_numeric ($parent))
				{
					$obj .= '[' . $parent . ']';
					continue;
				}
				else
				{
					$obj .= '[\'' . $parent . '\']';
					continue;
				}
			}

			return array ($obj, $tag);
		}

		function cmd_name ($tag)
		{
			if (preg_match ('/^(.+) > ([a-zA-Z0-9_.]+)$/', $tag, $tagvar))
			{
				$tag = $tagvar[1];
				list ($newblock, $newskalar) = $this->var_name ($tagvar[2]);
				$cmd = ('' . '$') . $newblock . ('' . '[\'' . $newskalar . '\']=');
			}
			else
			{
				$cmd = 'echo';
			}

			return array ($cmd, $tag);
		}
	}
?>
