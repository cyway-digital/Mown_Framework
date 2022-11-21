<?php
/**
 * Mown Framework - Configuration file
 *
 * @author M. Vulcano - cyway.it
 */

$workDir = realpath(__DIR__ . '/..');

// REGIONAL INFO
date_default_timezone_set('Europe/Rome');
setlocale(LC_ALL, 'it_IT.UTF-8');

// THAT STRANGE PHP BUG WITH FLOATS
ini_set('serialize_precision', 14);
ini_set('precision',14);

// SYSTEM INFO
define('SYS_NAME', 'Mown Framework 3.1');
define('SYS_VER', '3.1');
define('SYS_HOME', 'dashboard');
define('SYS_PHPVER','7.1.0');
define('SYS_STAGE', 'DEV');
define('SYS_THEME', 'MownLTE');
define('ADMIN_SESSION_MAX_INACTIVITY',7200); // in seconds (1800 = 30 min)
define('MAINTENANCE_MODE', false);

// SYSTEM PATHS DEFINITION (Always provide a TRAILING_SLASH/ after a path)
define('URL', 'https://crm.example.com/');
define('APP_PATH', $workDir.'/'); //absolute
define('LIBS_PATH', $workDir.'/libs/'); //absolute
define('LANGS_PATH', $workDir.'/langs/'); //absolute
define('IMG_PATH', 'public/imgs/');
define('THEME_PATH', 'public/themes/'.SYS_THEME.'/');
define('FAVICON_PATH', 'public/favicon/');

// LOGS INFO
define('LOG_FILE_SYSTEM', $workDir.'/logs/system.log'); //absolute
define('LOG_FILE_DB', $workDir.'/logs/db.log');
define('LOG_ROTATE_DAYS', 7);
define('LOG_MAX_FILES', 20); // retemption for each LOG_FILE_*
define('LOG_MAX_WEIGHT', 5); // single log file max weight, in MB

// CACHE SETTINGS
define('CACHE_PATH', $workDir.'/cache/'); //absolute
define('CACHE_DEFAULT_TIME', 3600);

// PUSHOVER
define('PUSHOVER_ENDPOINT','https://api.pushover.net/1/messages.json');
define('PUSHOVER_APP_TOKEN','');
define('PUSHOVER_USER_KEY','');

// MAILGUN INTEGRATION
define('MAILGUN_URL', '');
define('MAILGUN_APIKEY', '');
