<?php
defined('BASE_PATH') OR exit('No direct script access allowed');
/**
 * LongPHP
 * Author: William Jiang
 */

/**
 *  -------------------------------------------------------------
 * |System config                                               |
 *  -------------------------------------------------------------
 *
 * base_url
 * The Host of your application
 * Example: www.php.net
 */
$config['base_url'] = 'vm/';


/**
 *  -------------------------------------------------------------
 * |Log config                                               |
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

$config['blade_ext'] = '.blade.php';

/**
 * location of your application
 */
$config['application_path'] = 'Application';


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
$config['session_path'] = 'Framework/session';