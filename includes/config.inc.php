<?

// mysql's setting

define('DB_SERVER',	'localhost');
define('DB_USER',	'root');
define('DB_PASSWORD',	'');
define('DB_DATABASE',	'collect');
define('DB_TB_PRE',	'collect_');

// font

define('GD_FONT',	'simsun.ttc');

// error

define('DEBUG', 0);

// table setting

define('TB_RULES',	DB_TB_PRE . 'rules');
define('TB_LINKS',	DB_TB_PRE . 'links');
define('TB_DATA',		DB_TB_PRE . 'datas');
define('TB_DB2DB',	DB_TB_PRE . 'export');
define('TB_FILTER',	DB_TB_PRE . 'filter');
define('TB_CATE',		DB_TB_PRE . 'category');

// cookie info

define('COOKIE_PREFIX', '');
define('COOKIE_DOMAIN', '');
define('COOKIE_PATH', '');

// num of onepage

define('NUM_LINK_ONEPAGE', 50);
define('NUM_IMPORT_ONEPAGE', 50);
define('NUM_RULES_ONEPAGE', 12);

// registration information

define('REG_NAME',	'');
define('REG_TYPE',	2);
define('REG_SERVER_SN', '');
define('REG_LOCAL_SN',	'');

// username password

define('NEAT_USERNAME', 'root');
define('NEAT_PASSWORD', 'root');

$configIgnoreExt = array('pdf', 'zip', 'rar', 'exe', 'iso');
?>
