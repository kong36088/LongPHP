<?php
defined('BASE_PATH') OR exit('No direct script access allowed');
/**
 * LongPHP
 * Author: William Jiang
 */

/**
 *  -------------------------------------------------------------
 * |System config                                                |
 *  -------------------------------------------------------------
 *
 * base_url
 * The Host of your application
 * Example: www.php.net
 */
$config['base_url'] = 'vm/';

/**
 * location of your application
 */
$config['application_path'] = 'Application';

/**
 * The template extension
 */
$config['blade_ext'] = '.blade.php';

/**
 *  -------------------------------------------------------------
 * |Log config                                                   |
 *  -------------------------------------------------------------
 *
 * Log Levels
 *
 * LogLevel::EMERGENCY => 1,
 * LogLevel::ALERT => 1,
 * LogLevel::CRITICAL => 1,
 * LogLevel::ERROR => 1,
 * LogLevel::WARNING => 2,
 * LogLevel::NOTICE => 2,
 * LogLevel::INFO => 3,
 * LogLevel::DEBUG =>4
 *
 * 0 Don't do any log
 * 1 Log only error
 * 2 Log error、warning、notice
 * 3 error、warning、notice、info
 * 4 Log all
 */
$config['log_level'] = 4;
/**
 * The log path
 */
$config['log_path'] = APP_PATH . DIRECTORY_SEPARATOR . 'logs/';


/**
 *  -------------------------------------------------------------
 * |Session config                                               |
 *  -------------------------------------------------------------
 *
 * Configuring session driver
 *
 * Alternative: file database
 */
$config['session_driver'] = 'file';

/**
 * session_path leave blank to use default
 */
$config['session_path'] = BASE_PATH.'/Framework/session';

$config['session_cookie_name'] = 'long_session';

$config['session_expiration'] = 7200;



/**
 * --------------------------------------------------------------------------
 *| Cookie Related Variables                                                 |
 * --------------------------------------------------------------------------
 *
 * 'cookie_prefix'   = Set a cookie name prefix if you need to avoid collisions
 * 'cookie_domain'   = Set to .your-domain.com for site-wide cookies
 * 'cookie_path'     = Typically will be a forward slash
 * 'cookie_secure'   = Cookie will only be set if a secure HTTPS connection exists.
 * 'cookie_httponly' = Cookie will only be accessible via HTTP(S) (no javascript)
 *
 * Note: These settings (with the exception of 'cookie_prefix' and
 * 'cookie_httponly') will also affect sessions.
 *
 */
$config['cookie_prefix'] = 'long_';
$config['cookie_domain'] = '';
$config['cookie_path'] = '/';
$config['cookie_secure'] = FALSE;
$config['cookie_httponly'] = FALSE;