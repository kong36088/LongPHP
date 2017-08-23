<?php
defined('BASE_PATH') OR exit('No direct script access allowed');
/**
 * Longphp
 * Author: William Jiang
 */

define('LONGPHP_VERSION', '1.0');

//to require common functions
require SYS_PATH . '/Long/Common/Functions.php';

//set error handler
set_error_handler('errorHandler');
set_exception_handler('exceptionHandler');


//load configuration
Long\Core\Config::initialize();


//init input class
Long\Library\Input::initialize();

//session
Long\Library\Session\LongSession::initialize();

//router
Long\Core\LongDispatcher::initialize();

