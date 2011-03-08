<?

	class neat_cache
	{
		var $cachePath = null;
		var $cacheFile = null;
		var $cacheReadTimes = 0;
		var $cacheDoTimes = 0;
		var $cacheContents = null;
		function neat_cache ($path = '', $file = '')
		{
			$this->setCachePath ($path);
			$this->setCacheFile ($file);
		}

		function setcachepath ($path)
		{
			$this->cachePath = $path;
		}

		function setcachefile ($file)
		{
			$this->cacheFile = $file;
		}

		function docache ($array, $name = '')
		{
			++$this->cacheDoTimes;
			$cacheFileContent .= '<?php

';
			$cacheFileContent .= '/*
';
			$cacheFileContent .= '+----------------------------------+
';
			$cacheFileContent .= '|	' . $name . '
';
			$cacheFileContent .= '+----------------------------------+
';
			$cacheFileContent .= '|	Powered by NEAT Cache system
';
			$cacheFileContent .= '+----------------------------------+
';
			$cacheFileContent .= '*/

';
			$cacheFileContent .= '$this->cacheContents = \'' . serialize ($array) . '\';

';
			$cacheFileContent .= '?>';
			$cacheFile = $this->cachePath . $this->cacheFile . '.php';
			$fp = fopen ($cacheFile, 'w+');
			fwrite ($fp, $cacheFileContent);
			fclose ($fp);
		}

		function readcache ()
		{
			++$this->cacheReadTimes;
			include_once $this->cachePath . $this->cacheFile . '.php';
			return unserialize ($this->cacheContents);
		}

		function getreadcachetimes ()
		{
			return $this->cacheReadTimes;
		}

		function getdocachetimes ()
		{
			return $this->cacheDoTimes;
		}

		function checkcachefile ()
		{
			return file_exists ($this->cachePath . $this->cacheFile . '.php');
		}
	}

?>
